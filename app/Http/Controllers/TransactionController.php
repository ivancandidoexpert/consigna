<?php

namespace App\Http\Controllers;

use \App\Cliente;
use \App\Product;
use \App\Transaction;
use \App\Stocktaking;
use \DB;
use \App\Http\Controllers\ClienteController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public $blingapikey = "d0dcc156ec11ec3e42175f8e843e8412dc758cc6";

    public function showListaClientesARemeter(){
    	$prazomedioentrega = 10;
        $sql = "select Q1.cliente_id, Q1.product_id, Q1.quantidade, Q2.created_at FROM(select st.cliente_id, st.product_id, st.quantidade from stocktakings st where created_at = (select MAX(created_at) from stocktakings st2 where st2.cliente_id=st.cliente_id and st2.product_id=st.product_id)) Q1 JOIN (SELECT DISTINCT t.cliente_id, pt.product_id, t.created_at  FROM transactions t join product_transaction pt on pt.transaction_id=t.id WHERE  t.tipoTransacao='R' AND t.created_at=(select max(t1.created_at) FROM transactions t1 join product_transaction pt1 on t1.id=pt1.transaction_id WHERE t.cliente_id=t1.cliente_id and pt.product_id=pt1.product_id)) Q2 ON Q2.cliente_id=Q1.cliente_id and Q2.product_id=Q1.product_id order by Q1.quantidade asc";
        // t.created_at< DATE_SUB(NOW(),INTERVAL ".$prazomedioentrega." DAY) and 
        $result = DB::select($sql);
        $clientes = new Collection();
        foreach ($result as $row){
        	$cliente = Cliente::find($row->cliente_id);
        	$velocidade = $cliente->getVelocidade($row->product_id);
        	if (!isset($velocidade) || ($velocidade==0)){
        		$velocidade=0.3;
        		}
        		//echo ">> c:".$cliente->id.", p:".$row->product_id.", q:".$row->quantidade.", l:".$velocidade * $prazomedioentrega; 
        	if (isset($cliente) && ($row->quantidade <= ($velocidade * $prazomedioentrega))){
        		$clientes->add($cliente); //echo " entrei! ";
        		}
        		//echo "<BR>";
        }
        return view('transaction.clientesARemeterList', compact('clientes'));  
    }

    public function showListaClientesAFaturar(){
        $transaction = new Transaction();
        $clientes = $transaction->clientesAFaturarNesteMes();
        return view('transaction.clientesAFaturarList', ['clientes'=>$clientes]);
    }

    public function showListarEstoques(){
        return view('transaction.estoques', ['clientes'=>Cliente::all()]);
    } 

    private function storeTransacao(Request $request, $cliente_id, $tipoTransacao=null){
        $id_loja_fisica="203136761";
        $xml_itens = "";
        $product = null;
        $transaction = Transaction::create($request->all());
        if (! isset($transaction->tipoTransacao)){
            $transaction->tipoTransacao = $tipoTransacao;
        }
        if (isset($request->cliente_id)) {
            $cliente=Cliente::find($request->cliente_id);
        }else{
            $cliente=Cliente::find($cliente_id);
        }
        foreach ($request->products_id as $n => $item_id){
            if(isset($request->products_qty[$n]) && $request->products_qty[$n]>0){
                $product=Product::find($item_id);
                $transaction->products()->save($product, ['quantidade'=>$request->products_qty[$n]] );
                // atualizar estoque atual se for envio de remessa
                if ($tipoTransacao=='R'){
        			Stocktaking::create(['product_id'=>$product->id, 'quantidade'=>$cliente->getStockTackingAtual($product->id)->quantidade + $request->products_qty[$n],'cliente_id'=>$cliente->id]);
                }
                $xml_itens.= "
                <item>
                <codigo>".$product->codigoBling."</codigo>
                <qtde>".$request->products_qty[$n]."</qtde>
                <vlr_unit>0</vlr_unit>
                </item>";
            }
        }
        //$transactions = $transaction->cliente()->first()->transactions()->get();
        \Session::flash('notification_success', 'Transação Registrada com Sucesso');

        // GERANDO PEDIDO NO BLING
        $xml = "
        <pedido>
            <numero_loja>".$transaction->id."</numero_loja>
            <loja>$id_loja_fisica</loja>
            <nat_operacao>";
        if($transaction->tipoTransacao == 'R'){
            $xml.= "REMESSA PARA DEMONSTRAÇÃO (60 DIAS)";
        }
        elseif ($transaction->tipoTransacao == 'F'){
            $xml.= "VENDA CONSUMIDOR FINAL";
        }
        $xml.= "</nat_operacao>
            <cliente>
                <nome>".$cliente->razaosocial."</nome>
                <tipoPessoa>".$cliente->tipoPessoa."</tipoPessoa>
                <cpf_cnpj>".$cliente->cnpj."</cpf_cnpj>
            </cliente>
            <itens>".$xml_itens ."</itens>";

        if($transaction->tipoTransacao == 'R'){
         $xml.= "   
            <obs>Remessa de reposicao de estoque. O pagamento da presente NF sera feito conforme acordado entre as parte.</obs>
            <obs_internas>2016: Gerar NF de Demonstração, Lançar Estoque, Não lançar Contas | 2017: Gerar NF, Lançar Estoque, Não lançar Contas</obs_internas>";
        }
        elseif ($transaction->tipoTransacao == 'F'){
        $xml.= "  
            <obs>Faturamento de estoque negociado no PDV.</obs>
            <obs_internas>2016: Gerar NF de Retorno de Demonstração, Gerar NF de Venda,  Não Lançar Estoque,  Lançar Contas | 2017: Não gerar NF, Não Lançar Estoque, Lançar em Contas e Associar </obs_internas>";
        }
        $xml.=" </pedido>";

        $url = 'https://bling.com.br/Api/v2/pedido/json/';
        $data = array (
            "apikey" => $this->blingapikey,
            "xml" => rawurlencode($xml)
        );
        
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, count($data));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);

        $transaction->codPedidoExterno = json_decode($response)->retorno->pedidos['0']->pedido->idPedido;
        $transaction->save();

        return null;
    }

    public function storeRemessa(Request $request, $cliente_id){
        $this->storeTransacao($request,$cliente_id,'R');
        ClienteController::sendRemessaEmail($cliente_id);
        return back();
    }

    public function storeRemessaExpress(Request $request){
        $this->storeTransacao($request,$request->cliente_id,'R');
        return back();
    }

    public function storeFatura (Request $request, $cliente_id){
        $this->storeTransacao($request,$cliente_id,'F');
        ClienteController::sendExtratoEmail($cliente_id);
        return back();
    }

     public function store(Request $request) {
        $this->storeTransacao($request, $request->cliente_id);
        return $this->index();
    }

    public function createRemessa(Request $request, $cliente_id){
        //Exibe o formulário de criação de remessas.
        return view('transaction.remessa', ['cliente'=>Cliente::find($cliente_id)]);
    }

    public function createFatura(Request $request, $cliente_id){
        //Exibe o formulário de criação de remessas.
        return view('transaction.fatura', ['cliente'=>Cliente::find($cliente_id)]);
    }

    public function delete(Request $request) {
		$transaction = Transaction::find($request->transaction_id);
		$transactions = $transaction->cliente()->first()->transactions()->get();
    	if ($transaction->delete()){
    		\Session::flash('notification_success', 'Transação Removida com Sucesso!');
    		$status=' ';
		}else{
			\Session::flash('notification_danger', 'Erro ao deletar, registro não foi excluído. Comunique com o administrador.');
		}
    	return back(); //view(back(),['transaction'=>$transaction, 'status'=>$status, 'transactions'=>$transactions]);
    }

    public function updateStock(Request $request, Cliente $cliente){

        foreach ($request->products_id as $n => $item_id){
            if(isset($request->products_qty[$n]) && $request->products_qty[$n]>=0){
                Stocktaking::create(['product_id'=>$item_id, 'quantidade'=>$request->products_qty[$n],'cliente_id'=>$request->cliente_id]);
            }
        }
        \Session::flash('notification_success', 'Estoque Atualizado!');
        return redirect()->action('ClienteController@showFollowUpPanel', ['cliente_id' => $request->cliente_id]);

    }

    public function index(){

        //consultar todos os clientes para o dropdown
        $clientes = Cliente::all(['id', 'nomefantasia'])->pluck('nomefantasia', 'id')->sortBy("nomefantasia");
        $produtos = Product::all(['id', 'nome', 'urlImagem']);
        $tiposTransacao = ['R'=>'Envio de Remessa', 'F'=>'Faturar Vendas'];

        return view('transaction.new', ['clientes'=>$clientes, 'produtos'=>$produtos, 'tiposTransacao'=>$tiposTransacao]);
    }
}

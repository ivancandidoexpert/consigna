<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cliente;
use Mail;

class ClienteController extends Controller
{

	public $blingapikey = "d0dcc156ec11ec3e42175f8e843e8412dc758cc6";

	public function index(){
		return view('cliente.BlingClientImportForm'); 
	}

	public function showExtrato(Request $request, $client_id){
        $cliente = Cliente::findOrFail($client_id);
        return view ('transaction.extrato', ['cliente'=>$cliente]);
    }

    public static function sendExtratoEmail($client_id){
    	$cliente = Cliente::findOrFail($client_id);
    	if($cliente->email==''){
			\Session::flash('notification_danger', 'ERRO: E-mail do cliente em branco. Atualize as informações no Bling e importe novamente o cliente. A remessa foi registrada, porém o e-mail não foi enviado.');
		}
		else{
    		Mail::send('transaction.email-extratocliente', compact('cliente'), function ($message) use ($cliente) {
    			$message->from('atendimento@ivancandidoexpert.com.br', 'Atendimento Ivan Candido Expert');
				$message->to($cliente->email);
				$message->subject('Ivan Candido Expert - Extrato de Conferência');
			});
		}
		return back();
    }

    public static function sendRemessaEmail($client_id){
    	$cliente = Cliente::find($client_id);
		if($cliente->email==''){
			\Session::flash('notification_danger', 'ERRO: E-mail do cliente em branco. Atualize as informações no Bling e importe novamente o cliente. A remessa foi registrada, porém o e-mail não foi enviado.');
		}
		else{
	    	Mail::send('transaction.email-remessacliente', compact('cliente'), function ($msg) use ($cliente) {
	    		$msg->to($cliente->email);
	    		$msg->from('atendimento@ivancandidoexpert.com.br', 'Atendimento Ivan Candido Expert');
				$msg->subject('Ivan Candido Expert - Notificação de Reposição a Caminho');
			});
		}
    }

	public function show(Request $request) {
		//importa o registro do cliente no Bling pelo CPF ou CNPJ

		// Bloco request bling API
		$outputType = "json";
		$cpf_cnpj = str_replace('/','',str_replace('.', '', $request->get('cpf_cnpj')));
		$url = 'https://bling.com.br/Api/v2/contato/' . $cpf_cnpj . '/' . $outputType;
		
	    $curl_handle = curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, $url . '&apikey=' . $this->blingapikey);
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
	    $response = curl_exec($curl_handle);
	    curl_close($curl_handle);
	 
	    // ** TODO ** VERFICAR SUCESSO CONSULTA API

	    // Verifica na base de dados se já existe um registro para o cliente (cnpj/cpf)
	 	$novo = true;
	 	$cliente = Cliente::where('cnpj',json_decode($response, true)['retorno']['contatos'][0]['contato']['cnpj'])->first();
	    if (null !== $cliente){
	    	$novo = false;
	    	\Session::flash('notification_warning', 'Cliente importado anteriormente!');
	    }

	    return view('cliente.BlingClientImportForm', ['response'=>compact('response'), 'cliente'=>$cliente]);
	}

	public function create(Request $request){
		$cliente = new Cliente();
		if($cliente->create($request->all())){
			\Session::flash('notification_success', 'Cliente Importado com Sucesso!');
		}else{
			\Session::flash('notification_danger', 'Erro ao Importar');
		}
		return view('cliente.BlingClientImportForm', ['request'=>compact('request'), 'cliente'=>$cliente]);
	}

	public function update (Request $request, $id){
		$cliente = Cliente::findOrFail($id);
		$cliente->update($request->all());
		\Session::flash('notification_success', 'Cliente Atualizado!');
		return view('cliente.BlingClientImportForm', ['request'=>compact('request'), 'cliente'=>$cliente]);
	}


	public function showAll(Request $request){
		return view('cliente.clientslist',['clientes'=>Cliente::all()->sortBy("nomefantasia")]);
	}

	public function showFollowUpPanel(Request $request, $cliente_id){
		$cliente = Cliente::find($cliente_id);
		return view('cliente.followuppanel',['cliente'=>$cliente, 'stocks'=>$cliente->stocks()]);
	}

	public function showFollowUpList($intervalodias = '10'){
		//retorna uma tabela com o cliente_id dos clientes que devem ser acompanhados
    	$clientes_ids = DB::select("
    		select c.id , c.nomefantasia from clientes c LEFT JOIN stocktakings st ON c.id=st.cliente_id WHERE 
    		(c.id not in (select cliente_id as id from stocktakings)) 
    		OR ( st.created_at = (select MAX(st2.created_at) from stocktakings st2 where st2.cliente_id=st.cliente_id and st2.product_id=st.product_id) AND st.created_at < DATE_SUB(NOW(),INTERVAL ".$intervalodias." DAY) ) 
    		OR ( (NOW() > DATE_SUB(LAST_DAY(NOW()),INTERVAL 6 DAY)) AND (st.created_at < DATE_SUB(LAST_DAY(NOW()),INTERVAL 6 DAY) )) 
    		group by c.id, c.nomefantasia order by c.nomefantasia");
    	return view('cliente.followuplist',['clientes_ids'=>json_decode(json_encode($clientes_ids),true)]);
	}

}

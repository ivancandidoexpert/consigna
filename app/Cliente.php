<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \PDO;

class Cliente extends Model
{
    //
    protected $fillable =['razaosocial','cnpj','nomefantasia','cidade','uf','bairro','telefone','whatsapp','email','nomeVendedor', 'nomegerente','tipoPessoa' ];

    // retorna todos os registros de verificação de estoque associados aos cliente
    public function stocktakings (){
    		return $this->hasMany(Stocktaking::class);
    }

    public function transactions(){
    	return $this->hasMany(Transaction::class);
    }

    public function remessas(){
        return $this->transactions()->getQuery()->where('tipoTransacao','=', 'R')->orderby('id','desc');
    }

    public function faturas(){
        return $this->transactions()->getQuery()->where('tipoTransacao','=', 'F')->orderby('id','desc');
    }

  /*  private function getQtdProdutosTrasacionados($tipoTransacao='F'){
        $sql = "SELECT p.nome, pt.product_id, t.cliente_id as client_id, sum(pt.quantidade) as estoqueremetido FROM transactions t JOIN product_transaction pt ON pt.transaction_id=t.id JOIN products p ON p.id = pt.product_id WHERE t.cliente_id=? and t.tipoTransacao='?' GROUP BY product_id, cliente_id";
        return DB::select($sql,[$tipoTransacao, $this->id]);
    }

    public function getQtdRemetidaProdutos(){
        return $this->getQtdProdutosTrasacionados('R');
    }

    public function getQtdFaturadaProdutos(){
        return $this->getQtdProdutosTrasacionados('F');
    }*/
    
   /* public function getQtdEstoqueAtualAferidoProdutos(){
        $sql = "SELECT st.product_id, st.cliente_id, st.quantidade as estoqueatual, st.created_at FROM stocktakings st WHERE st.created_at = (SELECT MAX(st2.created_at) FROM stocktakings st2 WHERE st2.product_id = st.product_id and st2.cliente_id=st.cliente_id) and st.cliente_id=?";
         return DB::select($sql,[$id]);
    }*/
    
    public function getStockTackingAtual($product_id){
        $result = Stocktaking::where([['product_id','=',$product_id],['cliente_id','=',$this->id]])->orderBy('id', 'desc');
        if ($result->first()!=null){
         	$stock=$result->first();
    	}else{
    	    $stock=new Stocktaking();
    	    $stock->quantidade=0;
    	}
        return $stock;
    }

    private function getUltimaTransacaoProdutos($tipoTransacao='F',$product_id){
    	// retorna 1 linha keys: product_id, cliente_id, codPedidoExterno, dataultima, quantidadeultima, dataanterior, quantidadeanterior, ultimaduracao,
        $sql = "
            SELECT q1.product_id, q1.cliente_id, q1.codPedidoExterno, dataultima, ifnull(quantidadeultima,0) as quantidadeultima, dataanterior, ifnull(quantidadeanterior,0) as quantidadeanterior, DATEDIFF(dataultima,dataanterior) as ultimaduracao, ifnull(quantidadeultima / DATEDIFF(dataultima,dataanterior),0) as velocidade FROM 
                (SELECT  t.codPedidoExterno, pt.product_id, t.cliente_id, t.created_at as dataultima, pt.quantidade as quantidadeultima from transactions t join product_transaction pt on pt.transaction_id=t.id where t.tipoTransacao=? and t.cliente_id=? and pt.product_id=? ORDER BY t.created_at DESC limit 1) q1
                    LEFT JOIN (SELECT  pt.product_id, t.cliente_id, t.created_at as dataanterior, pt.quantidade as quantidadeanterior from transactions t join product_transaction pt on pt.transaction_id=t.id where t.tipoTransacao=? and t.cliente_id=? and pt.product_id=? ORDER BY t.created_at DESC limit 1,1) q2
                    ON q2.product_id=q1.product_id and q2.cliente_id=q1.cliente_id
                ";

        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($tipoTransacao,$this->id,$product_id,$tipoTransacao,$this->id,$product_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVelocidade($product_id){
        $row = $this->getUltimaTransacaoProdutos('F',$product_id);
        return $row['velocidade'];
    }

    public function getDadosUltimaReposicao($product_id){
        return $this->getUltimaTransacaoProdutos('R',$product_id);
    }

    public function getDadosUltimaFatura($product_id){
        return $this->getUltimaTransacaoProdutos('F',$product_id);
    }

    public function stocks(){
        $result=null;
        if ($this->transactions()!=null){
    	// retorna tabela (array) com: nomeproduto | product_id | estoqueremetido (total) | estoquefaturado (total) | afaturar | estoqueatual | dataafericao
        $sql = "SELECT  q9.product_id, q9.nome as nomeproduto, ifnull(q1.estoqueremetido,0) as estoqueremetido, ifnull(q2.estoquefaturado,0) as estoquefaturado, ifnull(q3.estoqueatual,0) as estoqueatual, q3.created_at as dataafericao, (ifnull(q1.estoqueremetido,0) - ifnull(q2.estoquefaturado,0) - ifnull(q3.estoqueatual,0)) as afaturar
            FROM 
                (SELECT DISTINCT p1.id as product_id, p1.nome, t1.cliente_id FROM product_transaction pt1 
                    RIGHT JOIN products p1 ON p1.id=pt1.product_id 
                    LEFT JOIN transactions t1 ON t1.id=pt1.transaction_id) q9
            LEFT JOIN 
                (SELECT pt.product_id, t.cliente_id, sum(pt.quantidade) as estoqueremetido FROM transactions t JOIN product_transaction pt ON pt.transaction_id=t.id JOIN products p ON p.id = pt.product_id WHERE t.tipoTransacao='R' GROUP BY product_id, cliente_id) q1
                ON q1.product_id=q9.product_id and q1.cliente_id=q9.cliente_id
            LEFT JOIN 
                (SELECT pt.product_id, t.cliente_id, sum(pt.quantidade) as estoquefaturado FROM transactions t JOIN product_transaction pt ON pt.transaction_id=t.id JOIN products p ON p.id = pt.product_id WHERE t.tipoTransacao='F' GROUP BY product_id, cliente_id) q2
                ON q2.product_id=q9.product_id and q2.cliente_id=q9.cliente_id
            LEFT JOIN 
                (SELECT st.product_id, st.cliente_id, st.quantidade as estoqueatual, st.created_at FROM stocktakings st WHERE st.created_at = (SELECT MAX(st2.created_at) FROM stocktakings st2 WHERE st2.product_id = st.product_id and st2.cliente_id=st.cliente_id)) q3
                ON q3.product_id=q9.product_id and q3.cliente_id=q9.cliente_id
            WHERE q1.cliente_id=?
            ";
        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($this->id));
        $result = $stmt->fetchAll();
        }
        return $result;
    }

}

<?php

namespace App;

use App\Cliente;
use App\Product;
use DateTime;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Transaction extends Model
{
    
	protected $fillable =['cliente_id', 'tipoTransacao'];

    public function cliente(){
    	return $this->belongsTo(Cliente::class);
    }

    public function products(){
    	return $this->belongsToMany(Product::class)->withPivot('quantidade')->withTimestamps();
	}

	private function dateDifference($datetime1 , $differenceFormat = 'days' ){
    	return date_diff(new DateTime($datetime1), new DateTime())->days;
	}

	protected function clientesSemTransacoesRecentes($tipoTransacao='F', $intervalMYSQLstyle= '1 MONTH',$intervalStockTaking='5 DAY'){
	
		$sql="select c.id as cliente_id, t.id as transaction_id, t.created_at from transactions t LEFT JOIN clientes c ON c.id=t.cliente_id
    		RIGHT JOIN stocktakings st ON st.cliente_id = c.id WHERE 
    		t.tipotransacao='F' AND
    		(( 
    			t.created_at = (SELECT MAX(t2.created_at) FROM transactions t2 WHERE t2.cliente_id=t.cliente_id)
    			AND t.created_at <= DATE_SUB(NOW(), INTERVAL 25 day)  	
            ) AND 
            (
           		st.created_at = (SELECT MAX(st2.created_at) FROM stocktakings st2 WHERE st2.cliente_id=st.cliente_id)
            	AND st.created_at >= DATE_SUB(NOW(), INTERVAL 5 day)  
            ))
            OR  t.id is null ORDER BY c.nomeFantasia"; 
		
		$pdo = \DB::connection()->getPdo();
		$pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll();
    	$clientes = new Collection();
    	foreach ($results as $result){
    		$clientes->add(Cliente::find($result['cliente_id']));
    	}
    	return null;//$clientes;
	}

	public function clientesAFaturarNesteMes(){
			$intervalodiasentrefaturas=25;
			$results = new Collection();
			foreach (Cliente::all()->sortBy("nomefantasia") as $cliente){
				$stocks = $cliente->stocks();
				foreach ($stocks as $stock){
					if($stock['dataafericao']!=null && $stock['afaturar']>0 && $this->dateDifference($stock['dataafericao'])<5){
						$ultimafatura = $cliente->getDadosUltimaFatura($stock['product_id']);
						//ECHO "<BR>>> dataultima: ".$ultimafatura['dataultima'].", dif: ".$this->dateDifference($ultimafatura['dataultima']);
						if( $ultimafatura['dataultima']==null || $this->dateDifference($ultimafatura['dataultima'])>$intervalodiasentrefaturas){
						
							$results->add($cliente);
							break;
						}
					}
				}
			}
			return $results;
	}

	/*public function dateFromBusinessDays($days, $dateTime=null) {
	 
   /* echo date("m/d/Y", dateFromBusinessDays(-7));
	* echo date("m/d/Y", dateFromBusinessDays(3, time() + 3*60*60*24));
	* echo date("m/d/Y",$t->dateFromBusinessDays(-5,strtotime('11/30/2016')));
	*

	  $dateTime = is_null($dateTime) ? time() : $dateTime;
	  $_day = 0;
	  $_direction = $days == 0 ? 0 : intval($days/abs($days));
	  $_day_value = (60 * 60 * 24);

	  while($_day !== $days) {
	    $dateTime += $_direction * $_day_value;

	    $_day_w = date("w", $dateTime);
	    if ($_day_w > 0 && $_day_w < 6) {
	      $_day += $_direction * 1; 
	    }
	  }

	  return $dateTime;
	}*/

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   	public function transactionItems(){
    	return $this->hasMany(Transactionitem::class);
    }
}

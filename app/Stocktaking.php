<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stocktaking extends Model
{
    protected $fillable =['product_id','quantidade','cliente_id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSubProduct extends Model
{
    use HasFactory;
    
    public function subProduct(){
        return $this->hasOne(SubProduct::class,'id','sub_product_id');
    }
}

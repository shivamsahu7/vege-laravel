<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public function cartSubProduct(){
        return $this->hasMany(CartSubProduct::class,'cart_id');
    }
}

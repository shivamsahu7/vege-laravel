<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function subProducts(){
        return $this->hasMany(SubProduct::class,'product_id');
    }
    public function productImages(){
        return $this->hasMany(ProductImage::class,'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => \App\Enum\CouponTypeEnum::class,
        'discount_type' => \App\Enum\CouponDiscountTypeEnum::class,
    ];

    public function couponProducts(){
        return $this->hasMany(CouponProduct::class,'coupon_id');
    }
}

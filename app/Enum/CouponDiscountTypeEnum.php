<?php

namespace App\Enum;

enum CouponDiscountTypeEnum:string{
    case PERCENT = 'percent';
    case FIX = 'fix';
}
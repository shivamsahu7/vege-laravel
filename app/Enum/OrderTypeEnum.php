<?php

namespace App\Enum;

enum OrderTypeEnum:string{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
}
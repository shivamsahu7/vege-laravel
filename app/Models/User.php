<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,  Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart(){
        return $this->hasOne(Cart::class,'user_id');
    }

    public function cartSubProducts(){
        return $this->hasMany(cartSubProduct::class,'user_id');
    }

    public function amount(){
        $amount = 0;
        $cartSubProducts =  $this->cartSubProducts;
        foreach($cartSubProducts as $cartSubProduct){
            $amount += ($cartSubProduct->SubProduct->price*$cartSubProduct->quantity);
        }
        return $amount;
    }

    public function productCount(){
        return $this->cartSubProduct->sum('quantity');
    }

    public function userOtp(){
        return $this->hasOne(UserOtp::class,'user_id')->orderBy('id', 'desc');
    }

    public function addresses(){
        return $this->hasMany(Address::class);
    }
}

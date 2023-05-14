<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\StripeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth API
Route::post('/login',[AuthController::class,'login']);
Route::post('/otp-verify',[AuthController::class,'otpVerify']);


Route::get('/category',[CategoryController::class,'category']);
Route::get('/sub-category-list/{slug}',[CategoryController::class,'subCategoryList']);

Route::controller(ProductController::class)->prefix('product')->group(function(){
    Route::get('list/{slug}/{number}','productList');
    Route::get('search/{search}','productSearch');
});

// Route::get('/product-list/{slug}/{number}',[ProductController::class,'productList']);

// coupon Api Without Middleware
Route::controller(CouponController::class)->prefix('coupon')->group(function () {
    Route::get('list', 'list');

});

// promos Api
Route::get('/promo/list', [PromoController::class,'list']);

Route::group(['middleware' => 'auth:api'],function(){
    // User Auth Option
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/profile-detail',[UserController::class,'userDetail']);
    // Route::post('profile-detail-update',[UserController::class,'userDetailUpdate']);

    // Address Api
    Route::controller(AddressController::class)->prefix('address')->group(function(){
        Route::post('add','add');
        Route::get('list','list');
        Route::post('update/{id}','update');    
    });

    // Cart Api
    Route::controller(CartController::class)->prefix('cart')->group(function(){
        Route::post('add', 'add');
        Route::get('detail','detail');
        Route::post('after-login-add', 'afterLoginAdd');
    });

    // coupon Api
    Route::controller(CouponController::class)->prefix('coupon')->group(function () {
        Route::post('apply', 'apply');
        Route::get('remove', 'remove');
    });

    // order Api
    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('/', 'order');
    });


});
// payment Api
Route::controller(StripeController::class)->prefix('stripe')->group(function () {
    Route::get('/payment-form',[StripeController::class,'paymentForm']);
    Route::post('/payment',[StripeController::class,'payment'])->name('payment-api');
    // Route::post('/payment',[StripeController::class,'payment']);
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/payment-form',[StripeController::class,'paymentForm']);
Route::post('/payment',[StripeController::class,'payment'])->name('payment');

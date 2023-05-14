<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SubcategoryController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

Route::group(['middleware' => 'auth:api-admins'],function(){
Route::controller(CategoryController::class)->group(function () {
    Route::post('/category/add', 'add');
    Route::get('/category/list', 'list');
    Route::post('/category/edit/{id}', 'edit');
    Route::post('/category/delete/{id}', 'delete');
});

Route::controller(CouponController::class)->group(function () {
    Route::post('/coupon/add', 'add');
    Route::get('/coupon/list', 'list');
    Route::post('/coupon/edit/{id}', 'edit');
    Route::post('/coupon/delete/{id}', 'delete');
});

Route::controller(SubcategoryController::class)->group(function () {
    Route::post('/subcategory/add', 'add');
    Route::get('/subcategory/list', 'list');
    // Route::post('/subcategory/edit/{id}', 'edit');
    // Route::post('/subcategory/delete/{id}', 'delete');
});
});
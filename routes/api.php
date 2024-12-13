<?php

use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

//un_authed
Route::post('user/register',[userController::class,'store']);
Route::post('user/login', [UserController::class, 'auth']);

Route::get('user/login', [UserController::class, 'index'])->name("login");


Route::get('products', [ProductController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);

//Route::resource('products', ProductController::class);

//authed
Route::middleware('auth:sanctum')->group( function () {

    Route::get('user',function(Request $request){
    return [
        'user'=> $request->user(),
        'token'=>$request->bearerToken()
    ];
    });

    //Route::resource('orders', OrderController::class);
    //Route::resource('products', ProductController::class);
    Route::post('order/place', [OrderController::class, 'store']);
    Route::get('order/list', [OrderController::class, 'index']);
    Route::get('order/{id}', [OrderController::class, 'show']);



    Route::post('products/add', [ProductController::class, 'store']);
    Route::post('user/logout', [UserController::class, 'logout']);
});

<?php

use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
Route::post('user/register',[userController::class,'store']);
Route::post('user/login', [UserController::class, 'auth']);
Route::resource('products', ProductController::class);


Route::middleware('auth:sanctum')->group( function () {

    Route::get('user',function(Request $request){
    return [
        'user'=> $request->user(),
        'token'=>$request->bearerToken()
    ];
    });

    Route::resource('orders', OrderController::class);

    Route::post('user/logout', [UserController::class, 'logout']);
});

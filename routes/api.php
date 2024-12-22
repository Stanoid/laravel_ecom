<?php

use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\admin;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RecipeController;

//un_authed
Route::post('user/register', [userController::class, 'store']);
Route::post('user/login', [UserController::class, 'auth']);


//DNT
Route::get('user/login', [UserController::class, 'index'])->name("login");

Route::get('test', [ProductController::class, 'test']);
Route::get('products', [ProductController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::post('recipes/create', action: [RecipeController::class, 'store']);
Route::get('recipes', [RecipeController::class, 'index']);
Route::get('recipe/{id}', [RecipeController::class, 'show']);
//Route::resource('products', ProductController::class);

//Authed
Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', function (Request $request) {
        return [
            'user' => $request->user(),
            'token' => $request->bearerToken()
        ];
    });



    //Admin role check
    Route::middleware([admin::class])->group(function () {
        Route::get('orders/list', [OrderController::class, 'adminOrders']);
        Route::post('brand/create', [BrandController::class, 'store']);
        Route::post('recipe/create', [RecipeController::class, 'store']);
        Route::post('products/add', [ProductController::class, 'store']);
    });


    //Route::resource('orders', OrderController::class);
    //Route::resource('products', ProductController::class);

    Route::post('order/place', [OrderController::class, 'store']);
    Route::get('order/list', [OrderController::class, 'index']);
    Route::get('order/{id}', [OrderController::class, 'show']);
    Route::get('user/me', [UserController::class, 'me']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::post('user/logout', [UserController::class, 'logout']);
});

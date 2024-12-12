<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;

Route::redirect('/', '/products')->name('home');


Route::get('/{user}/posts',[DashboardController::class,'userPosts'])->name('posts.user');




Route::middleware('auth')->group(function(){
// note: router protected by an in-controller middleware
Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard') ;
    Route::post("/logout",[AuthController::class,'logout'])->name("logout");


});




Route::middleware('guest')->group(function(){

    Route::view('/register','auth.register')->name('register')->middleware("guest") ;
    Route::post("/register",[AuthController::class,'register']) ;
    Route::view('/login','auth.index')->middleware('guest')->name('login');
    Route::post("/login",[AuthController::class,'login']);

});


//resource routes:

Route::resource('products', ProductController::class);

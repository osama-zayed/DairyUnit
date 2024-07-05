<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::middleware(['auth:sanctum', 'Permission:collector'])->group(function () {
        Route::post('logout', "AuthController@logout");
        Route::get('me', "AuthController@me");
        Route::put('editUser', "AuthController@editUser");
    });
});
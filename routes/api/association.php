<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::middleware(['auth:sanctum', 'Permission:association'])->group(function () {
        Route::post('logout', "AuthController@logout");
        Route::get('me', "AuthController@me");
        Route::put('editUser', "AuthController@editUser");
        Route::get('notification', "AuthController@notification");
    });
});

Route::prefix('driver')->middleware(['auth:sanctum', 'Permission:association'])->group(function () {
    Route::get('by-association', "DriverController@showByAssociation");
    Route::get('show/{id}', "DriverController@showById");
    Route::post('add', "DriverController@add");
    Route::put('update', "DriverController@update");
});
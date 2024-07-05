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
Route::prefix('farmers')->middleware(['auth:sanctum', 'Permission:collector'])->group(function () {
    Route::get('by-association', "CollectorFarmerController@showByAssociation");
    Route::post('add', "CollectorFarmerController@add");
});
Route::prefix('milk')->middleware(['auth:sanctum', 'Permission:collector'])->group(function () {
    Route::get('show/all', "MilkCollectionController@showAll");
    Route::post('collecting', "MilkCollectionController@collecting");
});
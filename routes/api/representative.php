<?php

use App\Http\Controllers\Api\Representative\ReceiptFromAssociationController;
use App\Http\Controllers\Api\Representative\ReturnTheQuantityController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::middleware(['auth:sanctum', 'Permission:representative', 'userStatus'])->group(function () {
        Route::post('logout', "AuthController@logout");
        Route::get('me', "AuthController@me");
        Route::put('editUser', "AuthController@editUser");
        Route::get('notification', "AuthController@notification");
    });
});

Route::middleware(['auth:sanctum', 'Permission:representative', 'userStatus'])->group(function () {
    Route::get('TransferToFactory', "TransferToFactoryController@index");
    Route::get('TransferToFactory/{id}', "TransferToFactoryController@show");
    Route::get('association', "AssociationController@index");
    Route::resource('ReceiptFromAssociation', ReceiptFromAssociationController::class);
    Route::post('ReceiptFromAssociation/store', "ReceiptFromAssociationController@store");
    Route::resource('ReturnTheQuantity', ReturnTheQuantityController::class);
    Route::post('ReturnTheQuantity/store', "ReturnTheQuantityController@store");
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::middleware(['auth:sanctum', 'Permission:association','userStatus'])->group(function () {
        Route::post('logout', "AuthController@logout");
        Route::get('me', "AuthController@me");
        Route::put('editUser', "AuthController@editUser");
        Route::get('notification', "AuthController@notification");
    });
});

Route::prefix('driver')->middleware(['auth:sanctum', 'Permission:association','userStatus'])->group(function () {
    Route::get('by-association', "DriverController@showByAssociation");
    Route::get('show/{id}', "DriverController@showById");
    Route::post('add', "DriverController@add");
    Route::put('update', "DriverController@update");
    Route::put('update/status', "DriverController@updateStatus");

});
Route::prefix('factory')->middleware(['auth:sanctum', 'Permission:association','userStatus'])->group(function () {
    Route::get('by-association', "FactoryController@showByAssociation");
});

Route::prefix('collector')->middleware(['auth:sanctum', 'Permission:association','userStatus'])->group(function () {
    Route::get('by-association', "CollectorController@showByAssociation");
    Route::get('show/{id}', "CollectorController@showById");
    Route::post('add', "CollectorController@add");
    Route::put('update', "CollectorController@update");
    Route::put('update/status', "CollectorController@updateStatus");
});

Route::prefix('milk')->middleware(['auth:sanctum', 'Permission:association','userStatus'])->group(function () {
    Route::get('show/all', "ReceiptInvoiceFromStoresController@showAll");
    Route::get('show/{id}', "ReceiptInvoiceFromStoresController@showById");
    Route::post('AddReceiptInvoiceFromCollector', "ReceiptInvoiceFromStoresController@AddReceiptInvoiceFromCollector");
    Route::put('update', "ReceiptInvoiceFromStoresController@update");
});
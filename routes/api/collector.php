<?php

use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', "AuthController@login");
    Route::middleware(['auth:sanctum', 'Permission:collector','userStatus'])->group(function () {
        Route::post('logout', "AuthController@logout");
        Route::get('me', "AuthController@me");
        Route::put('editUser', "AuthController@editUser");
        Route::get('notification', "AuthController@notification");
    });
});

Route::prefix('family')->middleware(['auth:sanctum', 'Permission:collector','userStatus'])->group(function () {
    Route::get('by-association', "FamilyController@showByAssociationBranche");
    Route::get('show/{id}', "FamilyController@showById");
    Route::post('add', "FamilyController@add");
    Route::put('update', "FamilyController@update");
    Route::put('update/status', "FamilyController@updateStatus");

});
Route::prefix('milk')->middleware(['auth:sanctum', 'Permission:collector','userStatus'])->group(function () {
    Route::get('show/all', "MilkCollectionController@showAll");
    Route::get('report', "MilkCollectionController@report");
    Route::get('show/{id}', "MilkCollectionController@showById");
    Route::post('collecting', "MilkCollectionController@collecting");
    Route::put('update', "MilkCollectionController@update");
});

Route::prefix('location')->middleware(['auth:sanctum', 'userStatus'])->group(function () {
    Route::get('governorate', "LocationController@governorate");
});
<?php

use App\Http\Controllers\PdfHelperController;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});
Route::prefix('report')->controller(PdfHelperController::class)->middleware('Permission:institution')->group(function(){
    Route::get('CollectingMilkFromFamily','CollectingMilkFromFamily')->name('CollectingMilkFromFamily');
    Route::get('ReceiptInvoiceFromStore','ReceiptInvoiceFromStore')->name('ReceiptInvoiceFromStore');
    Route::get('TransferToFactory','TransferToFactory')->name('TransferToFactory');
    Route::get('ReceiptFromAssociation','ReceiptFromAssociation')->name('ReceiptFromAssociation');
    Route::get('ReturnTheQuantity','ReturnTheQuantity')->name('ReturnTheQuantity');
    Route::get('ReturnTheQuantityToAssociation','ReturnTheQuantityToAssociation')->name('ReturnTheQuantityToAssociation');

});
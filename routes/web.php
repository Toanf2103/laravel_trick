<?php

use App\Http\Controllers\ImgController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\RemoveBackgroundController;
use Livewire\Livewire;



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
    return view('welcome');
});
//firebase storage
Route::get('/upload-image-form', [ImgController::class,'showUploadForm'])->name('upload');
Route::post('/upload-image', [ImgController::class,'upload']);

//vnpay
Route::get('/vnpay-form', [ImgController::class,'showVnpayForm']);
Route::post('/vnpay-checkout', [ImgController::class,'vnpayCheckout'])->name('vnpayCheckOut');
Route::get('/vnpay-done', [ImgController::class,'vnpayCheckoutDone'])->name('done');

//momo

Route::post('/momo-checkout', [ImgController::class,'momoCheckout'])->name('momoCheckOut');

Route::get('/momo-done', [ImgController::class,'momoCheckoutDone'])->name('momoDone');

//lirewire
Route::get('/liretest', [ImgController::class,'liretest']);


//photo room
Route::get("/remove-bg", [RemoveBackgroundController::class, "index"]);

//PDF
Route::get("/pdf", [PDFController::class, "generatePDF"]);


Livewire::setScriptRoute(function ($handle) {
    return Route::get(basename(base_path()).'/vendor/livewire/livewire/dist/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(basename(base_path()).'/livewire/update', $handle);
});

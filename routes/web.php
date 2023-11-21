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
Route::post('/upload-image', [ImgController::class,'upload'])->name('uploade');
Route::get('/delete-image', [ImgController::class,'deleteImg']);


//vnpay
Route::get('/vnpay-form', [ImgController::class,'showVnpayForm']);
Route::post('/vnpay-checkout', [ImgController::class,'vnpayCheckout'])->name('vnpayCheckOut');
Route::get('/vnpay-done', [ImgController::class,'vnpayCheckoutDone'])->name('done');

//momo
Route::post('/momo-checkout', [ImgController::class,'momoCheckout'])->name('momoCheckOut');
Route::get('/momoCheckout2', [ImgController::class,'momoTest2'])->name('momoTest2');

Route::get('/momo-done', [ImgController::class,'momoCheckoutDone'])->name('momoDone');
Route::post('/momo-done2', [ImgController::class,'momoCheckoutDone'])->name('momoDone2');

Route::get('/momo', [ImgController::class,'nmomoView'])->name('nmomoView');



//zalo
Route::get('/zalo', [ImgController::class,'zaloPay'])->name('zaloPay');

//lirewire
Route::get('/liretest', [ImgController::class,'liretest']);


//photo room
Route::get("/remove-bg", [RemoveBackgroundController::class, "index"]);

//PDF
Route::get("/pdf", [PDFController::class, "generatePDF"]);
Route::get("/pdfHtml", [PDFController::class, "pdfHtml"]);

//heroku
Route::get("/heroku", [PDFController::class, "heroku"]);



//login google
Route::get('/loginGoogleForm', [ImgController::class,'loginGoogleForm']);


Livewire::setScriptRoute(function ($handle) {
    return Route::get(basename(base_path()).'/vendor/livewire/livewire/dist/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(basename(base_path()).'/livewire/update', $handle);
});

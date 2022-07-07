<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XeroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    	
    return view('common.login');
});
Route::any('/register-user',[XeroController::class,'registerUser']);
Route::any('/login-user',[XeroController::class,'loginUser']);
Route::any('/logout',[XeroController::class,'logout']);
Route::any('/dashboard', [XeroController::class, 'xeroIndex']);
Route::any('/contact-custom-url',[XeroController::class, 'contactCustomUrl']);
Route::any('/xero-connect', [XeroController::class, 'xeroConnect']);
Route::any('/xero-disconnect', [XeroController::class, 'xeroDisconnect']);
Route::any('/callback',[XeroController::class, 'callback']);
Route::any('xero-redirect',[XeroController::class,'xeroRedirect']);
Route::any('/save-payment',[XeroController::class,'savePayment']);
Route::any('/make-payment',[XeroController::class,'loadPaymentForm']);
Route::any('/payment-return',[XeroController::class,'paymentReturn']);
Route::any('/payment-done-callback',[XeroController::class,'paymentDoneCallback']);
Route::any('/update-settings',[XeroController::class,'updateSettings']);

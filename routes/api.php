<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IdentityVerificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//endpoint for login with email and password
Route::post('login', [AuthController::class, 'login'])->name('login');
//endpoint for Register
Route::post('register', [AuthController::class, 'register'])->name('register');
//endpoint for reset password
Route::post('reset-password', [PasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('forget-password', [PasswordController::class, 'forgetPassword'])->name('reset.password');

//endpoint to fetch user information
Route::get('/fetch-user-info',[UserController::class,'fetchUserInfo']);
//identity verification endpoint
Route::post('identity/verification',[IdentityVerificationController::class,'identityVerification']);
//get biller by category endpoint
Route::post('airtime/billers',[AirtimeController::class,'getAirtimeBillers']);
//biller payment endpoint to get airtime
Route::post('airtime/payment',[AirtimeController::class,'airtimePayment']);
//get all power biller endpoint
Route::post('power/billers',[PowerController::class,'getpowerBillers']);
//power customer validation endpoint
Route::post('power/validation',[PowerController::class,'powerCustomerValidattion']);
//power payment endpoint
Route::post('power/validation',[PowerController::class,'powerPayment']);
Route::fallback(function () {
    return ResponseHelper::error(404, 'Check the endpoint and retry');
});

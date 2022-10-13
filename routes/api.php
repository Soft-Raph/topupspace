<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\AuthController;
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

Route::fallback(function () {
    return ResponseHelper::error(404, 'Check the endpoint and retry');
});


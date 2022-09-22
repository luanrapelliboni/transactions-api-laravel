<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WalletController;

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


Route::resource('users', 'App\Http\Controllers\API\UserController');
Route::post('/transfer', [WalletController::class, 'transfer']);


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GiphyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/gifs', [GiphyController::class, 'search']);
    Route::get('/gifs/{gifId}', [GiphyController::class, 'show']);
});

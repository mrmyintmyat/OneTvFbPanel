<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/matches', [ApiController::class, 'matches']);
Route::get('/vn-matches', [ApiController::class, 'matches']);
Route::get('/highlights', [ApiController::class, 'highlights']);
Route::get('/app-setting', [ApiController::class, 'app_setting']);
Route::get('/slider-setting', [ApiController::class, 'slider_setting']);
Route::get('/channels', [ApiController::class, 'channels']);
Route::get('/tables', [ApiController::class, 'fetchLeagues']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\HighLightController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SliderSettingController;

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
Route::get('/', [HomeController::class, 'index']);
// Route::get('/vn_matches', [HomeController::class, 'vn_matches']);

Route::resource('/league', LeagueController::class);

Route::get('/app_setting', [AppSettingController::class, 'index']);
Route::put('/app_setting/{id}', [AppSettingController::class, 'update']);

Route::get('/notification', [NotificationController::class, 'index']);
Route::post('/notification', [NotificationController::class, 'sendNotification']);
Route::post('/edit-key', [NotificationController::class, 'UpdateKey']);

Route::get('/password-change', [HomeController::class, 'pw_change_show_form']);
Route::post('/password-change', [HomeController::class, 'pw_change'])->name('password.change');

Route::resource('/matches', HomeController::class)->except(['show']);
Route::resource('/highlights', HighLightController::class)->except(['show', 'store']);

Route::post('/set-timezone', [LoginController::class, 'setTimezone']);

Route::resource('channel', ChannelController::class);
Route::resource('slider-setting',SliderSettingController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


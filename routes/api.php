<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SubBannerController;

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

Route::get('/banner', [BannerController::class,'get']);
Route::post('/banner',[BannerController::class,'store'])->middleware('auth');
Route::post('/banner/{banner}',[BannerController::class,'update'])->middleware('auth');
Route::delete('/banner/{banner}',[BannerController::class,'delete'])->middleware('auth');

Route::get('/subbanner', [SubBannerController::class,'get']);
Route::post('/subbanner', [SubBannerController::class,'store'])->middleware('auth');
Route::post('/subbanner/{subBanner}',[SubBannerController::class,'update'])->middleware('auth');
Route::delete('/subbanner/{subBanner}',[SubBannerController::class,'delete'])->middleware('auth');

Route::get('/texts', [TextController::class,'get']);
Route::post('/texts', [TextController::class,'store'])->middleware('auth');
Route::post('/texts/{text}', [TextController::class,'update'])->middleware('auth');
Route::delete('/texts/{text}', [TextController::class,'delete'])->middleware('auth');

Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout'])->middleware('auth:sanctum');
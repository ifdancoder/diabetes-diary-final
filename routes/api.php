<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=> 'v1'], function () {
    Route::post('entries', [App\Http\Controllers\CGMController::class, 'createCGMrecord'])->middleware('auth.api');
    Route::get('experiments/test', function (Request $request) {
        return 'OK';
    });
    Route::get('treatments', function (Request $request) {
        return 'OK';
    })->middleware('auth.api');
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/select-base-image', [ImageController::class, 'selectBaseImage']);
Route::post('/select-second-image', [ImageController::class, 'selectSecondImage']);
Route::get('/merge-images', [ImageController::class, 'mergeImages']);
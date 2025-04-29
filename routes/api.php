<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\FoodApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\CategoryApiController;

// Get Data User Login
Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

// must authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserApiController::class, array('as' => 'api'));
    Route::post('logout', [AuthApiController::class, 'logout']);

    // Categories
    Route::apiResource('foods', FoodApiController::class, array('as' => 'api'));
});

// Authentication
Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);

// Categories
Route::get('categories', [CategoryApiController::class, 'index']);

<?php

use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get Data User Login
Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');

// must authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserApiController::class, array('as' => 'api'));
    Route::post('logout', [AuthApiController::class, 'logout']);
});

Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);

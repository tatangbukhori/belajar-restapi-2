<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json(['status' => true, 'message' => 'User versi 2', 'data' => $request->user()]);
})->middleware('auth:sanctum');

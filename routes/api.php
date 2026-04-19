<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Settings\BackupController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Get API token for authenticated web users
Route::post('/auth/token', function () {
    if (auth()->check()) {
        $token = auth()->user()->createToken('api-token')->plainTextToken;
        return response()->json(['token' => $token]);
    }
    return response()->json(['message' => 'Unauthorized'], 401);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/users', [UserController::class, 'index'])->middleware('role:admin');

    // Backup & Restore routes (admin only)
    

});

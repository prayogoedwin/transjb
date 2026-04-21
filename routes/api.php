<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Settings\BackupController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\UserAccess\UserAccessController;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/

Route::post('/user/change-password', [UserAccessController::class, 'changePassword']);
Route::post('/user/forgot-password', [UserAccessController::class, 'forgettenpasswordRequest']);
Route::post('/user/reset-password', [UserAccessController::class, 'forgettenpasswordChange']);
Route::post('/user/generate-password', [UserAccessController::class, 'generateFirstTimePassword']);
Route::post('/user/login', [UserAccessController::class, 'authentification']);
Route::post('/user/session', [UserAccessController::class, 'authentificationtsession']);
Route::post('/user/activate-session', [UserAccessController::class, 'ssessionTokenActivation']);
Route::post('/user/reset-pin', [UserAccessController::class, 'resetUserPin']);
Route::post('/user/change-pin', [UserAccessController::class, 'changeUserPin']);

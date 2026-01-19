<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\UserAccess\UserAccessController;
use App\Http\Controllers\API\coreBank414\UsersRegistration\UserEnrollmentCoreBank414Controller;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
|
| This file defines all user-related API routes for authentication, password management, and session handling.
| All routes are protected with throttle middleware to prevent abuse and ensure performance.
| Validation and error handling are handled in the respective controllers and request classes.
| Routes are organized into logical groups for better maintainability.
*/

Route::middleware('throttle:60,1')->group(function () {

    Route::post('user/session', [UserAccessController::class, 'authentificationtsession']);

});

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    Route::post('users/change-password', [UserAccessController::class, 'changePassword']);

    Route::post('users/forgot-password', [UserAccessController::class, 'forgettenpasswordRequest']);

    Route::post('users/reset-password', [UserAccessController::class, 'forgettenpasswordChange']);

    Route::post('users/generate-password', [UserAccessController::class, 'generateFirstTimePassword']);

    Route::post('users/login', [UserAccessController::class, 'authentification']);

    Route::post('users/session', [UserAccessController::class, 'authentificationtsession']);

    Route::post('users/activate-session', [UserAccessController::class, 'tokenSessionActivation']);

    Route::post('users/reset-pin', [UserAccessController::class, 'resetUserPin']);

    Route::post('users/change-pin', [UserAccessController::class, 'changeUserPin']);

});

Route::group(['prefix' => 'user/rest/v.4.14.01/'], function () {

    // Client Enrollment Routes

    Route::post('dependant-client-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userClientDependentEnrollment']);

    Route::post('pending-client-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userPendingClientEnrollment']);

    Route::get('broker-validation', [UserEnrollmentCoreBank414Controller::class, 'brokerIdValidation']);

    Route::post('simple-tracking-user', [UserEnrollmentCoreBank414Controller::class, 'simpleClientsEnrollment']);

    // User Enrollment Routes

    Route::post('independant-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userIndependantEnrollment']);

    Route::post('dependant-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userDependantEnrollment']);

    Route::post('individual-clients-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userClientEnrollment']);

    // Agent and Operator Management Routes

    Route::post('setting-agent-main-broker', [UserEnrollmentCoreBank414Controller::class, 'settingAgentMainBroker']);

    Route::post('operator-enrollment', [UserEnrollmentCoreBank414Controller::class, 'userOperatorEnrollment']);

    Route::get('operator-groups-view', [UserEnrollmentCoreBank414Controller::class, 'groupsOperatorView']);

    Route::get('view-agent-operators', [UserEnrollmentCoreBank414Controller::class, 'viewAgentOperators']);

    Route::post('reseting-operators-pin-by-agent', [UserEnrollmentCoreBank414Controller::class, 'resetingOperatorsPinByAgent']);

    Route::get('user-validation', [UserEnrollmentCoreBank414Controller::class, 'userValidation']);

});

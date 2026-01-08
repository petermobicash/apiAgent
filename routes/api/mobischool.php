<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\VAS\MobiSchoolController;

/*
|--------------------------------------------------------------------------
| MobiSchool Education Payment Routes
|--------------------------------------------------------------------------
| Handles school fee inquiries and payments through the MobiSchool system.
*/

Route::prefix('agent/vas/mobischool/rest/v.4.14.01')->group(function () {
    Route::get('student-validation', [MobiSchoolController::class, 'studentValidation']);
    Route::post('payment', [MobiSchoolController::class, 'paySchoolFees']);
});

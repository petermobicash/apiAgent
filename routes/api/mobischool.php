<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VAS\MobiSchoolController;

/*
|--------------------------------------------------------------------------
| MobiSchool Education Payment Routes
|--------------------------------------------------------------------------
| Handles school fee inquiries and payments through the MobiSchool system.
*/

Route::prefix('v1')->group(function () {
    Route::get('mobischool/student-validations', [MobiSchoolController::class, 'studentValidation']);
    Route::post('mobischool/payments', [MobiSchoolController::class, 'paySchoolFees']);
});

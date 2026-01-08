<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\VAS\UtilitiesPaymentController;

/*
|--------------------------------------------------------------------------
| Utilities Services Routes
|--------------------------------------------------------------------------
| Manages payments and validation for water and other utility services.
*/

Route::prefix('agent/vas/utilities/rest/v.4.14.01')->group(function () {
    Route::get('meter-number-validation', [UtilitiesPaymentController::class, 'meterNumberValidation']);
    Route::post('payment', [UtilitiesPaymentController::class, 'utilitiesPayment']);
});

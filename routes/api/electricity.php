<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\VAS\ElectricityPaymentController;

/*
|--------------------------------------------------------------------------
| Electricity VAS Services Routes
|--------------------------------------------------------------------------
| Validates meter numbers and handles electricity payments.
*/

Route::prefix('agent/vas/electricity/rest/v.4.14.01')->group(function () {
    Route::get('meter-number-validation', [ElectricityPaymentController::class, 'cashPowerMeterNumberValidation']);
    Route::post('payment', [ElectricityPaymentController::class, 'electricityPayment']);
});

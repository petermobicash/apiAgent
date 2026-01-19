<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VAS\ElectricityPaymentController;

/*
|--------------------------------------------------------------------------
| Electricity VAS Services Routes
|--------------------------------------------------------------------------
| Validates meter numbers and handles electricity payments.
*/

Route::prefix('v1')->group(function () {
    Route::get('electricity/meter-number-validations', [ElectricityPaymentController::class, 'cashPowerMeterNumberValidation']);
    Route::post('electricity/payments', [ElectricityPaymentController::class, 'electricityPayment']);
});

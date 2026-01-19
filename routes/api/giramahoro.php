<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VAS\GiramahoroController;

/*
|--------------------------------------------------------------------------
| Giramahoro Program Routes
|--------------------------------------------------------------------------
| Provides services related to the Giramahoro social support program.
*/

Route::prefix('v1')->group(function () {
    Route::get('giramahoro/beneficiary-validations', [GiramahoroController::class, 'validateBeneficiary']);
    Route::post('giramahoro/payments', [GiramahoroController::class, 'disburseSupport']);
});

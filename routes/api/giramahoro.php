<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Programs\GiramahoroController;

/*
|--------------------------------------------------------------------------
| Giramahoro Program Routes
|--------------------------------------------------------------------------
| Provides services related to the Giramahoro social support program.
*/

Route::prefix('agent/programs/giramahoro/rest/v.4.14.01')->group(function () {
    Route::get('beneficiary-validation', [GiramahoroController::class, 'validateBeneficiary']);
    Route::post('payment', [GiramahoroController::class, 'disburseSupport']);
});

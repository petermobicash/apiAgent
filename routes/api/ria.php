<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Remittances\RiaTransactionController;

/*
|--------------------------------------------------------------------------
| RIA Remittance Services Routes
|--------------------------------------------------------------------------
| Handles international money transfer services via RIA.
*/

Route::prefix('v1')->group(function () {
    Route::post('ria/validations', [RiaTransactionController::class, 'validateTransaction']);
    Route::post('ria/payments', [RiaTransactionController::class, 'payTransaction']);
});

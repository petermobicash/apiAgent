<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Remittances\RiaTransactionController;

/*
|--------------------------------------------------------------------------
| RIA Remittance Services Routes
|--------------------------------------------------------------------------
| Handles international money transfer services via RIA.
*/

Route::prefix('agent/remittance/ria/rest/v.4.14.01')->group(function () {
    Route::post('validate', [RiaTransactionController::class, 'validateTransaction']);
    Route::post('pay', [RiaTransactionController::class, 'payTransaction']);
});

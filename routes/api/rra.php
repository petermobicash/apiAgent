<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Government\RraTaxCollectionController;

/*
|--------------------------------------------------------------------------
| RRA Tax Services Routes
|--------------------------------------------------------------------------
| Handles RRA document validation and tax payments.
*/

Route::prefix('v1')->group(function () {
    Route::get('rra/doc-id-validations', [RraTaxCollectionController::class, 'rraDocIdValidation']);
    Route::post('rra/payments', [RraTaxCollectionController::class, 'rraTaxPayment']);
    // Route::post('rra/payments/individual-client', [RraTaxCollectionController::class, 'rraTaxPaymentIndividualClients']);
});

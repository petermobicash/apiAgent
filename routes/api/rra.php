<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Governement\RraTaxCollectionController;

/*
|--------------------------------------------------------------------------
| RRA Tax Services Routes
|--------------------------------------------------------------------------
| Handles RRA document validation and tax payments.
*/

Route::prefix('agent/goverment-services/rra/rest/v.4.14.01')->group(function () {
    Route::get('doc-id-validation', [RraTaxCollectionController::class, 'rraDocIdValidation']);
    Route::post('payment', [RraTaxCollectionController::class, 'rraTaxPayment']);
    Route::post('payment-individual-client', [RraTaxCollectionController::class, 'rraTaxPaymentIndividualClients']);
});

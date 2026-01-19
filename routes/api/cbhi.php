<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Government\CbhipaymentController;

/*
|--------------------------------------------------------------------------
| CBHI API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('cbhi/nid-details', [CbhipaymentController::class, 'niddetails']);
    Route::post('cbhi/payments/agent', [CbhipaymentController::class, 'cbhiMutuellePaymentDependentAgent']);
    Route::post('cbhi/payments/individual', [CbhipaymentController::class, 'cbhiMutuellePaymentIndividualClients']);
    Route::post('cbhi/collections/daily', [CbhipaymentController::class, 'cbhiDailyCollection']);
});

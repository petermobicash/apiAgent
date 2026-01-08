<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Governement\CbhipaymentController;

/*
|--------------------------------------------------------------------------
| CBHI API Routes
|--------------------------------------------------------------------------
*/

Route::post('/cbhi/nid-details', [CbhipaymentController::class, 'niddetails']);
Route::post('/cbhi/payment/agent', [CbhipaymentController::class, 'cbhiMutuellePaymentDependentAgent']);
Route::post('/cbhi/payment/individual', [CbhipaymentController::class, 'cbhiMutuellePaymentIndividualClients']);
Route::post('/cbhi/collection/daily', [CbhipaymentController::class, 'cbhiDailyCollection']);

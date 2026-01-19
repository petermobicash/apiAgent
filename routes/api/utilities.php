<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VAS\UtilitiesPaymentController;
use App\Http\Controllers\API\CoreBanking\MobireUtilitiesController;
use App\Http\Controllers\API\CoreBanking\AllTransactionByUserController;

/*
|--------------------------------------------------------------------------
| Utilities Services Routes
|--------------------------------------------------------------------------
| Manages payments and validation for water and other utility services.
*/

Route::prefix('v1')->group(function () {
    Route::get('utilities/meter-number-validations', [UtilitiesPaymentController::class, 'meterNumberValidation']);
    Route::post('utilities/payments', [UtilitiesPaymentController::class, 'utilitiesPayment']);
    });
    
    Route::group(['prefix' => 'utilities/user/rest/v.4.14.01/'], function () {
        Route::post('user-vaidation-by-admin', [MobireUtilitiesController::class, 'userSearchByAdmin']);
        Route::post('float-top-up-bpr-bank', [MobireUtilitiesController::class, 'userFloatTopUpBprBank']);
        Route::post('user-infos', [MobireUtilitiesController::class, 'SelfSearchByUsername']);
        Route::post('view-by-transaction-id', [MobireUtilitiesController::class, 'viewTransactionById']);
        Route::get('account-balance', [MobireUtilitiesController::class, 'agentAccountSummary']);
        Route::post('commission-selfserve', [MobireUtilitiesController::class, 'agentCommssonWithdrawal']);
        Route::post('all-transacion-by-id', [AllTransactionByUserController::class, 'alltransactionByUserAccount']);
        Route::post('transacion-by-reference', [AllTransactionByUserController::class, 'cBhitransactionByTransactionReference']);
        Route::post('transacion-by-reference-rra', [AllTransactionByUserController::class, 'rRAtransactionByTransactionReference']);
        Route::post('search-account-summary-by-user-account', [AllTransactionByUserController::class, 'searchAccountSummaryByUserAccount']);
        Route::post('payment-validation', [AllTransactionByUserController::class, 'validationTransactionByTransactionReference']);
        Route::get('cbhi-daily-collection', [AllTransactionByUserController::class, 'selectCbhiCollection']);
    });

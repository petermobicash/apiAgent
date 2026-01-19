<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Government\LtssContributionController;

/*
|--------------------------------------------------------------------------
| LTSS Contribution Routes
|--------------------------------------------------------------------------
| Handles LTSS identification and contributions.
*/

Route::prefix('v1')->group(function () {
    Route::get('ltss/identification-validations', [LtssContributionController::class, 'nidLtssValidation']);
    Route::post('ltss/payments', [LtssContributionController::class, 'ltssSendContribution']);
});

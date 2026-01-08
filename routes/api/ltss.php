<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Governement\LtssContributionController;

/*
|--------------------------------------------------------------------------
| LTSS Contribution Routes
|--------------------------------------------------------------------------
| Handles LTSS identification and contributions.
*/

Route::prefix('agent/goverment-services/ltss/rest/v.4.14.01')->group(function () {
    Route::get('identification-validation', [LtssContributionController::class, 'nidLtssValidation']);
    Route::post('payment', [LtssContributionController::class, 'ltssSendContribution']);
});

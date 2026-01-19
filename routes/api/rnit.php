<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Government\RnitContributionController;

/*
|--------------------------------------------------------------------------
| RNIT Contribution Routes
|--------------------------------------------------------------------------
| Handles RNIT identification and contribution submission.
*/

Route::prefix('v1')->group(function () {
    Route::get('rnit/identification-validations', [RnitContributionController::class, 'nidrnitNidvalidation']);
    Route::post('rnit/payments', [RnitContributionController::class, 'rnitSendContribution']);
});

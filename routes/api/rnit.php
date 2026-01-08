<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Governement\RnitCotisationController;

/*
|--------------------------------------------------------------------------
| RNIT Contribution Routes
|--------------------------------------------------------------------------
| Handles RNIT identification and contribution submission.
*/

Route::prefix('agent/goverment-services/rnit/rest/v.4.14.01')->group(function () {
    Route::get('identification-validation', [RnitCotisationController::class, 'nidrnitNidvalidation']);
    Route::post('payment', [RnitCotisationController::class, 'rnitSendContribution']);
});

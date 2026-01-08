<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\FloatDeposityToUpMigration\FloatDeposityTopUpCoreBankMigrationController;

/*
|--------------------------------------------------------------------------
| Float Deposits & Commission Migration Routes
|--------------------------------------------------------------------------
| Manages float balance and commission migration tasks.
*/

Route::prefix('agent/float-deposity-migration/rest/v.4.14.01')->group(function () {
    Route::post('float-closing-balance', [FloatDeposityTopUpCoreBankMigrationController::class, 'AgentAccountClosingBalanceMigration']);
    Route::post('delayed-commission', [FloatDeposityTopUpCoreBankMigrationController::class, 'AgentDelayedCommissionAccountClosingBalanceMigration']);
    Route::post('instant-commission', [FloatDeposityTopUpCoreBankMigrationController::class, 'AgentInstantCommissionAccountClosingBalanceMigration']);
});

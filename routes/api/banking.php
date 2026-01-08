<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\coreBank414\Services\Finance\BankingTransactionController;

/*
|--------------------------------------------------------------------------
| Banking API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/banking/cash-in', [BankingTransactionController::class, 'cash_in']);

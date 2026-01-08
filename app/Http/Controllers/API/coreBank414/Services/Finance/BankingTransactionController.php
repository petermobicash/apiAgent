<?php

namespace App\Http\Controllers\API\coreBank414\Services\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Finance\BankingTransaction;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * @group finance
 *
 * API endpoints for managing finance
 */
class BankingTransactionController extends BaseController
{
    /**
     * Handle cash-in transaction.
     *
     * @param Request $request
     * @return array
     */
    public function cash_in(Request $request)
    {
        try {
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                "account" => 'required',
                "payer_name" => 'required',
                "payer_phone" => 'required',
                "amount" => 'required'
            ]);

            if ($validator->fails()) {
                return [
                    "responseCode" => 105,
                    "communicationStatus" => 'FAILURE',
                    "codeDescription" => "Data validation",
                    "data" => json_decode($validator->errors()),
                    "responseDate" => now()
                ];
            }

            // Ensure authorization header is present
            if (!$request->header('Authorization')) {
                return [
                    "responseCode" => 104,
                    "communicationStatus" => 'FAILURE',
                    "codeDescription" => "Authorization please",
                    "data" => "",
                    "responseDate" => now()
                ];
            }

            // Ensure content is JSON
            if (!$request->isJson()) {
                return [
                    "responseCode" => 104,
                    "communicationStatus" => 'FAILURE',
                    "codeDescription" => 'Invalid content type',
                    "data" => '',
                    "responseDate" => now()
                ];
            }

            // Execute banking transaction
            $header = $request->header('Authorization');
            $transaction = new BankingTransaction();

            $response = $transaction->cash_in(
                $request->amount,
                $request->payer_name,
                $request->payer_phone,
                $request->account,
                $header
            );

            $response = json_decode($response);

            // Success transaction case
            if (isset($response->transactionNumber)) {
                return [
                    "responseCode" => 100,
                    "communicationStatus" => 'SUCCESS',
                    "codeDescription" => "SUCCESS",
                    "data" => [
                        "transactionNumber" => $response->transactionNumber,
                        "amount" => $response->amount,
                        "fees" => 0,
                        "transactiondate" => $response->date
                    ],
                    "responseDate" => now()
                ];
            }

            // Handle known failure codes
            if (isset($response->code)) {
                $codeMap = [
                    'login' => 103,
                    'insufficientBalance' => 106
                ];

                $code = $codeMap[$response->code] ?? 107;
                $description = match ($response->code) {
                    'login' => isset($response->passwordStatus) ? "Password is temporarily blocked" :
                               (isset($response->userStatus) ? "User is {$response->userStatus}" : "Invalid authentication"),
                    'insufficientBalance' => "Insufficient Balance",
                    default => isset($response->customFieldErrors) ? "Custom Field Errors" : "FAILURE"
                };

                return [
                    "responseCode" => $code,
                    "communicationStatus" => 'FAILURE',
                    "codeDescription" => $description,
                    "data" => $response,
                    "responseDate" => now()
                ];
            }

            // Fallback for unknown failure
            return [
                "responseCode" => 104,
                "communicationStatus" => 'FAILURE',
                "codeDescription" => 'FAILURE',
                "data" => $response,
                "responseDate" => now()
            ];
        } catch (Exception $e) {
            Log::error('Cash-in exception: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                "responseCode" => 500,
                "communicationStatus" => 'FAILURE',
                "codeDescription" => 'Unexpected server error',
                "data" => [],
                "responseDate" => now()
            ];
        }
    }
}

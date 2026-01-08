<?php

namespace App\Http\Controllers\API\coreBank414\FloatDeposityToUpMigration;

use App\Classes\coreBank414\FloatDeposityToUpMigration\FloatDeposityToUpMigration;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class FloatDeposityTopUpCoreBankMigrationController
{
    /**
     * Handles agent float deposit account closing balance migration.
     */
    public function AgentAccountClosingBalanceMigration(Request $request)
    {
        return $this->handleMigration(
            $request,
            'AgentAccountClosingBalanceMigration'
        );
    }

    /**
     * Handles delayed commission account closing balance migration.
     */
    public function AgentDelayedCommissionAccountClosingBalanceMigration(Request $request)
    {
        return $this->handleMigration(
            $request,
            'AgentDelayedCommissionAccountClosingBalanceMigration'
        );
    }

    /**
     * Handles instant commission account closing balance migration.
     */
    public function AgentInstantCommissionAccountClosingBalanceMigration(Request $request)
    {
        return $this->handleMigration(
            $request,
            'AgentInstantCommissionAccountClosingBalanceMigration'
        );
    }

    /**
     * Common migration handler for reuse across methods with exception handling.
     */
    private function handleMigration(Request $request, string $method)
    {
        try {
            if (!$request->header('Authorization')) {
                return [
                    "responseCode" => 400,
                    "status" => "Failed",
                    "message" => "Authorization please"
                ];
            }

            $validator = Validator::make($request->all(), [
                "amount" => 'required',
                "user" => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "responseCode" => 400,
                    "responseData" => $validator->errors()
                ]);
            }

            if (!$request->isJson()) {
                return [
                    "responseCode" => 400,
                    "responseDescription" => "Content type Not Allowed"
                ];
            }

            $header = $request->header('Authorization');
            $floatDeposityTopUp = new FloatDeposityToUpMigration();

            $mobicoreResponse = $floatDeposityTopUp->{$method}(
                $request->amount,
                $request->user,
                $header
            );

            $mobicoreResponse = json_decode($mobicoreResponse);

            if (!empty($mobicoreResponse->id)) {
                return [
                    "responseCode" => 200,
                    "status" => "success",
                    "transactionId" => $mobicoreResponse->id
                ];
            }

            return [
                "responseCode" => 400,
                "status" => "Failed",
                "mobicore" => $mobicoreResponse
            ];
        } catch (Exception $e) {
            Log::error('Migration Exception: ' . $e->getMessage(), [
                'method' => $method,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                "responseCode" => 500,
                "status" => "error",
                "message" => "An unexpected error occurred. Please try again later."
            ];
        }
    }
}

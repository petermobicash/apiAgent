<?php
namespace App\Http\Controllers\API\coreBank414\Services\Giramahoro; 

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
 
use App\Classes\coreBank414\Services\Giramahoro\GiramahoroMobiCashServicePayment; 
 


class PaymentServiceController extends BaseController
{
    public function InsurrencePayment(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            "amount" => 'required',
            "description" => 'required',
            // "payer_phone" => 'required',
            "payer_name" => 'required',
            "brokering" => 'required',
            "userGroup" => 'required'
        ]);

        if ($validator->fails()) {
            return $this->buildErrorResponse(105, "Data validation", $validator->errors());
        }

        // Authorization header
        $header = $request->header('Authorization');
        
                // Determine the type based on brokering and user group
        $type = $this->determineType($request->brokering, $request->userGroup);
       
         if($type=="agents_account.agent_client_payment"){

           $subject =$request->payer_phone;
           
         }else{
         
         $subject="giramahoro";

         }
        // Prepare the payload
        $payload = [
            'amount' => $request->amount,
            'description' => $request->description,
            'currency' => 'RW',
            'type' => $type,
            'customValues' => [
                'payer_phone' => $request->payer_phone,
                'payer_name' => $request->payer_name,
                'plan' => $request->plan
            ],
            'subject' =>$subject
        ];
        


         // Initialize the GiramahoroMobicashService

        $giramahoroService = new GiramahoroMobiCashServicePayment();

        // Make the API call

        $response = $giramahoroService->post('/self/payments', $payload, $header);         

         

        $responseDecoded = json_decode($response);

        // Handle the response
        return $this->handleResponse($responseDecoded);
    }

    private function determineType($brokering, $userGroup)
    {
        $types = [
            'Broker_retail_agents' => 'agents_account.agent_client_payment',
            'DDI_Broker_retail_agents' => 'clients_current_account.insurance_pay',
            'Independent_retail_agents' => 'agents_account.agent_client_payment',
            'Independent_sacco_mfi' => 'clients_current_account.insurance_pay',
            'Independent_Individual_clients' => 'clients_current_account.insurance_pay'
        ];

        return $types["{$brokering}_{$userGroup}"] ?? null;
    }

    private function handleResponse($response)
    {
        $date = now()->format('Y-m-d H:i:s');

        if (!isset($response->transactionNumber)) {
            $errorCode = $this->mapErrorCode($response);
            $description = $this->mapErrorDescription($response, $errorCode);

            return $this->buildErrorResponse($errorCode, $description, $response);
        }

        // Successful response
        $responseData = [
            "mobicashTransactionNo" => $response->transactionNumber,
            "amountPaid" => $response->amount,
            "transaction_fees" => $response->fees->commission_fees ?? 0,
            "date" => $response->date
        ];

        return $this->buildSuccessResponse($responseData);
    }

    private function mapErrorCode($response)
    {
        if (isset($response->code)) {
            return match ($response->code) {
                'login' => isset($response->passwordStatus) ? 102 : 103,
                'insufficientBalance' => 106,
                default => 107
            };
        }

        return 107;
    }

    private function mapErrorDescription($response, $errorCode)
    {
        return match ($errorCode) {
            102 => "Password is temporarily blocked",
            101 => "User is {$response->userStatus}",
            103 => "Invalid authentication",
            106 => "Insufficient Balance",
            default => "FAILURE"
        };
    }

    private function buildErrorResponse($code, $description, $data)
    {
        return [
            "responseCode" => $code,
            "communicationStatus" => 'FAILURE',
            "codeDescription" => $description,
            "data" => $data,
            "responseDate" => now()->format('Y-m-d H:i:s')
        ];
    }

    private function buildSuccessResponse($data)
    {
        return [
            "responseCode" => 100,
            "communicationStatus" => 'SUCCESS',
            "codeDescription" => "SUCCESS",
            "data" => $data,
            "responseDate" => now()->format('Y-m-d H:i:s')
        ];
    }
}

<?php  
namespace App\Classes\coreBank414\Services\Utilities;
use GuzzleHttp\Client; 
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Class MobicoreUtilites{

	 function getMobicashRefNumber($agentId){

        $timestamp=time();
        $mobicashRefNumber = $agentId."".$timestamp ;
        
        return $mobicashRefNumber ;

    }

  /*
    Function Name:        mobileFormat
    Function Description:    mobile phone formatting
    Returns:         stdClass object
    */

    public function mobileFormat($mobile){

        $glogal_param=array(
        'name'          => 'Rwanda',
        'countrycode'   => '250',
        'prefix'        => '0',
        'currency'      => '1',
        'length'        => '10',
        'gmt'           => '+2',
        'date'          => 'd/m/y',
        'lang'          => 'fr'
        );

        $result = $mobile = str_replace( ' ', '', $mobile );
        // suppress the 011
        //-----------------
        if ( substr( $mobile, 0, 3 ) == "011" )
        {
        $result = substr( $mobile, 3 );
        }else if ( substr( $mobile, 0, 1 ) == "+" )
        {
        $result = substr( $mobile, 1 );
        }else if ( is_numeric( $mobile ) )
        {
        // internationnal prefix
        //----------------------
        if ( substr( $mobile, 0, 2 ) == "00" )
        {
        $result = substr( $mobile, 2 );
        }
        // national number
        //----------------
        else if ( strlen( $mobile ) <= $glogal_param['length'] )
        {
        $l = strlen( $glogal_param['prefix'] );

        // with prefix
        //------------
        if ( $l > 0 )
        {
                if ( substr( $mobile, 0, $l ) == $glogal_param['prefix'] ){
                        $result = $glogal_param['countrycode'] .  substr( $mobile, $l );
                }else{
                        $result = $glogal_param['countrycode'] . $mobile;
                }
        }
        // without prefix
        //---------------
        else
        {
                $result = $glogal_param['countrycode'] . $mobile;
        }

        }
        }
        return $result;
    }

    public function SelfSearchByUsername($agenttester,$header){

    	$curl = curl_init();

curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/'.$agenttester.'?fields=brokers',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
				'Authorization: '.$header.''
		),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    }
  public function viewTransactionById($transactionId,$header){

  $curl = curl_init();

curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/transfers/'.$transactionId.'',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                                'Authorization:'.$header.''
                ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    }

public function agentAccount($header){

  $curl = curl_init();

curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/accounts',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                                'Authorization:'.$header.''
                ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    }


public function groupAppartenance($header){


$curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/self',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization:'.$header.''
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;



    }

public function userSearchByAdmin($account,$header){


$curl = curl_init();

curl_setopt_array($curl, array(

    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/\''.$account.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
         'Authorization: '.$header.''
    ),
));

$response = curl_exec($curl);

curl_close($curl);
 
return $response;
} 

public function userFloatTopUpEquityBank(){


    $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "amount": "10000",
  "description": "Equity  Bank Automatic User Float Top Up(Level 1 Test)",
  "currency": "RW",
  "type": "equity_bank_trust_account.equitybank_automatic_agent_float_top_up",
  "customValues": {
    "transaction_reference_type": "bank_top_up",
    "bank_transaction_date":"2022-06-03",
    "transaction_reference_number": "12345677110"
  },
  "subject": "\'0780000227"
}',
        CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ZXF1aXR5ZmxvYXRhZG1pbjplUSF1aXR5MjEjZmxvQXRhZG1pbg==',
                // 'Authorization: '.$header.'',
                'Content-Type: application/json'
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




}
public function userFloatTopUpBprBank($amount,$bankDate,$bankTransactionRef,$userAccount,$header){

 
    $request =$arrayVar = [
    "amount" => $amount,
    "description" => "BPR Bank Automatic Agent Float Top Up(Level 2 Test)",
    "currency" => "RW",
    "type" => "bpr_trust_account.bpr_automatic_agent_float_top_up",
    "customValues" => [
        "transaction_reference_type" => "bank_top_up",
        "bank_transaction_date" =>$bankDate,
        "transaction_reference_number" => $bankTransactionRef,
    ],
    "subject" =>"\'$userAccount",
];



   $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($request),
        CURLOPT_HTTPHEADER => array(
                'Authorization: Basic YnByZmxvYXRhZG1pbjpiQnIjMTJmTG9hVCFhZG1pbkAx',
                // 'Authorization: '.$header.'',
                'Content-Type: application/json'
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




}


public function userFloatTopUpCogeBank(){


   $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "amount": "10000",
  "description": "Cogebank Automatic Agent Float Top Up(Level 1 Test)",
  "currency": "RW",
  "type": "cogebank_trust_account.cogebank_automatic_agent_float_top_up",
  "customValues": {
    "transaction_reference_type": "bank_top_up",
     "bank_transaction_date":"2022-06-03",
    "transaction_reference_number": "12345677034"
  },
  "subject": "\'0788621254"
}',
        CURLOPT_HTTPHEADER => array(
                'Authorization: Basic Y29nZWZsb2F0YWRtaW46Y08hZzEyI0VmbG9hdCMxJGFkTWlu',
                // 'Authorization: '.$header.'',
                'Content-Type: application/json'
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




}
public function userFloatTopUpGitBank(){

$curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "amount": "10000",
  "description": "GT Bank Automatic Agent Float Top Up(Level 1 Test)",
  "currency": "RW",
  "type": "gt_bank_trust_account.gtbank_automatic_agent_float_top_up",
  "customValues": {
    "transaction_reference_type": "bank_top_up",
     "bank_transaction_date":"2022-06-03",
    "transaction_reference_number": "12345677076"
  },
  "subject": "\'0788621254"
}',
        CURLOPT_HTTPHEADER => array(
                'Authorization: Basic Z3RiYW5rZmxvYXRhZG1pbjpnVCMxMiFCYW4kMEtmbG9BdGFkbWlu',
            // 'Authorization: '.$header.'',
                'Content-Type: application/json'
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

}
public function searchAccountSummaryByUserAccount($header){


    $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/transactions?fields=id&fields=date&fields=amount&fields=type.internalName&fields=description',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
               'Authorization: '.$header.'',
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;



}

public function commissionWithdrawal($amount,$header){ 
 


 $jayParsedAry = [
   "amount" =>$amount, 
   "description" => "Agent Commission Withdrawal", 
   "currency" => "RW", 
   "type" => "agents_instant_commission_account.agent_comm_withdrawal", 
   "subject" => "system" 
]; 
 
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Authorization:'.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;


}
public function commissionPayment($amount,$header){ 



if (isset($header)) {
    
    $auth = $header;
    $auth_array = explode(" ", $auth);
    $un_pw = explode(":", base64_decode($auth_array[1]));
    $un = $un_pw[0];
    $pw = $un_pw[1];
}



$loginname=$un;



 
 $jayParsedAry = [
   "amount" => $amount, 
   "description" => "Agent Commission Payment", 
   "currency" => "RW", 
   "type" => "i_m_bank_trust.agent_comm_payment", 
   "subject" => "\''.$loginname.'" 
]; 

 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic aW1iYW5rZmxvYXRhZG1pbjppbUJAIWFuS2Zsb2F0YWRtaW4=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;
}

public function mobiCashDelayedCommissionCollection($amount,$mobicashRefNumber){
    

    $url ='https://testbox.mobicash.rw/CoreBank/test_box/api/system/payments';

    $headers=[
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ZGVsX2NvbW1fdXNlcjpNb2JpY2FzaEAxMjM=', // Replace with your actual authorization header
    ];
     
    $data = [
        'amount' => $amount,
        'description' => 'Delayed MobiCash Commission Payment',
        'currency' => 'RW',
        'type' => 'delayed_comm_cbhi_account.delayed_mobi_commi',
        'customValues' => [
            'transaction_reference_number' => 'M20342'
        ],
        'subject' => 'mobidelayed'
    ];

    $client = new Client();

    try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            $body = json_decode($body, true);

            // Handle response based on statusCode and body
            return response()->json(['statusCode' => $statusCode, 'body' => $body]);

         } catch (\Exception $e) {
            // Catch and handle any exception
            if ($e instanceof CustomPaymentException) {
                // Log the error or perform specific actions based on custom exception
                return response()->json(['statusCode' =>$e->getStatusCode(),'error' => $e->getMessage()], $e->getStatusCode());
            }

            // Handle generic exceptions
            return response()->json(['statusCode' =>500,'error' => 'Payment processing failed'], 500);
        }

// Handle the response data as needed

}

public function makeDelayedCommissionSelfPayment($amount,$agentphone,$auth)
    {


              try {
            $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => $auth,
                ])->post('https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments', [
                    'amount' => $amount,
                    'description' => 'Delayed Agent Commission Payment',
                    'currency' => 'RW',
                    'type' => 'agents_delayed_commission.delayed_commission_payment',
                    'subject' => "'$agentphone", // Note: escaped single quote
                ]);

                // // Handle response
                // if ($response->successful()) {

                    $statusCode = $response->getStatusCode();
                    $body = $response->getBody()->getContents();                     


                    if ($statusCode == 201) {

                       $body = json_decode($body, true);
                        // Handle response based on statusCode and body
                        return response()->json(['statusCode' => $statusCode, 'body' => $body]);

                    } else {
                        // Payment failed
                        return response()->json(['error' => 'Payment failed', 'response' => $body], $statusCode);
                    }
                // } else {
                //     // Handle error
                //     $errorMessage = $response->body();

                //     return $errorMessage;
                }catch (RequestException $e) {

                    // Handle request exception
                    $response = $e->getResponse();
                    $statusCode = $response->getStatusCode();
                    $body = $response->getBody()->getContents();

                    return response()->json(['error' => 'Request failed', 'message' => $e->getMessage(), 'response' => $body], $statusCode);
                }
    
    }

    public function agentDelayedCommission($amount,$mobicashRefNumber,$agentphone){

    $url = 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments';
    $data = [
        "amount" => $amount,
        "description" => "Delayed Agent Commission Payment",
        "currency" => "RW",
        "type" => "delayed_comm_cbhi_account.delayed_agent_comm_pay",
        "customValues" => [
            "transaction_reference_number" =>$mobicashRefNumber
        ],
        "subject" => '\''.$agentphone.''
    ];

    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ZGVsX2NvbW1fdXNlcjpNb2JpY2FzaEAxMjM=' // Base64 encoded credentials
    ];

    try {
        $client = new Client();

        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        // Handle response based on status code
        if ($statusCode == 201) {

           $body = json_decode($body, true);
            // Handle response based on statusCode and body
            return response()->json(['statusCode' => $statusCode, 'body' => $body]);

        } else {
            // Payment failed
            return response()->json(['error' => 'Payment failed', 'response' => $body], $statusCode);
        }
    } catch (RequestException $e) {
        // Handle request exception
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        return response()->json(['error' => 'Request failed', 'message' => $e->getMessage(), 'response' => $body], $statusCode);
    }
}

    


}
<?php
namespace App\Classes\coreBank414\Services\VAS;
use DB;
class ElectricityService
{

      /*  GIT Bank
		This function returns meter number details
		@param $meterNumberValue meter number value
		@return  MeterNumber object 
	  */ 

  public  function getCashpowerMeterDetails($CashPowerMeter){

 

	 $url = 'http://10.22.198.114:7764';	    
	  
	  	
	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "$url/Consumer",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\n\"meterNo\":\"$CashPowerMeter\"\n}",
	  
	  

	 CURLOPT_HTTPHEADER => array(
	    "Authorization: Basic bW9iaWNhc2g6bTBiMUBHVEI=",
	    "Content-Type: application/json"
	   ),
	));

	$response = curl_exec($curl);

	curl_close($curl);

	return $response;
		 


 }

/*  GIT Bank
	This function is used to  send payment to eVend
	@param $meterNumber meter number value 
	@param $mobicashRefNumber mobicash reference number
	@param $amount amount
	@return  PaymentResponse object 
*/

 public function buyElectricity($meterNo,$amountPaid,$msisdn,$refNo){  

 	$mcashResponse =[
					  'status' => '1',
					  'refNo' => '58401605768412',
					  'vend' => [
					    'meterNo' => $meterNo,
					    'receiptNo' => '2214/25069',
					    'invoiceTaxNo' => '000078683',
					    'vendDate' => 'Nov 19, 2020',
					    'vendTime' => '09:03:10',
					    'invoiceRef' => '89010009/14665383',
					    'customerName' => 'MUDENGE',
					    'remark' => 'Electricity Pre-paid',
					    'sgc' => '600059',
					    'ti' => '01',
					    'krn' => '1',
					    'tokenCount' => '1',
					    'token' => '30709790049814143303',
					    'units' => '68.0',
					    'amountTendered' => $amountPaid,
					    'amountPaid' => $amountPaid,
					    'electricityAmount' => $amountPaid,
					    'unitPrice' => '@ 249.0/kWh',
					    'taxAmount' => '3050.847',
					    'taxRate' => '@ 18.0%',
					    'balance' => 'RWF38809129.000',
					    'taxDesc' => 'TVA',
					    'operator' => 'GTBANK',
					  ],
					  'message' => NULL,
                    ];

	            return json_encode($mcashResponse);

 
 
           $url =  'http://10.22.198.110:7764';	    
    	    $customerId = 211111111;

			  try{

			  
			  $curl = curl_init();
			   curl_setopt_array($curl, array(
			  CURLOPT_URL => "$url/postvend",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>"{\n    \"customerId\": \"$customerId\",\n    \"meterNo\": \"$meterNo\",\n    \"amountPaid\": \"$amountPaid\",\n    \"msisdn\": \"$msisdn\",\n    \"refNo\":\"$refNo\"\n}",
			  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json",
		    "Authorization: Basic bW9iaWNhc2g6bTBiMUBHVEI="
		  ),

			
			));

			$response = curl_exec($curl);

			

			curl_close($curl);

	         return $response;
			 	
			 }catch(Exeption $x){
			 
			  return  $x->getMessage();
			 	
			 }	 

 } 
 


public function electricityPaymentIndividualClients($amount,$payerName,$payerPhone,$taxIdentificationNumber,$meterNumber ,$header){

	$request=array (
					  'amount' => $amount,
					  'description' => 'T14: Electricity Payment(Dependent Agent - Level 2 Test)',
					  'currency' => 'RW',
					  'type' => 'clients_current_account.electricity_payment',
					  'customValues' => 
					  array (
					    'transaction_reference_type' => 'electricity',					    
					    // 'tax_identification_number' => $taxIdentificationNumber,
					    'payer_name' => $payerName,
					    'payer_phone' => $payerPhone,
					    'meter_number' =>$meterNumber,
					    
					  ),
					  'subject' =>'gtbankuser',
					);

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
		CURLOPT_POSTFIELDS =>json_encode($request),
		CURLOPT_HTTPHEADER => array(
				'Authorization:'.$header.'',
				'Content-Type: application/json'
		),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

		 
}
public function electricityPaymentAUtorisation($transactionId,$comments){


$curl = curl_init();
curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/pending-payments/'.$transactionId.'/authorize',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,		 
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>json_encode($comments),
		CURLOPT_HTTPHEADER => array(
				'Authorization: Basic YnByYWRtaW46YnByQDIwMjIh',
				'Content-Type: application/json'
		),
));




$response = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
return   $httpCode;

 



}
public function electricityPaymentDeny($transactionId,$comments){	


$request='{
  "comments": "Denied"
}';


	$curl = curl_init();

curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/pending-payments/'.$transactionId.'/deny',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$request,
		CURLOPT_HTTPHEADER => array(
				'Authorization: Basic YnByYWRtaW46YnByQDIwMjIh',
				'Content-Type: application/json'
		),
));

$response = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
return  $httpCode;



}

 public function saveElectricityLog($mobicoreResponseId,$mobicash_ref_number, $payerPhone,$payerName,$amount,$authorization_code,$transfer_code,$electricityPoviderResponse1Vend,$status){

 	 $electricityToken= $electricityPoviderResponse1Vend->token;
     $meterNumber=$electricityPoviderResponse1Vend->meterNo;
      


        $vendResponse=json_encode($electricityPoviderResponse1Vend);

       
         $data=array('mobicore_response_id'=>$mobicoreResponseId,"mobicash_ref_number"=>$mobicash_ref_number,"payer_phone"=>$payerPhone,'payer_name'=>$payerName,"amount"=>$amount,"token"=>$electricityToken, "meter_number"=>$meterNumber,"authorization_code"=>$authorization_code,"transfer_code"=>$transfer_code,'electricity_povider_response_vend'=> $vendResponse,"status"=> $status );         
        
    DB::table('logs_electricity_notification_status')->insert($data);

    }


 public function bankTransfer($amount,$responseId,$transactionReference,$electricityPoviderResponse){

   $electricityToken= $electricityPoviderResponse->token;
   $meterNumber=$electricityPoviderResponse->meterNo;
   $operationvendDate=$electricityPoviderResponse->vendDate;
   $receiptno=$electricityPoviderResponse->receiptNo;
   $description='Token:'.$electricityToken.' , meter number :'.$meterNumber.', receiptNo:'.$receiptno.' , operation date :'.$operationvendDate;

  $request=array (
  'amount' =>$amount,
  'description' => $description,
  'currency' => 'RW',
  'type' => '8241755934761622950',
  'customValues' => 
  array (
    'transaction_reference_type' => 'remote_elec_transfer',
    'transaction_reference_number' => $responseId,
    'bank_response'=>$electricityToken
  ),
  'subject' => 'system',
);


  $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/gtbankuser/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($request),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic c3lzZ2xvYmFsOnN5c2dsb2JhbEA0OA==',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
return  $httpCode;




}


}
?>
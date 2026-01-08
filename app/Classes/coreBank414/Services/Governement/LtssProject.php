<?php
namespace App\Classes\coreBank414\Services\Governement;
use DB;
class LtssProject
{
public function ltssnidValidation($identification){
	

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://10.10.90.40:8080/ltss-integration-service/pservice/ltssservice/validateSubscriber",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{\r\n\"identification\":\"$identification\"\r\n}",
	  CURLOPT_HTTPHEADER => array(
	    "authorization: Basic MTAxODU4NTQwOiAhTW9iaUAyMDIy",
	    "content-type: application/json"
	    
	  ),
	));

	$response = curl_exec($curl);

	return $response;

  }
  
  public function ltssSendContribution($identification,$amount,$intermediary,$extReferenceNo,$paymentDate){



  	$response='{"status":"200","message":"OK","beneficiary":{"identification":"1199080169044034","name":"MUSENGIMANA Theoneste"},"amount":15000,"description":"MUkoresheje Mobicash. ","intermediary":"MUSENGIMANA Theoneste","extReferenceNo":"M201009981","refNo":"CP0012654583","paymentDate":"2022-09-19 09:52:39"}';
  	return $response;

  	

	$curl = curl_init();

	 

	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://197.243.10.68:8080/ltss-integration-service/pservice/ltssservice/sendContribution",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{\r\n\"beneficiary\":{\r\n\"identification\":\"$identification\"\r\n},\r\n\"amount\":\"$amount\",\r\n\"description\":\"Contribution testing\",\r\n\"intermediary\":\"$intermediary\",\r\n\"extReferenceNo\":\"$extReferenceNo\",\r\n\"paymentDate\": \"$paymentDate\"\r\n}",
	  CURLOPT_HTTPHEADER => array(
	    "authorization: Basic MTAxNTEzNzc2Oi84TlpHR1l0RFNSTlQzblBPVitBbmVFbGFOWXhXaS9G",
	    "content-type: application/json"
	    
	  ),
    ));

	$response = curl_exec($curl);
	return $response;

}



public function ltssMobicorePaymentIndividualClients($amount,$nid,$payer_phone,$payer_name,$header){

	$request=array (
  'amount' => $amount,
  'description' => 'LTSS Contribution Payment',
  'currency' => 'RW',
  'type' => 'clients_current_account.ltss_payment',
  'customValues' => 
  array (
    'transaction_reference_type' => 'ltss',
    'payer_name' =>$payer_name,
    'national_identity_number' => $nid,
    'payer_phone' => $payer_phone,
  ),
  'subject' =>'8241755934761492902',
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
				'Authorization: '.$header.'',
				'Content-Type: application/json'
		),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

}
 
public function saveLtssLog( $payerPhone,$nid,$mobicoreResponseId,$ltssRefNumber,$amount,$ltssResponse,$status){
       
    $data=array('payer_phone'=>$payerPhone,'nid'=>$nid,"mobicore_response_id"=>$mobicoreResponseId,'ltss_ref_number'=>$ltssRefNumber,'amount'=>$amount,'ltss_response'=>$ltssResponse,"status"=> $status );
    DB::table('test_logs_ltss')->insert($data);

    } 

       

}
?>
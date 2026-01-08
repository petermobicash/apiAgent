<?php
namespace App\Classes;
class StarTimeService
{
   /*
		This function returns Subscriber details
		@param  Subscriber value
		@return  Subscriber object 
	*/

function startimeRecharge_infos($service_code){  

	

	// $service_code='01868000018';


   $curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'http://staging.stariboss.com/api-payment-service/v1/subscribers/'.$service_code.'/recharge-infos',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic MzMwMEBSVy5EOjEyMzQ1Ng=='
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
   }
	/*
		This function  return startimeReplaceable_packages details
		@param service_code (in general, is a smart card)
		@return  object 
	*/
	function startimeReplaceable_packages($service_code){
    
	    $curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://staging.stariboss.com/api-payment-service/v1/subscribers/'.$service_code.'/replaceable-packages',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
			    'Authorization: Basic MzMwMEBSVy5EOjEyMzQ1Ng=='
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			return $response;
	}


	/*
		This function is used to  send payment to eVend
		@param $transaction_time transaction date date value 
		@param $mobicashRefNumber mobicash reference number
		@param $amount amount
		@return  response json 
	*/

	function starTimeRecharging($mobicashRefNumber,$transaction_time,$amount,$mobile,$service_code){


		$request=array (
		  'serial_no' => $mobicashRefNumber,
		  'transaction_time' => $transaction_time,
		  'service_code' => $service_code,
		  'amount' => $amount,		 
		  'mobile' => $mobile
		);

		
    

    $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://staging.stariboss.com/api-payment-service/v1/recharging',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>json_encode($request),
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic MzMwMEBSVy5EOjEyMzQ1Ng==',
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;

}



	/*
		This function returns the latest 5 meter number payment done
		@param $meterNumber meter number value 
		@return  JSON
	*/
function starTimeTransactionStatus($mobicashRefNumber){
	    
	   $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://staging.stariboss.com//api-payment-service/v1/partners/transactions/'.$mobicashRefNumber.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic MzMwMEBSVy5EOjEyMzQ1Ng=='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;


	}
    /*  GIT Bank
		This function returns meter number details
		@param $meterNumberValue meter number value
		@return  MeterNumber object 
	  */
    function starTimeAccountStatus($mobicashRefNumber){

    $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://staging.stariboss.com//api-payment-service/v1/partners/transactions/'.$mobicashRefNumber,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic MzMwMEBSVy5EOjEyMzQ1Ng=='
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;

	}
    public function mobicorePayment($amount,$mobicashreferencenumber,$clientpayerphone,$clientId, $description,$header){
                   
		$request=array (
			'amount' => $amount,
			'description' => $description,
			'currency' => 'rwf',
			'type' => '-5069334217217555185',
			'customValues' => 
			array (
			//   'mobicashreferencenumber' => $mobicashreferencenumber,
			  'clientpayerphone' => $clientpayerphone
			  
			),
			'subject' => '-5069334227686537969',
		);


		$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://sandbox.mobicash.rw/mobicore/api/'.$clientId.'/payments',
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


}
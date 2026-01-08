<?php

namespace App\Classes;

class DstvProject{
	
    function getDstvBouquet($serviceID){
    	
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://160.153.235.96:11008/v1/payments/bouquets",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{ \r\n\"serviceID\": \"$serviceID\" \r\n} ",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Basic bW9iaWNhc2hfYXBpOmF0ejNoZzdu",
		    "Content-Type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		return $response;
	}

		/*  DSTV
		This function returns meter number details
		@param $serviceID SERVICE ID 
		@return  BOUQUETS Object 
	   */

    function sendDstvPayment($serviceID,$amount,$accountNumber,$payerTransactionID,$msisdn){

		  $curl = curl_init();
		  curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://160.153.235.96:11008/v1/payments/submit",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{ \r\n\"serviceID\" : \"$serviceID\", \r\n\"amount\": \"$amount\", \r\n\"accountNumber\" : \"$accountNumber\", \r\n\"payerTransactionID\" : \"$payerTransactionID\",\r\n\"msisdn\" : \"$msisdn\", \r\n\"paymentDescrption\" : \"DSTV TEST\", \r\n\"metadata\": { \r\n\t\"bouquetID\":\"\"\r\n\t} \r\n}",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Basic bW9iaWNhc2hfYXBpOmF0ejNoZzdu",
		    "Content-Type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		
        return $response;

}
}
?>
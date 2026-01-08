<?php

namespace App\Classes\coreBank414\Services\Utilities;

Class RssbIntergation{
	
	public function authentification()
	{
		
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://172.16.17.61/api/v1/session',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"serviceName": "mobicash",
"serviceSecret": "8237ba851c984464a93d8cdf232c7278"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

	}

// public function authentification(){
    
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'http://172.16.19.214/api/v1/session',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>'{
//   "serviceName": "mobicash",
//   "serviceSecret": "dd715b2c6cc64baf8fea4976201759c2"
//   }',
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/json'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// return $response;

// }

	  //GET NID DETAILS
	 public function getNidDetails($householdNID,$year,$token){


//     $response='{
//     "headId": "1195080032745065",
//     "headHouseHoldNames": "Célestin NDAGIJIMANA",    
//     "totalHouseHoldMembers": 10,
//     "totalAmount": 30000,
//     "totalPaidAmount": 30000,
//     "members": [
//         {
//             "fullNames": "JACQUELINE NYIRAHABIMANA"
//         },
//         {
//             "fullNames": "X NSABIMANA"
//         },
//         {
//             "fullNames": "X NYIRANSABIMANA"
//         },
//         {
//             "fullNames": "BELLA MANIRASUBIZA"
//         },
//         {
//             "fullNames": "LILIANE MUHIRE"
//         },
//         {
//             "fullNames": "nziza shema Iradukunda"
//         },
//         {
//             "fullNames": "PATRICK MANIORAKIZA"
//         },
//         {
//             "fullNames": "XXX BIKORIMANA"
//         },
//         {
//             "fullNames": "BERTHIN YVES GANZA"
//         },
//         {
//             "fullNames": "Célestin NDAGIJIMANA"
//         }
//     ]
// }';


// return $response;

    
		
$curl = curl_init();

curl_setopt_array($curl, array(
   
  CURLOPT_URL => 'http://172.16.17.61/api/v1/member/'.$householdNID.'/bill?subscriptionYear='.$year.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

}

public function sendNotification($householdNID,$paydate,$amount,$mcashReferenceNumber,$year,$token){


$json='{
"amountPaid":'.$amount.',
"reconciliationReferenceNumber": "'.$mcashReferenceNumber.'",
"transactionDate": "'.$paydate.'",
"billId": "0",
"subscriptionYear":'.$year.'
}';
 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://172.16.17.61/api/v1/member/'.$householdNID.'/bill',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$json,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

}
}


?>
<?php  
namespace App\Classes\coreBank414\Services\Utilities;

Class AlltransactionByUserAccount{

public function alltransactionByUserAccount($header){


$curl = curl_init();

curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/transactions?fields=id&fields=date&fields=amount&fields=type.internalName&fields=description&fields=transactionNumber&fields=description&fields=authorizationStatus',
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

public function cBhitransactionByTransactionReference($referenceNumber){

$curl = curl_init();

 
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/transactions/'.$referenceNumber.'?fields=description&fields=customValues%5B0%5D&fields=date&fields=amount&fields=id&fields=customValues&fields=transactionNumber',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic cmVkYXRhYWRtaW46ckVhZE1pbkFEQDIzI0thbCMkMEAx'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

public function rRAtransactionByTransactionReference($referenceNumber){



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/transactions/'.$referenceNumber.'?fields=date&fields=amount&fields=id&fields=customValues&fields=transactionNumber',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic cmVkYXRhYWRtaW46ckVhZE1pbkFEQDIzI0thbCMkMEAx'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

public function validationTransactionByTransactionReference($referenceNumber){


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/transactions/'.$referenceNumber.'fields=date&fields=amount&fields=id&fields=transactionNumber&fields=description',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic cmVkYXRhYWRtaW46ckVhZE1pbkFEQDIzI0thbCMkMEAx'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}


public function selectCbhiCollection(){

date_default_timezone_set('Africa/Kigali');

// $datePeriod1=date("Y-m-d\TH:00");
 
// $datePeriod2= date('Y-m-d\TH:i',strtotime('-1 hour',strtotime($datePeriod1)));


$datePeriod1='2022-06-29';
$datePeriod2='2022-07-18';




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/transactions?datePeriod='.$datePeriod1.'&datePeriod='.$datePeriod2.'&fromAccountTypes=agents_account&toAccountTypes=governments_account&user=20000011_15&fields=transactionNumber',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic cmVkYXRhYWRtaW46ckVhZE1pbkFEQDIzI0thbCMkMEAx'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

public function selectElectricityPendingStatus(){        

    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/transactions?transferFilters=vas_providers_account.vas_electricity_payment_collections',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic YnByYWRtaW46YnByQDIwMjIh'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response; 
  
}




}
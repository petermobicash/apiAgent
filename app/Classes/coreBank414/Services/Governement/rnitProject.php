<?php

namespace App\Classes\coreBank414\Services\Governement;

class rnitProject
{

public function rnitNidValidation($identification){  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://app.mobicash.rw/rnit-1/check-id?identification=$identification",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

  } 
public function rnitMobicorePaymentDependentAgent($amount ,$bankaccount,$nid,$payerName,$payerPhone,$bankname ,$payerEmail,$header) {

   $request=array (
  'amount' =>$amount,
  'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
  'currency' => 'RW',
  'type' => '8241755934761449894',
  'customValues' => 
  array (
    'transaction_reference_type' => 'rnit',
    'payer_name' =>$payerName,
    'national_identity_number' => $nid,
    'bank_name' => $bankname,
    'bank_account' => $bankaccount,
    'payer_email'=>$payerEmail,    
    'payer_phone' => $payerPhone,
  ),
  'subject' =>'8241755934761496998',
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

public function rnitMobicorePaymentDdiBrokerDependentAgent($amount ,$bankaccount,$nid,$payerName,$payerPhone,$bankname ,$payerEmail,$header) {

   $request=array (
  'amount' =>$amount,
  'description' => 'T9:RNIT Contribution Payment(DDI Agent Level 2 Test)',
  'currency' => 'RW',
  'type' => 'agents_account.rnit_contribution_payment_by_ddi_dep_agent',
  'customValues' => 
  array (
    'transaction_reference_type' => 'rnit',
    'payer_name' =>$payerName,
    'national_identity_number' => $nid,
    'bank_name' => $bankname,
    'bank_account' => $bankaccount,
    'payer_email'=>$payerEmail,    
    'payer_phone' => $payerPhone,
  ),
  'subject' =>'rnit',
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
public function rnitMobicorePaymentIndependentAgent($amount ,$bankaccount,$nid,$payerName,$payerPhone,$bankname ,$payerEmail,$header) {

  $request=array (
  'amount' =>$amount,
  'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
  'currency' => 'RW',
  'type' => '8241755934761545126',
  'customValues' => 
  array (
    'transaction_reference_type' => 'rnit',
    'payer_name' =>$payerName,
    'national_identity_number' => $nid,
    'bank_name' => $bankname,
    'bank_account' => $bankaccount,
    'payer_email'=>$payerEmail,     
    'payer_phone' => $payerPhone,
  ),
  'subject' =>' 8241755934761496998',
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
    CURLOPT_POSTFIELDS =>json_encode($request) ,
    CURLOPT_HTTPHEADER => array(
        'Authorization:'.$header.'',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
  

    }

    public function rnitMobicorePaymentIndividualClients($amount ,$bankaccount,$nid,$payerName,$payerPhone,$bankname ,$payerEmail,$header) {

  $request=array (
  'amount' =>$amount,
  'description' => 'T8:RNIT Contribution Payment(Post Office Agent Level 2 Test)',
  'currency' => 'RW',
  'type' => 'clients_current_account.rnit_payment',
  'customValues' => 
  array (
    'transaction_reference_type' => 'rnit',
    'payer_name' =>$payerName,
    'national_identity_number' => $nid,
    'bank_name' => $bankname,
    'bank_account' => $bankaccount,
    'payer_email'=>$payerEmail,     
    'payer_phone' => $payerPhone,
  ),
  'subject' =>' 8241755934761496998',
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
    CURLOPT_POSTFIELDS =>json_encode($request) ,
    CURLOPT_HTTPHEADER => array(
        'Authorization:'.$header.'',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
  

    }
}
?>
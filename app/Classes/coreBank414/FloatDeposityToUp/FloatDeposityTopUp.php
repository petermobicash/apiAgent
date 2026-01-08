<?php
namespace App\Classes\coreBank414\FloatDeposityToUp;
use DB;
class FloatDeposityTopUp
{

public function AgentFloatDepostyTopUpBpr($amount,$subject,$header){

$requets=array (
  'amount' => $amount,
  'description' => 'Agent Closing Float Balance Migration(Level 2)',
  'currency' => 'RW',
  'type' => '8241755934761457062',
  'subject' => $subject,
);

  $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($requets),
    CURLOPT_HTTPHEADER => array(
        'Authorization: '.$header.'',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

public function AgentFloatDepostyTopUpCogeBank($amount,$subject,$header){




$requets=array (
  'amount' => $amount,
  'description' => 'Agent Closing Float Balance Migration(Level 2)',
  'currency' => 'RW',
  'type' => '8241755934761549222',
  'subject' => $subject,
);

 

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($requets),
    CURLOPT_HTTPHEADER => array(
        'Authorization: '.$header.'',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}
public function AgentFloatDepostyTopUpBpr($amount,$subject,$header){




$requets=array (
  'amount' => $amount,
  'description' => 'Agent Closing Float Balance Migration(Level 2)',
  'currency' => 'RW',
  'type' => '8241755934761531814',
  'subject' => $subject,
);

 

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/system/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($requets),
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
?>
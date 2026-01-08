<?php
namespace App\Classes\coreBank414\Services\Finance;
use DB;
class BankingTransaction
{

public function cash_in($amount,$payer_name,$payer_phone,$account,$header){


  $request=array (
  'amount' => $amount,
  'description' => 'Client Account Refill(Independent Agent Level 1 Test)',
  'currency' => 'RW',
  'type' => 'agents_account.client_account_refill',
  'customValues' => 
  array (
    'transaction_reference_type' => 'client_account_refill',
    'payer_name' => $payer_name,
    'payer_phone' => $payer_phone,
  ),
  'subject' =>'\''.$account.'',
);
 
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($request));

$headers = array();
$headers[] = 'Authorization:'.$header.'';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);


return $result;

  }
  
   
       

}
?>
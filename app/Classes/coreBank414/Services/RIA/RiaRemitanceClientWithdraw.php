<?php  
namespace App\Classes\coreBank414\Services\RIA;

class RiaRemitanceClientWithdraw{


public function riaRemitanceClientWithdraw($amount,$agentaccount,$confirmationPassword,$Session_Token){




$jayParsedAry= [
   "amount" => $amount, 
   "description" => "RIA Remittance Withdrawal Transaction", 
   "currency" => "RW", 
   "type" => "clients_current_account.riaremittancewith", 
   'subject' =>'\''.$agentaccount.''
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
    'confirmationPassword:'.$confirmationPassword.'',
    'Content-Type: application/json',
    'Session-Token:'.$Session_Token.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;



}

public function riaRemitanceClientWithdrawAuthorisation($transactionRef,$header){

   

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/pending-payments/'.$transactionRef.'/authorize',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "comments": "Client RIA Remittance Funds Withdrawal"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: '.$header.'',
  ),
));

$response = curl_exec($curl);
  
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
 
return $httpcode;







}

}
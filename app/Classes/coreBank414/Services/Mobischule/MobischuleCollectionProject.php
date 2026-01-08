<?php
namespace App\Classes\coreBank414\Services\Mobischule;
use DB; 

class MobischuleCollectionProject
{
    
public function ParenttoSchoolPayment($amount,$student_name,$student_id,$patternofpayment,$schoolIdentify,$header){


    $request=array (
  'amount' => $amount,
  'description' => 'Parent School Fees Payment(Parentt - Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'clients_current_account.schoolfeetransfer',
  'customValues' => 
  array (
    'student_name' => $student_name,
    'student_id' => $student_id,
    'patternofpayment' => $patternofpayment,
  ),
   'subject' =>'\''.$schoolIdentify.'',
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

public function StudenttoSchoolPayment($amount,$student_id,$patternofpayment,$schoolIdentify,$header){

$request= array (
  'amount' => $amount,
  'description' => 'Parent School Fees Payment(Parentt - Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'studentacc.studentpaymenttoschool',
  'customValues' => 
  array (    
    'student_id' => $student_id,
    'patternofpayment' => $patternofpayment,
  ),
  'subject' =>'\''.$schoolIdentify.'',
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
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization:'.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

 
 

} 


public function AgenttoSchoolPayment($amount,$student_id,$student_name,$payer_name,$payer_phone,$patternofpayment,$schoolIdentify,$header){

$request=array (
  'amount' => $amount,
  'description' => 'Agent School Fees Payment(Student - Level 1 Testing)',
  'currency' => 'RW',
  'type' => 'agents_account.agentschoolfees',
  'customValues' => 
  array (
    'student_id' => $student_id,
    'student_name' => $student_name,
    'payer_name' => $payer_name,
    'payer_phone' => $payer_phone,
    'patternofpayment' => $patternofpayment,
  ),
   'subject' =>'\''.$schoolIdentify.'',
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

public function ParenttoMerchantPayments($amount,$payer_name,$payer_phone,$patternofpayment,$MerchentIdentify,$header){



$request=array (
  'amount' => $amount,
  'description' => 'Parent Buying Suppliers from Merchant(Parent - Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'clients_current_account.parent_products_purchases_payment',
  'customValues' => 
  array (
    'payer_name' => $payer_name,
    'payer_phone' => $payer_phone,
    'patternofpayment' => $patternofpayment,
  ),
  'subject' =>'\''.$MerchentIdentify.'',
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
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization:'.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
 

} 

public function StudenttoMerchantPayments($amount,$payer_name,$payer_phone,$patternofpayment,$MerchentIdentify,$header){
 

$request= array (
  'amount' => $amount,
  'description' => 'Student Paying for School Suppliers from Merchant(Student - Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'studentacc.student_products_purchases_payment',
  'customValues' => 
  array (
    'patternofpayment' => $patternofpayment,
  ),
  'subject' =>'\''.$MerchentIdentify.'',
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


public function SchooltoMerchantPayments($amount ,$patternofpayment,$MerchentIdentify,$header){


  $request=array (
  'amount' => $amount,
  'description' => 'School Paying for Teachers Suppliers from Merchant( Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'schoolaccount.school_products_payment',
  'customValues' => 
  array (
    'patternofpayment' => 'School Paying for Teachers Suppliers',
  ),
  'subject' =>'\''.$MerchentIdentify.'',
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
 public function save_fee_($amount,$student_name,$student_id,$transaction_reference_number){


$request=array (
  'amount' =>$amount,
  'description' => 'Student Saving Fee Payment',
  'currency' => 'RW',
  'type' => 'mobicash_agents_account.student_saving_fee_payment',
  'customValues' => 
  array (
    'student_name' => $student_name,
    'student_id' => $student_id,
    'transaction_reference_number' => $transaction_reference_number,
    'patternofpayment' => 'School Fees Payment',
  ),
  'subject' => "'onboardstudent1",
);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

$headers = array();
$headers[] = 'Authorization: Basic bW9iaWFnZ3JlZ2F0b3I6bW9iaWNhc2gxMjM=';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

return $result;


 }

 public function send_save_fee_to_rnit($amount,$student_name,$student_id){

  $request= array (
  'amount' => $amount,
  'description' => 'Student Saving Fee Payment',
  'currency' => 'RW',
  'type' => 'student_saving_account.student_saving_rnit',
  'customValues' => 
  array (
    'student_name' =>$student_name,
    'student_id' =>$student_id,
    'patternofpayment' => 'School Fees Payment',
  ),
  'subject' => 'rnit',
);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://testbox.mobicash.rw/CoreBank/test_box/api/self/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

$headers = array();
$headers[] = 'Authorization: Basic bW9iaWFnZ3JlZ2F0b3I6bW9iaWNhc2gxMjM=';
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
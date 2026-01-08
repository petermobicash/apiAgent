<?php
namespace App\Classes\coreBank414\Services\Governement;
use DB;

class Cbhicollection
{    

public function cbhiDailyCollection($mobicash_ref_no){

       return DB::table("cbhi_notification_status")->select("mobicash_ref_no")->where(DB::raw("mobicash_ref_no"),"$mobicash_ref_no")->get();


}

public function cbhiMutuellePaymentIndividualClients($amountPaid,$payerName,$houseHoldNID,$houseHoldCategory,$householdMemberNumber,$totalPremium,
    $paymentYear,$payerPhoneNumber,$header){


$description='Mutuelle de sante amazina :'.$payerName.' , Indangamuntu :'.$houseHoldNID.' , Yishyuye amafaranga :'.$amountPaid.' , umwaka wa :'.$paymentYear.'.';

  

$request=array (
  'amount' => $amountPaid,
  'description' => $description,
  'currency' => 'RW',
  'type' => 'clients_current_account.cbhi_mutuelle_payment',
  'customValues' => 
  array (
    'transaction_reference_type' => 'cbhi',
    'payer_name' => $payerName,
    'national_identity_number' => $houseHoldNID,
    'payer_economic_status' => $houseHoldCategory,
    'payer_household_member_number' =>$householdMemberNumber,
    'total_premium' => $totalPremium,
    'year_of_payment' => $paymentYear,
  ),
  'subject' => '20000011_15',
);   

  $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/payments??fields=id&fields=transactionNumber&fields=date&fields=amount',
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

}
?>
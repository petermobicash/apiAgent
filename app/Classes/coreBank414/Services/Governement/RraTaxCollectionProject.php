<?php
namespace App\Classes\coreBank414\Services\Governement;
use DB;
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('soap.wsdl_cache_enabled', 0);

class RraTaxCollectionProject
{
    


public function rraTaxPaymentIndividualClients($amountPaid,$rraTaxDescription,$taxDocumentId,$taxIdentificationNumber,$fees,$payerName,$agentIdentify,$header){


    $description='RRA : '.$payerName.' ,Yishyuye amafaranga :'.$amountPaid.'. Umusoro witwa : '.$rraTaxDescription.', Charges:'.$fees.'';

    

  $request=array (
  'amount' => $amountPaid,
  'description' =>$description,
  'currency' => 'RW',
  'type' => 'clients_current_account.rra_tax_payment',
  'customValues' => 
  array (
    'transaction_reference_type' => 'rra_tax',
    'rra_tax_description' => $rraTaxDescription,
    'tax_document_id' => $taxDocumentId,
    'tax_identification_number' =>$taxIdentificationNumber,
    'payer_name' => $payerName,
  ),
  'subject' =>'\''.$agentIdentify.'',
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
public function rraAccountMapping($tax_type_no,$tax_center_no){
    
return DB::connection('mysql')->table('rra_accounts_mapping')->where("tax_type_no" , "$tax_type_no")->Where('tax_center_no' , "$tax_center_no")->get();
}


public function insert_rra_bpr_notification_status($rra_ref,$mcash_ref_no,$amount,$status,$bprResponse){

  $data=array('mcash_ref_no'=>$mcash_ref_no,"rra_ref"=>$rra_ref,'amount'=>$amount,"status"=>$status,'bprResponse'=>$bprResponse);

    DB::table('rra_bpr_notification_status')->insert($data);

}

public function insert_rra_bpr_to_be_notified($bank_ref, $rraRefNo, $dec_id, $tin, $taxPayerName, $amountToPay, $taxTypeNo,$taxTypeDesc,$taxCentreNo, $assessNo,$rraOriginNo){

    $data=array('mcash_ref_no'=>$bank_ref,"rra_ref"=>$rraRefNo,'dec_id'=>$dec_id,"tin_no"=>$tin,'taxpayer_name'=>$taxPayerName,"amount"=> $amountToPay,'tax_type_no'=>$taxTypeNo,"tax_type_desc"=>$taxTypeDesc,"tax_center_no"=>$taxCentreNo,'assess_no'=>$assessNo,"origin_no"=>$rraOriginNo);
    DB::table('rra_bpr_to_be_notified')->insert($data);


}

public function checkChargeesFee($trans_ID, $amount){



       
       if($trans_ID != 'Broker'){        
           
           $trans_ID = 48;
        
        }else{

           $trans_ID =138;
       }
                                                                                                         
                                                                                                       
      return DB::connection('mysql')->table('commission_fees')->where("transaction_id" , "$trans_ID")->where("less_amount", '<=', $amount)->where("great_amount", '>=',$amount)->get();
}

public function bankTransfer($amount,$responseId,$transactionReference){


  $request=array (
  'amount' =>$amount,
  'description' => 'Remote RRA Payment Transfer/BPR Level 2 Testing)',
  'currency' => 'RW',
  'type' => 'governments_account.remote_rra_payment_transfer_bpr',
  'customValues' => 
  array (
    'transaction_reference_type' => 'remote_rra_transfer',
    'transaction_reference_number' =>$responseId,
    'bank_response'=>$transactionReference
  ),
  'subject' => 'system',
);

  $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/8241755934761548198/payments',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($request),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic cG55aXJ1cnVnbzpwZXRlckAwMQ==',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




}

 
 

}
<?php  
namespace App\Classes\coreBank414\Services\RIA;

class RiaRemitanceToTheClient{


public function riaRemitanceToTheClient($orderStatus,$orderDate,$orderTime,$orderNo,$pin,$countryFrom,$CountryTo,$DeliveryMethod,$TransferReason,$customerPaymentId,$customerPaymentMethod,$paymentCurrency,$paymentAmount,$commissionCurrency,$commisionAmount,$custChargeCurrency,$custChargeAmount,$custID,$custTypeID,$custFirstName,$custLastname,$CustCountryOfBirth,$custDateOfBirth,$custOccupationID ,$custOccupation,$custBeneRelationshipID,$custBeneRelationship,$custIdentityType,$custIdentityNumber,$custIDExpirationDate,$custIDIssuedBy,$custIDIssuedByCountry,$custIDIssuedDate,$custAddress,$custState,$custCountryOfResidence,$beneTypeID,$custCity,$custZipCode,$custPhoneCountryCode,$custPhoneNo,$sendingPartnerBranchNo,$beneFirstName,$BeneMiddleName,$BeneLastName,$BenLastName2,$BeneNationality,$BeneAddress,$BeneCountry,$beneCity,$beneState,$beneZipCode,$beneEmailAddress,$bankAccountNo,$bankRoutingCode,$bankValueType,$payingCorrespSequenceID,$payingCorrespID,$payingCorrespLocID,$clientaccount){




 $jayParsedAry = [
   "amount" => $paymentAmount, 
   "description" => "RIA Customer Remittance Deposit Transaction", 
   "currency" => "RW", 
   "type" => "ria_prefund_account.ria_remittance_trans_depo", 
   "customValues" => [
         "orderStatus" => $orderStatus, 
         "orderDate" =>$orderDate, 
         "orderTime" => $orderTime, 
         "orderNo" =>$orderNo, 
         "pin" =>$pin, 
         "countryFrom" =>$countryFrom, 
         "CountryTo" =>$CountryTo, 
         "DeliveryMethod" =>$DeliveryMethod, 
         "TransferReason" =>$TransferReason, 
         "customerPaymentId" =>$customerPaymentId, 
         "customerPaymentMethod" =>$customerPaymentMethod, 
         "paymentCurrency" =>$paymentCurrency, 
         "paymentAmount" =>$paymentAmount, 
         "commissionCurrency" =>$commissionCurrency, 
         "commisionAmount" =>$commisionAmount, 
         "custChargeCurrency" => $custChargeCurrency, 
         "custChargeAmount" =>$custChargeAmount, 
         "custID" =>$custID, 
         "custTypeID" =>$custTypeID, 
         "custFirstName" =>$custFirstName, 
         "custLastname" =>$custLastname, 
         "CustCountryOfBirth" => $CustCountryOfBirth, 
         "custDateOfBirth" =>$custDateOfBirth, 
         "custOccupationID" =>$custOccupationID ,
         "custOccupation" => $custOccupation, 
         "custBeneRelationshipID" =>$custBeneRelationshipID, 
         "custBeneRelationship" =>$custBeneRelationship, 
         "custIdentityType" =>$custIdentityType, 
         "custIdentityNumber" =>$custIdentityNumber, 
         "custIDExpirationDate" =>$custIDExpirationDate, 
         "custIDIssuedBy" => $custIDIssuedBy, 
         "custIDIssuedByCountry" =>$custIDIssuedByCountry, 
         "custIDIssuedDate" =>$custIDIssuedDate, 
         "custAddress" => $custAddress, 
         "custState" => $custState, 
         "custCountryOfResidence" =>$custCountryOfResidence, 
         "beneTypeID" =>$beneTypeID, 
         "custCity" =>$custCity, 
         "custZipCode" =>$custZipCode, 
         "custPhoneCountryCode" =>$custPhoneCountryCode, 
         "custPhoneNo" =>$custPhoneNo, 
         "sendingPartnerBranchNo" =>$sendingPartnerBranchNo, 
         "beneFirstName" =>$beneFirstName, 
         "BeneMiddleName" =>$BeneMiddleName, 
         "BeneLastName" =>$BeneLastName, 
         "BenLastName2" =>$BenLastName2, 
         "BeneNationality" =>$BeneNationality, 
         "BeneAddress" =>$BeneAddress, 
         "BeneCountry" =>$BeneCountry, 
         "beneCity" =>$beneCity, 
         "beneState" =>$beneState, 
         "beneZipCode" =>$beneZipCode, 
         "beneEmailAddress" => $beneEmailAddress, 
         "bankAccountNo" => $bankAccountNo, 
         "bankRoutingCode" =>$bankRoutingCode, 
         "bankValueType" =>$bankValueType, 
         "payingCorrespSequenceID" =>$payingCorrespSequenceID, 
         "payingCorrespID" =>$payingCorrespID, 
         "payingCorrespLocID" =>$payingCorrespLocID 
      ], 
   "subject" =>$clientaccount 
]; 
 
 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/ria_prefund_user/payments',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Basic cmlhX3ByZWZ1bmRfdXNlcjptb2JpY2FzaEAxMjM='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return  $response;

}



}
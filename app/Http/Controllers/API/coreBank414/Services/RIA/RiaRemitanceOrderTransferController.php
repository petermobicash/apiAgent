<?php 
namespace App\Http\Controllers\API\coreBank414\Services\RIA; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\RIA\ReadRiaRemitanceOrdersReadyFileToBePaid;
use App\Classes\coreBank414\Services\RIA\RiaRemitanceToTheClient;
 
class RiaRemitanceOrderTransferController extends BaseController
{
    public function riaRemitanceOrderTransfer(Request $request){

 
        $validator = Validator::make($request->all(), [

            "orderNo"=>'required',
            "PIN"=>'required',
            "clientaccount"=>'required'                 
            
            ]);
  
            if ($validator->fails()) {
  
                $error=json_decode($validator->errors());

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "data validation" ,                   
                "data" =>$error,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
  
            }

                // $date = date('Y-m-d H:i:s');

                // $mcashResponse = [ "responseCode" =>100, 
                // "communicationStatus" =>'SUCCESS', 
                // "codeDescription" =>"SUCCESS",                   
                // "data" =>'M25295',
                // "responseDate"=>$date

                // ]; 
                // return $mcashResponse ;
    
     
         $ReadRiaRemitanceOrdersReadyFileToBePaidDownloadable = new ReadRiaRemitanceOrdersReadyFileToBePaid();
         $riaResponse = $ReadRiaRemitanceOrdersReadyFileToBePaidDownloadable->selectLogRiaRemitanceOrdersReadyFileToBePaidDownload($request->input("orderNo"),$request->input("PIN"));

         


         $riaResponseArray= json_decode($riaResponse[0]->FullOrderApiResponse);     

           // $riaResponseArray=$riaResponseArray->Ria->ProductID;  
           

         if(!empty($riaResponseArray)){            



          if(isset($riaResponseArray->Transaction)){  


$orderStatus=$riaResponseArray->Transaction->OrderStatus;
$OrderDate=$riaResponseArray->Transaction->OrderDate ;
$OrderTime=$riaResponseArray->Transaction->OrderTime ;
$OrderNo=$riaResponseArray->Transaction->OrderNo;
$PIN=$riaResponseArray->Transaction->PIN;
$countryFrom=$riaResponseArray->Transaction->CountryFrom; 
$CountryTo=$riaResponseArray->Transaction->CountryTo;
$DeliveryMethod=$riaResponseArray->Transaction->DeliveryMethod;
$TransferReason=$riaResponseArray->Transaction->TransferReason;
$customerPaymentId=$riaResponseArray->Transaction->CustPaymentMethodID;
$CustPaymentMethod=$riaResponseArray->Transaction->CustPaymentMethod;
$paymentCurrency=$riaResponseArray->Quotation->PaymentCurrency;
$paymentAmount=$riaResponseArray->Quotation->BeneAmount;
$commissionCurrency=$riaResponseArray->Quotation->CommissionCurrency;
$commisionAmount=$riaResponseArray->Quotation->PaymentAmount;
$custChargeCurrency=$riaResponseArray->Quotation->CustChargeCurrency;
$custChargeAmount=$riaResponseArray->Quotation->CustChargeAmount;
$custID=$riaResponseArray->Customer->RiaInformation->CustID;
$custTypeID=$riaResponseArray->Customer->RiaInformation->CustTypeID;
$custFirstName=$riaResponseArray->Customer->PersonalInformation->CustFirstName;
$custLastname=$riaResponseArray->Customer->PersonalInformation->CustLastName;
$CustCountryOfBirth=$riaResponseArray->Customer->PersonalInformation->CustCountryOfBirth;
$custDateOfBirth=$riaResponseArray->Customer->PersonalInformation->CustDateOfBirth; 
$custOccupationID="";
$custOccupation=$riaResponseArray->Customer->PersonalInformation->CustOccupation;
$custBeneRelationshipID="";
$custIdentityType=$riaResponseArray->Customer->IdentityDocument->CustID1Type;
$custBeneRelationship=""; 
$custIdentityNumber=$riaResponseArray->Customer->IdentityDocument->CustID1No;
$custIDExpirationDate=$riaResponseArray->Customer->IdentityDocument->CustID1ExpirationDate;
$custIDIssuedBy=$riaResponseArray->Customer->IdentityDocument->CustID1IssuedBy;
$custIDIssuedByCountry=$riaResponseArray->Customer->IdentityDocument->CustID1IssuedByCountry;
$custIDIssuedDate=$riaResponseArray->Customer->IdentityDocument->CustID1IssuedDate;
$custAddress=$riaResponseArray->Customer->Residence->CustAddress;
$custState=$riaResponseArray->Customer->Residence->CustState;
$custCountryOfResidence=$riaResponseArray->Customer->Residence->CustCountryofResidence;
$beneTypeID="";
$custCity=$riaResponseArray->Customer->Residence->CustCity;
$custZipCode=$riaResponseArray->Customer->Residence->CustZipCode;
$custPhoneCountryCode=$riaResponseArray->Customer->ContactDetails->CustPhoneCountryCode;
$custPhoneNo=$riaResponseArray->Customer->ContactDetails->CustPhoneNo;
$sendingPartnerBranchNo=$riaResponseArray->SendingPartner->SendingCorrespBranchNo;
$BeneFirstName=$riaResponseArray->Beneficiary->PersonalInformation->BeneFirstName;
$BeneLastName=$riaResponseArray->Beneficiary->PersonalInformation->BeneLastName;
$BenLastName2=$riaResponseArray->Beneficiary->PersonalInformation->BeneLastName2;
$BeneMiddleName=$riaResponseArray->Beneficiary->PersonalInformation->BeneLastName2;
$BeneNationality=$riaResponseArray->Beneficiary->PersonalInformation->BeneMiddleName; 
$CustFirstName=$riaResponseArray->Customer->PersonalInformation->CustFirstName;
$CustLastName=$riaResponseArray->Customer->PersonalInformation->CustLastName; 

$BeneAddress=$riaResponseArray->Beneficiary->Residence->BeneAddress;
$BeneCountry=$riaResponseArray->Beneficiary->Residence->BeneCountry;
$beneCity=$riaResponseArray->Beneficiary->Residence->BeneCity;
$beneState=$riaResponseArray->Beneficiary->Residence->BeneState;
$beneZipCode=$riaResponseArray->Beneficiary->Residence->BeneZipCode;
$beneEmailAddress=$riaResponseArray->Beneficiary->ContactDetails->BeneEmailAddress; 
$bankAccountNo=$riaResponseArray->Beneficiary->BankAccount->BankAccountNo;
$bankRoutingCode=$riaResponseArray->Beneficiary->BankAccount->BankRoutingCode;
$bankValueType=$riaResponseArray->Beneficiary->BankAccount->Valuetype;
$payingCorrespSequenceID=$riaResponseArray->PayoutPartner->PayingCorrespSequenceID;
$payingCorrespID=$riaResponseArray->PayoutPartner->PayingCorrespID;
$payingCorrespLocID=$riaResponseArray->PayoutPartner->PayingCorrespLocID;               

$riaRemitanceToTheClient = new RiaRemitanceToTheClient();


// echo $custCity;
// exit();

$mobicoreResponseRiaRemitanceToTheClient = $riaRemitanceToTheClient->riaRemitanceToTheClient($orderStatus,$OrderDate,$OrderTime,$OrderNo,$PIN,$countryFrom,$CountryTo,$DeliveryMethod,$TransferReason,$customerPaymentId,$CustPaymentMethod,$paymentCurrency,$paymentAmount,$commissionCurrency,$commisionAmount,$custChargeCurrency,$custChargeAmount,$custID,$custTypeID,$custFirstName,$custLastname,$CustCountryOfBirth,$custDateOfBirth,$custOccupationID ,$custOccupation,$custBeneRelationshipID,$custBeneRelationship,$custIdentityType,$custIdentityNumber,$custIDExpirationDate,$custIDIssuedBy,$custIDIssuedByCountry,$custIDIssuedDate,$custAddress,$custState,$custCountryOfResidence,$beneTypeID,$custCity,$custZipCode,$custPhoneCountryCode,$custPhoneNo,$sendingPartnerBranchNo,$BeneFirstName,$BeneMiddleName,$BeneLastName,$BenLastName2,$BeneNationality,$BeneAddress,$BeneCountry,$beneCity,$beneState,$beneZipCode,$beneEmailAddress,$bankAccountNo,$bankRoutingCode,$bankValueType,$payingCorrespSequenceID,$payingCorrespID,$payingCorrespLocID,$request->clientaccount);

// echo $mobicoreResponseRiaRemitanceToTheClient;
// exit();

            $mobicoreResponseRiaRemitanceToTheClientArray=json_decode($mobicoreResponseRiaRemitanceToTheClient);


            if (isset($mobicoreResponseRiaRemitanceToTheClientArray->transactionNumber)){


                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" =>100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" =>"SUCCESS",                   
                "data" =>$mobicoreResponseRiaRemitanceToTheClientArray->transactionNumber,
                "responseDate"=>$date

                ]; 
                return $mcashResponse ;



            }else{

           if(isset($mobicoreResponseRiaRemitanceToTheClientArray->code)){

                        $mobicoreResponse1=$mobicoreResponseRiaRemitanceToTheClientArray->code;

                        if($mobicoreResponse1=="login"){


                        if(isset($mobicoreResponseRiaRemitanceToTheClientArray->passwordStatus)){

                        $code=102;
                        $codeDescription="Password is temporarily blocked";

                        }elseif(isset($mobicoreResponseRiaRemitanceToTheClientArray->userStatus)){

                        $code=101;
                        $codeDescription="User is ".$mobicoreResponseRiaRemitanceToTheClientArray->userStatus;

                        }else{

                        $code=103;
                        $codeDescription="Invalid authentication";
                        }
                        }elseif($mobicoreResponse1=="insufficientBalance"){

                        $code=106;
                        $codeDescription="Insufficient Balance";

                        }else{


                        $code=107;

                        $codeDescription="FAILURE";


                        }


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>$code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$mobicoreResponseRiaRemitanceToTheClientArray,
                        "responseDate"=>$date

                        ];

                        return $mcashResponse;

                        }else{

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$mobicoreResponseRiaRemitanceToTheClientArray,
                        "responseDate"=>$date

                        ];

                        return $mcashResponse;


                        }

                               
          
       


         }                 
                       
            }else{



               $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"they are no transaction pending",                   
                "data" =>$riaResponseArray,
                "responseDate"=>$date

                ];

                return $mcashResponse ;                
                 

            }
        }else{
                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"RIA is not responding",                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

        }


                  

   
  
}

}
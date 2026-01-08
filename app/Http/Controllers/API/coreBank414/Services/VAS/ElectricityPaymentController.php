<?php   
namespace App\Http\Controllers\API\coreBank414\Services\VAS; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\VAS\ElectricityService;
use App\Classes\coreBank414\Services\Utilities\MobicoreUtilites;
use DB;
class ElectricityPaymentController extends BaseController
{
    public function cashPowerMeterNumberValidation(Request $request){


 
        $validator = Validator::make($request->all(), [

            "meterNumber"=>'required'                 
            
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
        

           
        
         $ElectricityService = new ElectricityService();
         $electricityPoviderResponse = $ElectricityService->getCashpowerMeterDetails($request->input("meterNumber")); 

         echo $electricityPoviderResponse;
         exit();

         $electricityPoviderResponse1= json_decode($electricityPoviderResponse);        

             

         if(!empty($electricityPoviderResponse1)){            



          if( $electricityPoviderResponse1!="" && $electricityPoviderResponse1->status==1){


                        $clientNmane=$electricityPoviderResponse1->consumer->customerName ;         
                        $location=$electricityPoviderResponse1->consumer->location ;
                        $minAmount=$electricityPoviderResponse1->consumer->minAmount;
                        $maxAmount=$electricityPoviderResponse1->consumer->maxAmount;   



                        $responseData=[

                            'customerName' => $clientNmane,
                            'location' => $location,
                            'minAmount' => $minAmount,
                            'maxAmount' =>10000000,                    
                       ];


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>100, 
                        "communicationStatus" =>'SUCCESS', 
                        "codeDescription" =>"SUCCESS",                   
                        "data" =>$responseData,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ; 
           
      }else{



               $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>$electricityPoviderResponse1->message ,                   
                "data" =>$electricityPoviderResponse1 ,
                "responseDate"=>$date

                ];

                return $mcashResponse ;




                
                 
          
        }
    }else{
                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Provider is not responding",                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

        }


                  

   
  
}
public function electricityPayment(Request $request){

    $validator = Validator::make($request->all(), [
        "amount"=>'required',
        "payerName"=>'required',
        "payerPhone"=>'required',        
        "taxIdentificationNumber"=>'required',
        "meterNumber"=>'required',        
        "brokering"=>'required' ,
        "userGroup"=>'required' 
        ]);

        if ($validator->fails()) {

            $error=json_decode($validator->errors());

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Data validation" ,                   
                "data" =>$error,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

        }

        $header = $request->header('Authorization');



           
            if ($request->isJson()){ 
         
         
          $ElectricityService = new ElectricityService();
          $utility = new MobicoreUtilites();        

         try{
        

                if($request->brokering=="Broker" && $request->userGroup=="retail_agents"){



                $mobicoreResponse=$ElectricityService->electricityPaymentDependentAgent($request->amount,$request->payerName,$request->payerPhone,$request->taxIdentificationNumber,$request->meterNumber,$header);

                }
                if(($request->brokering=="Independent" && $request->userGroup=="retail_agents") || ($request->brokering=="Independent" && $request->userGroup=="sacco_mfi")){

                 $mobicoreResponse=$ElectricityService->electricityPaymentIndependentAgent($request->amount,$request->payerName,$request->payerPhone,$request->taxIdentificationNumber,$request->meterNumber ,$header);

                }


                if($request->brokering=="DDI_Broker" && $request->userGroup=="retail_agents"){

                 $mobicoreResponse=$ElectricityService->electricityPaymentDdinDependentAgent($request->amount,$request->payerName,$request->payerPhone,$request->taxIdentificationNumber,$request->meterNumber ,$header);

                }


                if($request->brokering=="Independent" && $request->userGroup=="Individual_clients"){

                $mobicoreResponse=$ElectricityService->electricityPaymentIndividualClients($request->amount,$request->payerName,$request->payerPhone,$request->taxIdentificationNumber,$request->meterNumber ,$header); 
                }               
           
           

         $mobicoreResponse=json_decode($mobicoreResponse);



          if (isset($mobicoreResponse->transactionNumber)){
          

          $electricityPoviderResponse = $ElectricityService->buyElectricity($request->meterNumber,$request->amount,$request->payerPhone,$mobicoreResponse->transactionNumber); 

          
          $electricityPoviderResponse1 = json_decode($electricityPoviderResponse);

          if(isset($electricityPoviderResponse1->vend->token)){       
    

                   $authorization_code= $ElectricityService->electricityPaymentAUtorisation($mobicoreResponse->transactionNumber,$electricityPoviderResponse1->vend);                   

                   $transfer_code= $ElectricityService->bankTransfer($request->amount,$mobicoreResponse->transactionNumber,$mobicoreResponse->transactionNumber,$electricityPoviderResponse1->vend); 

                   $ElectricityService->saveElectricityLog($mobicoreResponse->id,$mobicoreResponse->transactionNumber, $request->payerPhone,$request->payerName,$request->amount,$authorization_code,$transfer_code,$electricityPoviderResponse1->vend,$electricityPoviderResponse1->status);                     

              $responseData=[

                      'mobicashTransctionNo'=>$mobicoreResponse->transactionNumber,
                      'meterNo' =>$electricityPoviderResponse1->vend->meterNo,
                      'receiptNo' =>$electricityPoviderResponse1->vend->receiptNo,
                      'invoiceTaxNo' =>$electricityPoviderResponse1->vend->invoiceTaxNo,
                      'vendDate' =>$electricityPoviderResponse1->vend->vendDate,
                      'vendTime' =>$electricityPoviderResponse1->vend->vendTime,
                      'invoiceRef' => $electricityPoviderResponse1->vend->invoiceRef,
                      'customerName' => $electricityPoviderResponse1->vend->customerName,  
                      'tokenCount' =>$electricityPoviderResponse1->vend->tokenCount,
                      'token' =>$electricityPoviderResponse1->vend->token,
                      'units' =>$electricityPoviderResponse1->vend->units,
                      'amountTendered' =>$electricityPoviderResponse1->vend->amountTendered,
                      'amountPaid' =>$electricityPoviderResponse1->vend->amountPaid,
                      'electricityAmount' =>$electricityPoviderResponse1->vend->electricityAmount,
                      'unitPrice' =>$electricityPoviderResponse1->vend->unitPrice,
                      'taxAmount' => $electricityPoviderResponse1->vend->taxAmount,
                      'taxRate' =>$electricityPoviderResponse1->vend->taxRate,   
                      'taxDesc' => $electricityPoviderResponse1->vend->taxDesc,
  
           ];


                    $date = date('Y-m-d H:i:s');


                    $mcashResponse = [ "responseCode" => 100, 
                    "communicationStatus" =>'SUCCESS', 
                    "codeDescription" =>"Payment is successfully received",                   
                    "data" =>$responseData,
                    "responseDate"=>$date

                    ];

                    return $mcashResponse;


              

          }else{

                
                  
                $transfer_code='00';
                $authorization_code=$ElectricityService->electricityPaymentDeny($mobicoreResponse->transactionNumber,$electricityPoviderResponse1);              

                $authorization_code1='00';
                $ElectricityService->saveElectricityLog($mobicoreResponse->transactionNumber,$request->meterNumber, $request->payerPhone,$request->payerName,$request->amount,$authorization_code,$transfer_code,$electricityPoviderResponse1->vend,$electricityPoviderResponse1->status);

              
                 

                    $date = date('Y-m-d H:i:s');

                    $mcashResponse = [ "responseCode" => 104, 
                    "communicationStatus" =>'FAILURE', 
                    "codeDescription" =>"FAILURE",                   
                    "data" =>$electricityPoviderResponse1->message,
                    "responseDate"=>$date

                    ];


                    return $mcashResponse; 
              
              }


         }else{

           if(isset($mobicoreResponse->code)){

                           $mobicoreResponse1=$mobicoreResponse->code;

                           if($mobicoreResponse1=="login"){


                            if(isset($mobicoreResponse->passwordStatus)){

                            $code=102;
                            $codeDescription="Password is temporarily blocked";

                            }elseif(isset($mobicoreResponse->userStatus)){

                             $code=101;
                            $codeDescription="User is ".$mobicoreResponse->userStatus;

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
        
                           }


                             $date = date('Y-m-d H:i:s');

                            $mcashResponse = [ "responseCode" => $code, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>$codeDescription,                   
                            "data" =>$mobicoreResponse,
                            "responseDate"=>$date
                    
                   ];

                   return $mcashResponse;

                                  
          
       


         }

         }catch(Exeption $ex){

            $date = date('Y-m-d H:i:s');

              $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"FAILURE",                   
                            "data" =>$x->getMessage(),
                            "responseDate"=>$date
                    
                   ];

                   return $mcashResponse; 

         } 

              

       

                  

}else{

             $date = date('Y-m-d H:i:s');

              $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"Content type Not Allowed",                   
                            "data" =>"",
                            "responseDate"=>$date
                    
                   ];

                   return $mcashResponse;

       
}

 
  


}


}
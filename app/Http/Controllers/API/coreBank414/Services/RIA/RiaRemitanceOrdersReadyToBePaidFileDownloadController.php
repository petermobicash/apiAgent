<?php 
namespace App\Http\Controllers\API\coreBank414\Services\RIA; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\RIA\ReadRiaRemitanceOrdersReadyFileToBePaid;
 
class RiaRemitanceOrdersReadyToBePaidFileDownloadController extends BaseController
{
    public function readRiaRemitanceOrdersReadyFileToBePaid(Request $request){


 
        $validator = Validator::make($request->all(), [

            "orderNo"=>'required',
            "PIN"=>'required'                 
            
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

           
        
         $ReadRiaRemitanceOrdersReadyFileToBePaidDownloadable = new ReadRiaRemitanceOrdersReadyFileToBePaid();
         $riaResponse = $ReadRiaRemitanceOrdersReadyFileToBePaidDownloadable->readRiaRemitanceOrdersReadyFileToBePaid($request->input("orderNo"),$request->input("PIN")); 

         $riaResponseArray= json_decode($riaResponse);        

             

         if(!empty($riaResponseArray)){            



          if(isset($riaResponseArray->value->Response->Order->Transaction)){        



            $OrderDate=$riaResponseArray->value->Response->Order->Transaction->OrderDate ;
            $OrderTime=$riaResponseArray->value->Response->Order->Transaction->OrderTime ;
            $OrderNo=$riaResponseArray->value->Response->Order->Transaction->OrderNo;
            $PIN=$riaResponseArray->value->Response->Order->Transaction->PIN;
            $TransferReason=$riaResponseArray->value->Response->Order->Transaction->TransferReason;
            $CustPaymentMethod=$riaResponseArray->value->Response->Order->Transaction->CustPaymentMethod;
            $BeneFirstName=$riaResponseArray->value->Response->Order->Beneficiary->PersonalInformation->BeneFirstName;
            $BeneLastName=$riaResponseArray->value->Response->Order->Beneficiary->PersonalInformation->BeneLastName;
            $BeneAmount=$riaResponseArray->value->Response->Order->Quotation->BeneAmount;
            $CustFirstName=$riaResponseArray->value->Response->Order->Customer->PersonalInformation->CustFirstName;
            $CustLastName=$riaResponseArray->value->Response->Order->Customer->PersonalInformation->CustLastName;


         $insertresponseriaResponse = $ReadRiaRemitanceOrdersReadyFileToBePaidDownloadable->logRiaRemitanceOrdersReadyFileToBePaidDownload($OrderDate,$OrderTime,$OrderNo,$PIN,$TransferReason,$CustPaymentMethod,$BeneFirstName,$BeneLastName,$BeneAmount,$CustFirstName,$CustLastName,json_encode($riaResponseArray->value->Response->Order));

                



                         $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>100, 
                        "communicationStatus" =>'SUCCESS', 
                        "codeDescription" =>"SUCCESS",                   
                        "data" =>$riaResponseArray->value->Response->Order,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ; 
           
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
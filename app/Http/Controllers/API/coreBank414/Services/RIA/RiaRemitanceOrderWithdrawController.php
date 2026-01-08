<?php 
namespace App\Http\Controllers\API\coreBank414\Services\RIA; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\RIA\RiaRemitanceClientWithdraw;
 
class RiaRemitanceOrderWithdrawController extends BaseController
{
    public function riaRemitanceClientWithdraw(Request $request){

        // echo $request;
        // exit();
 
        $validator = Validator::make($request->all(), [

            "amount"=>'required',
            "agentaccount"=>'required'                 
            
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
        
         $riaRemitanceClientWithdraw = new RiaRemitanceClientWithdraw();

          
         $riaMobicoreResponse = $riaRemitanceClientWithdraw->riaRemitanceClientWithdraw($request->amount,$request->agentaccount,$request->header('Confirmation-Pin'),$request->header('Session-Token')); 


        

        $riaMobicoreResponseArray= json_decode($riaMobicoreResponse);    

            
         if(!empty($riaMobicoreResponseArray)){         



          if(isset($riaMobicoreResponseArray->transactionNumber)){  

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" =>100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" =>"SUCCESS",                   
                "data" =>$riaMobicoreResponseArray->transactionNumber,
                "responseDate"=>$date

                ]; 
                return $mcashResponse ;
           
       }else{

            if(isset($riaMobicoreResponseArray->code)){

                        $mobicoreResponse1=$riaMobicoreResponseArray->code;

                        if($mobicoreResponse1=="login"){


                        if(isset($riaMobicoreResponseArray->passwordStatus)){

                        $code=102;
                        $codeDescription="Password is temporarily blocked";

                        }elseif(isset($riaMobicoreResponseArray->userStatus)){

                        $code=101;
                        $codeDescription="User is ".$riaMobicoreResponseArray->userStatus;

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

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$riaMobicoreResponseArray,
                        "responseDate"=>$date

                        ];

                        return $mcashResponse;

                        }                 

            }
                
                 
          
        
        }else{
                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Mobicore is not responding",                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

        }               

   
  
}

public function riaRemitanceClientWithdrawAuhorization(Request $request){
 
        $validator = Validator::make($request->all(), [

            "mobicashref"=>'required'                             
            
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

            $header=$request->header('Authorization');   
        
             $riaRemitanceClientWithdrawAuthorization = new RiaRemitanceClientWithdraw();
             $riaMobicoreResponse = $riaRemitanceClientWithdrawAuthorization->riaRemitanceClientWithdrawAuthorisation($request->mobicashref,$header); 



            if(isset($riaMobicoreResponse)){


            if($riaMobicoreResponse==204){

                $communicationStatus='SUCCESS';
                $codeDescription='SUCCESS';

            }else{


                $communicationStatus='FAILURE';
                $codeDescription='FAILURE';


            } 

            $date = date('Y-m-d H:i:s');

            $mcashResponse = [ "responseCode" =>$riaMobicoreResponse, 
            "communicationStatus" =>$communicationStatus, 
            "codeDescription" =>$communicationStatus,                   
            "data" =>"",
            "responseDate"=>$date

            ]; 
            return $mcashResponse ; 

            }else{
            $date = date('Y-m-d H:i:s');
            $mcashResponse = [ "responseCode" => 104, 
            "communicationStatus" =>'FAILURE', 
            "codeDescription" =>"Mobicore is not responding",                   
            "data" =>"",
            "responseDate"=>$date

            ];

            return $mcashResponse ;

            }               

   
  
}

}
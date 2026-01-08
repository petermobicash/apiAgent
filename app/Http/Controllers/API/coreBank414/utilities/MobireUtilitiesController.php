<?php
namespace App\Http\Controllers\API\coreBank414\utilities;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Utilities\MobicoreUtilites; 
 


/**
 * @group rssb mutuelle de sante
 *
 * API endpoints for managing mutuelle de sante
 */

class MobireUtilitiesController extends BaseController{

    public function SelfSearchByUsername(Request $request){

        if($request->header('Authorization')){ 

        $SelfSearchByUsername = new MobicoreUtilites();

        $useInfos= $SelfSearchByUsername->SelfSearchByUsername($request->userIdentity,$request->header('Authorization'));

        $userInfos=json_decode($useInfos);

        

        if(isset($userInfos->brokers)){

          $mcashResponse =["responseCode" => 200,
                        "status"=>"success",
                        "type"=>"Dependent",
                         

                                                       
                          ];

        }else{

            $mcashResponse =["responseCode" =>'',
                        "status"=>"success",
                        "response"=>'Independent'
                        
                                                       
                          ];
        }
       
    }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>"Authorization please"                    
                                                       
                          ];
                 
          }

return $mcashResponse;

    
    
}

public function userSearchByAdmin(Request $request){

        if($request->header('Authorization')){ 

         

        $SelfSearchByUsername = new MobicoreUtilites();

        $useInfos= $SelfSearchByUsername->userSearchByAdmin($request->account,$request->header('Authorization'));

        
        $userInfos=json_decode($useInfos);

        

        if(isset($userInfos->id)){

          $mcashResponse =["responseCode" => 200,
                           "status"=>1,
                           "names"=>$userInfos->display
                         

                                                       
                          ];

        }else{

            $mcashResponse =["responseCode" =>400,
                        "status"=>"success",
                        "response"=>$userInfos
                        
                                                       
                          ];
        }
       
    }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>"Authorization please"                    
                                                       
                          ];
                 
          }

return $mcashResponse;

    
    
}

public function userFloatTopUpBprBank(Request $request){       

         

        $floatTopUp = new MobicoreUtilites();

        $header="Basic YnByZmxvYXRhZG1pbjpiQnIjMTJmTG9hVCFhZG1pbkAx";

        $response= $floatTopUp->userFloatTopUpBprBank($request->amount,$request->bankDate,$request->bankTransactionRef,$request->userAccount,$header);

        
        $response=json_decode($response);

        

        if(isset($response->id)){

          $mcashResponse =["responseCode" => 200,
                           "status"=>1,
                           "transactionReference"=>$response->id
                         

                                                       
                          ];

        }else{

            $mcashResponse =["responseCode" =>400,
                        "status"=>"success",
                        "response"=>$response
                        
                                                       
                          ];
        }
       
    

return $mcashResponse;

    
    
}


public function viewTransactionById(Request $request){

        if($request->header('Authorization')){ 

        $viewTransactionById = new MobicoreUtilites();

        $transactionByIdInfo= $viewTransactionById->viewTransactionById($request->transactionId,$request->header('Authorization'));
        

        $transactionByIdInfo=json_decode($transactionByIdInfo); 


        

        if(isset($transactionByIdInfo->children)){

          $mcashResponse =["responseCode" => 200,
                        "status"=>"success",
                        "transactioninfos"=>$transactionByIdInfo->children                       

                                                       
                          ];

        }else{

            $mcashResponse =["responseCode" =>400,
                        "status"=>"success",
                        "transactioninfos"=>$transactionByIdInfo
                        
                                                       
                          ];
        }
       
    }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>"Authorization please"                    
                                                       
                          ];
                 
          }

return $mcashResponse;

    
    
}

public function agentAccountSummary(Request $request){

if($request->header('Authorization')){ 

        $viewTransactionById = new MobicoreUtilites();

        $transactionByIdInfo= $viewTransactionById->agentAccount($request->header('Authorization'));   

        // echo $transactionByIdInfo;
        // exit();      



        $response=json_decode($transactionByIdInfo);

        if(empty($response)){           

                        $date = date('Y-m-d H:i:s');
                        $mcashResponse = [ "responseCode" => 104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>"EMPTY RESPONSE",                   
                        "data" =>"",
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  


       }

        if(isset($response->code)){

           if(isset($response->code)){

                           $mobicoreResponse1=$response->code;

                           if($mobicoreResponse1=="login"){


                            if(isset($response->passwordStatus)){

                            $code=102;
                            $codeDescription="Password is temporarily blocked";

                            }elseif(isset($response->userStatus)){

                             $code=101;
                            $codeDescription="User is ".$response->userStatus;

                            }else{

                            $code=103;
                            $codeDescription="Invalid authentication";
                            }
                           }else{                          


                            $code=107;

                            $codeDescription="FAILURE";
                       


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                           }

      }


        $arrayLength = count($response);         

        $arrayResponse = array();
        $i = 0;

        while ($i < $arrayLength)
        {
                
               $userTransactionBydata[]=$response[$i];
               $userTransactionBydata= array(
                                             "account_name"=>$response[$i]->type->name,
                                             "details"=>$response[$i]->status
                                              
                                           );

              array_push($arrayResponse,$userTransactionBydata);


            
            $i++;
        }      
       
        if(isset($arrayResponse)){


              



                        $date = date('Y-m-d H:i:s');
                        $mcashResponse = [ "responseCode" =>100, 
                        "communicationStatus" =>'SUCCESS', 
                        "codeDescription" =>"SUCCESS",                   
                        "data" =>$arrayResponse,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;           

         

        }
       
    }else{

                         $date = date('Y-m-d H:i:s');
                        $mcashResponse = [ "responseCode" =>105, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>"Data validation(Authorization please)",                   
                        "data" =>"$request->header('Authorization')" ,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ; 

        


                 
          }

 
    
    
}


public function agentCommssonWithdrawal(Request $request){

if($request->header('Authorization')){ 

    $commissionwithdrawal = new MobicoreUtilites();

    $commissionwithdrawalResponse= $commissionwithdrawal->commissionWithdrawal($request->amount,$request->header('Authorization'));
    $responseWithdrawal=json_decode($commissionwithdrawalResponse);

    if(isset($responseWithdrawal->transactionNumber)){     //withdrawal success


        $commissionPaymentResponse= $commissionwithdrawal->commissionPayment($request->amount,$request->header('Authorization'));
        $PaymentResponse=json_decode($commissionPaymentResponse);

        if(isset($PaymentResponse->transactionNumber)){
          

            $date = date('Y-m-d H:i:s');
            $mcashResponse = [ "responseCode" =>100, 
            "communicationStatus" =>'SUCCESS', 
            "codeDescription" =>"Commission selfserve is successfully done",                   
            "data" =>$PaymentResponse->transactionNumber,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;


        }else{

            $date = date('Y-m-d H:i:s');
            $mcashResponse = [ "responseCode" =>104, 
            "communicationStatus" =>'FAILURE', 
            "codeDescription" =>"payment Failed",                   
            "data" =>$PaymentResponse,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;

            
        }

    }else{

            //withdrawal 
            $date = date('Y-m-d H:i:s');
            $mcashResponse = [ "responseCode" =>104, 
            "communicationStatus" =>'FAILURE', 
            "codeDescription" =>"withrawal Failed",                   
            "data" =>$commissionwithdrawalResponse,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;


    }

}else{

    $date = date('Y-m-d H:i:s');
    $mcashResponse = [ "responseCode" =>105, 
    "communicationStatus" =>'FAILURE', 
    "codeDescription" =>"Data validation(Authorization please)",                   
    "data" =>"" ,
    "responseDate"=>$date

    ]; 
    return $mcashResponse ; 


}
}


public function mobiCashDelayedCommissionCollection(Request $request){


    $mobiCashDelayedCommissionCollection = new MobicoreUtilites();

    return $mobiCashDelayedCommissionCollection->mobiCashDelayedCommissionCollection($request->amount,$request->mobicashRefNumber);


}

public function makeDelayedCommissionSelfPayment(Request $request){

    if($request->header('Authorization')){ 

    $makeDelayedCommissionSelfPayment = new MobicoreUtilites();

    return $makeDelayedCommissionSelfPayment->makeDelayedCommissionSelfPayment($request->amount,$request->agentphone,$request->header('Authorization'));
  }else{ 

    // Handle generic exceptions
    return response()->json(['statusCode' =>401,'error' => 'Authorization'], 401);

  }


}

public function agentDelayedCommission(Request $request){


    $agentDelayedCommission = new MobicoreUtilites();

    return $agentDelayedCommission->agentDelayedCommission($request->amount,$request->mobicashRefNumber,$request->agentphone);


}


}
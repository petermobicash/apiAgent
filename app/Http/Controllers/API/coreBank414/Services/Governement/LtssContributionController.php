<?php   
namespace App\Http\Controllers\API\coreBank414\Services\Governement;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Governement\LtssProject; 
use App\Classes\coreBank414\Services\Utilities\tokenValidation;  

/**
 * @group LTSS
 *
 * API endpoints for managing LTSS
 */
class LtssContributionController extends BaseController
{

    public function nidLtssValidation(Request $request){       

      
      $validator = Validator::make($request->all(), [
        "identification"=>'required|alpha_num|max:16|min:8'       
        
    ]);
    if($validator->fails()){

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
                  
                 

                 $service='ltss';
                 $gettoken=new TokenValidation();
                 $authentification = new LtssProject();
                
        
                 $user=$gettoken->selectToken($service);  

                if (isset($user)&&(!empty ($user->token))) {

                $user= json_encode($user);
                $user=json_decode($user);
                

                $currenttoken = $user->token;

                    
                    $break_1_start = $user->date;           
                    $break_1_ends = date("Y-m-d H:i");
                    
                    $datetime1 = new DateTime($break_1_start);
                    $datetime2 = new DateTime($break_1_ends);
                    $interval = $datetime1->diff($datetime2);
                    $totalDuration= $interval->format('%h');
               

                    if ($totalDuration==0) {

                        $token =$currenttoken;
                        
                    }else{

                         $token=$authentification->authentification();
                         $token=json_decode($token);
                         $gettoken->insertToken($token->token,$service);


                    }
                }else{

                         $token=$authentification->authentification();

                         
                         $token=json_decode($token);
                          
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => 104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" => "Please contact mobicash support" ,                   
                        "data" =>"",
                        "responseDate"=>$date

                        ];

                        return $mcashResponse ;


                         }
                         
                         
                }

                
    
               $token='Bearer '.$token;

                                  
           $LtssProject = new LtssProject();     
           $ltssbRespone = $LtssProject->ltssNidvalidate($request->input("identification"),$token);


           
      
           if(isset($ltssbRespone)){

            
          
           $ltssbRespone1 =json_decode($ltssbRespone);


           
           if(isset($ltssbRespone1->id)){                       


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>100, 
                        "communicationStatus" =>'SUCCESS', 
                        "codeDescription" =>"SUCCESS",                   
                        "data" =>$ltssbRespone1,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;          
             
                                
          }else{

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>$ltssbRespone1->message ,                   
                "data" =>$ltssbRespone1 ,
                "responseDate"=>$date

                ];

                return $mcashResponse ; 

            

          }
        }else{
                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"LTSS is not responding",                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ; 

            
          }
           
        
 }
 public function ltssSendContribution(Request $request){

        $header = $request->header('Authorization');

        $validator = Validator::make($request->all(), [
          "identification"=>'required|alpha_num|max:16|min:8',          
          "amount"=>'required',           
          "payerPhone"=>'required',
          "payerName"=>'required',
          "brokering"=>'required' ,
          "userGroup"=>'required' 
          
         ]);
         if($validator->fails()){

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


        if ($request->isJson()){ 
     
                    
      
             $LtssProject = new LtssProject();          
             
             $paymentDate = date('Y-m-d H:i:s'); 

            if($request->brokering=="Broker" && $request->userGroup=="retail_agents"){

            $resp_payment= $LtssProject->ltssMobicorePaymentDependentUser($request->amount,$request->identification, $request->payerPhone,$request->payerName,$header);

            }

            if($request->brokering=="DDI_Broker" && $request->userGroup=="retail_agents"){

            $resp_payment= $LtssProject->ltssMobicorePaymentDdiDependentUser($request->amount,$request->identification, $request->payerPhone,$request->payerName,$header);

            }
            if(($request->brokering=="Independent" && $request->userGroup=="retail_agents") || ($request->brokering=="Independent" && $request->userGroup=="sacco_mfi")){

            $resp_payment= $LtssProject->ltssMobicorePaymentIndependentUser($request->amount,$request->identification, $request->payerPhone,$request->payerName,$header);

            }

            if($request->brokering=="Independent" && $request->userGroup=="Individual_clients"){

            $resp_payment= $LtssProject->ltssMobicorePaymentIndividualClients($request->amount,$request->identification, $request->payerPhone,$request->payerName,$header);
            }      



             
             $mobicoreResponse =json_decode($resp_payment);                          
              
            if(isset($mobicoreResponse->transactionNumber)){

                 $service='ltss';
                 $gettoken=new TokenValidation();
                 $authentification = new LtssProject();
                
        
                 $user=$gettoken->selectToken($service);  

                if (isset($user)&&(!empty ($user->token))) {

                $user= json_encode($user);
                $user=json_decode($user);
                

                $currenttoken = $user->token;

                    
                    $break_1_start = $user->date;           
                    $break_1_ends = date("Y-m-d H:i");
                    
                    $datetime1 = new DateTime($break_1_start);
                    $datetime2 = new DateTime($break_1_ends);
                    $interval = $datetime1->diff($datetime2);
                    $totalDuration= $interval->format('%h');
               

                    if ($totalDuration==0) {

                        $token =$currenttoken;
                        
                    }else{

                         $token=$authentification->authentification();
                         $token=json_decode($token);
                         $gettoken->insertToken($token->token,$service);


                    }
                }else{

                         $token=$authentification->authentification();

                         
                         $token=json_decode($token);
                          
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => 104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" => "Please contact mobicash support" ,                   
                        "data" =>"",
                        "responseDate"=>$date

                        ];

                        return $mcashResponse ;


                         }
                         
                         
                }                
    
             $token='Bearer '.$token;          
             $paymentDate="2024-12-03 10:48:39.820433";

             $ltssbResponse = $LtssProject->ltssPaymentNotification($request->amount,$mobicoreResponse->date,$paymentDate,$request->identification,$mobicoreResponse->transactionNumber,$token);                     
               
                 
                $ltssbResponse1 =json_decode($ltssbResponse);
                $status=200;

                if(isset($ltssbResponse1->status)){

                   $refNo=$ltssbResponse1->status; 

                }else{
                    $refNo=0;

                }

                $LtssProject->saveLtssLog($request->payerPhone,$request->identification,$mobicoreResponse->id,$refNo,$request->amount,$ltssbResponse,$status);

                      $transactionFees=$request->amount*2.5/100;

                       $responseData=[

                        "mobicashTransctionNo"=>$mobicoreResponse->transactionNumber,
                        "amountPaid"=>$mobicoreResponse->amount,
                        "transactionfees"=>$transactionFees,
                        "ltssRefNo"=>$refNo,
                        "date"=>$mobicoreResponse->date
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



                            if(isset($mobicoreResponse->customFieldErrors->tax_document_id[0])){

                            $code=105;
                            $codeDescription=$mobicoreResponse->customFieldErrors->tax_document_id[0];



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
                        return $mcashResponse ;  
        
                           }              
             }
             
           
           
         }else{

                 $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Content type Not Allowed" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
           }

          
 }




}
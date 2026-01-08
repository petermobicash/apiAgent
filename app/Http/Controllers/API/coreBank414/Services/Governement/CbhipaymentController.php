<?php
namespace App\Http\Controllers\API\coreBank414\Services\Governement;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Utilities\tokenValidation; 
use App\Classes\coreBank414\Services\Utilities\RssbIntergation;
use App\Classes\coreBank414\Services\Governement\Cbhicollection;
use App\Classes\coreBank414\usersAccess\UsersAccess; 
use App\Classes\coreBank414\Services\Utilities\MobicoreUtilites; 

/**
 * @group rssb mutuelle de sante
 *
 * API endpoints for managing mutuelle de sante
 */

class CbhipaymentController extends BaseController{

    public function niddetails(Request $request){ 
      

      $validator = Validator::make($request->all(), [
        "houseHoldNID"=>'required|alpha_num|max:16|min:8',
        "paymentYear"=>'required|integer'
        
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

         
           $service='cbhi';
           try{

                 $gettoken=new TokenValidation();
                 $authentification = new RssbIntergation();
                
        
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




                $result = $authentification->getNidDetails($request->input("houseHoldNID"),$request->input("paymentYear"),$token);
                 
                $response = json_encode($result);
                
                 
                $params['cbhi_response']= $response;
                
                $result =json_decode($result); 

                 $params['nid'] = $request->houseHoldNID ;        

                
                if(isset($result)&&(!empty($result->headId)))
                {  

                $headId=$result->headId; 


                $jayParsedAry = [
                "headId" => $result->headId, 
                "headHouseHoldNames" =>$result->headHouseHoldNames, 
                "houseHoldCategory" =>0, 
                "totalHouseHoldMembers" =>$result->totalHouseHoldMembers, 
                "totalAmount" =>$result->totalAmount, 
                "totalPaidAmount" =>$result->totalPaidAmount, 
                "members" => $result->members
                ]; 
                   
                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCES', 
                "codeDescription" => "SUCCES",                   
                "data" =>$jayParsedAry,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
 
                          

                        $params['status_code'] = 200 ;             

                        $gettoken->saveCbhiProfileLog($params);              
                }else{       // no response from RSSB

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => 104, 
                        "communicationStatus" =>'FAILED', 
                        "codeDescription" => $result->message ,                   
                        "data" =>$result,
                        "responseDate"=>$date

                        ];

                      return $mcashResponse ;

 
                         
                        $params['status_code'] = 315 ;
                        // save log
                        $gettoken->saveCbhiProfileLog($params) ;                
                }
        }catch (Exception $ex){ // exceptin accured


                       $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => 104, 
                        "communicationStatus" =>'FAILED', 
                        "codeDescription" => $ex->message() ,                   
                        "data" =>"",
                        "responseDate"=>$date

                        ];

                      return $mcashResponse ;


                
                 
                $params['cbhi_response']=$ex->message();
                $params['status_code'] = 308 ;
                // save log
                $gettoken->saveCbhiProfileLog($params) ;
        }
              
    
 }


 public function cbhiMutuellePaymentDependentAgent(Request $request){

   
     $header = $request->header('Authorization');
     
     $validator = Validator::make($request->all(), [

      "amountPaid"=>'required|integer',
      "payerName"=>'required',
      "houseHoldNID"=>'required|integer',      
      "householdMemberNumber"=>'required',
      "totalPremium"=>'required',
      "paymentYear"=>'required',
      "payerPhoneNumber"=>'required',      
      "brokering"=>'required' ,
      "userGroup"=>'required' 
      

        ]);

 
        if($validator->fails()){


            $error=json_decode($validator->errors());



                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Data validation" ,                   
                "data" =>$error,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
          
       }
   
     if ($request->isJson()){


     if($request->amountPaid %1000 != 0){
            
            $minimumAmount=1000;


            $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Amafaranga make yo kwishyura ni ".$minimumAmount."kandi agomba kuba ari ibinyagihumbi 1000,2000,3000,.... " ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ; 
        }         
 
       $cbhicollection = new Cbhicollection();

        date_default_timezone_set("Africa/Kigali");
       $paydate = date('Y-m-d H:i:s ');
       
        if($request->userGroup=='Individual_clients'){

            $resp_payment=$cbhicollection->cbhiMutuellePaymentIndividualClients($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber ,$header);

        }elseif($request->brokering=="Broker"&& $request->userGroup=="retail_agents"){

       $resp_payment=$cbhicollection->cbhiMutuellePaymentDependentAgent($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber ,$header);


       }elseif($request->brokering=="DDI_Broker" && $request->userGroup=="retail_agents"){

       $resp_payment=$cbhicollection->cbhiMutuellePaymentDependentDDINAgent($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber ,$header);


       }elseif($request->brokering=="Independent" && $request->userGroup=='retail_agents'){

         $resp_payment=$cbhicollection->cbhiMutuellePaymentIndependentAgent($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber,$header);

       }elseif($request->brokering=="Independent" && $request->userGroup=='sacco_mfi'){

         $resp_payment=$cbhicollection->cbhiMutuellePaymentSaccoAgent($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber, $header);


       }else{       

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"unkown group or brokering " ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;



        


       } 
       
         

      
       $mobicoreResponse=json_decode($resp_payment);     
       
       if (isset($mobicoreResponse->transactionNumber)) {


                if($request->businessCat==true){

                    $commissionAmount=$request->amountPaid*2.5/100;        


                }else{

                     $commissionAmount=$request->amountPaid*1.5/100;


                }

                $mobiCashDelayedCommissionCollection = new MobicoreUtilites();

                $mobiCashDelayedCommissionCollection->mobiCashDelayedCommissionCollection($commissionAmount,$mobicoreResponse->transactionNumber);

                $mobiCashDelayedCommissionCollection->agentDelayedCommission($commissionAmount,$mobicoreResponse->transactionNumber,$request->agentphone);

                $service='cbhi';

        

                 $gettoken=new TokenValidation();
                 $authentification = new RssbIntergation();
                
        
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
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{



                             $date = date('Y-m-d H:i:s');

                            $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"Please contact admin",                   
                            "data" =>"",
                            "responseDate"=>$date
                    
                   ];
 
                         return $mcashResponse ;


                         }


                    }
                }else{

                         $token=$authentification->authentification();

                         
                         $token=json_decode($token);
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{

                         $token='';


                         }
                         
                         
                }

                try{     
            
                    $result = $authentification->sendNotification($request->houseHoldNID,$paydate,$request->amountPaid,$mobicoreResponse->transactionNumber,$request->paymentYear,$token);                  

                    $response_pay=json_decode($result);                 

                    if((!empty($response_pay))&&($response_pay->message=="Payment is successfully received")||($response_pay->message=="The transaction has already been received")) {
                     
                      $status = 200;                                
                      $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);

                      }elseif(!empty($response_pay)){

                         $status = 400; 
                         $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);
                      }else{

                      $status = 400; 
                      $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);
                      $gettoken->new_rssb_tobe_notified($mobicoreResponse->transactionNumber,$request->houseHoldNID,$request->amountPaid,$request->paymentYear,$request->payerPhoneNumber,$paydate);

                    }                                          
                   }catch(Exception $ex){ // if the exception happens 
                                            
                          $status = 400; 
                          $gettoken->new_rssb_tobe_notified($mobicoreResponse->transactionNumber,$request->houseHoldNID,$request->amountPaid,$request->paymentYear,$request->payerPhoneNumber,$paydate);
                          $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);                                                                         
                    } 


                     $date = date('Y-m-d H:i:s');
 

        $mcashResponse = [ "responseCode" => 100, 
                            "communicationStatus" =>'SUCCESS', 
                            "codeDescription" =>"Payment is successfully received",                   
                            "data" =>$mobicoreResponse,
                            "responseDate"=>$date
                    
                   ];


                  

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

                                  
          
       }
       
     }else{


              $date = date('Y-m-d H:i:s');

              $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"Content type Not Allowed",                   
                            "data" =>"",
                            "responseDate"=>$date
                    
                   ];

          
          
     }
      return $mcashResponse;

 }


 public function cbhiMutuellePaymentIndividualClients(Request $request){


   
     $header = $request->header('Authorization');
     
     $validator = Validator::make($request->all(), [

      "amountPaid"=>'required|integer',
      "payerName"=>'required',
      "houseHoldNID"=>'required|integer',
      "houseHoldCategory"=>'required',
      "householdMemberNumber"=>'required',
      "totalPremium"=>'required',
      "paymentYear"=>'required',
      "payerPhoneNumber"=>'required',      
      "brokering"=>'required' ,
      "userGroup"=>'required' 
      

        ]);

 
        if($validator->fails()){


            $error=json_decode($validator->errors());



                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Data validation" ,                   
                "data" =>$error,
                "responseDate"=>$date

                ];

                return $mcashResponse ;




 



          
       }
   
     if ($request->isJson()){


     if($request->amountPaid %1000 != 0){
            
            $minimumAmount=1000;


            $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>"Amafaranga make yo kwishyura ni ".$minimumAmount."kandi agomba kuba ari ibinyagihumbi 1000,2000,3000,.... " ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;




 
        }

$confirmationpin= $request->header('Confirmationpin');
$Session_Token = $request->header('Session-Token'); 

$usersAccess = new UsersAccess();
$codeResponse =$usersAccess->tokenSessionActivation( $Session_Token,$confirmationpin);


   if(!isset($codeResponse)||$codeResponse!=204){

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" =>404, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "session activation issue" ,                   
                "data" =>$codeResponse,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


   }        
 
       $cbhicollection = new Cbhicollection();

       date_default_timezone_set("Africa/Kigali");
       $paydate = date('Y-m-d H:i:s ');

        

        if($request->userGroup=='Individual_clients'){


            $resp_payment=$cbhicollection->cbhiMutuellePaymentIndividualClients($request->amountPaid,$request->payerName,$request->houseHoldNID,$request->houseHoldCategory,$request->householdMemberNumber,$request->totalPremium,$request->paymentYear,$request->payerPhoneNumber ,$Session_Token,$confirmationpin);



        }else{


                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" =>104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "You are not Mobicash client!" ,                   
                "data" =>$request->userGroup,
                "responseDate"=>$date

                ];

                return $mcashResponse ;



        }
       


      
       $mobicoreResponse=json_decode($resp_payment);     
       
       if (isset($mobicoreResponse->transactionNumber)) {

                 $service='cbhi';

                 $gettoken=new TokenValidation();
                 $authentification = new RssbIntergation();
                
        
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
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{



                             $date = date('Y-m-d H:i:s');

                            $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"Please contact admin",                   
                            "data" =>"",
                            "responseDate"=>$date
                    
                   ];
 
                         return $mcashResponse ;


                         }


                    }
                }else{

                         $token=$authentification->authentification();

                         
                         $token=json_decode($token);
                         if(isset($token)){

                         $token=$token->token;                
                         $gettoken->insertToken($token,$service);
                         }else{

                         $token='';


                         }
                         
                         
                }

                try{     
            
                    $result = $authentification->sendNotification($request->houseHoldNID,$paydate,$request->amountPaid,$mobicoreResponse->transactionNumber,$request->paymentYear,$token);                  

                    $response_pay=json_decode($result);                 

                    if((!empty($response_pay))&&($response_pay->message=="Payment is successfully received")||($response_pay->message=="The transaction has already been received")) {
                     
                      $status = 200;                                
                      $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);

                      }elseif(!empty($response_pay)){

                         $status = 400; 
                         $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);
                      }else{

                      $status = 400; 
                      $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);
                      $gettoken->new_rssb_tobe_notified($mobicoreResponse->transactionNumber,$request->houseHoldNID,$request->amountPaid,$request->paymentYear,$request->payerPhoneNumber,$paydate);

                    }                                          
                   }catch(Exception $ex){ // if the exception happens 
                                            
                          $status = 400; 
                          $gettoken->new_rssb_tobe_notified($mobicoreResponse->transactionNumber,$request->houseHoldNID,$request->amountPaid,$request->paymentYear,$request->payerPhoneNumber,$paydate);
                          $gettoken->new_rssb_notification($request->houseHoldNID,$mobicoreResponse->transactionNumber,$status,$result,$paydate);                                                                         
                    } 


                     $date = date('Y-m-d H:i:s');
 

        $mcashResponse = [ "responseCode" => 100, 
                            "communicationStatus" =>'SUCCESS', 
                            "codeDescription" =>"Payment is successfully received",                   
                            "data" =>$mobicoreResponse,
                            "responseDate"=>$date
                    
                   ];


                  

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

                                  
          
       }
       
     }else{


              $date = date('Y-m-d H:i:s');

              $mcashResponse = [ "responseCode" => 105, 
                            "communicationStatus" =>'FAILURE', 
                            "codeDescription" =>"Content type Not Allowed",                   
                            "data" =>"",
                            "responseDate"=>$date
                    
                   ];

          
          
     }
      return $mcashResponse;

 }

 public function cbhiDailyCollection(Request $request){
      
 
       $cbhicollection = new Cbhicollection();
       $resp_payment=$cbhicollection->cbhiDailyCollection($request->mobicash_ref_number);      
       return $resp_payment;

 }


}


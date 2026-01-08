<?php   
namespace App\Http\Controllers\API\coreBank414\UsersRegistration; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\UsersRegistration\UserEnrollment;  

/**
 * @group User Enrollment CoreBank414
 *
 * API endpoints for managing Enrollment
 */
      

class UserEnrollmentCoreBank414Controller extends BaseController
{
    public function userIndependantEnrollment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string', 
              "username"=>'required|string',          
              "email"=>'required|email',
              "nationality"=>'required',              
              "identity_number"=>'required|string',     
              "tax_identification_number"=>'required|string',
              "registration_date"=>'required',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'                   
        
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
           
           $header=$request->header('Authorization');
           $userEnrollment = new UserEnrollment();

           $response =$userEnrollment->userIndependantEnrollment($request->names,$request->username,$request->email,$request->nationality,$request->identity_type,$request->identity_number,$request->tax_identification_number,$request->business_website_url,$request->registration_date,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$header);
          

           $response=json_decode($response);



           if(isset($response->user->id)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;



                }else{
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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
           }else{


               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  
             
         
  
}

public function userDependantEnrollment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string', 
              "username"=>'required|string',          
              "email"=>'required|email',
              "nationality"=>'required',              
              "identity_number"=>'required|string',     
              "tax_identification_number"=>'required|string',
              "registration_date"=>'required',
              "broker"=>'required',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'                   
        
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
           
           $header=$request->header('Authorization');
           $userEnrollment = new UserEnrollment();

           $response =$userEnrollment->userDependantEnrollment($request->names,$request->username,$request->email,$request->nationality,$request->identity_type,$request->identity_number,$request->tax_identification_number,$request->business_website_url,$request->registration_date,$request->broker,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$header);
          

           $response=json_decode($response);



           if(isset($response->user)){

             $settingAgentMainBroker = new UserEnrollment();

             $settingAgentMainBroker->settingAgentMainBroker($request->username,$request->broker,$header);


               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                }else{
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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
              }
           }else{
               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

   
             
         
  
}

public function userOperatorEnrollment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [

                                             
              "operatorName"=>'required|string', 
              "operatorUserName"=>'required|string',          
              "operatorUserEmail"=>'required|email',
              "nationality"=>'required',              
              "country_code"=>'required|string',     
              "identity_type"=>'required|string',
              "identity_number"=>'required',
              "registration_date"=>'required',
              "email_validation_date"=>'required',             
              "position"=>'required|string',  
              "mainUserName"=> 'required|string',          
              "mainPhone"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "pin"=>'required|string',
              "confirmationPin"=>'required|string'                   
        
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
           
           $header=$request->header('Authorization');
           $userOperatorEnrollment = new UserEnrollment();
           
           $response =$userOperatorEnrollment->userOperatorEnroment($request->operatorName,$request->operatorUserName,$request->operatorUserEmail,$request->nationality,$request->country_code,$request->identity_type,$request->identity_number,$request->registration_date,$request->email_validation_date,$request->position,$request->mainUserName,$request->mainPhone,$request->pin,$request->confirmationPin,$header);
          

           $response=json_decode($response);



           if(isset($response->user)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                }else{
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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                }

            }else{

               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  return $mcashResponse;  
             
         
  
}

public function groupsOperatorView(Request $request){

  if($request->header('Authorization')){


  $header=$request->header('Authorization');

  $userOperatorGroupView = new UserEnrollment();

  $response=$userOperatorGroupView->groupsOperatorView($header);

  $response=json_decode($response);

  if(isset($response)){



     $mcashResponse =["responseCode" => 200,
                      "status"=>"success",
                      "infos"=>$response
                    ]; 

  }else{

    $mcashResponse =["responseCode" => 400,
                    "status"=>"Failed",
                    "message"=>"group not found"                    
                                                       
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


public function viewAgentOperators(Request $request){

  if($request->header('Authorization')){


  $header=$request->header('Authorization');

  $userOperatorGroupView = new UserEnrollment();

  $response=$userOperatorGroupView->viewAgentOperators($header);

  $response=json_decode($response);

  if(isset($response)){



     $mcashResponse =["responseCode" => 200,
                      "status"=>"success",
                      "infos"=>$response
                    ]; 

  }else{

    $mcashResponse =["responseCode" => 400,
                    "status"=>"Failed",
                    "message"=>"group not found"                    
                                                       
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

public function resetingOperatorsPinByAgent(Request $request){


   if($request->header('Authorization')){


  $header=$request->header('Authorization');

  

  $userOperator = new UserEnrollment();

  $response=$userOperator->resetingOperatorsPinByAgent($request->agentOperator,$header); 

  if(isset($response)){

 return response()->json([

                        // "responseCode" =>$response,
                        // "response"=>"success",                      
                        // "response"=>$response 
             ], $response);   

  }else{

    return response()->json([

                        "responseCode" => 500,
                        "response"=>"Failed"                      
                        
             ],500);   
  }
  }else{
               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;  
                 
          }

 




}
public function settingAgentMainBroker(Request $request){

  if($request->header('Authorization')){


  $header=$request->header('Authorization');

  $settingAgentMainBroker = new UserEnrollment();

  $response=$settingAgentMainBroker->settingAgentMainBroker($request->dependant,$request->broker,$header);

  $response=json_decode($response);

 

  if(isset($response)){

     $mcashResponse =["responseCode" => 200,
                      "status"=>"success",
                      "infos"=>$response
                    ]; 

  }else{

    $mcashResponse =["responseCode" => 400,
                    "status"=>"Failed",
                    "message"=>"group not found"                    
                                                       
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


public function userValidation(Request $request){

  if($request->header('Authorization')){    


  $header=$request->header('Authorization');

  $userValidation = new UserEnrollment();

  $response=$userValidation->userValidation($request->input("useridentify"),$header);

  // echo $response;
  // exit();


  $response=json_decode($response);

  if(isset($response->id)){

    if(isset($response->email)){
     $email=$response->email;


    }else{


        $email='';


    }



     $userdetails =[
                      "id"=>$response->id,
                      "names"=>$response->display,
                      "email"=>$email,
                      "group"=>$response->group->internalName,
                      "phoneNumber"=>$response->phones[0]->number
                    ];

            // return $mcashResponse; 


            $date = date('Y-m-d H:i:s');

            $mcashResponse = [ "responseCode" => 100, 
            "communicationStatus" =>'SUCCESS', 
            "codeDescription" =>"SUCCESS" ,                   
            "data" =>$userdetails,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;

  }else{

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


                      $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 

                         return $mcashResponse ;

                      
    }   

  }else{
                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => 'Authorization please',                   
                "data" =>'',
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }

 
}


public function RegisterNewSchoolByAdmin(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [

              "names"=>'required|string',
              "username"=>'required|string',
              "email"=>'required|email',
              "bank_name"=>'required|string', 
              "bank_account_holder_name"=>'required|string',
              "bank_account_number"=>'required|string',
              "bank_branch"=>'required|string',
              "bank_code"=>'required|string',
              "schoolfees"=>'required|string',
              "schoolstatus"=>'required|string',
              "schoolcat"=>'required|string',
              "schoolcode"=>'required|string',
              "phoneNumber"=>'required|string',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "cell"=>'required|string',
              "village"=>'required|string',
              "city"=>'required|string'
                              
        
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
           
           $header=$request->header('Authorization');
           $userEnrollment = new UserEnrollment();

           $response =$userEnrollment->RegisterNewSchoolByAdmin($request->names,$request->username,$request->email,$request->bank_name, $request->bank_account_holder_name,$request->bank_account_number,$request->bank_branch,$request->bank_code,$request->schoolfees,$request->schoolstatus,$request->schoolcat,$request->schoolcode,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->cell,$request->village,$request->city,$header);
          

           $response=json_decode($response);



           if(isset($response->user)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                          }else{

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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
        }else{
               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  
             
         
  
}

public function StudentOnboarding(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [

                  "names"=>'required|string',
                  "username"=>'required|string',
                  "email"=>'required|email',
                  "firstname"=>'required|string', 
                  "lastname"=>'required|string',
                  "maritial_status"=>'required|string',
                  "gender"=>'required|string',
                  "BOD"=>'required',
                  "studentregistration"=>'required',
                  "studentnatid"=>'required',
                  "schoolcode"=>'required',
                  "parentguardian"=>'required|string',
                  "parentguardid"=>'required|string',
                  "parentguardianphone"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',                  
                  "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                  "province"=>'required|string',
                  "district"=>'required|string',
                  "sector"=>'required|string',
                  "city"=>'required|string'                   
        
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
           
           $header=$request->header('Authorization');
           $userEnrollment = new UserEnrollment();



           $response =$userEnrollment->StudentOnboarding($request->names,$request->username,$request->email,$request->firstname, $request->lastname,$request->maritial_status,$request->gender,$request->BOD,$request->studentregistration,$request->studentnatid,$request->schoolcode,$request->parentguardian,$request->parentguardid,$request->parentguardianphone ,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$request->header);
           

           $response=json_decode($response);



           if(isset($response->user)){         


               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                          }else{

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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
        }else{
              $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  
             
         
  
}

public function RegisterNewParentByAdmin(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              

              "names"=>'required|string',
              "username"=>'required|string',
              "email"=>'required|email',
              "firstname"=>'required|string', 
              "lastname"=>'required|string',
              "nextkinname"=>'required|string',
              "nextkinrelation"=>'required|string',
              "identity_number"=>'required',
              "maritial_status"=>'required|string',
              "gender"=>'required|string',
              "date_of_birth"=>'required',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'


        
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
           
           $header=$request->header('Authorization');
           $userEnrollment = new UserEnrollment();

           $response =$userEnrollment->RegisterNewParentByAdmin($request->names,$request->username,$request->email,$request->firstname, $request->lastname,$request->nextkinname,$request->nextkinrelation,$request->identity_number,$request->maritial_status,$request->gender,$request->date_of_birth,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$header);
          

           $response=json_decode($response);



           if(isset($response->user)){           

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                          }else{

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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
        }else{
                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

     
             
         
  
}

public function userClientEnrollment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string', 
              "username"=>'required|string',          
              "email"=>'required|email',
              "nationality"=>'required',
              "identity_type"=> 'required',             
              "identity_number"=>'required|string',      
              "maritial_status"=>'required|string',
              "gender"=>'required|string',
              "date_of_birth"=>'required|string',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'                   
        
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



             
             $header=$request->header('Authorization');


             $userEnrollment = new UserEnrollment();
            if($request->maritial_status='M'){
            $maritial_status="married";
            }
            if($request->maritial_status='S'){

            $maritial_status="single";

            }

            if($request->gender='M'){
            $gender="male";
            }
            if($request->gender='F'){

            $gender="female";

            }
          

          $response =$userEnrollment->userClientEnrollmentWithAdmin($request->names,$request->username,$request->email,$request->identity_type,$request->identity_number,$maritial_status,$gender,$request->date_of_birth,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$header);
           

           $response=json_decode($response);



           if(isset($response->user)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                          }else{

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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                      }

                        if(!isset($code)){

                            $code=104;


                        }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                         if(!isset($code)){

                            $code=104;


                        }


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
        }else{
               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  
             
         
  
}

public function userClientEnrollmentAgent(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string', 
              "username"=>'required|string',          
              "email"=>'required|email',
              "nationality"=>'required',
              "identity_type"=> 'required',             
              "identity_number"=>'required|string',      
              "maritial_status"=>'required|string',
              "gender"=>'required|string',
              "date_of_birth"=>'required|string',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'                   
        
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



             
             $header=$request->header('Authorization');


             $userEnrollment = new UserEnrollment();
            if($request->maritial_status='M'){
            $maritial_status="married";
            }
            if($request->maritial_status='S'){

            $maritial_status="single";

            }

            if($request->gender='M'){
            $gender="male";
            }
            if($request->gender='F'){

            $gender="female";

            }
          

          $response =$userEnrollment->userClientEnrollmentWithAgent($request->names,$request->username,$request->email,$request->identity_type,$request->identity_number,$maritial_status,$gender,$request->date_of_birth,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city,$header);
           

           $response=json_decode($response);



           if(isset($response->user)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                          }else{

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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                      }

                        if(!isset($code)){

                            $code=104;


                        }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                         if(!isset($code)){

                            $code=104;


                        }


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 
                          }
        }else{
               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Authorization please" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                 
          }
        

  
             
         
  
}


public function userClientDependentEnrollment(Request $request){




          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string', 
              "username"=>'required|string',          
              "email"=>'required|email',
              // "nationality"=>'required',
              "identity_type"=> 'required',             
              "identity_number"=>'required|string',      
              "maritial_status"=>'required|string',
              "gender"=>'required|string',
              "date_of_birth"=>'required|string',
              "group"=>'required|string',
              "brokerid"=>'required',
              "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "city"=>'required|string'                   
        
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


          $userEnrollment = new UserEnrollment(); 

          $response =$userEnrollment->brokerIdValidation($request->input("brokerid"));         



          $responseArray=json_decode($response);

          if(isset($responseArray[0]->id)){

            $brokerId=$responseArray[0]->id;          


          $userEnrollment = new UserEnrollment();   


          if($request->maritial_status='M'){
            $maritial_status="married";
            }
            if($request->maritial_status='S'){

            $maritial_status="single";

            }

            if($request->gender='M'){
            $gender="male";
            }
            if($request->gender='F'){

            $gender="female";

            }       


        $response =$userEnrollment->userClientDependentEnrollment($request->names,$request->username,$request->email,$request->identity_type,$request->identity_number,$maritial_status,$gender,$request->date_of_birth,$request->group,$brokerId,$request->phoneNumber,$request->province,$request->district,$request->sector,$request->city);   

              
          

          $response=json_decode($response);
          if(isset($response->user->id)){


                $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;



            


          }else{


              
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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 





          }


          }else{


               
             

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "FAILURE" ,                   
                "data" =>$response,
                "responseDate"=>$date

                ];

                return $mcashResponse ;





          }
          
 
             
         
  
}

public function userPendingClientEnrollment(Request $request){

 




          $validator = Validator::make($request->all(), [
                                 
              "names"=>'required|string',                           
              "identity_number"=>'required|string',      
              "maritial_status"=>'required|string',
              "gender"=>'required|string',
              "date_of_birth"=>'required|string',             
              "province"=>'required|string',
              "district"=>'required|string',
              "sector"=>'required|string',
              "cell"=>'required|string',
              "sector"=>'required|string',
              "village"=>'required|string'                   
        
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


          $userEnrollment = new UserEnrollment(); 

                    


          $userEnrollment = new UserEnrollment();   


          if($request->maritial_status='M'){
            $maritial_status="married";
            }
            if($request->maritial_status='S'){

            $maritial_status="single";

            }

            if($request->gender='M'){
            $gender="male";
            }
            if($request->gender='F'){

            $gender="female";

            }       


        $response =$userEnrollment->userPendingClientEnrollment($request->names,$request->identity_number,$maritial_status, $gender,$request->date_of_birth,$request->province,$request->district,$request->sector,$request->cell,$request->village);   

              
          

          $response=json_decode($response);
          if(isset($response->user->id)){


                $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
 


            


          }else{             
             
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
                           }elseif($mobicoreResponse1=="insufficientBalance"){

                            $code=106;
                            $codeDescription="Insufficient Balance";

                           }else{



                            if(isset($response->customFieldErrors)){

                            $code=105;
                            $codeDescription="Custom Field Errors" ;



                            }else{


                            $code=107;

                            $codeDescription="FAILURE";
                        }


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;  
        
                    }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>'FAILURE',                   
                        "data" =>$response->customFieldErrors,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    } 





          }


           
          
 
             
         
  
}

public function brokerIdValidation(Request $request){


          $userEnrollment = new UserEnrollment(); 



          $response =$userEnrollment->brokerIdValidation($request->input("brokercode"));

          

          $response=json_decode($response);

          if(isset($response[0]->id)){

               $userinfo=[ "id" =>$response->user->id,
               "display" =>$response->user->display,
               "principal"=>$response->principals
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$userinfo,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


            


          }else{
               
             

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "FAILURE" ,                   
                "data" =>$response,
                "responseDate"=>$date

                ];

                return $mcashResponse ;





          }



          
 
             
         
  
}

public function externalClientsGroupEnrollment(Request $request){   

                $validator = Validator::make($request->all(), [
                   
                "clientPhone"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                          

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


                $userOperatorEnrollment = new UserEnrollment();

                $response =$userOperatorEnrollment->externalClientsGroupEnrollment($request->clientPhone);
                

                $response=json_decode($response);



                if(isset($response->user)){              

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" =>$response->user->display ,                   
                "data" =>$response->user->id,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

                }else{
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
                       }elseif($mobicoreResponse1=="insufficientBalance"){

                        $code=106;
                        $codeDescription="Insufficient Balance";

                       }else{



                        if(isset($response->customFieldErrors)){

                        $code=105;
                        $codeDescription="Custom Field Errors" ;

                        }

                       if(isset($response->propertyErrors)){

                          $code=105;
                          $codeDescription=json_encode($response->propertyErrors);

                          if($codeDescription==$response->propertyErrors->username[0]){
                            $code=101;
                            $codeDescription="This phone is already in the system.";
                          

                          }

                        }else{


                        $code=107;

                        $codeDescription="FAILURE";
                        }


                       }

                    $date = date('Y-m-d H:i:s');

                    $mcashResponse = [ "responseCode" => $code, 
                    "communicationStatus" =>'FAILURE', 
                    "codeDescription" =>$codeDescription,                   
                    "data" =>$response,
                    "responseDate"=>$date

                    ]; 
                    return $mcashResponse ;  

                }else{


                    $date = date('Y-m-d H:i:s');

                    $mcashResponse = [ "responseCode" =>105, 
                    "communicationStatus" =>'FAILURE', 
                    "codeDescription" =>'FAILURE',                   
                    "data" =>$response,
                    "responseDate"=>$date

                    ]; 
                    return $mcashResponse ;



                } 
                }

            }


public function simpleClientsEnrollment(Request $request){   

        $validator = Validator::make($request->all(), [
           
        "phoneNumber"=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        "taxpayername"=>'required',
                  

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

        $userOperatorEnrollment = new UserEnrollment();

        $authorisation=$request->header('Authorization');                 

        $response =$userOperatorEnrollment->simpleClientsEnrollment($request->phoneNumber,$request->taxpayername,$request->username,$request->taxidentificationnumber,$authorisation);        

        $response=json_decode($response);

        if(isset($response->user)){              

        $date = date('Y-m-d H:i:s');
        $mcashResponse = [ "responseCode" => 100, 
        "communicationStatus" =>'SUCCESS', 
        "codeDescription" =>$response->user->display ,                   
        "data" =>$response->user->id,
        "responseDate"=>$date

        ];

        return $mcashResponse ;

        }else{
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
               }elseif($mobicoreResponse1=="insufficientBalance"){

                $code=106;
                $codeDescription="Insufficient Balance";

               }else{

                if(isset($response->customFieldErrors)){

                $code=105;
                $codeDescription="Custom Field Errors" ;

                }

               if(isset($response->propertyErrors)){

                  if(isset($response->propertyErrors->username[0])){
                  
                    $code=105;
                    $codeDescription=$response->propertyErrors->username[0];
                  

                  }                 

                }else{

                $code=107;

                $codeDescription="FAILURE";
                }


               }

            $date = date('Y-m-d H:i:s');

            $mcashResponse = [ "responseCode" => $code, 
            "communicationStatus" =>'FAILURE', 
            "codeDescription" =>$codeDescription,                   
            "data" =>$response,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;  

        }else{


            $date = date('Y-m-d H:i:s');

            $mcashResponse = [ "responseCode" =>105, 
            "communicationStatus" =>'FAILURE', 
            "codeDescription" =>'FAILURE',                   
            "data" =>$response,
            "responseDate"=>$date

            ]; 
            return $mcashResponse ;



        } 
        }

    }



}
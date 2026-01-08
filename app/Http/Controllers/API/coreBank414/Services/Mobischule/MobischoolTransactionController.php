<?php   
namespace App\Http\Controllers\API\coreBank414\Services\Mobischule; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Mobischule\MobischuleCollectionProject;  
   

class MobischoolTransactionController extends BaseController
{
    public function ParenttoSchoolPayment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
             "amount"=>'required',
             "student_name"=>'required',
             "student_id"=>'required',
             "patternofpayment"=>'required',
             "schoolIdentify"=>'required'                 
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();

           $response =$mobischuleCollectionProject->ParenttoSchoolPayment($request->amount,$request->student_name,$request->student_id,$request->patternofpayment,$request->schoolIdentify,$header);


           // echo $response;
           // exit();


           $response=json_decode($response);

            if(empty($response->transactionNumber)){            
               
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

     

   }else{   
                
         
                $mobischuleCollectionProject->save_fee_(200,$request->student_name,$request->student_id,$response->transactionNumber);             

         
               $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>600,
               "total"=>$request->amount+600
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


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

public function StudenttoSchoolPayment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [                                
                                  
             "amount"=>'required',             
             "student_id"=>'required',
             "patternofpayment"=>'required',
             "schoolIdentify"=>'required'                   
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();

            $response =$mobischuleCollectionProject->StudenttoSchoolPayment($request->amount,$request->student_id,$request->patternofpayment,$request->schoolIdentify,$header);
           

           $response=json_decode($response);

            if(empty($response->transactionNumber)){ 

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
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    }       

     

          }else{

                $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>600,
               "total"=>$request->amount+600
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


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


public function AgenttoSchoolPayment(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [                                
                                  
             "amount"=>'required',             
             "student_id"=>'required',
             "student_name"=>'required',
             "payer_phone"=>'required',
             "payer_name"=> 'required',          
             "patternofpayment"=>'required',
             "schoolIdentify"=>'required'                   
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();


           

            $response =$mobischuleCollectionProject->AgenttoSchoolPayment($request->amount,$request->student_id,$request->student_name,$request->payer_name,$request->payer_phone,$request->patternofpayment,$request->schoolIdentify,$header);
           

           $response=json_decode($response);

            if(empty($response->transactionNumber)){ 

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



                            if(isset($response->customFieldErrors )){

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
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    }         

     

             }else{

                $mobischuleCollectionProject->save_fee_(200,$request->student_name,$request->student_id,$response->transactionNumber);

                $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>600,
               "total"=>$request->amount+600
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


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


public function ParenttoMerchantPayments(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [                                
                                  
             "amount"=>'required',          
             "payer_name"=>'required',             
             "payer_phone"=>'required',           
             "patternofpayment"=>'required',
             "MerchentIdentify"=>'required',
             "payer_name"=>'required'                   
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();           

            $response =$mobischuleCollectionProject->ParenttoMerchantPayments($request->amount,$request->payer_name,$request->payer_phone,$request->patternofpayment,$request->MerchentIdentify,$header);
           

           $response=json_decode($response);

            if(empty($response->transactionNumber)){ 

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



                            if(isset($response->customFieldErrors )){

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
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    }          

     

             }else{

               $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>0,
               "total"=>$request->amount+0
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


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


public function StudenttoMerchantPayments(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [                                
                                  
             "amount"=>'required',          
             "payer_name"=>'required',             
             "payer_phone"=>'required',           
             "patternofpayment"=>'required',
             "merchentIdentify"=>'required'
                                
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();  



            $response =$mobischuleCollectionProject->StudenttoMerchantPayments($request->amount,$request->payer_name,$request->payer_phone,$request->patternofpayment,$request->merchentIdentify,$header);
           

           $response=json_decode($response);

            if(empty($response->transactionNumber)){ 

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



                            if(isset($response->customFieldErrors )){

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
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    }          

     

   }else{

                $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>0,
               "total"=>$request->amount+0
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;


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


public function SchooltoMerchantPayments(Request $request){

      

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [                                
                                  
             "amount"=>'required',             
             "patternofpayment"=>'required',
             "merchentIdentify"=>'required',
             "payer_name"=>'required'                   
        
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
           $mobischuleCollectionProject = new MobischuleCollectionProject();  

                  

            $response =$mobischuleCollectionProject->SchooltoMerchantPayments($request->amount ,$request->patternofpayment,$request->merchentIdentify,$header);
           

           $response=json_decode($response);

            if(empty($response->transactionNumber)){ 

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
                        "data" =>$response,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;



                    }          

     

         }else{

                $paymentdata=[ 

               "transactionNumber" =>$response->transactionNumber,                
               "amount" =>$request->amount,                
               "fees"=>0,
               "total"=>$request->amount+0
                                             
                ];

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$paymentdata,
                "responseDate"=>$date

                ];

                return $mcashResponse ;






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






}
<?php
namespace App\Http\Controllers\API\coreBank414\Services\Governement;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Governement\RnitProject;

 
use App\Classes\Rclient ;

/**
 * @group rnit
 *
 * API endpoints for managing rnit
 */
class RnitCotisationController extends BaseController{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */

    public function nidrnitNidvalidation(Request $request){        
    
       
        $validator = Validator::make($request->all(), [
                    
            "identification"=>'required'                      
      
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
         
     
       
      
           $rnitProject = new rnitProject();        
               
           

           $rnitRespone = $rnitProject->rnitNidValidation($request->input("identification")); 
           $rnitRespone1 = json_decode($rnitRespone) ;          


           if(isset($rnitRespone1->fullName)){

             if($rnitRespone1->fullName!= "null null"){

                $mcashResponse =  ["responseCode" => 200,
                                   "status" =>'Success',             
                                   "Response" => $rnitRespone1                             

                               ];  



                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCES', 
                "codeDescription" => "SUCCES",                   
                "data" =>$rnitRespone1,
                "responseDate"=>$date

                ];

                return $mcashResponse ;                          
               
             }else{

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>'NID Not found' ,                   
                "data" =>$rnitRespone1,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                             
               

             }

            
             
           }else{               

                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" =>'FAILURE'  ,                   
                "data" => "",
                "responseDate"=>$date

                ];

                return $mcashResponse ;
               
           }           

   


      
 }

  public function rnitSendContribution(Request $request){
      
    $validator = Validator::make($request->all(), [
          "nid"=>'required', 
          "amount"=>'required',  
          "bankAccount"=>'required',          
          "payerName"=>'required',
          "bankName"=>'required',           
          "payerPhone"=>'required',
          "brokering"=>'required' ,
          "userGroup"=>'required',  
          "payerEmail"=>'required'                       
  
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
                   
             
                $RnitProject = new rnitProject() ;


            if($request->brokering=="Broker" && $request->userGroup=="retail_agents"){


            $resp_payment= $RnitProject->rnitMobicorePaymentDependentAgent($request->amount,$request->bankAccount, $request->nid,$request->payerName,$request->payerPhone,$request->bankName, $request->payerEmail,$header);

            }

             if($request->brokering=="DDI_Broker" && $request->userGroup=="retail_agents"){


            $resp_payment= $RnitProject->rnitMobicorePaymentDependentAgent($request->amount,$request->bankAccount, $request->nid,$request->payerName,$request->payerPhone,$request->bankName, $request->payerEmail,$header);

            }

            if(($request->brokering=="Independent" && $request->userGroup=="retail_agents") || ($request->brokering=="Independent" && $request->userGroup=="sacco_mfi")){

             $resp_payment=$RnitProject->rnitMobicorePaymentIndependentAgent($request->amount,$request->bankAccount, $request->nid,$request->payerName,$request->payerPhone,$request->bankName, $request->payerEmail,$header);

            }

             if($request->brokering=="Independent" && $request->userGroup=="Individual_clients"){

            $resp_payment=$RnitProject->rnitMobicorePaymentIndividualClients($request->amount,$request->bankAccount, $request->nid,$request->payerName,$request->payerPhone,$request->bankName, $request->payerEmail,$header); 
            } 



                $mobicoreResponse=json_decode($resp_payment);

                if(!isset($mobicoreResponse->id)){

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



                            if($mobicoreResponse->customFieldErrors->national_identity_number[0]){

                            $code=105;
                            $codeDescription=$mobicoreResponse->customFieldErrors->national_identity_number[0];



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


                    

                }else{ 



                        $responseData=[


                        "mobicashTransctionNo"=>$mobicoreResponse->transactionNumber,
                        "amountPaid"=>$mobicoreResponse->amount,                        
                        "date"=>$mobicoreResponse->date





                       ];




                            $date = date('Y-m-d H:i:s');
 

                            $mcashResponse = [ "responseCode" => 100, 
                            "communicationStatus" =>'SUCCESS', 
                            "codeDescription" =>"Payment is successfully received",
                            "transaction fees"=>"",                   
                            "data" =>$responseData,
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


}
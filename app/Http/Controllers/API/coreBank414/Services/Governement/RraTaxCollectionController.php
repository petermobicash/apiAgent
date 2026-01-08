<?php
namespace App\Http\Controllers\API\coreBank414\Services\Governement;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Governement\RraTaxCollectionProject; 
use App\Classes\coreBank414\Services\Utilities\RraIntegration;
use App\Classes\coreBank414\usersAccess\UsersAccess; 
use Illuminate\Support\Str;

class RraTaxCollectionController extends BaseController{
/**
 * @group rra tax collection
 *
 * API endpoints for managing tax collection
 */
public function rraDocIdValidation(Request $request){


   

$uuid = Str::uuid()->toString();

// return $uuid;

    
    $validator = Validator::make($request->all(), [

        "rra_doc_id_ref"=>'required|alpha_num|max:30|min:8'                
 
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
    

       $RraTaxCollectionProject = new RraIntegration(); 
      
       
     try{

      $xmlres = $RraTaxCollectionProject->getDec($request->input("rra_doc_id_ref"));     

      

       if (false === $xmlres) {

          // echo "Failed loading XML\n";

            foreach(libxml_get_errors() as $error) {
                 $error->message;
            } 


               $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "data validation" ,                   
                "data" =>$error->message,
                "responseDate"=>$date

                ];

                return $mcashResponse ;           

               
       }

       $string1='<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
       $string2=$xmlres->getDecReturn;
       
       $resultat=strcmp($string2, $string1);
        
      if($resultat <70){



                 $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "invalid doc id",                   
                "data" =>$resultat,
                "responseDate"=>$date

                ];

                return $mcashResponse ;

      } 
     
      $xml_return=(array)simplexml_load_string($xmlres->getDecReturn);
     }catch(Exception $ex){


                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => 'FAILURE' ,                   
                "data" =>$ex->getMessage(),
                "responseDate"=>$date

                ];

                return $mcashResponse ;

        
        

     }

     try {    
         // Check RRA response  
         if(isset($xml_return['@attributes']['ID'])){
                                 
             $array_response=array();
             $array_return=array();
             foreach($xml_return['DECLARATION'] as $key=>$Entry){
                 $array_response[$key]=strval($Entry);                                
             }
                     
             // Check if we got RRA REF details 
             if(!empty($array_response['RRA_REF'])){


                $rraResponse=[

                                                         
                      'bank_name' => $xml_return['@attributes']['ID'],
                      'RRA_REF' => $array_response['RRA_REF'],
                      'TIN' => $array_response['TIN'],
                      'TAX_PAYER_NAME' => $array_response['TAX_PAYER_NAME'],
                      'TAX_TYPE_DESC' => $array_response['TAX_TYPE_DESC'],
                      'TAX_CENTRE_NO' => $array_response['TAX_CENTRE_NO'],
                      'TAX_TYPE_NO' => $array_response['TAX_TYPE_NO'],
                      'ASSESS_NO' => $array_response['ASSESS_NO'],
                      'RRA_ORIGIN_NO' => $array_response['RRA_ORIGIN_NO'],
                      'AMOUNT_TO_PAY' => $array_response['AMOUNT_TO_PAY'],
                      'DEC_ID' => $array_response['DEC_ID'],
                      'DEC_DATE' => $array_response['DEC_DATE']
                ];


                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 100, 
                "communicationStatus" =>'SUCCESS', 
                "codeDescription" => "suCCESS" ,                   
                "data" =>$rraResponse,
                "responseDate"=>$date

                ];

                return $mcashResponse ;
                               
             }else{



                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Deralation is no longer valid" ,                   
                "data" =>$xml_return,
                "responseDate"=>$date

                ];

                return $mcashResponse ;







             }

         }else{



                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "RRA is not responding" ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

 
                  
         }             

     }catch (Exception $ex){


                $date = date('Y-m-d H:i:s');
                $mcashResponse = [ "responseCode" => 105, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => 'FAILURE' ,                   
                "data" =>$ex->getMessage(),
                "responseDate"=>$date

                ];

                return $mcashResponse ;

     }


    
}
  

public function rraTaxPayment(Request $request){   


    $validator = Validator::make($request->all(), [
        "bankName"=>'required',
        "rraRef"=>'required',
        "tin" =>'required',
        "taxPayerName"=>'required',
        "taxTypeDesc"=>'required',
        "taxCenterNo"=>'required',
        "taxTypeNo"=>'required',
        "assessNo"=>'required',
        "rraOriginNo"=>'required',
        "amountToPay"=>'required',
        "descId"=>'required',
        // "payerPhone"=>'required',
        "brokering"=>'required' ,
        "userGroup"=>'required'              
        
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
    
    $header = $request->header('Authorization');
    
    if ($request->isJson()){      
    
       
    $payment_request = new RraTaxCollectionProject();


 
    $taxAccount = $payment_request->rraAccountMapping($request->taxTypeNo,$request->taxCenterNo);



    if (empty($taxAccount)) {

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Current Mobicash cannot be used to pay this tax." ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

    }

    

    $myArray = json_decode($taxAccount, true);
    $account= $myArray[0]["account_no"];

    if($request->brokering=="Broker" && $request->userGroup=="retail_agents"){

        $fees = $payment_request->checkChargeesFee($request->brokering, $request->amountToPay);
        $charges= $fees[0]->commission_fees;
        $resp_payment= $payment_request->rraTaxPaymentDependentAgent($request->amountToPay,$request->taxTypeDesc,$request->rraRef,$request->tin,$charges,$request->taxPayerName,$account,$header);

     }

     if($request->brokering=="DDI_Broker" && $request->userGroup=="retail_agents"){

        $fees = $payment_request->checkChargeesFee($request->brokering, $request->amountToPay);
        $charges= $fees[0]->commission_fees;
        $resp_payment= $payment_request->rraTaxPaymentDdiBrokerDependentAgent($request->amountToPay,$request->taxTypeDesc,$request->rraRef,$request->tin,$charges,$request->taxPayerName,$account,$header);

     }

     if(($request->brokering=="Independent" && $request->userGroup=="retail_agents") || ($request->brokering=="Independent" && $request->userGroup=="sacco_mfi")){

                 $fees = $payment_request->checkChargeesFee($request->brokering, $request->amountToPay);
        $charges= $fees[0]->commission_fees;
        $resp_payment= $payment_request->rraTaxPaymentIndependentAgent($request->amountToPay,$request->taxTypeDesc,$request->rraRef,$request->tin,$charges,$request->taxPayerName,$account,$header);    

    }

      if($request->brokering=="Independent" && $request->userGroup=="Individual_clients"){

               $fees = $payment_request->checkChargeesFee($request->brokering, $request->amountToPay);
        $charges= $fees[0]->commission_fees;
        $resp_payment= $payment_request->rraTaxPaymentIndividualClients($request->amountToPay,$request->taxTypeDesc,$request->rraRef,$request->tin,$charges,$request->taxPayerName,$account,$header) ;
     }    
             
    
    $mobicoreResponse=json_decode($resp_payment);   


   if(!isset($mobicoreResponse->transactionNumber)){ 



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


   




    
     

   }else{ 

           //  $RraTaxCollectionProject = new RraIntegration();

           //  $string = str_replace(' ', '-', $request->taxPayerName);
           //  $taxPayerName=preg_replace('/[^A-Za-z0-9\-]/', '', $string);
           //  $string = str_replace(' ', '-', $request->taxTypeDesc);
           //  $taxTypeDesc=preg_replace('/[^A-Za-z0-9\-]/', '', $string);

           //  $bprResponse = $RraTaxCollectionProject->rraPayment($request->transactionNumber,$request->rraRef,$request->descId,$request->tin,substr($taxPayerName,0,20),floatval($request->amountToPay),$request->taxTypeNo,substr($taxTypeDesc,0,20),$request->taxCenterNo,$request->assessNo,$request->rraOriginNo);




           //  $transfercode='';
           //  $bprResponsearray=json_decode($bprResponse);

           //  if(isset($bprResponsearray->status)){

           //  $status=$bprResponsearray->status;
           //  }else{

           //  $status=500;
           //  }

            

           //  if($status == 200||$status == 601||$status == 710){             
             
           //   $operationDateTime=$bprResponsearray->datetime;  
           //  if($bprResponsearray->status==601){
           //    $bprRefNo='';                
           //  }else{

           //      if(isset($bprResponsearray->bprRefNo)){

           //           $bprRefNo=$bprResponsearray->bprRefNo;
                      
           //       }else{

           //           $bprRefNo="";           
           //       }
           //  }

           //  if(!$bprRefNo=="") {

           //   $transfercode =$payment_request->bankTransfer($request->amountToPay,$request->rraRef,$bprRefNo,$operationDateTime,$account) ;
           //  }            
                      
           
           //  $payment_request->clean_rra_bpr_to_be_notified($request->transactionNumber);

           //  $payment_request->insert_rra_bpr_notification_status($request->rraRef,$request->transactionNumber,$request->amountToPay,$status,$bprResponse,$transfercode);         

           //  }else{

           //      $payment_request->insert_rra_bpr_notification_status($request->rraRef,$request->transactionNumber,$request->amountToPay,$status,$bprResponse,$transfercode); 
  
           // }

            $responseData=[

            "mobicashTransctionNo"=>$mobicoreResponse->transactionNumber,
            "amountPaid"=>$mobicoreResponse->amount,
            "transaction_fees"=>$fees[0]->commission_fees,
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


public function rraTaxPaymentIndividualClients(Request $request){   


    $validator = Validator::make($request->all(), [
        "bankName"=>'required',
        "rraRef"=>'required',
        "tin" =>'required',
        "taxPayerName"=>'required',
        "taxTypeDesc"=>'required',
        "taxCenterNo"=>'required',
        "taxTypeNo"=>'required',
        "assessNo"=>'required',
        "rraOriginNo"=>'required',
        "amountToPay"=>'required',
        "descId"=>'required',
        "payerPhone"=>'required',
        "brokering"=>'required' ,
        "userGroup"=>'required'              
        
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
     
    
    if ($request->isJson()){      
    
       
    $payment_request = new RraTaxCollectionProject();
 
    $taxAccount = $payment_request->rraAccountMapping($request->taxTypeNo,$request->taxCenterNo);

    if (empty($taxAccount)) {

                $date = date('Y-m-d H:i:s');

                $mcashResponse = [ "responseCode" => 104, 
                "communicationStatus" =>'FAILURE', 
                "codeDescription" => "Current Mobicash cannot be used to pay this tax." ,                   
                "data" =>"",
                "responseDate"=>$date

                ];

                return $mcashResponse ;

    }   

    $myArray = json_decode($taxAccount, true);
    $account= $myArray[0]["account_no"];




     

    if($request->brokering=="Independent" && $request->userGroup=="Individual_clients"){

       $fees = $payment_request->checkChargeesFee($request->brokering, $request->amountToPay);
    $charges= $fees[0]->commission_fees;
    $resp_payment= $payment_request->rraTaxPaymentIndividualClients($request->amountToPay,$request->taxTypeDesc,$request->rraRef,$request->tin,$charges,$request->taxPayerName,$request->payerPhone,$account,$Session_Token,$confirmationpin) ;
    }    
             
    
    $mobicoreResponse=json_decode($resp_payment);   


     


   if(!isset($mobicoreResponse->transactionNumber)){ 

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
     

   }else{ 

           //  $RraTaxCollectionProject = new RraIntegration();

           //  $string = str_replace(' ', '-', $request->taxPayerName);
           //  $taxPayerName=preg_replace('/[^A-Za-z0-9\-]/', '', $string);
           //  $string = str_replace(' ', '-', $request->taxTypeDesc);
           //  $taxTypeDesc=preg_replace('/[^A-Za-z0-9\-]/', '', $string);

           //  $bprResponse = $RraTaxCollectionProject->rraPayment($request->transactionNumber,$request->rraRef,$request->descId,$request->tin,substr($taxPayerName,0,20),floatval($request->amountToPay),$request->taxTypeNo,substr($taxTypeDesc,0,20),$request->taxCenterNo,$request->assessNo,$request->rraOriginNo);




           //  $transfercode='';
           //  $bprResponsearray=json_decode($bprResponse);

           //  if(isset($bprResponsearray->status)){

           //  $status=$bprResponsearray->status;
           //  }else{

           //  $status=500;
           //  }

            

           //  if($status == 200||$status == 601||$status == 710){             
             
           //   $operationDateTime=$bprResponsearray->datetime;  
           //  if($bprResponsearray->status==601){
           //    $bprRefNo='';                
           //  }else{

           //      if(isset($bprResponsearray->bprRefNo)){

           //           $bprRefNo=$bprResponsearray->bprRefNo;
                      
           //       }else{

           //           $bprRefNo="";           
           //       }
           //  }

           //  if(!$bprRefNo=="") {

           //   $transfercode =$payment_request->bankTransfer($request->amountToPay,$request->rraRef,$bprRefNo,$operationDateTime,$account) ;
           //  }            
                      
           
           //  $payment_request->clean_rra_bpr_to_be_notified($request->transactionNumber);

           //  $payment_request->insert_rra_bpr_notification_status($request->rraRef,$request->transactionNumber,$request->amountToPay,$status,$bprResponse,$transfercode);         

           //  }else{

           //      $payment_request->insert_rra_bpr_notification_status($request->rraRef,$request->transactionNumber,$request->amountToPay,$status,$bprResponse,$transfercode); 
  
           // }

            $responseData=[

            "mobicashTransctionNo"=>$mobicoreResponse->transactionNumber,
            "amountPaid"=>$mobicoreResponse->amount,
            "transaction_fees"=>$fees[0]->commission_fees,
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
?>
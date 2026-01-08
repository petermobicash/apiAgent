<?php   
namespace App\Http\Controllers\API\coreBank414\Services\Governement;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Governement\LtssProject; 


/**
 * @group LTSS
 *
 * API endpoints for managing LTSS
 */
class LtssCollectionProjectCoreBank414Controller extends BaseController
{

    public function nidLtssValidation(Request $request){ 
      
      $validator = Validator::make($request->all(), [
        "identification"=>'required|alpha_num|max:16|min:8'       
        
    ]);
    if($validator->fails()){
        return $this->sendError('Error validation', $validator->errors());       
    }
    
        if ($request->isJson()){      
                    
           $LtssProject = new LtssProject();     
           $ltssbRespone = $LtssProject->ltssnidValidation($request->identification);
      
           if($ltssbRespone!=''){
          
           $ltssbRespone1 =json_decode($ltssbRespone);
           
           if($ltssbRespone1->status == 200){
           
             $mcashResponse =["responseCode" => 200,                
                            "status"=>$ltssbRespone1->status,
                            "message"=>"valid",
                            "identification"=>$ltssbRespone1->identification,
                            "name"=>$ltssbRespone1->name                                            
                    
                   ];  
                                
          }else{

           $mcashResponse =["responseCode" => 400,                
                            "status"=>$ltssbRespone1->status,                             
                            "identification"=>$ltssbRespone1->message                                                                         
                    
                   ];
           

          }
        }else{
         $mcashResponse = [ "responseCode" => 400, 
                            "status" =>'Failed',                     
                            "responseDescription" => "empty response!" 
                          
                         ];
            
          }
           
         }else{

                 $mcashResponse = [ "responseCode" => 400, 
                                    "status" =>'Failed',                     
                                    "responseDescription" => "Content type Not Allowed" 
                          
                         ];
                 
           }

           return $mcashResponse;
 }
 public function ltssSendContribution(Request $request){

        $header = $request->header('Session-Token');

        $validator = Validator::make($request->all(), [
          "identification"=>'required|alpha_num|max:16|min:8',
          "userIdentifier"=>'required',
          "amount"=>'required',
          "agentCategory"=>'required',
          "payerPhone"=>'required',
          "payerName"=>'required',

          
         ]);
         if($validator->fails()){
          return $this->sendError('Error validation', $validator->errors());       
        }


        if ($request->isJson()){ 
     
                    
      
             $LtssProject = new LtssProject(); 
             ; 
            
            
             

            
             $paymentDate = date('Y-m-d H:i:s'); 

              if($request->agentCategory=="Independent"){       
                                                    
       

       $resp_payment= $payment_request->ltssMobicorePaymentIndependentUser($request->amount,$request->identification,$request->userIdentifier,$request->payerPhone,$request->payerName,$header);

       }else{

       $resp_payment= $payment_request->ltssMobicorePaymentDependentUser($request->amount,$request->identification,$request->userIdentifier,$request->payerPhone,$request->payerName,$header);
           
       } 

             $resp_payment = $LtssProject->ltssmobicorePayment($request->amount,$mobicash_reference_id,$request->identification,$request->userIdentifier,$request->payerPhone,$request->payerName,$header);           
             $mobicoreResponse=json_decode($resp_payment);

             
             
            if(isset($mobicoreResponse->id)){
             

                $ltssbResponse = $LtssProject->ltssContributionNotification($request->identification,$request->amount,$request->payerName,$mobicoreResponse->id,$paymentDate);
             
                $ltssbResponse1 =json_decode($ltssbResponse);

                $mcashResponse =["responseCode" => 200,                
                                  "status"=>"success", 
                                  "mobicashTransctionNo"=>$mobicoreResponse->id,
                                  "date"=>$paymentDate,
                                  "ltssResponse"=>$ltssbResponse1                                            
                                
                               ];

            }else{ 

              $mcashResponse =["responseCode" => 400,
                               "status"=>"Failed", 
                               "message" =>$mobicoreResponse               
                            
                           ];
           
                     
                     
             }
             
           
           
         }else{

                 $mcashResponse = [ "responseCode" => 400, 
                                    "status" =>'Failed',                     
                                    "responseDescription" => "Content type Not Allowed" 
                          
                         ];
                 
           }

           return $mcashResponse;
 }




}
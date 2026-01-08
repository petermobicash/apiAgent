<?php
namespace App\Http\Controllers\API\coreBank414;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\RnitCollectionProject;  
use App\Classes\Rclient ;

/**
 * @group rnit
 *
 * API endpoints for managing rnit
 */
class RnitCollectionProjectCoreBank414Controller extends BaseController{

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
 
                return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);
 
            }
            
        if ($request->isJson()){ 
     
       
      
           $rnitProject = new RnitCollectionProject(); 
           
               
           

           $rnitRespone = $rnitProject->rnitNidValidation($request->identification); 
           $rnitRespone1 = json_decode($rnitRespone) ;          


           if(!empty($rnitRespone1->fullName)){

             if($rnitRespone1->fullName!= "null null"){

                $mcashResponse =  ["responseCode" => 200,
                                   "status" =>'Success',             
                                   "rssbRssbResponse" => $rnitRespone1                             

                               ];                            
               
             }else{

                  $mcashResponse =  ["responseCode" => 400,
                                     "status" =>'Failed',
                                     "rssbResponse" =>$rnitRespone1             
                                     
                           
                                   ];                             
               

             }

            
             
           }else{
              $mcashResponse =  ["responseCode" => 400, 
                                 "status" =>'Failed',                                                                
                                 "description" =>"no response"
                           
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

  public function rnitSendContribution(Request $request){
      
    $validator = Validator::make($request->all(), [
           
        "identification"=>'required'                      
  
        ]);

        if ($validator->fails()) {

            return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

    } 
    $header = $request->header('Session-Token');

        if ($request->isJson()){ 
     
          $validator = Validator::make($request->all(), [
           
          "identification"=>'required', 
          "amount"=>'required',  
          "bankaccount"=>'required',          
          "payerName"=>'required',
          "bankname"=>'required',
          "clientid"=>'required'                     
     
           ]);

           if ($validator->fails()) {

               return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

           }          
      
             
                $client = new Rclient(); 
                $RnitProject = new RnitCollectionProject() ;        
               
                $extReferenceNo = $client->getMobicashRefNumber($request->clientid);  
                                            
                             
                $resp_payment=$RnitProject->rnitMobicorePayment($request->amount,$extReferenceNo,$request->bankaccount, $request->identification,$request->payerName,$request->payer_phone,$request->bankname,$request->payer_email,$request->clientid,$header);
                $mobicoreResponse=json_decode($resp_payment);

                if(!isset($mobicoreResponse->id)){

                $mcashResponse =["responseCode" => 400,
                                    "status"=>'Failed',
                                    "response"=>$mobicoreResponse                   
                                ];    

                    

                }else{ 
                                           
                
                $mcashResponse =["responseCode" => 200,                
                                    "status"=>"success", 
                                    "mobicashTransctionNo"=>$mobicoreResponse->id                                 
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
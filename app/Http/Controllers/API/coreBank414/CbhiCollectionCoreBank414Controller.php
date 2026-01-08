<?php   
namespace App\Http\Controllers\API\coreBank414;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\CbhiCollectionProject;  
use App\Classes\Rclient ;
/**
 * @group rssb mutuelle de sante
 *
 * API endpoints for managing mutuelle de sante
 */
class CbhiCollectionCoreBank414Controller extends BaseController{

    public function nidDetails(Request $request){ 
      
      $validator = Validator::make($request->all(), [
        "national_identity_number"=>'required|alpha_num|max:16|min:8',
        "year_of_payment"=>'required'
        
    ]);
    if($validator->fails()){
        return $this->sendError('Error validation', $validator->errors());       
    }   

        if ($request->isJson()){   
      
           $cbhicollection = new CbhiCollectionProject();       
           
           $rssbRespone = $cbhicollection->getHouseholdNIDDetails($request->national_identity_number,$request->year_of_payment); 
              
           

           if(!empty($rssbRespone)){

             if($rssbRespone->Errorcode==0){

                $mcashResponse =  ["responseCode" => 200,
                                   "status" =>'Success',             
                                   "rssbRssbResponse" => $rssbRespone                             

                               ];                            
               
             }else{

                  $mcashResponse =  ["responseCode" => 400,
                                     "status" =>'Failed',
                                     "rssbResponse" =>$rssbRespone             
                                     
                           
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


 public function cbhiSendPayment(Request $request){ 
   
     $header = $request->header('Session-Token');
     $validator = Validator::make($request->all(), [

      "amountPaid"=>'required',                   
      "national_identity_number"=>'required', 
      "payer_economic_status"=>'required',          
      "payer_household_member_number"=>'required',
      "total_premium"=>'required',
      "year_of_payment"=>'required',                   
      "payer_name"=>'required',
      "client_id"=>'required'     

        ]);
        if($validator->fails()){
         return $this->sendError('Error validation', $validator->errors());       
       }
   
     if ($request->isJson()){

      

       
       $cbhicollection = new CbhiCollectionProject();
       $client = new Rclient();  
       $mobicashreferencenumber = $client->getMobicashRefNumber($request->client_id);
      
       $paymentDate = date('Y-m-d H:i:s'); 


       $resp_payment=$cbhicollection->cbhimobicorePayment($request->amountPaid,$mobicashreferencenumber,$request->national_identity_number,$request->payer_economic_status,$request->payer_household_member_number,$request->total_premium,$request->year_of_payment,$request->payer_name,$request->client_id,$header);       
      
       $mobicoreResponse=json_decode($resp_payment);
       
       if (isset($mobicoreResponse->id)) {       
           
          
           $mcashResponse = ["responseCode" =>200,           
           "status" =>'success', 
           "transfersId" =>$mobicoreResponse->id
          
           
           ];         

       }else{ 

         $mcashResponse = ["responseCode" => 400, 
                             "status" =>"Failed",                             
                             "responseDescription" =>$mobicoreResponse 
                            
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


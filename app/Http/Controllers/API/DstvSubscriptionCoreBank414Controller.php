<?php   
namespace App\Http\Controllers\API; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\StarTimeService;  
use App\Classes\Rclient ;
/**
 * @group startTimes
 *
 * API endpoints for managing startTimes
 */
class DstvSubscriptionCoreBank414Controller extends BaseController
{
    public function startimeRecharge_infos(Request $request){	  
      $validator = Validator::make($request->all(), [
        "servicecode"=>'required|alpha_num|max:12|min:10'                
      ]);
      if ($validator->fails()) {

          return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

      } 
		
        if ($request->isJson()){       

           
        
         $StarTimeService = new StarTimeService();
         $StarTimeServiceResponse = $StarTimeService->startimeRecharge_infos($request->servicecode);      
         $StarTimeServiceResponseObject= json_decode($StarTimeServiceResponse);

         if(!isset($StarTimeServiceResponseObject->errorCode)){         
         $mcashResponse =["responseCode" => 200,
                          "status"=>"success",
                          "message"=>$StarTimeServiceResponseObject				             
                                                       
                          ];           
        }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>$StarTimeServiceResponseObject				             
                                                       
                          ];
                 
          }
        

     }else{

        $mcashResponse = [ "responseCode" => 400,					             
                           "responseDescription" => "Content type Not Allowed"
                         ];

        
  }
  return $mcashResponse;            
  
  
}
public function startimeReplaceable_packages(Request $request){  
  $validator = Validator::make($request->all(), [
    "servicecode"=>'required|alpha_num|max:12|min:10'                
  ]);
  if ($validator->fails()) {

      return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

  }
		
    if ($request->isJson()){         

       
    
     $StarTimeService = new StarTimeService();
     $StarTimeServiceResponse = $StarTimeService->startimeReplaceable_packages($request->servicecode); 
     
     
     $StarTimeServiceResponseObject= json_decode($StarTimeServiceResponse);
     
     

     if(!isset($StarTimeServiceResponseObject->errorCode)){  

     $mcashResponse =["responseCode" => 200,
                      "status"=>"success",
                      "message"=>$StarTimeServiceResponseObject				             
                                                   
                      ];           
    }else{
           $mcashResponse =["responseCode" => 400,
                            "status"=>"Failed",
                            "message"=>$StarTimeServiceResponseObject				             
                                                   
                      ];
             
      }
    

 }else{

    $mcashResponse = [ "responseCode" => 400,					             
                       "responseDescription" => "Content type Not Allowed"
                     ];

    
}
return $mcashResponse;            


}

public function starTimeRecharging(Request $request){	
  $validator = Validator::make($request->all(), [
    "servicecode"=>'required|alpha_num|max:12|min:10'                
  ]);
  if ($validator->fails()) {

      return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

  } 
    $header = $request->header('Session-Token');
    $transaction_time = date("Y-m-d h:i:s");
   
		
    if ($request->isJson()){         

           
    
     $StarTimeService = new StarTimeService();
     $client = new Rclient();
     $mobicashreferencenumber = $client->getMobicashRefNumber($request->clientId);
     
     $StarTimeServiceResponse = $StarTimeService->starTimeRecharging($mobicashreferencenumber,$transaction_time,$request->amount,$request->mobile,$request->servicecode);      
     $StarTimeServiceResponseObject= json_decode($StarTimeServiceResponse);

     

     if(isset($StarTimeServiceResponseObject->basic_offer_display_name)){ 
     
      
      $description ='service_code:'.$request->servicecode.','.'Client mobilePhone :'.$request->mobile.','.'bouquet:'.$StarTimeServiceResponseObject->basic_offer_display_name;        
      $mobicoreresponse=$StarTimeService->mobicorePayment($request->amount,$mobicashreferencenumber,$request->mobile,$request->clientId,$description,$header);
      $mcashResponse =["responseCode" => 200,
                      "status"=>"success",
                      "mobicoreresponse"=>json_decode($mobicoreresponse),
                      "message"=>$StarTimeServiceResponseObject				             
                                                   
                      ];           
    }else{
           $mcashResponse =["responseCode" => 400,
                            "status"=>"Failed",
                            "message"=>$StarTimeServiceResponseObject				             
                                                   
                      ];
             
      }
    

 }else{

    $mcashResponse = [ "responseCode" => 400,					             
                       "responseDescription" => "Content type Not Allowed"
                     ];

    
}
return $mcashResponse;            


}


}
<?php   
namespace App\Http\Controllers\API\coreBank414; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\ElectricitySubscription;
use App\Classes\Rclient ;  

class ElectricitySubscripptionCoreBank414Controller extends BaseController
{
    public function cashPowerMeterNumberValidation(Request $request){	  
 
        $validator = Validator::make($request->all(), [

            "meterNumber"=>'required|alpha_num|max:12|min:10'                 
            
            ]);
  
            if ($validator->fails()) {
  
                return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);
  
            } 
        if ($request->isJson()){        

           
        
         $ElectricityService = new ElectricitySubscription();
         $electricityPoviderResponse = $ElectricityService->getCashpowerMeterDetails($request->meterNumber);
         

         $electricityPoviderResponse1= json_decode($electricityPoviderResponse);
        
         if(!empty($electricityPoviderResponse)){           

          if( $electricityPoviderResponse1!="" && $electricityPoviderResponse1->status==1){
         $mcashResponse =["responseCode" => 200,
                          "status"=>$electricityPoviderResponse1->status,
                          "consumer"=>$electricityPoviderResponse1->consumer				             
                                                       
                          ];
           
      }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>$electricityPoviderResponse1->status,
                                "message"=>$electricityPoviderResponse1->message				             
                                                       
                          ];
                 
          }
        }else{

            $mcashResponse =["responseCode" => 400,
            "status"=>"Failed",
            "message"=>"Emputy response"				             
                                   
      ];

        }


                  

   }else{

          $mcashResponse = [ "responseCode" => 400,					             
                             "responseDescription" => "Content type Not Allowed"
                           ];

          
    }
    return $mcashResponse;
  
}
public function electricityPayment(Request $request){




    $validator = Validator::make($request->all(), [

        "clientid"=>'required',
        "payer_phone"=>'required|alpha_num|max:12|min:10',
        "amount"=>'required|integer|min:500', 
        "meternumber"=>'required' ,
        "payer_name"=>'required' 

        
        ]);

        if ($validator->fails()) {

            return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

        }

        $header = $request->header('Session-Token');

      
        if ($request->isJson()){  

            if ($request->isJson()){ 
        

                  
         
          $ElectricityService = new ElectricitySubscription();
          $client = new Rclient();
          $mobicashReference = $client->getMobicashRefNumber($request->clientid);
          $mobicorePreviewResponse=$ElectricityService->mobicorePreviewPayment($request->amount,$mobicashReference,$request->meternumber,$request->payer_phone,$request->payer_name,$request->clientid,$header);
        $mobicorePreviewResponse1= json_decode($mobicorePreviewResponse) ;

         if(!empty($mobicorePreviewResponse1->paymentType->id)){               
           
         try{

         $electricityPoviderResponse = $ElectricityService->buyElectricity($request->meternumber,$request->amount,$request->payer_phone,$mobicashReference);  
          
         $electricityPoviderResponse1 = json_decode($electricityPoviderResponse);

         }catch(Exeption $ex){
         

         }  

        if( $electricityPoviderResponse1!="" && $electricityPoviderResponse1->status==1){


         $token=$electricityPoviderResponse1->vend->token;


          $mobicoreResponse=$ElectricityService->mobicorePayment($request->amount,$mobicashReference,$request->meternumber,$request->payer_phone,$request->payer_name,$token,$request->clientid,$header);
          
            $mobicoreResponse1=json_decode($mobicoreResponse);    
           if(!empty($mobicoreResponse1->id)){
            $mobicoreid=$mobicoreResponse1->id;
           }else{
            $mobicoreid="$mobicoreResponse";
           }
             
             $mcashResponse =["responseCode" => 200,
                              "mobicore"=>$mobicoreid,
                              "status"=>$electricityPoviderResponse1->status,
                              "response"=>$electricityPoviderResponse1->vend			                    	                    
                                                           
                              ];

              

      }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>$electricityPoviderResponse1->status,
                                "mobicashReference"=>$electricityPoviderResponse1->refNo,
                                "message"=>$electricityPoviderResponse1->message
                                                             
                                                       
                          ];
          
          }
          }else{

               $mcashResponse =["responseCode" => 400,                                 
                                "message"=> $mobicorePreviewResponse1
                                                             
                                                       
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


}
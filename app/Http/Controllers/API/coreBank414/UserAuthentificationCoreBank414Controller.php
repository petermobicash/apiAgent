<?php   
namespace App\Http\Controllers\API\coreBank414; 
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\UserAuthentification;  

/**
 * @group User Authentification CoreBank414
 *
 * API endpoints for managing Authentification
 */
class UserAuthentificationCoreBank414Controller extends BaseController
{
    public function useAuthentification(Request $request){
if($request->header('Authorization')){
           
           $header=$request->header('Authorization');
           $UserAuthentification = new UserAuthentification();
           $response =$UserAuthentification->useAuthentification($header);
          
           $response=json_decode($response);

           if(isset($response->user)){

           $userinfo=[ "id" =>$response->user->id,
                       "display" =>$response->user->display,
                       "principal"=>$response->principal
                                                     
                        ];

           $mcashResponse =["responseCode" => 200,
                                "status"=>"success",
                                "infos"=>$userinfo                    
                                                       
                          ];
                        }else{

                          $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>$response                    
                                                       
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

     $header=$request->header('Authorization');
   

         $validator = Validator::make($request->all(), [
        "user"=>'required'                
         ]);
         if ($validator->fails()) {

          return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

          }
              
         if ($request->isJson()){

         if($request->header('Authorization')){
           
           $header=$request->header('Authorization');

           $UserAuthentification = new UserAuthentification();

           $response =$UserAuthentification->userValidation($request->user,$header);

           // $response=json_encode($response);  

           $response=json_decode($response);          


           if(isset($response[0]->id)){
           
           
            $mcashResponse =["responseCode" => 200,
                            "status"=>"success",
                            "userinfo"=>$response                    
                                                       
                          ];
          }else{
            $mcashResponse =["responseCode" => 400,
                            "status"=>"Failed",
                            "userinfo"=>$response                    
                                                       
                          ];

          }

        }else{
               $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>"Authorization please"                    
                                                       
                          ];
                 
          }
        }else{

        $mcashResponse = [ "responseCode" => 400,                      
                           "responseDescription" => "Content type Not Allowed"
                         ];        
     }       

  return $mcashResponse;     
  
  
}

public function userTokenSession(Request $request){    
     

         if($request->header('Authorization')){
           
           $header=$request->header('Authorization');
           $UserAuthentification = new UserAuthentification();
           $response =$UserAuthentification->userTokenSession($header);
           
           $response=json_decode($response);

           if(isset($response->user)){

           $userinfo=[ "id" =>$response->user->id,
                       "display" =>$response->user->display,
                       "principal"=>$response->principal,
                       "sessionToken"=>$response->sessionToken                    
                                                       
                        ];

           $mcashResponse =["responseCode" => 200,
                                "status"=>"success",
                                "infos"=>$userinfo                    
                                                       
                          ];
                        }else{

                          $mcashResponse =["responseCode" => 400,
                                "status"=>"Failed",
                                "message"=>$response                    
                                                       
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



}
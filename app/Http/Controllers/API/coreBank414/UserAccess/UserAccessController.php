<?php   
namespace App\Http\Controllers\API\coreBank414\UserAccess;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\usersaccess\UsersAccess;  

/**
 * @group User Authentification CoreBank414
 *
 * API endpoints for managing Authentification
 */

class UserAccessController extends BaseController
{
    public function changePassword(Request $request){

          if($request->header('Authorization')){


          $validator = Validator::make($request->all(), [
                                 
              "oldPassword"=>'required|string', 
              "newPassword"=>'required|string',          
              "newPasswordConfirmation"=>'required|string',
                               
        
          ]);

        if ($validator->fails()) {

            return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

    }
           
           $header=$request->header('Authorization');
           $usersAccess = new UsersAccess();

           $response =$usersAccess->changePassword($request->changePassword,$request->newPassword,$request->newPasswordConfirmation,$header);
          
              echo $response;
              exit();

           $response=json_decode($response);



           if(isset($response->user)){

             $userinfo=[ "id" =>$response->user->id,
                         "display" =>$response->user->display,
                         "principal"=>$response->principals
                                                       
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

public function forgettenpasswordRequest(Request $request){

           


          $validator = Validator::make($request->all(), [
                                 
              "user"=>'required|string', 
              "sendMedium"=>'required|email'           
               
                               
        
          ]);

        if ($validator->fails()) {

            return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

    }
           
           $header=$request->header('Authorization');
           $usersAccess = new UsersAccess();

           $response =$usersAccess->forgettenpasswordRequest($request->user,$request->sendMedium);
          
              echo $response;
              exit();

           $response=json_decode($response);



           if(isset($response->user)){

             $userinfo=[ "id" =>$response->user->id,
                         "display" =>$response->user->display,
                         "principal"=>$response->principals
                                                       
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
         
        

  return $mcashResponse;  
             
         
  
}


}
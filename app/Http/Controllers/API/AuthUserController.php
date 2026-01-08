<?php 

namespace App\Http\Controllers\API;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\AuthentificationCyclos4 ;
use App\Classes\AuthentificationCyclos3 ;

/**
 * @group Authentication
 *
 * API endpoints for managing authentication
 */  
class AuthUserController extends BaseController
{

    public function login(Request $request)
    {  
    	

    
        $validator = Validator::make($request->all(), [
        
        'user_name'=> 'required',
        'principal_type'=>'required',
        'password'=> 'required'
        
        
    ]);
    if($validator->fails()){
        return $this->sendError('Authorization', $validator->errors());       
    }
    $user = new AuthentificationCyclos4();
    $user4 = new AuthentificationCyclos3();
$user4->principal_type = $request->principal_type;
          if($request->principal_type == 'mobilePhone'){
            $user4->principal=$user4->mobileFormat($request->user_name);
          }else{
            $user4->principal=$request->user_name;
          }

         $check_agent = $user4->getMember();

    
    $response=$user->checkCredentials($request->user_name,$request->password);


      $response=json_decode($response);




      if(isset($response->user->id)){

         

          $user4->principal_type = $request->principal_type;
          if($request->principal_type == 'mobilePhone'){
            $user4->principal=$user4->mobileFormat($request->user_name);
          }else{
            $user4->principal=$request->user_name;
          }

         $check_agent = $user4->getMember();

         

         if(isset($check_agent->return->groupId) && ($check_agent->return->groupId == 13)){ 

            $agentId = $check_agent->return->id ;

            $content_data=$check_agent->return->fields;

            foreach($content_data as $item){
              if($item->internalName == "mobilePhone"){
                $agentNumber=$item->value;
              }
              
            }

         }      
      

          return response()->json([

                    'code' =>200,
                    'status'=>'Success',
                    'agentId4' =>$response->user->id,
                    'agentId3'=>$agentId,
                    'agentphone'=>$agentNumber,
                    'name' =>$response->user->display
        ],200);
                         
         
      }else{


        return response()->json([

                    'code' =>400,
                    'status' =>'Failed',
                    'Message'=>$response

        ],501);

                       


      }

        
  }

    
   
}
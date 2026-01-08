<?php
namespace App\Classes\coreBank414\usersAccess;
use DB;

class UsersAccess  
{
    public function changePassword($oldPassword,$newPassword,$newPasswordConfirmation,$header){


  $request=array (
  'oldPassword' => $oldPassword,
  'newPassword' => $newPassword,
  'checkConfirmation' => true,
  'newPasswordConfirmation' => $newPasswordConfirmation,
  'forceChange' => false,
  );
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/passwords/pin/change',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

    return $response;
    
    }

  public function forgettenpasswordRequest($user){


  $request=array (
  'user' => $user,
  'sendMedium' =>'email'   
  );

    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/auth/forgotten-password/request',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode( $request),
  CURLOPT_HTTPHEADER => array(
     
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
 
return $response;

}
 public function forgettenpasswordChange($user,$code,$newPassword,$newPasswordConfirmation){

  $request=array (
  'user' => $user,
  'code' => $code,
  'newPassword' => $newPassword,
  'checkConfirmation' => true,
  'newPasswordConfirmation' =>$newPasswordConfirmation,
  'sendMedium' => 'email'
);


$curl = curl_init();

curl_setopt_array($curl, array(

    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/auth/forgotten-password',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($request),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;


}
public function generateFirstTimePassword($header){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/passwords/pin/generate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$header.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;


}
public function authentification($header){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/auth',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$header.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
}

public function tokenSessionActivation($Session_Token,$pin){
 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/auth/session/secondary-password',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$pin,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/plain',
    'Session-Token:'.$Session_Token.''
  ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

return $httpcode;
}

public function authentificationtsession($header){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/auth/session',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Authorization:'.$header.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return  $response;
}




public function groupAppartenance($header){


$curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/self',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization:'.$header.''
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;



    }

  public function mainAgentGroup($agentId,$header){

      $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/'.$agentId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
       'Authorization:'.$header.''
    ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;


$curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL =>'https://testbox.mobicash.rw/CoreBank/test_box/api/users/self',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization:'.$header.''
        ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;



    }


public function userSearchByAdmin($userAccount,$header){

    $curl = curl_init();

curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users//\'$userAccount',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization:'.$header.''
        ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;


}


public function resetUserPin($account,$header,$type){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/\''.$account.'/passwords/'.$type.'/reset-and-send?sendMediums=email',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Authorization:'.$header.''
  ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);




return $httpcode;






}


public function changeUserPin($account,$header,$oldauth,$newauth,$newauthconfirmaation,$type){ 


if($type=='pin'){
 $url= 'https://testbox.mobicash.rw/CoreBank/test_box/api/\''.$account.'/passwords/pin/change';
}else{
 $url= 'https://testbox.mobicash.rw/CoreBank/test_box/api/\''.$account.'/passwords/user_password/reset-and-send?sendMediums=email';
}



$jayParsedAry = [
   "oldPassword" =>$oldauth, 
   "newPassword" =>$newauth, 
   "checkConfirmation" => true, 
   "newPasswordConfirmation" =>$newauthconfirmaation, 
   "forceChange" => false 
]; 


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>$url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization:'.$header.''
  ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);





curl_close($curl);

if(!$response){

 return $httpcode;
 
}else{

  return $response;


}








}


}
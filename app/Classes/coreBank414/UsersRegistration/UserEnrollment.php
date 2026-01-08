<?php
namespace App\Classes\coreBank414\UsersRegistration;
use DB;

class UserEnrollment
{
public function userIndependantEnrollment($names,$username,$email,$nationality,$identity_type,$identity_number,$tax_identification_number,$business_website_url,$registration_date,$number,$province,$district,$sector,$city,$header){

$group="mini_agents_group";
$request=array (
  "name" => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'nationality' => $nationality,
    'countrycode' => 'RWA',
    'identity_type' => $identity_type,
    'identity_number' => $identity_number,
    'tax_identification_number' => $tax_identification_number,
    'business_size_type' => 'small',
    'business_website_url' => $business_website_url,
    'business_category' => 'private',
    'business_type' => 'sole_proprietorship',
    'registration_date' => $registration_date
  ),
  'group' =>$group,
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'skipActivationEmail' => true,
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => ''.$city.'',
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => false,
);
// echo json_encode($request);
// exit();

       $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    
    }

  public function userDependantEnrollment($names,$username,$email,$nationality,$identity_type,$identity_number,$tax_identification_number,$business_website_url,$registration_date,$broker,$number,$province,$district,$sector,$city,$header){


$request=array (
  "name" => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'nationality' => $nationality,
    'countrycode' => 'RWA',
    'identity_type' => $identity_type,
    'identity_number' => $identity_number,
    'tax_identification_number' => $tax_identification_number,
    'business_size_type' => 'small',
    'business_website_url' => $business_website_url,
    'business_category' => 'private',
    'business_type' => 'sole_proprietorship',
    'registration_date' => $registration_date
  ),
  'group' =>'retail_agents',
  'broker'=> $broker,
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'skipActivationEmail' => true,
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => ''.$city.'',
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => false,
);


       $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: '.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    
    }

  public function userOperatorEnroment($operatorName,$operatorUserName,$operatorUserEmail,$nationality,$countrycode,$identity_type,$identity_number,$registration_date,$email_validation_date,$position,$mainUserName,$mainPhone,$pin,$confirmationPin,$header){

  

$request=array (
  'name' =>$operatorName,
  'username' => $operatorUserName,
  'email' => $operatorUserEmail,
  'customValues' => 
  array (
    'nationality' => $nationality,
    'country_code' => $countrycode,
    'identity_type' => $identity_type,
    'identity_number' => $identity_number,
    'registration_date' => $registration_date,
    'email_validation_date' => $email_validation_date,
    'position' => $position,
  ),
  
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $mainPhone,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'passwords' => 
  array (
    0 => 
    array (
      'type' => 'pin',
      'value' => $pin,
      'checkConfirmation' => true,
      'confirmationValue' => $confirmationPin,
      'forceChange' => true,
    ),
  ),
  
);

// echo json_encode($request);
// exit();
 
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/'.$mainUserName.'/operators',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>json_encode($request),
    CURLOPT_HTTPHEADER =>  array(
    'Authorization: '.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    
    }

  public function settingAgentMainBroker($dependant,$broker,$header){

  $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/'.$dependant.'/brokers/'.$broker.'?main=true',
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
public function groupsOperatorView($header){



  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/operator-groups',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER =>  array(
        'Authorization: '.$header.''
    ),
));

$response = curl_exec($curl);

curl_close($curl);
 

 
return $response;
    }

    public function resetingOperatorsPinByAgent($operator,$header){

      $curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/'.$operator.'/passwords/pin/reset-and-send?sendMediums=email',
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


$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
return $httpCode;




    }


public function viewAgentOperators($header){



  $curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/self/operators',
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

public function userValidation($params,$header){


$curl = curl_init();

curl_setopt_array($curl, array(

    CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users/\''.$params.'',
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


public function StudentOnboarding($names,$username,$email,$firstname, $lastname,$maritial_status,$gender,$BOD,$studentregistration,$studentnatid,$schoolcode,$parentguardian,$parentguardid,$parentguardianphone ,$number,$province,$district,$sector,$city,$header){

   $request=array (
  'name' => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'firstname' => $firstname,
    'lastname' => $lastname,
    'maritial_status' => $maritial_status,
    'gender' => $gender,
    'date_of_birth' => $BOD,
    'studentregistration' =>$studentregistration,
    'studentnatid' => $studentnatid,
    'studentemail' => $email,
    'studentphone' =>$number,
    'schoolcode' => $schoolcode,
    'parentguardian' => $parentguardian,
    'parentguardid' => $parentguardid,
    'parentguardianphone' => $parentguardianphone,
  ),
  'group' => 'student_clients',
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' =>$province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => $city,
      'region' => 'string',
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => false,
);

   // echo json_encode($request);
   // exit();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Y2xpZW50cmVnaXN0ZXI6Y2xpZW50cmVnaXN0ZXI=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




  }

  public function RegisterNewSchoolByAdmin($names,$username,$email,$bank_name, $bank_account_holder_name,$bank_account_number,$bank_branch,$bank_code,$schoolfees,$schoolstatus,$schoolcat,$schoolcode,$number,$province,$district,$sector,$cell,$village,$city,$header){


    $request =array (
  'name' =>$names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'bank_name' =>$bank_name,
    'bank_account_holder_name' => $bank_account_holder_name,
    'bank_account_number' => $bank_account_number,
    'bank_branch' => $bank_branch,
    'bank_code' => $bank_code,
    'schoolfees' => $schoolfees,
    'schoolstatus' => $schoolstatus,
    'schoolcat' => $schoolcat,
    'schoolcode' => $schoolcode,
  ),
  'group' => 'school_group',
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'complement' => $cell,
      'village' => $village,
      'city' => $city,
      'region' => 'string',
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => false,
);

   

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
     'Authorization:'.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;





  }

  public function RegisterNewParentByAdmin($names,$username,$email,$firstname, $lastname,$nextkinname,$nextkinrelation,$identity_number,$maritial_status,$gender,$date_of_birth,$number,$province,$district,$sector,$city,$header){


    $request =array (
  'name' =>$names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'firstname' => $firstname,
    'lastname' => $lastname,
    'nextkinname' => $nextkinname,
    'nextkinrelation' => $nextkinrelation,
    'identity_type' => 'national_id',
    'identity_number' => $identity_number,
    'maritial_status' => $maritial_status,
    'gender' =>$gender,
    'date_of_birth' => $date_of_birth,
  ),
  'group' => 'Individual_clients',
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => $city,
      'region' => 'string',
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
 
  'asMember' => false,
);

  

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
     'Authorization:'.$header.'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;





  }

  public function userClientEnrollmentWithAdmin($names,$username,$email,$nationalityidentity_type,$identity_number,$maritial_status,$gender,$date_of_birth,$number,$province,$district,$sector,$city,$header){



$group="Individual_clients";
$request=array (
  'name' => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'identity_type' => $nationalityidentity_type,
    'identity_number' => $identity_number,
    'maritial_status' =>$maritial_status,
    'gender' => $gender,
    'date_of_birth' => $date_of_birth,
  ),
  'group' =>$group,

  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' =>$province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => $city,
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => false,
);
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization:'.$header.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    
} 


public function userClientEnrollmentWithAgent($names,$username,$email,$nationalityidentity_type,$identity_number,$maritial_status,$gender,$date_of_birth,$number,$province,$district,$sector,$city,$header){



$group="Individual_clients";
$request=array (
  'name' => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'identity_type' => $nationalityidentity_type,
    'identity_number' => $identity_number,
    'maritial_status' =>$maritial_status,
    'gender' => $gender,
    'date_of_birth' => $date_of_birth,
  ),
  'group' =>$group,

  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $number,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' =>$province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => $city,
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
  'asMember' => true,
);
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization:'.$header.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    
    } 


public function userClientDependentEnrollment($names,$username,$email,$identity_type,$identity_number,$maritial_status,$gender,$date_of_birth,$group,$brokerid,$phonenumber,$province,$district,$sector,$city){

   

  $request = array (
  'name' => $names,
  'username' => $username,
  'email' => $email,
  'customValues' => 
  array (
    'identity_type' => $identity_type,
    'identity_number' => $identity_number,
    'maritial_status' => $maritial_status,
    'gender' =>$gender,
    'date_of_birth' => $date_of_birth,
  ),

  'group' => 'Individual_clients',
  'broker' => $brokerid,
  'mobilePhones' => 
  array (
    0 => 
    array (
      'name' => 'Main Phone',
      'number' => $phonenumber,
      'extension' => '250',
      'hidden' => false,
      'enabledForSms' => true,
      'verified' => true,
      'kind' => 'landLine',
    ),
  ),
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'city' => $city,
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
);
 // return json_encode($request);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Y2xpZW50YWRtaW46Y2xpZW50YWRtaW4=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    }


public function brokerIdValidation($brokerId){



  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users?keywords='.$brokerId.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Y2xpZW50YWRtaW46Y2xpZW50YWRtaW4='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;




    }

  public function userPendingClientEnrollment($names ,$identity_number,$maritial_status,$gender,$date_of_birth,$province,$district,$sector,$cell,$village){

   

  $request = array (
  'name' => $names,
  'customValues' => 
  array (
    'identity_type' => 'national_id',
    'identity_number' => $identity_number,
    'maritial_status' => $maritial_status,
    'gender' => $gender,
    'date_of_birth_nid' => $date_of_birth,
  ),
  'group' => 'pending_clients_group',
  'broker' => '8241755934761394598',
  'addresses' => 
  array (
    0 => 
    array (
      'name' => 'Main Address',
      'addressLine1' => $province,
      'addressLine2' => $district,
      'neighborhood' => $sector,
      'complement' => $cell,
      'city' => $village,
      'country' => 'RW',
      'defaultAddress' => true,
      'hidden' => false,
    ),
  ),
);
 




$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($request),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Y2xpZW50YWRtaW46Y2xpZW50YWRtaW4=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;
    }

public function externalClientsGroupEnrollment($phoneNumber){

$jayParsedAry = [
   "name" =>$phoneNumber, 
   "group" => "external_clients_group", 
   "mobilePhones" => [
         [
            "name" => "Main Phone", 
            "number" => $phoneNumber, 
            "extension" => "250", 
            "hidden" => false, 
            "enabledForSms" => true, 
            "verified" => true, 
            "kind" => "landLine" 
         ] 
      ], 
   "skipActivationEmail" => true 
]; 

echo $jayParsedAry;
exit();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic ZXh0ZXJuYWxjbGFkbWluOmV4dGVybmFsY2xhZG1pbkAyNTA=',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

    }
public function simpleClientsEnrollment($phoneNumber,$taxpayername,$username,$taxidentificationnumber,$authorisation){


  $jayParsedAry = [
   "name" => $taxpayername, 
   "username" => $username, 
   "group" => "Individual_clients", 
   "customValues" => [
         "tax_identification_number" => $taxidentificationnumber 
      ], 
   "mobilePhones" => [
            [
               "name" => "Main Phone", 
               "number" => $phoneNumber, 
               "extension" => "250", 
               "hidden" => false, 
               "enabledForSms" => true, 
               "verified" => true, 
               "kind" => "landLine" 
            ] 
         ] 
];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://testbox.mobicash.rw/CoreBank/test_box/api/users',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($jayParsedAry),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: '.$authorisation.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
return $response;

  }




}
<?php
 
namespace App\Classes\coreBank414\Services\Utilities;
use DB;

Class TokenValidation{  


public function insertToken($token)
{     
    $service='cbhi';
    $data=array('service'=>$service,"token"=>$token);
    // DB::table('third_party_auth')->insert($data);
    DB::connection('mysql')->table("third_party_auth")->insert($data);
    
    
}
public function selectToken($service)
{
     
    
    $sql = "SELECT service,token,date FROM third_party_auth WHERE service='.$service.'  ORDER BY id desc limit 1";        
    $result = DB::select($sql);


    return $result;

    
}
public function saveCbhiProfileLog($params){  

    $data=array('nid'=>$params['nid'],"status_code"=>$params['status_code'],'cbhi_response'=>$params['cbhi_response'],"channel"=>'Mobicash');
    DB::table('logs_cbhi_profile_call')->insert($data);

}
public function existMobicahRefInMobicore($nid,$amount){    


$my_date_time = date("Y-m-d H:i:s", strtotime("-1 hours"));   

$sql = "SELECT cfv.id, cfv.string_value, cfv.transfer_id, tr.date, tr.amount FROM custom_field_values cfv, transfers tr WHERE cfv.transfer_id = tr.id AND cfv.field_id = 110 AND  cfv.string_value = '$nid' AND tr.date>'$my_date_time' AND tr.amount = $amount";
$result = DB::select($sql);

 

if(isset($result)){

    foreach ($result as $result) {

    return $result->id;
}
}else{

    return 0;

}
 



}                 
public function existNidInMobicore($nid,$amount){  
   
     

    $my_date_time = date("Y-m-d H:i:s", strtotime("-1 hours"));  
    
    $sql = "SELECT cfv.id, cfv.string_value, cfv.transfer_id, tr.date, tr.amount FROM custom_field_values cfv, transfers tr WHERE cfv.transfer_id = tr.id AND cfv.field_id = 110 AND  cfv.string_value = '$nid' AND tr.date = '$my_date_time' AND tr.amount = $amount";
    $result = DB::select($sql);
    


if(isset($result)){

    foreach ($result as $result) {
        
    return $result->id;
}
}else{

    return 0;

}
    
}
public function new_rssb_tobe_notified($mcashReferenceNumber,$householdNID,$amount,$year,$payerPhone,$paydate){      
    
      

    $data=array('mobicash_ref_no'=>$mcashReferenceNumber,"nid"=>$householdNID,'amount'=>$amount,"year_of_collection"=>$year,'client_phone_number'=>$payerPhone,"created_at"=>$paydate);
    DB::table('cbhi_to_be_notified')->insert($data);       
     

}
public function new_rssb_notification($householdNID,$mobicash_ref_no,$status,$cbhi_response,$created_at){ 

$data=array('nid'=>$householdNID,"mobicash_ref_no"=>$mobicash_ref_no,'status'=>$status,"cbhi_response"=>$cbhi_response,"created_at"=>$created_at);
DB::table('cbhi_notification_status')->insert($data);
    
    
    
}
public function updatenotification($mcashReferenceNumber){   

    DB::table('cbhi_to_be_notified')
            ->where('mobicash_ref_no', $mcashReferenceNumber)
            ->update([array('status' =>NULL)]);

    
}
public function cbhipending(){
    
    $sql ="SELECT mobicash_ref_no,nid,amount,year_of_collection,client_phone_number,created_at FROM cbhi_to_be_notified WHERE status IS NULL limit 200";
    $result = DB::select($sql);

    return $result;   
   
}

public function removetransaction($mcashReferenceNumber){     
    

    DB::table('cbhi_to_be_notified')->where('mobicash_ref_no', $mcashReferenceNumber)->delete();

    
} 
 

}
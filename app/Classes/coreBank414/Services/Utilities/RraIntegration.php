<?php

namespace App\Classes\coreBank414\Services\Utilities;

Class RraIntegration{
	
	//RRA Get details Production
    public function getDec($rra_ref){
        try{
   $username='15';
        $password='249d727e4737f6c2d054bd1455f5ce28';
        $params = new \stdClass();

        $parameters=array(
                "userID" => $username,
                "userPassword" => $password,
                "RRA_ref" => $rra_ref);

        $client = new \SoapClient("http://10.0.0.82:8082/RRA_BANK_DEX/services/WebService?wsdl",array('trace' => 1,'exceptions'=>0));

        $result= $client->getDec($parameters);
        error_log(json_encode($result), 0);
        return $result;
         }catch(Exception $e){

         return $e->getMessage();
        } 

 }
 function process_payment($mcash_ref_no, $rra_ref, $dec_id, $tin_no, $taxpayer_name, $amount, $tax_type_no, $tax_type_desc, $tax_center_no, $assess_no, $origin_no){
    
    $request_data = array("mcRefNo" => $mcash_ref_no, "rraRef" => $rra_ref, "decId" => $dec_id, "tinNo" => $tin_no, "taxPayerName" => $taxpayer_name, "amount" => $amount, "taxTypeNo" => $tax_type_no, "taxTypeDesc" => $tax_type_desc, "taxCenterNo" => $tax_center_no, "assessNo" => $assess_no, "originNo" => $origin_no);      
    
     

    try{

    $base_url = "http://192.168.35.60:8085/v1/rra/payment";
    $headers = array(
        'Content-Type: application/json',
    );
    $ch = curl_init($base_url);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, FALSE); // Includes the header in the output
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    
    curl_close($ch);    

      $response['bpr_response']  = $result;
   

      if(strpos($result, 'html') > 0){                               
                
               
                if(strpos($result, '503') > 0){

                $response['transResponseCode']  =503;                
                
                                        
                }else if(strpos($result, '400') > 0){

                $response['transResponseCode']  =400;                
                 
                                     
                }else if(strpos($result, '404') > 0){

                $response['transResponseCode']  =404;                
                 
                                    
                }
                 
                
         }else{

               $decoded_response = json_decode($result, true);                         
               

              if(isset($decoded_response)){

                $response['transResponseCode']  = $decoded_response['status']; 
            
                
              }else{

                $response['transResponseCode']  = 500;
                $response['bpr_response']  = 'empty response';                
                
                
              }                 
                             
                                
                 

               }
       
        }catch(Exception $e){

            $response['transResponseCode']  = 500;
            $response['bpr_response']  = $e->getMessage();
        }  
     
      return $response;
}

}
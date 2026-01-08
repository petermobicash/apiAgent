<?php
namespace App\Http\Controllers\API\coreBank414;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\RraTaxCollectionProject; 
use Illuminate\Support\Facades\Auth; 
use App\Classes\Rclient ;
class RraTaxCollectionCoreBank414Controller extends BaseController{
/**
 * @group rra tax collection
 *
 * API endpoints for managing tax collection
 */
public function rraDocIdValidation(Request $request){        
    
    $validator = Validator::make($request->all(), [

        "tax_document_id"=>'required|alpha_num|max:30|min:8'                
 
          ]);
 
          if ($validator->fails()) {
 
              return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);
 
          }
      if ($request->isJson()){

        
      
      $RraTaxCollectionProject = new RraTaxCollectionProject(); 
      try {    

      $xmlres = $RraTaxCollectionProject->getDec($request->tax_document_id); 


      if (false === $xmlres) {
          // echo "Failed loading XML\n";
            foreach(libxml_get_errors() as $error) {
                 $error->message;
            }

            $mcashResponse = [ "responseCode" => 400,
                            "status" =>'Failed',                      
                            "responseDescription" => $error->message 
                  
                 ];

                 return $mcashResponse;
       }
       $string1='<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
       $string2=$xmlres->getDecReturn;
       
       $resultat=strcmp($string2, $string1);
        
      if($resultat <70){

        $mcashResponse = [ "responseCode" => 400,
                            "status" =>'Failed',                      
                            "responseDescription" =>"invalid doc id"
                  
                 ];

                 return $mcashResponse;
      }
       

      $xml_return=(array)simplexml_load_string($xmlres->getDecReturn);
       

         
         // Check RRA response  
         if(isset($xml_return['@attributes']['ID'])){
                                 
             $array_response=array();
             $array_return=array();
             foreach($xml_return['DECLARATION'] as $key=>$Entry){
                 $array_response[$key]=strval($Entry);                                
             }
                     
             // Check if we got RRA REF details 
             if(!empty($array_response['RRA_REF'])){                            
                 $mcashResponse = array (
                                          'responseCode' =>200,
                                          'result' => "Success",                                     
                                          'bank_name' => $xml_return['@attributes']['ID'],
                                          'RRA_REF' => $array_response['RRA_REF'],
                                          'TIN' => $array_response['TIN'],
                                          'TAX_PAYER_NAME' => $array_response['TAX_PAYER_NAME'],
                                          'TAX_TYPE_DESC' => $array_response['TAX_TYPE_DESC'],
                                          'TAX_CENTRE_NO' => $array_response['TAX_CENTRE_NO'],
                                          'TAX_TYPE_NO' => $array_response['TAX_TYPE_NO'],
                                          'ASSESS_NO' => $array_response['ASSESS_NO'],
                                          'RRA_ORIGIN_NO' => $array_response['RRA_ORIGIN_NO'],
                                          'AMOUNT_TO_PAY' => $array_response['AMOUNT_TO_PAY'],
                                          'DEC_ID' => $array_response['DEC_ID'],
                                          'DEC_DATE' => $array_response['DEC_DATE']);                                        
             }else{
                     $mcashResponse = [ "responseCode" => 400,                      
                     "responseDescription" =>"No longer valid", 
                     "rraMessage"=> $xml_return['@attributes']['ID']
                  
                 ];
         return $mcashResponse;
             }

         }else{
                  $mcashResponse = [ "responseCode" => 400,                      
                                     "responseDescription" =>"no response" 
                  
                 ];
         return $mcashResponse;
         }             

     }catch (Exception $ex){
     
     
         $mcashResponse = [ "responseCode" => 400,                      
                           "responseDescription" =>$ex  
                  
                 ];
         return $mcashResponse; 
        
     }


     }else{

         $mcashResponse = [ "responseCode" => 400,
                            "status" =>'Failed',                      
                            "responseDescription" => "Content type Not Allowed" 
                  
                 ];
        
   }
    return $mcashResponse;
}
  

public function rraTaxPayment(Request $request){
    
    $validator = Validator::make($request->all(), [
                           
        "amount"=>'required|integer|min:1000', 
        "client_id"=>'required',          
        "tax_document_id"=>'required',
        "tax_identification_number"=>'required',
        "taxpayer_name"=>'required',     
        "rra_tax_description"=>'required',
        "taxpayer_name"=>'required',
        "tax_type_no"=>'required',
        "tax_center_no"=>'required'           
        
          ]);

        if ($validator->fails()) {

            return response()->json(["responseCode" => 400, "responseData" => $validator->errors()]);

    }
    
    $header = $request->header('Session-Token');
    
    if ($request->isJson()){

      
    
    $client = new Rclient();     
    $payment_request = new RraTaxCollectionProject();

    $mobicashReference = $client->getMobicashRefNumber($request->client_id);
 
    $taxAccount = $payment_request->rraAccountMapping($request->tax_type_no,$request->tax_center_no);

    if (empty($taxAccount)) {
    
    $mcashResponse =["responseCode" => 400,                
                     "status"=>"Failed", 
                     "response"=>"tax account not setted"                                 
                  ];
    return $mcashResponse;
    }

    

    $myArray = json_decode($taxAccount, true);
    $account= $myArray[0]["account_no"];   
    
    
   
   


    $resp_payment=$payment_request->rraTaxMobicorePayment($request->amount,$mobicashReference,$request->tax_document_id,$request->tax_identification_number,$request->taxpayer_name,$request->client_id,$request->rra_tax_description,$header,$account);    
    $response=json_decode($resp_payment);     

   if(empty($response->id)){

    $mcashResponse =["responseCode" => 400,
                     "status"=>'Failed',
                     "response"=>$response                   
                  ];    

     

   }else{ 

                          
    
    $mcashResponse =["responseCode" => 200,                
                     "status"=>"success", 
                     "mobicashTransctionNo"=>$response->id                                 
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
?>
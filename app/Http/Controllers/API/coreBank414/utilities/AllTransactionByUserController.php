<?php
namespace App\Http\Controllers\API\coreBank414\utilities;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Utilities\AlltransactionByUserAccount;
use App\Classes\coreBank414\Services\Utilities\MobicoreUtilites;
use App\Classes\coreBank414\Services\Utilities\RraIntegration; 
/**
 * @group rssb mutuelle de sante
 *
 * API endpoints for managing mutuelle de sante
 */

class AllTransactionByUserController extends BaseController{


    public function validationTransactionByTransactionReference(Request $request){  
         

       
       
        $alltransactionByUserAcc = new AlltransactionByUserAccount();

        echo $alltransactionByUserAcc;
        exit();

        $userTransactions1= $alltransactionByUserAcc->cBhitransactionByTransactionReference($request->reference);            


        $userTransactions=json_decode($userTransactions1);

        

        if(isset($userTransactions->id)){


            if(isset($userTransactions->transactionNumber))
            {

                $transactionNumber=$userTransactions->transactionNumber;

            }else{

                $transactionNumber=$userTransactions->id;

            }

            $mobicoreId=$userTransactions->id;
            $transactionReference=$transactionNumber;
            $amount=$userTransactions->amount;
            $date=$userTransactions->date;
            $description=$userTransactions->description;       
              
            $date = $userTransactions->date;
            $time = strtotime($date);
            $fixed = date('Y-m-d H:i', $time);
            $mcashResponse =["responseCode" => 200,
            "status"=>"success",             
            "transactionReference"=>$transactionReference,
            "date"=>$fixed,
            "amount"=>floatval($amount),
            "description"=>$description,
                             
                          
            ];



          

        }else{


            if(isset($userTransactions->key)){

                $error='Reference not found'.','.$userTransactions->key;
            }else{

              $error=$userTransactions;  
            }

            $mcashResponse =["responseCode" =>400,
                             "status"=>"Failed",
                             "response"=>$error
                        
                                                       
                          ];
        }      
    

return $mcashResponse;   
    
}


    public function cBhitransactionByTransactionReference(Request $request){    
     
        $alltransactionByUserAcc = new AlltransactionByUserAccount();
        $userTransactions1= $alltransactionByUserAcc->cBhitransactionByTransactionReference($request->reference);          
        $userTransactions=json_decode($userTransactions1);        

        if(isset($userTransactions->id)){

            if(isset($userTransactions->transactionNumber))
            {

                $transactionNumber=$userTransactions->transactionNumber;

            }else{

                $transactionNumber=$userTransactions->id;

            }

            $mobicoreId=$userTransactions->id;
            $transactionReference=$transactionNumber;
            $amount=$userTransactions->amount;
            $date=$userTransactions->date;
            $data=$userTransactions->customValues;  

               


         if($data[1]->field->internalName=='payer_name'){

            $payerName=$data[1]->stringValue;
         }

          if($data[2]->field->internalName=='national_identity_number'){

           $nid=$data[2]->stringValue;
         }

          if($data[6]->field->internalName=='year_of_payment'){

           $year=$data[6]->stringValue;
         }

   
       $date = $userTransactions->date;

       $time = strtotime($date);

       $fixed = date('Y-m-d H:i', $time);
        

        $mcashResponse =["responseCode" => 200,
        "status"=>"success",        
        "transactionReference"=>$transactionReference,
        "date"=>$fixed,
        "amount"=>floatval($amount),
        "year"=>$year,
        "payerName"=>$payerName,
        "nid"=>$nid                        
                          
            ];



          

        }else{

            $mcashResponse =["responseCode" =>400,
                             "status"=>"Failed",
                             "response"=>$userTransactions
                        
                                                       
                          ];
        }
       
    

return $mcashResponse;

    
    
}

public function rRAtransactionByTransactionReference(Request $request){

        

        $alltransactionByUserAcc = new AlltransactionByUserAccount();

        $userTransactions1= $alltransactionByUserAcc->rRAtransactionByTransactionReference($request->reference);            


        $userTransactions=json_decode($userTransactions1);

        

        if(isset($userTransactions->id)){

            $data=$userTransactions->customValues;  



             if($data[2]->field->internalName=='tax_document_id'){

                $doc_id=$data[2]->stringValue;



            }

        $date = $userTransactions->date;

        $time = strtotime($date);

        $fixed = date('Y-m-d H:i', $time);



            $RraTaxCollectionProject = new RraIntegration();               


            try{

            $xmlres = $RraTaxCollectionProject->getDec($doc_id);

            if (false === $xmlres) {
               
                foreach(libxml_get_errors() as $error) {
                     $error->message;
                }            

                     return response()->json([

                                "responseCode" => 400,
                                "status" =>'Failed',                      
                                "responseDescription" => $error->message

                       ],400);
            }

            $string1='<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
            $string2=$xmlres->getDecReturn;

            $resultat=strcmp($string2, $string1);

            if($resultat <70){




                     return response()->json([

                                         "responseCode" => 400,
                                        "status" =>'Failed',                      
                                        "responseDescription" =>"invalid doc id"

                       ],400);
            } 

            $xml_return=(array)simplexml_load_string($xmlres->getDecReturn);
            }catch(Exception $ex){


            return response()->json([

                                         "responseCode" => 400,
                                         "status" =>"Failed",                      
                                         "responseDescription" =>$ex->getMessage()

                       ],400);

            }

            try {    
                
             if(isset($xml_return['@attributes']['ID'])){
                                 
             $array_response=array();
             $array_return=array();
             foreach($xml_return['DECLARATION'] as $key=>$Entry){
                 $array_response[$key]=strval($Entry);                                
             }
                     
              
             if(!empty($array_response['RRA_REF'])){               

             

              $response=array(
              'responseCode' =>  200,
              'responseDescription' =>"valid doc id",
              'bank_name' => $xml_return['@attributes']['ID'],
              'RRA_REF' =>$array_response['RRA_REF'],
              'TIN' => $array_response['TIN'],
              'TAX_PAYER_NAME' =>$array_response['TAX_PAYER_NAME'],
              'TAX_TYPE_DESC' =>$array_response['TAX_TYPE_DESC'],
              'TAX_CENTRE_N' =>$array_response['TAX_CENTRE_NO'],
              'TAX_TYPE_NO' =>$array_response['TAX_TYPE_NO'],
              'ASSESS_NO' => $array_response['ASSESS_NO'],
              'RRA_ORIGIN_NO' => $array_response['RRA_ORIGIN_NO'],
              'AMOUNT_TO_PAY' =>$array_response['AMOUNT_TO_PAY'],
              'DEC_ID' => $array_response['DEC_ID'],
              'DEC_DATE' => $array_response['DEC_DATE']
                );        

                                    
                                                       
             }else{


               

              $response=array(
              'responseCode' => 400,
              'responseDescription' =>"invalid doc id",
              'bank_name' => $xml_return['@attributes']['ID'],
              'RRA_REF' => $doc_id,
              'TIN' => '',
              'TAX_PAYER_NAME' => '',
              'TAX_TYPE_DESC' => '',
              'TAX_CENTRE_N' => '',
              'TAX_TYPE_NO' => '',
              'ASSESS_NO' =>  '',
              'RRA_ORIGIN_NO' => '',
              'AMOUNT_TO_PAY' =>'',
              'DEC_ID' => '',
              'DEC_DATE' =>  ''
                );        

             }

         }else{

             $response=array(
              'responseCode' => 500,
              'responseDescription' =>"Timeout",
              'bank_name' => '',
              'RRA_REF' =>$doc_id,
              'TIN' => '',
              'TAX_PAYER_NAME' => '',
              'TAX_TYPE_DESC' => '',
              'TAX_CENTRE_N' => '',
              'TAX_TYPE_NO' => '',
              'ASSESS_NO' =>  '',
              'RRA_ORIGIN_NO' => '',
              'AMOUNT_TO_PAY' =>'',
              'DEC_ID' => '',
              'DEC_DATE' =>  ''
                );

             
                  
         }             

     }catch (Exception $ex){

          $response=array(

              'responseCode' => 500,
              'responseDescription' =>$ex->getMessage(),
              'bank_name' => '',
              'RRA_REF' => $doc_id,
              'TIN' => '',
              'TAX_PAYER_NAME' => '',
              'TAX_TYPE_DESC' => '',
              'TAX_CENTRE_N' => '',
              'TAX_TYPE_NO' => '',
              'ASSESS_NO' =>  '',
              'RRA_ORIGIN_NO' => '',
              'AMOUNT_TO_PAY' =>'',
              'DEC_ID' => '',
              'DEC_DATE' =>  ''
                );
             


     }     
         
        

        $mcashResponse =["responseCode" => 200,
                        "status"=>"success",
                        "docid"=> $doc_id ,
                        "mobicash_ref"=> $request->reference,  
                        "amount"=>floatval($userTransactions->amount),
                        "date"=>$fixed,
                        "responseDescription"=>$response                 
                                          
                        ];



          

        }else{

            $mcashResponse =["responseCode" =>400,
                             "status"=>"Failed",
                             "responseDescription"=>$userTransactions
                        
                                                       
                          ];
        }
       
    

return $mcashResponse;

    
    
}



    public function alltransactionByUserAccount(Request $request){




        if($request->header('Authorization')){ 

        $alltransactionByUserAcc = new AlltransactionByUserAccount();

        $userTransactions1= $alltransactionByUserAcc->alltransactionByUserAccount($request->header('Authorization'));

        // echo$userTransactions1;
        // exit();



        $userTransactions=json_decode($userTransactions1);        


          if(isset($userTransactions->code)){

                           $mobicoreResponse1=$userTransactions->code;

                           if($mobicoreResponse1=="login"){


                            if(isset($userTransactions->passwordStatus)){

                            $code=102;
                            $codeDescription="Password is temporarily blocked";

                            }elseif(isset($userTransactions->userStatus)){

                             $code=101;
                            $codeDescription="User is ".$userTransactions->userStatus;

                            }else{

                            $code=103;
                            $codeDescription="Invalid authentication";
                            }
                           }else{                          


                            $code=107;

                            $codeDescription="FAILURE";
                       


                           }

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" => $code, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>$codeDescription,                   
                        "data" =>$userTransactions,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ; 







        }

        if(isset($userTransactions->id)){

            $transactionByUser=$userTransactions->id;

        }else{        

        $transactionByUser =json_encode($userTransactions[0]);

        $transactionByUser=json_decode($transactionByUser);

         $transactionByUser= $transactionByUser->id;

        }
        if(isset($userTransactions)){


        $arrayLength = count($userTransactions); 
        $arrayResponse = array();
        $i = 0;

        while ($i < $arrayLength)
        {
               

               if(isset($userTransactions[$i]->transactionNumber)){

                $transactionNumber=$userTransactions[$i]->transactionNumber;

               }else{

                $transactionNumber=$userTransactions[$i]->id;
               }

               if(isset($userTransactions[$i]->description)){
                $description=$userTransactions[$i]->description;
               }else{
                $description='';

               }
               if(isset($userTransactions[$i]->authorizationStatus)){


                $autorisationStatus=$userTransactions[$i]->authorizationStatus;

               }else{

                $autorisationStatus='';



               }
        $date = $userTransactions[$i]->date;

        $time = strtotime($date);

        $OperationDate = date('Y-m-d H:i', $time);
              
              
             

            $response=array(
                  'id' => $transactionNumber,                  
                  'operationDate' => $OperationDate,
                  'amount' => $userTransactions[$i]->amount,
                  'type' =>$userTransactions[$i]->type->internalName  ,
                  'autorisationStatus' =>$autorisationStatus,
                  'responseDescription' =>$description
                );


            array_push($arrayResponse,$response);


        $i ++;
    }
        

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>100, 
                        "communicationStatus" =>'SUCCESS', 
                        "codeDescription" =>"SUCCESS",                   
                        "data" =>$arrayResponse,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;
        
             

           

        }else{


                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>104, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>"FAILURE",                   
                        "data" =>$userTransactions,
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;

           
        }
       
    }else{

                        $date = date('Y-m-d H:i:s');

                        $mcashResponse = [ "responseCode" =>105, 
                        "communicationStatus" =>'FAILURE', 
                        "codeDescription" =>"Authorization please",                   
                        "data" =>"",
                        "responseDate"=>$date

                        ]; 
                        return $mcashResponse ;
              
                 
          }

return $mcashResponse;

    
    
}

public function searchAccountSummaryByUserAccount(Request $request){


        if($request->header('Authorization')){ 

        $alltransactionByUserAcc = new MobicoreUtilites();

        $userTransactions= $alltransactionByUserAcc->searchAccountSummaryByUserAccount($request->header('Authorization'));

        
       
        $userTransactionById=json_decode($userTransactions,true);
        
        $arrayLength = count($userTransactionById); 
        $arrayResponse = array();
        $i = 0;
        while ($i < $arrayLength)
        {
             $accountInternalName=$userTransactionById[$i]['type']['internalName'];


            if($accountInternalName=='agents_account.cbhi_mutuelle_payment_by_sacco_agent'){

               $userTransactionBydata[]=$userTransactionById[$i];

              $userTransactionBydata= array("id"=>$userTransactionById[$i]['id'], "amount"=>$userTransactionById[$i]['amount'], "date"=>$userTransactionById[$i]['date'],"description"=>$userTransactionById[$i]['description']);

              array_push($arrayResponse,$userTransactionBydata);


            }
            $i++;
        }

        return  $arrayResponse;
         


         }

}


public function selectCbhiCollection(){        

        $alltransactionByUserAcc = new AlltransactionByUserAccount();
        $userTransactions= $alltransactionByUserAcc->selectCbhiCollection();

        return $userTransactions;  
  
}


public function selectElectricityPendingStatus(){        

        $alltransactionByUserAcc = new AlltransactionByUserAccount();
        $userTransactions= $alltransactionByUserAcc->selectElectricityPendingStatus();

        return $userTransactions;  
  
}



}
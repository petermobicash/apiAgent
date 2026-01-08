<?php 

/*Date:2022-00-15
  Author: NYIRURUGO 
  Object :  This API is used to send payment on mobicore  

*/


// include classes

require_once("../bio-api/classes/payment.php");
require_once("../bio-api/classes/client.php");
require_once("../bio-api/standalone/sendJson_Class.php");
require_once("../bio-api/classes/newClient.php");
require_once("../bio-api/lang.php");
require_once("rssb_intergation.php");
require_once("tokenValidation.php");

$gettoken=new TokenValidation();
$authentification=new CbhiCollection(); 
// declare classes objects
$json = new jsonResponse_Class();
$client = new Client();
$agent = new Client();
$payment = new Payment();
$clientMember = new Client();
$clientz = new newClient();



// parameter received when this API is called

$householdNID = $_REQUEST['householdNID']; 
$amount = $_REQUEST['amount'] ;
$dealer = $_REQUEST['dealer'] ;
$payerName = $_REQUEST['payerName'] ;
$payerPhone = $agent->mobileFormat($_REQUEST['payerPhone']);
$year = $_REQUEST['year'] ;
$totalPremium = $_REQUEST['totalPremium'] ;
$amountAlreadyPaid = $_REQUEST['amountAlreadyPaid'] ;
$houseHoldCategory = $_REQUEST['houseHoldCategory'] ;
$numberOfMembers = $_REQUEST['numberOfMembers'] ;
$payment_channel =$_REQUEST['payment_channel'] ;
 

$agentNumber =  $agent->mobileFormat($_REQUEST['agentNumber']) ;
$agentPin = $_REQUEST['pin'];
$agentId = $_REQUEST['agentId'];
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
// $principal_type=$_REQUEST['principal_type'];

$rssbInvoiceNumber=0;
// $year=0000;




if( empty($latitude) ){
    $latitude = 0;
}

if( empty($longitude) ){
    $longitude = 0 ;
}
// minimum amount of this transaction

$minimumAmount = 1000 ;

// @params will contain all data that will be inserted in the logs

$params = array();

// date and time

$paydate= date("Y-m-d H:i") ;

//check if the agent phone and pin are valid

$agent->principal_type ='mobilePhone';
$agent->principal  = $agentNumber;
$agent->pin        = $agentPin;



    // set $params values that will be inserted in logs_cbhi table

    $params['agent_phone_number'] = $agentNumber ;
    $params['client_name'] = $payerName ;
    $params['client_phone_number'] = $payerPhone ;
    $params['nid'] = $householdNID ;
    $params['amount'] = $amount ;
    $params['device_type'] = $payment_channel ;
    $params['transaction_type'] = "T" ;
    $params['latitude'] = $latitude ;
    $params['longitude'] = $longitude ;
    $params['year'] = $year ;

   


  
    // if($gettoken->existMobicahRefInMobicore($householdNID,$amount)> 0){
    //     $json->result = FAILED;
    //     $json->message ='Please contact mobicash support.';
    //     $json->details = " ";
    //     echo $json->printJson();                
    //     // close Database connection 
    //     exit();     
    // }



    //call Mobicore to get the agent ID

    $client_data = $agent->getMember(); 


    // Get Mobicash reference number
    $mcashReferenceNumber = $client->getMobicashRefNumber($agentId) ; 
    $params['mobicash_ref_number'] = $mcashReferenceNumber ;
    // check if the aleady paid amount is null or empty
    if($client->isNullOrEmptyString($amountAlreadyPaid)){
        $amountAlreadyPaid = 0 ;
    }

    // amount to be paid

    $amountToBePaid = $totalPremium - $amountAlreadyPaid ;
    $remainingBalance = $amount - $amountToBePaid ;

    // check if the amount to be paid is less than the amount sent by the agent

    if($amount > $amountToBePaid){

         if($amountToBePaid == 0 ){

                $json->result = FAILED;
                $json->message = NID_PAID_ERROR_MESSAGE;
                $json->details = " ";
                echo $json->printJson();               
                
                exit();

            }else{
                
                $json->result = FAILED;
                $json->message = " Ntabwo wakwishyura amafaranga arenze ".$amountToBePaid."  / The maximum payment amount is  ".$amountToBePaid." rwf ";
                $json->details = " ";
                echo $json->printJson();              

                exit();
                
            }

    }

    if($amount %1000 != 0){
            $json->result = FAILED;
            $json->message = "Amafaranga make yo kwishyura ni ".$minimumAmount."kandi agomba kuba ari ibinyagihumbi 1000,2000,3000 ";
            $json->details = " ";
            echo $json->printJson();

            
             
            exit();
        }
      
         

        $payment->fromMemberPrincipalType= "USER";
        $payment->fromMember=  $agent->getMainAccount($client_data);
        $payment->toMemberPrincipalType= "USER";
        $payment->toMember= "20000011_15";
        $payment->amount= $amount;
        if($dealer=='ARED'){$payment->transferId=144;}
        elseif($dealer=='Broker'){$payment->transferId=144;}
        elseif($dealer=='Independent'){$payment->transferId=143;}
        else{$payment->transferId=0;}

         

        $payment->customValues= array($payment->setRSSBMcReference($mcashReferenceNumber),$payment->setPerformedBy($agentNumber),$payment->setPayerName($payerName),$payment->setPayerPhone($payerPhone), $payment->setHouseHoldNID($householdNID), $payment->setRSSBInvoiceNumber($rssbInvoiceNumber),$payment->setcfFiscalYear($year) ,$payment->setPaymentChannel($payment_channel)); 
        $payment->description = "RSSB >> NID : ".$householdNID." | Names : ".$payerName." | Amount : ".$amount." Client phone: ".$payerPhone; 
  

           
                 $resp_payment = $payment->doPayment();               


                 if(isset($resp_payment->return->status)){

                        $cylcosResponse=$resp_payment->return->status;
                        
                    }else{
                        $cylcosResponse ='UNKNOWN_ERROR';
                    }

                 // check if agent have i$cylcosResponse = $resp_payment->return->status;nsufient float 

                 if ($cylcosResponse !='PROCESSED'){                              
                           
                    $params['rssb_response'] = " " ;
                    $params['status_code'] = $client->getMobicoreErrorMessageCode($cylcosResponse);
                    $client->openDbCon();
                    $client->saveCbhiLog($params) ; 
                    $client->closeDbCon();
                    $msg = $client->getErrorMessage($cylcosResponse) ;                                    
                    $json->result = FAILED;
                    $json->message = $msg;
                    $json->details = " ";
                    echo $json->printJson();
                    // close Database connection 
                    exit();        
                    }                                     
                   
                    $params['rssb_response'] = " " ;
                    $params['status_code'] = $client->getMobicoreErrorMessageCode($cylcosResponse);
                    // save transaction in logs_cbhi table
                    $client->openDbCon();
                    $client->saveCbhiLog($params) ; 
                    $client->closeDbCon();


                    $user=$gettoken->selectToken();       
        
                    if ($user) {

                    $user= json_encode($user);
                    $user=json_decode($user);
                    

                    $currenttoken = $user->token;

                        
                        $break_1_start = $user->date;           
                        $break_1_ends = date("Y-m-d H:i");
                        
                        $datetime1 = new DateTime($break_1_start);
                        $datetime2 = new DateTime($break_1_ends);
                        $interval = $datetime1->diff($datetime2);
                        $totalDuration= $interval->format('%h');
                   

                        if ($totalDuration==0) {

                            $token =$currenttoken;
                            
                        }else{

                             $token=$authentification->authentification();
                             $token=json_decode($token);
                             $gettoken->insertToken($token->token);


                        }
                    }else{

                             $token=$authentification->authentification();
                             $token=json_decode($token);
                             $token=$token->token;                
                             $gettoken->insertToken($token);
                             
                             
                    }                    

                    try{     
            
                    $result = $authentification->sendNotification($householdNID,$paydate,$amount,$mcashReferenceNumber,$year,$token);                  

                    $response_pay=json_decode($result);                 

                    if((!empty($response_pay))&&($response_pay->message=="Payment is successfully received")||($response_pay->message=="The transaction has already been received")) {
                     
                      $status = 200;                                
                      $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$paydate);

                      }elseif(!empty($response_pay)){

                         $status = 400; 
                         $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$paydate);
                      }else{

                      $status = 400; 
                      $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$paydate);
                      $gettoken->new_rssb_tobe_notified($mcashReferenceNumber,$householdNID,$amount,$year,$payerPhone,$paydate);

                    }                                          
                   }catch(Exception $ex){ // if the exception happens 
                                            
                          $status = 400; 
                          $gettoken->new_rssb_tobe_notified($mcashReferenceNumber,$householdNID,$amount,$year,$payerPhone,$paydate);
                          $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$paydate);                                                                         
                    }               
                    

                    $json->message = "Successfully Paid, PayerName: ".$payerName.", ID: ".$householdNID." Mobicash reference: ".$mcashReferenceNumber;
                    $json->result = SUCCESS;
                    
                    $json->extra1 = $mcashReferenceNumber;
                    $json->amount = $amount;
                    $json->details =$payerName;
                    $json->extra2 = $response_pay->message;
                    $json->transactionid =$resp_payment->return->transfer->id;
                    $json->datetime = $paydate;
                    echo $json->printJson();
                     
                    exit();

    
?>
 

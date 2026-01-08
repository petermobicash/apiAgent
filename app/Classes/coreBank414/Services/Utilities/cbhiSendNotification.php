<?php 

       header('Access-Control-Allow-Origin: *');    
       require_once("rssb_intergation.php");
       require_once("tokenValidation.php");

       $gettoken=new TokenValidation();
       $authentification=new CbhiCollection();   
   
  
        $householdNID = $_REQUEST['nid'];  
        $paydate = $_REQUEST['created_at'];

        $date = strtotime($paydate); 
        $paydate=date('Y-m-d H:i', $date); 

       
        
        $amount = $_REQUEST['amount'];
        $year= $_REQUEST['year'];
        $mcashReferenceNumber = $_REQUEST['mobicash_ref_no'];         
        // $year = $_REQUEST['year_of_collection'];
        $payerPhone = $_REQUEST['client_phone_number'];
        $created_at =date("Y-m-d h:i:s");
        $user=$gettoken->selectToken();       
        
        if (isset($user)&&(!empty ($user->token))) {

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

            

            try{

            $response_pay=json_decode($result);

            }catch(Exception $ex){

               
            }            

            if((!empty($response_pay))&&($response_pay->message=="Payment is successfully received")||($response_pay->message=="The transaction has already been received")) {
             
              $status = 200;             

            }else{
              $status = 400; 
            }               
            
           $response = array("nid"=>$householdNID, "mobicash_ref"=>$mcashReferenceNumber,"status"=>$status, "cbhi_response"=> json_decode($result));
           echo json_encode($response);
                 
            $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$created_at);

            if(($status == 200) || (!empty($response_pay))){
               
                $gettoken->removetransaction($mcashReferenceNumber);                
                  
            }            
                                       
           }catch(Exception $ex){ // if the exception happens 
                                    
                  $status = 400; 
                  $response = array("nid"=>$householdNID, "mobicash_ref"=>$mcashReferenceNumber, "status"=>$status,"cbhi_response"=> json_decode($result));
                  echo json_encode($response);
                  
                  $gettoken->updatenotification($mcashReferenceNumber);                    
                  $gettoken->new_rssb_notification($householdNID,$mcashReferenceNumber,$status,$result,$created_at);
                                                                
          }
  
  

    


?>

 

 
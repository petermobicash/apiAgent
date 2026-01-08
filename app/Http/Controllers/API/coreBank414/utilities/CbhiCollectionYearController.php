<?php
namespace App\Http\Controllers\API\coreBank414\utilities;   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Classes\coreBank414\Services\Utilities\CbhiCollectionYear; 
 


/**
 * @group rssb mutuelle de sante
 *
 * API endpoints for managing mutuelle de sante
 */

class CbhiCollectionYearController extends BaseController{

    public function returnYearofcollection(){
        $yearOfCollection = new CbhiCollectionYear();

        $yearOfcollect= $yearOfCollection->yearOfCollection();



        $date = date('Y-m-d H:i:s');
 

        $mcashResponse = [ "responseCode" => 100, 
                            "communicationStatus" =>'SUCCESS', 
                            "responseDescription" =>"SUCCESS",                   
                            "data" =>$yearOfcollect,
                            "date"=>$date
                    
                   ];


            return  $mcashResponse;
         
    }
    
}
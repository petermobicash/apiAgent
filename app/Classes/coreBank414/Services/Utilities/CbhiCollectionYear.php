<?php  
namespace App\Classes\coreBank414\Services\Utilities;

Class CbhiCollectionYear{
	
	public function yearOfCollection()
	{
		
		
date_default_timezone_set('Africa/Kigali');


$currentYear  = date('Y');
$currentYearObject = new Year();
$nextYearObject = new Year() ;
$lastAnnulBudgete = date('Y-05-31');
$currentAnnulBudgete = date('Y-m-d');
$currentMonthAddYear = date('Y-01-01');

if($lastAnnulBudgete < $currentAnnulBudgete){
	 
	$lastYear  = date('Y', strtotime('+1 years')) ;
	$currentYearObject->setYear($lastYear);	
	$nextYearObject->setYear($currentYear);
	if($currentAnnulBudgete <= $currentMonthAddYear){

	          $output  = array($nextYearObject,$currentYearObject) ;
              
	
     } else{     	
           $output  = array($nextYearObject) ;
           
}

}else{	
	$currentYearObject->setYear($currentYear);
	$lastYear  = date('Y', strtotime('-1 years')) ;
	$nextYearObject->setYear($lastYear);
	$output = array($nextYearObject,$currentYearObject) ;
     	
}
return  $output ;
}



}
class Year{
	public $year;

	public function setYear($year){
		$this->year = $year ;
	}

	public function getYear(){
		return $this->year;
	}
}
class Response {
	public $response;

	public function setResponse($response){
		$this->response = $response ;
	}
	public function getResponse(){
		return $this->response;
	}
}
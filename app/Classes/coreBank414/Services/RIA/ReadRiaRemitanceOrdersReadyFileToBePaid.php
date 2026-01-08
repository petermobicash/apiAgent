<?php  
namespace App\Classes\coreBank414\Services\RIA;
use DB;

class ReadRiaRemitanceOrdersReadyFileToBePaid{



	public function readRiaRemitanceOrdersReadyFileToBePaid()
    {
        $riaFile = file_get_contents("/var/www/apiAgent/app/Classes/coreBank414/Services/RIA/RiaRemitanceOrderFile"."/riaFile.json");

        return $riaFile;

        
    }

    public function readRiaRemitanceOrdersReadyFileToBePaidDownload($riaCallerCorrelationId,)
    {
		$riaCallDateTimeLocal;

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'mt-staging.dandelionpayments.com/PayOrders/Orders/Downloadable',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'ria-CallerCorrelationId:$riaCallerCorrelationId',
		    'ria-CallDateTimeLocal:$riaCallDateTimeLocal',
		    'ria-AgentId: 105491711',
		    'Ocp-Apim-Subscription-Key: 652f644203f74a838d14a720604edd2e',
		    'ria-ApiVersion: v1'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;

        
}


public function logRiaRemitanceOrdersReadyFileToBePaidDownload($OrderDate,$OrderTime,$OrderNo,$PIN,$TransferReason,$CustPaymentMethod,$BeneFirstName,$BeneLastName,$BeneAmount,$CustFirstName,$CustLastName,$FullOrderApiResponse){
		

		DB::table('ria_remitance_orders_ready_to_be_paid')->insert([
		'OrderDate' => $OrderDate,
		'OrderTime' =>$OrderTime,
		'OrderNo'=>$OrderNo,
		'PIN'=>$PIN,
		'TransferReason' =>$TransferReason,
		'CustPaymentMethod' =>$CustPaymentMethod,
		'BeneFirstName'=>$BeneFirstName,
		'BeneLastName'=>$BeneLastName,
		'BeneAmount' =>$BeneAmount,
		'CustFirstName' =>$CustFirstName,
		'CustLastName' =>$CustLastName,
		'FullOrderApiResponse' =>$FullOrderApiResponse
	 
		]);


        
}

public function selectLogRiaRemitanceOrdersReadyFileToBePaidDownload($OrderNo,$PIN){


	$Data = DB::table('ria_remitance_orders_ready_to_be_paid')
	->where([
	['OrderNo',$OrderNo],
	['PIN',  $PIN],
	]
	)->get();


	return $Data;

        
}

}
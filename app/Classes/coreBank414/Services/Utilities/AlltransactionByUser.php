<?php  
namespace App\Classes\coreBank414\Services\Utilities;

Class AlltransactionByUser{

public function alltransactionByUser($header){


$curl = curl_init();

curl_setopt_array($curl, array(
		CURLOPT_URL => 'http://testbox.mobicash.rw/CoreBank/test_box/api/self/transactions?fields=id&fields=date&fields=amount&fields=type.internalName&fields=description',
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


}
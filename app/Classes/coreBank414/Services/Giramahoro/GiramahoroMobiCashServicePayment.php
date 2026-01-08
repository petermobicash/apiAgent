<?php
namespace App\Classes\coreBank414\Services\Giramahoro;

use Illuminate\Support\Facades\Log;

class GiramahoroMobiCashServicePayment
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://testbox.mobicash.rw/CoreBank/test_box/api/';
    }

    /**
     * Send a POST request using cURL
     *
     * @param string $endpoint
     * @param array $data
     * @param string $authorization
     * @return array|null
     */
    public function post(string $endpoint, array $data, string $authorization)
    {

         

        $url = $this->baseUrl . $endpoint;

        // Construct headers with dynamic authorization
        $headers = [
            'Content-Type: application/json',
            "Authorization: $authorization",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $response;
        // exit();

        // if ($httpCode >= 200 && $httpCode < 300) {
        //     return json_decode($response, true);
        // }

        

        // return $response;
    }
}

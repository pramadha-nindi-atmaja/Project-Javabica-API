<?php

namespace App\Services\RajaOngkir;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Http;

/**
 * Class CityServices
 * @package App\Services
 */
class CityServices extends BaseController
{
    public function getCity($payload)
    {
       
        // method URL
        $methodUrl = '/city';

        // get Data
        $postInput = [
            "id"       => $payload['id'],
            "province" => $payload['province_id'],
        ];

        // Headers
        $headers = array(
            'key' => ''.config('rajaongkir.token').'',
        );

        $response       = Http::withHeaders($headers)->get(config('rajaongkir.base_url') . $methodUrl, $postInput);
        $responseBody   = json_decode($response->getBody(), true);
        $statusCode     = $response->getStatusCode(); // status code

        $returnValue =  array(
            'status'    => $statusCode,
            'response'  => $responseBody,
        );
     
        if ($statusCode == 200) {
          
                return $this->handleArrayResponse($returnValue['response']['rajaongkir']['results'], 'raja ongkir update get city success', 'info');
         
        } else {

            return $this->handleArrayErrorResponse($returnValue, 'raja ongkir connection city error ', 'danger');
        }
    }
}

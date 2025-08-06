<?php

namespace App\Services\RajaOngkir;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Http;

/**
 * Class ProvinceServices
 * @package App\Services
 */
class ProvinceServices extends BaseController
{
    public function getProvince($payload)
    {

        // method URL
        $methodUrl = '/province';

        // get Data
        $postInput = [
            "id"       => $payload['id'],
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
          
                return $this->handleArrayResponse($returnValue['response']['rajaongkir']['results'], 'raja ongkir update get success', 'info');
         
        } else {

            return $this->handleArrayErrorResponse($returnValue, 'raja ongkir connection error ', 'danger');
        }
    }
}

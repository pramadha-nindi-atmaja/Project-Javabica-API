<?php

namespace App\Services\RajaOngkir;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Http;

/**
 * Class CostServices
 * @package App\Services
 */
class CostServices extends BaseController
{
    public function getCost($payload)
    {

        // method URL
        $methodUrl = '/cost';

        // get Data
        $postInput = [
            "origin"       => $payload['origin'],
            "destination"  => $payload['destination'],
            "weight"       => $payload['weight'],
            "courier"      => $payload['courier'],
        ];

        // Headers
        $headers = array(
            'key' => '' . config('rajaongkir.token') . '',
        );

        $response       = Http::withHeaders($headers)->post(config('rajaongkir.base_url') . $methodUrl, $postInput);
        $responseBody   = json_decode($response->getBody(), true);
        $statusCode     = $response->getStatusCode(); // status code
      
        $returnValue =  array(
            'status'    => $statusCode,
            'response'  => $responseBody,
        );

        if ($statusCode == 200) {

            return $this->handleArrayResponse($returnValue['response']['rajaongkir']['results'], 'raja ongkir  get cost success', 'info');
            
        } else {

            return $this->handleArrayErrorResponse($returnValue, 'raja ongkir connection cost error ', 'danger');
        }
    }
}

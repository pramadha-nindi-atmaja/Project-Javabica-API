<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleCaptchaValidateController extends Controller
{
    // public function validateCaptcha()
    // {
    //     $response       = Http::withHeaders($headers)->get(config('NeoGlobal.NEO_APIURL') . $methodUrl, $postInput);
    //     $responseBody   = json_decode($response->getBody(), true);
    //     $statusCode     = $response->getStatusCode(); // status code
      
    //     $returnValue =  array(
    //         'status'    => $statusCode,
    //         'response'  => $responseBody,
    //     );
    // }
}

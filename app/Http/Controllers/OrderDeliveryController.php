<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDeliveryRequest\OrderDeliveryGetRequest;
use App\Interfaces\OrderInterface;
use App\Services\OrderDeliveryGeneratorServices;
use Illuminate\Http\Request;

class OrderDeliveryController extends Controller
{
    private $orderInterface;


    public function __construct(OrderInterface $orderInterface)
    {
        $this->orderInterface            = $orderInterface;
    }

    public function show(OrderDeliveryGetRequest $request, OrderDeliveryGeneratorServices $orderInvoiceGenerator)
    {
      
        //get order
        $selectedColumn  = '*';
        $getOrder        = $this->orderInterface->show($request->only('by_id'), $selectedColumn, 'show_with_product');
        
   
        if ($getOrder['queryStatus']) {

            $convertData = json_encode($getOrder['queryResponse']);
            $arrayData   = json_decode($convertData);
          
            return $orderInvoiceGenerator->generate($arrayData);
            
        } else {

            return   $this->handleError(null, $getOrder['queryMessage'], $request->all(), str_replace('/', '.', $request->path()), 422);
        }

        return $getOrder;
    }
}

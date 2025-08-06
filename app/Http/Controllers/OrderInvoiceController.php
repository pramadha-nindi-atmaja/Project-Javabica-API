<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderInvoiceRequest\OrderInvoiceGetRequest;
use App\Http\Resources\OrderResource\OrderTestResource;
use App\Http\Resources\OrderResource\OrderWithProductResource;
use App\Interfaces\OrderInterface;
use App\Services\OrderInvoiceGeneratorService;


class OrderInvoiceController extends Controller
{
    private $orderInterface;


    public function __construct(OrderInterface $orderInterface)
    {
        $this->orderInterface            = $orderInterface;
    }

    public function show(OrderInvoiceGetRequest $request, OrderInvoiceGeneratorService $orderInvoiceGenerator,)
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

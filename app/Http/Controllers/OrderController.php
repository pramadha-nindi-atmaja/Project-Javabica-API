<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest\OrderCreateRequest;
use App\Http\Requests\OrderRequest\OrderDestroyRequest;
use App\Http\Requests\OrderRequest\OrderGetRequest;
use App\Http\Requests\OrderRequest\OrderUpdateRequest;
use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Services\OrderNumberGeneratorService;
use App\Services\OrderProductCalculationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class OrderController extends BaseController
{
    private $orderInterface;
   
    public function __construct(OrderInterface $orderInterface)
    {
        $this->orderInterface            = $orderInterface;
    }

    public function show(OrderGetRequest $request) {

       $selectedColumn = array('*');
       $get = $this->orderInterface->show($request->all(),$selectedColumn,'show_all');

        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get order success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-order',
            'message'=> 'error when show order'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }

    public function create(OrderCreateRequest $request,OrderNumberGeneratorService $orderNumberGenerator) { 

    
        //get order number
        $getOrderNumber = $orderNumberGenerator->generate();
       
        //merge order number
        $request->merge([
            'uuid'         => Str::uuid().'-'.date('Ymd-His'),
            'queue_number' => $getOrderNumber['arrayResponse']['queue_number'],
            'order_number' => $getOrderNumber['arrayResponse']['invoice_number']
        ]);

        //insert
        $insert = $this->orderInterface->store($request->all(),'show_all');
        
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert order success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-order',
                'message'=> 'order create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }
    public function update(OrderUpdateRequest $request) { 
        
       

        $update = $this->orderInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-order',
                'message'=> 'order successfuly updated'
            );


            return $this->handleResponse($data,'Update order success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-order',
                'message'=> 'order update fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

     public function destroy(OrderDestroyRequest $request) { 
        
      
        //remove data
        $destroy =   $this->orderInterface->destroy($request->by_id);

        if($destroy['queryStatus']) {

            //response
            $data  = array(
                'field' =>'destroy-order',
                'message'=> 'order successfuly destroyed'
            );

             return $this->handleResponse($data,'Destroy order  success',$request->all(),str_replace('/','.',$request->path()),204);
       
        } else {
            
            $data  = array([
                'field' =>'destroy-order',
                'message'=> 'order destroy fail'
            ]);

             return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

          
     }
}

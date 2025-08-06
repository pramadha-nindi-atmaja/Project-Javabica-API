<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProductResource\OrderProductCreateRequest;
use App\Http\Requests\OrderProductResource\OrderProductDestroyRequest;
use App\Http\Requests\OrderProductResource\OrderProductGetRequest;
use App\Http\Requests\OrderProductResource\OrderProductUpdateRequest;

use App\Http\Requests\OrderRequest\OrderGetRequest;
use App\Interfaces\OrderProductInterface;
use App\Models\Order_product;
use App\Services\OrderCalculationService;
use App\Services\OrderProductCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderProductController extends BaseController
{
    private $orderProductInterface;
   
    
    public function __construct(OrderProductInterface $orderProductInterface)
    {
        $this->orderProductInterface            = $orderProductInterface;
     
    }

    public function show(OrderProductGetRequest $request,OrderCalculationService $orderCalculation) {

        $selectedColumn = array('*');

        $get = $this->orderProductInterface->show($request,$selectedColumn);

        //get calculation
        if($request->only('by_order_id') !==  null)
        {
            $calculate = $orderCalculation->orderCalculation($request->only('by_order_id'));

            $getCalculation =$calculate['arrayResponse'];
        }
        else
        {
            $getCalculation =null;
        }

        $orderProduct   = array(
            'data_product'      => $get['queryResponse'],
            'data_calculation'  => $getCalculation
        );

        if($get['queryStatus']) {
            
            return $this->handleResponse($orderProduct,'get order-product success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-order-product',
            'message'=> 'error when show order-product'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

      
    }

    public function create(OrderProductCreateRequest $request) { 

      
        $insert = $this->orderProductInterface->store($request->all(),'show_all');
        
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert order product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-order-product',
                'message'=> 'order product create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }
    public function update(OrderProductUpdateRequest $request) { 
        
        $update = $this->orderProductInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-order product',
                'message'=> 'order product successfuly updated'
            );


            return $this->handleResponse($data,'Update order product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-order product',
                'message'=> 'order product update fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

     public function destroy(OrderProductDestroyRequest $request) { 

         //remove data
         $destroy =   $this->orderProductInterface->destroy($request->by_id);

         if($destroy['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-order-product',
                 'message'=> 'order-product successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy order-product  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-order-product',
                 'message'=> 'order-product destroy fail'
             ]);
 
              return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }        

}

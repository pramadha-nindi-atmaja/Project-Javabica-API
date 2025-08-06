<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest\ProductCreateRequest;
use App\Http\Requests\ProductRequest\ProductDestroyRequest;
use App\Http\Requests\ProductRequest\ProductGetRequest;
use App\Http\Requests\ProductRequest\ProductUpdateRequest;
use App\Interfaces\ProductInterface;

class ProductController extends BaseController
{
     private $productInterface;
   
    
    public function __construct(ProductInterface $productInterface)
    {
        $this->productInterface            = $productInterface;
     
    }
    public function show(ProductGetRequest $request) {

        $selectedColumn = array('*');

        $get = $this->productInterface->show($request,$selectedColumn,'show_all');
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-user',
            'message'=> 'error when show product'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);

    }

    public function create(ProductCreateRequest $request) { //done

        $insert = $this->productInterface->store($request->all(),'show_all');
        
        if($insert['queryStatus']) {

            return $this->handleResponse( $insert['queryResponse'],'Insert product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'create-product',
                'message'=> 'product create fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }
    public function update(ProductUpdateRequest $request) { //done
        
        $update = $this->productInterface->update($request->id,$request->except(['id']),'show_all');

        if($update['queryStatus']) {

            $data  = array(
                'field' =>'update-product',
                'message'=> 'product successfuly updated'
            );


            return $this->handleResponse($data,'Update product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'update-product',
                'message'=> 'product destroy fail'
            ]);

            return   $this->handleError($data,$update['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }


     public function destroy(ProductDestroyRequest $request) { //done
        
           //remove data
           $destroyAdmin =   $this->productInterface->destroy($request->by_id);

           if($destroyAdmin['queryStatus']) {
   
               //response
               $data  = array(
                   'field' =>'destroy-product',
                   'message'=> 'product successfuly destroyed'
               );
   
                return $this->handleResponse($data,'Destroy product  success',$request->all(),str_replace('/','.',$request->path()),204);
          
           } else {
               
               $data  = array([
                   'field' =>'destroy-product',
                   'message'=> 'product destroy fail'
               ]);
   
                return   $this->handleError($data,$destroyAdmin['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
           }

     }
    
}

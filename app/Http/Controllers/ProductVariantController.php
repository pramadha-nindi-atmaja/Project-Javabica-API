<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantRequest\ProductVariantdestroyRequest;
use App\Http\Requests\ProductVariantRequest\ProductVariantGetRequest;
use App\Http\Requests\ProductVariantRequest\ProductVariantUpsertRequest;
use App\Interfaces\ProductVariantInterface;
use Illuminate\Http\Request;

class ProductVariantController extends BaseController
{
    private $productVariantInterface;
   
    
    public function __construct(ProductVariantInterface $productVariantInterface)
    {
        $this->productVariantInterface            = $productVariantInterface;
     
    }

    public function show(ProductVariantGetRequest $request) {
        
        $selectedColumn = array('*');

        $get = $this->productVariantInterface->show($request,$selectedColumn);
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product variant success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-products-variant',
            'message'=> 'error when show product variant'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);


      
    }
      /**
     * @lrd:start
     * # contoh format request untuk upsert
     * # ubah kutip nya menjadi kutip string
     * =============
     * 
    
    *   {
    *   "data": [
    *   {
    *   "id":1,
    *   "fk_product_id":1,
    *   "fk_attribute_id":66,
    *   "fk_attribute_value_id":96,
    *   "sku":111,
    *   "stock":10,
    *   "price":10,
    *   "discount":10,
    *   "image_path":"/image1",
    *   "status":"ACTIVE",
    *   "is_ignore_stock":"ACTIVE"
    *   },
    *   {
    *   "id":null,
    *   "fk_product_id":1,
    *   "fk_attribute_id":66,
    *   "fk_attribute_value_id":96,
    *   "sku":212,
    *   "stock":10,
    *   "price":10,
    *   "discount":10,
    *   "image_path":"/image2",
    *   "status":"ACTIVE",
    *   "is_ignore_stock":"ACTIVE"
    *   }
    *   ]
    *   }
    *    =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function upsert(ProductVariantUpsertRequest $request) { //done

        
        $insert = $this->productVariantInterface->upsert($request->all());
         
        if($insert['queryStatus']) {
            
            $data  = array([
                'field' =>'upsert variant product',
                'message'=> 'total upsert : '.$insert['queryResponse']
            ]);


            return $this->handleResponse($data,'Insert variant product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'create-update-variant-product',
                'message'=> 'update-variant-product fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }


    }
   
     public function destroy(ProductVariantdestroyRequest $request) { //done


           //remove data
            
           $destroy =   $this->productVariantInterface->destroy($request->all());

           if($destroy['queryStatus']) {
   
               //response
               $data  = array(
                   'field' =>'destroy-product variant',
                   'message'=> 'product variant successfuly destroyed'
               );
   
                return $this->handleResponse($data,'Destroy product variant  success',$request->all(),str_replace('/','.',$request->path()),204);
          
           } else {
               
               $data  = array([
                   'field' =>'destroy-product-variant',
                   'message'=> 'product variant destroy fail'
               ]);
   
                return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
           }


     }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImageRequest\ProductImageDestroyRequest;
use App\Http\Requests\ProductImageRequest\ProductImageGetRequest;
use App\Http\Requests\ProductImageRequest\ProductImageUpsertRequest;
use App\Interfaces\ProductImageInterface;
use Illuminate\Http\Request;

class ProductImageController extends BaseController
{
    private $productImageInterface;
   
    
    public function __construct(ProductImageInterface $productImageInterface)
    {
        $this->productImageInterface            = $productImageInterface;
     
    }

    public function show(ProductImageGetRequest $request) {

        
        $selectedColumn = array('*');

        $get = $this->productImageInterface->show($request,$selectedColumn);
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product images success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-products-collection',
            'message'=> 'error when show product images'
        ]);

        return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);


    }
      /**
     * @lrd:start
     * # contoh format request untuk upsert
     * # ubah kutip nya menjadi kutip string
     * =============
    * 
    
    *         {
    *            "data": [
    *            {
    *            "id":1,
    *            "fk_product_id":"asd",
    *            "path":1,
    *            "sort_number":"1"
    *            },
    *            {
    *            "id":1,
    *            "fk_product_id":"asd",
    *            "path":1,
    *            "sort_number":"1"
    *            }
    *            ]
    *            }
    * =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function upsert(ProductImageUpsertRequest $request) { //done

        
        $insert = $this->productImageInterface->upsert($request->all());
         
        if($insert['queryStatus']) {
            
            $data  = array([
                'field' =>'upsert image product',
                'message'=> 'total upsert : '.$insert['queryResponse']
            ]);


            return $this->handleResponse($data,'Insert image product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'create-update-image-product',
                'message'=> 'update-image-product fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
   
     public function destroy(ProductImageDestroyRequest $request) { //done


         //remove data
         $destroyAdmin =   $this->productImageInterface->destroy($request->by_id);

         if($destroyAdmin['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-product image',
                 'message'=> 'product image successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy product image  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-product-image',
                 'message'=> 'product image destroy fail'
             ]);
 
              return   $this->handleError($data,$destroyAdmin['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }
}

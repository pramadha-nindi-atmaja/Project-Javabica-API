<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest\ProductCategoryDestroyRequest;
use App\Http\Requests\ProductCategoryRequest\ProductCategoryGetRequest;
use App\Http\Requests\ProductCategoryRequest\ProductCategoryUpsertRequest;
use App\Interfaces\ProductCategoryInterface;
use Illuminate\Http\Request;

class ProductCategoryController extends BaseController
{
    private $productCategoryInterface;
   
    
    public function __construct(ProductCategoryInterface $productCategoryInterface)
    {
        $this->productCategoryInterface            = $productCategoryInterface;
     
    }

    
    public function show(ProductCategoryGetRequest $request) {

        $selectedColumn = array('*');

        $get = $this->productCategoryInterface->show($request,$selectedColumn);
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product category success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-products-category',
            'message'=> 'error when show product category'
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
    *            "fk_collection_id":1,
    *            },
    *            {
    *            "id":1,
    *            "fk_product_id":"asd",
    *            "fk_collection_id":1,
    *            }
    *            ]
    *            }
    * =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function upsert(ProductCategoryUpsertRequest $request) { //done

        $insert = $this->productCategoryInterface->upsert($request->all());
         
        if($insert['queryStatus']) {
            
            $data  = array([
                'field' =>'upsert category product',
                'message'=> 'total upsert : '.$insert['queryResponse']
            ]);


            return $this->handleResponse($data,'Insert category product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'create-update-category-product',
                'message'=> 'update-category-product fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
      
    }
   
     public function destroy(ProductCategoryDestroyRequest $request) { //done
        
              //remove data
         $destroy =   $this->productCategoryInterface->destroy($request->by_id);

         if($destroy['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-product destroy',
                 'message'=> 'product destroy successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy product destroy  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-product-destroy',
                 'message'=> 'product destroy destroy fail'
             ]);
 
              return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }
}

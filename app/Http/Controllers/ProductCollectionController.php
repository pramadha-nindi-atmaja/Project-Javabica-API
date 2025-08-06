<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCollectionRequest\ProductCollectionCreateRequest;
use App\Http\Requests\ProductCollectionRequest\ProductCollectionDestroyRequest;
use App\Http\Requests\ProductCollectionRequest\ProductCollectionGetRequest;
use App\Http\Requests\ProductCollectionRequest\ProductCollectionUpdateRequest;
use App\Http\Requests\ProductCollectionRequest\ProductCollectionUpsertRequest;
use App\Interfaces\ProductCollectionInterface;
use Illuminate\Http\Request;

class ProductCollectionController extends BaseController
{
    private $productCollectionInterface;
   
    
    public function __construct(ProductCollectionInterface $productCollectionInterface)
    {
        $this->productCollectionInterface            = $productCollectionInterface;
     
    }
    

    public function show(ProductCollectionGetRequest $request) {

        $selectedColumn = array('*');

        $get = $this->productCollectionInterface->show($request,$selectedColumn);
   
        if($get['queryStatus']) {
            
            return $this->handleResponse( $get['queryResponse'],'get product collections success',$request->all(),str_replace('/','.',$request->path()),201);
        }

        $data  = array([
            'field' =>'show-products-collection',
            'message'=> 'error when show product collections'
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
    public function upsert(ProductCollectionUpsertRequest $request) { //done

        $insert = $this->productCollectionInterface->upsert($request->all());
         
        if($insert['queryStatus']) {
            
            $data  = array([
                'field' =>'upsert collection product',
                'message'=> 'total upsert : '.$insert['queryResponse']
            ]);


            return $this->handleResponse($data,'Insert collection product success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'create-update-collection-product',
                'message'=> 'update-collection-product fail'
            ]);

            return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }


    }
   
     public function destroy(ProductCollectionDestroyRequest $request) { //done
        

         //remove data
         $destroyAdmin =   $this->productCollectionInterface->destroy($request->by_id);

         if($destroyAdmin['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-product collection',
                 'message'=> 'product collection successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy product collection  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-product-collection',
                 'message'=> 'product collection destroy fail'
             ]);
 
              return   $this->handleError($data,$destroyAdmin['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }
}

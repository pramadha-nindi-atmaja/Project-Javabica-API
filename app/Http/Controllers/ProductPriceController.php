<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPriceRequest\ProductPriceDestroyRequest;
use App\Http\Requests\ProductPriceRequest\ProductPriceGetRequest;
use App\Http\Requests\ProductPriceRequest\ProductPriceUpsertRequest;

use App\Interfaces\ProductPriceInterface;
use Illuminate\Http\Request;

class ProductPriceController extends BaseController
{
  private $productPriceInterface;
   
    
  public function __construct(ProductPriceInterface $productPriceInterface)
  {
      $this->productPriceInterface            = $productPriceInterface;
   
  }

    public function show(ProductPriceGetRequest $request) {
        
      $selectedColumn = array('*');
     
      $get = $this->productPriceInterface->show($request,$selectedColumn);
    
      if($get['queryStatus']) {
          
          return $this->handleResponse( $get['queryResponse'],'get product prices success',$request->all(),str_replace('/','.',$request->path()),201);
      }

      $data  = array([
          'field' =>'show-product-prices',
          'message'=> 'error when show product prices'
      ]);

      return   $this->handleError( $data,$get['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);


      
       
    }
      /**
     * @lrd:start
     * # contoh format request untuk upsert
     * # ubah kutip nya menjadi kutip string
     * =============
    * 
    *  {
    *  "data": [
    *  {
    *  "id":1,
    *  "fk_product_id":"asd",
    *  "price":"1",
    *  },
    *  {
   *  "id":1,
    *  "fk_product_id":"asd",
    *  "price":"1",
    *  }
    *  ]
    *  }
    * =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function upsert(ProductPriceUpsertRequest $request) { //done


        $insert = $this->productPriceInterface->upsert($request->all());
          
          if($insert['queryStatus']) {
              
              $data  = array([
                  'field' =>'upsert product price',
                  'message'=> 'total upsert : '.$insert['queryResponse']
              ]);


              return $this->handleResponse($data,'Insert product price success',$request->all(),str_replace('/','.',$request->path()),201);
          }
          else {
              $data  = array([
                  'field' =>'create-update-price-product',
                  'message'=> 'update-price-product fail'
              ]);

              return   $this->handleError($data,$insert['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
          }
    }
   
     public function destroy(ProductPriceDestroyRequest $request) { //done

     
         //remove data
         $destroyAdmin =   $this->productPriceInterface->destroy($request->by_id);

         if($destroyAdmin['queryStatus']) {
 
             //response
             $data  = array(
                 'field' =>'destroy-product price',
                 'message'=> 'product price successfuly destroyed'
             );
 
              return $this->handleResponse($data,'Destroy product price  success',$request->all(),str_replace('/','.',$request->path()),204);
        
         } else {
             
             $data  = array([
                 'field' =>'destroy-product-collection',
                 'message'=> 'product price destroy fail'
             ]);
 
              return   $this->handleError($data,$destroyAdmin['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }
}

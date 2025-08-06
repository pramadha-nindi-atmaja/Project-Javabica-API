<?php

namespace App\Services\Cart;

use App\Http\Controllers\BaseController;
use App\Models\Product_price;

/**
 * Class HandlerWholeSaleItemServices
 * @package App\Services
 */
class HandlerWholeSaleItemServices extends BaseController
{
    public function wholesalechecking($product_id,$qty) {

        $matchPriceWholeSale = Product_price::where('fk_product_id',$product_id)->where('start_qty','<=',$qty)->orderBy('start_qty','desc')->first();
      
        if($matchPriceWholeSale != true)
        {
          return $this->handleArrayErrorResponse($matchPriceWholeSale, 'product price tidak di temukan','info');
        }

        $checkOneLevelAbove = Product_price::where('fk_product_id',$product_id)->where('start_qty','>=',$qty)->orderBy('start_qty','desc')->first();
        
        if($checkOneLevelAbove)
        {
            $oneLevelAbove = array(
                'difference_qty' => $checkOneLevelAbove->start_qty-$qty,
                'above_start_qty'=> $checkOneLevelAbove->start_qty,
                'price'          =>   $checkOneLevelAbove->price
            );
        }
        else
        {
            $oneLevelAbove = null;
        }
    
        $data =  array(
            'id'                => $matchPriceWholeSale->id,
            'qty'               => $qty,
            'price'             => $matchPriceWholeSale->price,         
            'one_level_above'   => $oneLevelAbove 
        );

        return $this->handleArrayResponse($data, 'product price di temukan','info');

    }
}

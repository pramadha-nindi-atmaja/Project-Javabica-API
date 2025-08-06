<?php

namespace App\Services;

use App\Http\Controllers\BaseController;
use App\Models\Product_variant;

/**
 * Class OrderReduceStockServices
 * @package App\Services
 */
class OrderReduceStockServices extends BaseController
{
    public function reduceStock(array $cart) 
    {
       
        $totalRequest =  count($cart);
        
        for($start=0;$start<$totalRequest;$start++)
        {
            $variantId       = $cart[$start]['variant_id'];
            $cartQty         = $cart[$start]['qty'];
            $stockAvailable  = $cart[$start]['stock'];
            $is_ignore_stock  = $cart[$start]['is_ignore_stock'];

           
            $reduceStock     = $stockAvailable-$cartQty;
                
            // if variant ignore status off
            if($is_ignore_stock  == 'INACTIVE')
            {
                $datas = array(
                    'stock' => $reduceStock 
                );
                //update variant
                $updateData = Product_variant::find($variantId)->update($datas);
            }
            else
            {
                $updateData = true;
            }

        }
             
            return $this->handleArrayResponse($updateData,'reduce stock success','info');
     
    }
}

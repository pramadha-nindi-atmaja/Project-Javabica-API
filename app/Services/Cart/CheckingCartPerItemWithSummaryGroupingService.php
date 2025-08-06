<?php

namespace App\Services\cart;

use App\Http\Controllers\BaseController;
use App\Services\Cart\AddTocartServices;

/**
 * Class CheckingCartPerItemWithSummaryGroupingService
 * @package App\Services
 */
class CheckingCartPerItemWithSummaryGroupingService extends BaseController
{
    private $addTocartServices;
   
    public function __construct(AddTocartServices $addTocartServices)
    {
        $this->addTocartServices            = $addTocartServices;
    }

    // data cart hanya array yang ada di dalam cart
    public function  groupingPerItem(array $dataCart) {
      
        $totalRequest =  count($dataCart);
       
        $myCart      = [];
        $outOfStock  = [];
        $subTotal    =  0;
        $totalCart   =  0;
        $totalWeight =  0;

        for($start=0;$start<$totalRequest;$start++)
        {
            $checkCart =  $this->addTocartServices->addToCart($dataCart[$start]['variant_id'],$dataCart[$start]['qty'],$dataCart[$start]['note']);
           
            if($checkCart['arrayStatus'] == true) {

                if($checkCart['arrayResponse']['in_stock'] == true)
                {
                    array_push($myCart,$checkCart['arrayResponse']);
                    $subTotal    = $subTotal+$checkCart['arrayResponse']['sub_total'];

                    if($checkCart['arrayResponse']['is_freeshiping'] == 'INACTIVE')
                    {
                        $weightWithQty   = $checkCart['arrayResponse']['weight']*$checkCart['arrayResponse']['qty'];
                        $totalWeight     = $totalWeight+$weightWithQty;
                    }
                    
                    $totalCart   = $totalCart+$checkCart['arrayResponse']['qty'];
                }
                else 
                {
                    array_push($outOfStock,$checkCart['arrayResponse']);
                }
            }

        }
        
        $data = array(
            'cart' => $myCart,
            'calculation'=>[
                'sub_total'        => $subTotal,
                'total_cart'       => $totalCart,
                'total_weight'     => $totalWeight,         
            ],
            'out_of_stock' => $outOfStock
        );

        return $this->handleArrayResponse($data, 'checking cart per item with summary group success','info');

    }
}

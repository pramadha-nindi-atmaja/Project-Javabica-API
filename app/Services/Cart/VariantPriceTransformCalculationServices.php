<?php

namespace App\Services\Cart;

use App\Http\Controllers\BaseController;

/**
 * Class VariantPriceTransformCalculationServices
 * @package App\Services
 */
class VariantPriceTransformCalculationServices extends BaseController
{
    public function variantPriceTransform($normalPrice,$discountPrice,$type) {

        if($discountPrice == null || $discountPrice <= 0)
        {
            $price = array(
                'purchase_price'      => $normalPrice,
                'normal_price'        => null,
                'discount'            => null,
                'discount_persentage' => null,
                'discount_margin'     => null,
                'type'                => $type
            );
        }
        else 
        {
            //convert into persentage
            $discount_margin     =  $normalPrice-$discountPrice;
            $getNominal          =  $normalPrice/$discountPrice;
            $getPersentage       =  100-(100/$getNominal);

            $price = array(
                'purchase_price'      => $discountPrice,
                'normal_price'        => $normalPrice,
                'discount'            => $discountPrice,
                'discount_persentage' => round($getPersentage),
                'discount_margin'     => $discount_margin,
                'type'                => $type
            );
        }

    
        return $this->handleArrayResponse($price, 'price transform berhasil','info');
    }
}

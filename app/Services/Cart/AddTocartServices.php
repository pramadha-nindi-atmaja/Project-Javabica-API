<?php

namespace App\Services\Cart;

use App\Http\Controllers\BaseController;
use App\Models\Product_price;
use App\Models\Product_variant;

/**
 * Class AddTocartServices
 * @package App\Services
 */
class AddTocartServices extends BaseController
{
    private $checkingStockServices;
    private $handlerWholeSaleItemServices;
    private $variantPriceTransformCalculationServices;
    private $cartCalculationServices;
    private $convertWeightServices;
   
    
    public function __construct(
    CheckingStockServices $checkingStockServices,
    HandlerWholeSaleItemServices $handlerWholeSaleItemServices,
    VariantPriceTransformCalculationServices $variantPriceTransformCalculationServices,
    CartCalculationServices $cartCalculationServices,
    ConvertWeightServices $convertWeightServices
    )
    {
        $this->checkingStockServices                        = $checkingStockServices;
        $this->handlerWholeSaleItemServices                 = $handlerWholeSaleItemServices;
        $this->variantPriceTransformCalculationServices     = $variantPriceTransformCalculationServices;
        $this->cartCalculationServices                      = $cartCalculationServices;
        $this->convertWeightServices                        = $convertWeightServices;
     
    }
    
    public function addToCart($variant_id,$qty,$note = null) {
        
        //check variant and variant stock
        $checkingStock = $this->checkingStockServices->variantStockChecking($variant_id,$qty);
       
        if($checkingStock['arrayStatus'] != true)
        {
            return $this->handleArrayErrorResponse($checkingStock['arrayResponse'], $checkingStock['arrayMessage'],'info');
        }
        //check if product has a wholesale price

        $checkingWholeSale =  $this->handlerWholeSaleItemServices->wholesalechecking($checkingStock['arrayResponse']['product']['id'],$qty);
      
        if($checkingWholeSale['arrayStatus'] != true) // use variant price
        {
            $getPriceTransform =   $this->variantPriceTransformCalculationServices->variantPriceTransform($checkingStock['arrayResponse']['price'],$checkingStock['arrayResponse']['discount'],'VARIANT');
            
        }
        else //use wholesale price
        {
            $getPriceTransform =   $this->variantPriceTransformCalculationServices->variantPriceTransform($checkingWholeSale['arrayResponse']['price'],null,'GROSIR');
        }

       //calculation 
       $calulation =  $this->cartCalculationServices->cartCalculation($getPriceTransform['arrayResponse']['purchase_price'],$qty);
       
      
       $variantDesc     = $this->variantDescription($checkingStock);
       $moreInformation = $this->moreInformation($getPriceTransform,$checkingWholeSale,$checkingStock['arrayResponse']['price'],$qty,$checkingStock['arrayResponse']['product']['id']);
      
       //convert KG TO GRAM 
      
        if($checkingStock['arrayResponse']['product']['type_weight'] == 'KG')
        {
            $GramValue  =  $this->convertWeightServices->ConvertWeight($checkingStock['arrayResponse']['product']['weight'],'KG_TO_GRAM');
            $weightType = 'GRAM';
        }
        else
        {
            $GramValue = $checkingStock['arrayResponse']['product']['weight'];
            $weightType = 'GRAM';
        }
     
       $dataCart =  array(
            'product_id'            => $checkingStock['arrayResponse']['product']['id'],
            'product_image'         => $checkingStock['arrayResponse']['product']['image'],
            'product_slug'         => $checkingStock['arrayResponse']['product']['slug'],
            'product_name'          => $checkingStock['arrayResponse']['product']['name'],
            'weight'                => $GramValue,
            'type_weight'           => $weightType,
            'variant_description'   => $variantDesc,
            'is_freeshiping'        => $checkingStock['arrayResponse']['product']['is_freeshiping'],
            'variant_id'            => $checkingStock['arrayResponse']['variant_id'],
            'variant_sku'           => $checkingStock['arrayResponse']['sku'],
            'stock'                 => $checkingStock['arrayResponse']['stock'],
            'in_stock'              => $checkingStock['arrayResponse']['in_stock'],
            'is_ignore_stock'       =>$checkingStock['arrayResponse']['is_ignore_stock'],
            'qty'                   => $qty,
            'purchase_price'        => $getPriceTransform['arrayResponse']['purchase_price'],
            'normal_price'          => $getPriceTransform['arrayResponse']['normal_price'],
            'price_info'            => [
                'price'         => $checkingStock['arrayResponse']['price'],
                'discount'      => $checkingStock['arrayResponse']['discount'],
            ],
            'type_price'            => $getPriceTransform['arrayResponse']['type'],
            'sub_total'             => $calulation,
            'more_information'      => $moreInformation,
            'note'                  => $note
        );

        return $this->handleArrayResponse($dataCart, 'maaf stock tidak tersedia','info');
    }

    private function moreInformation($getPriceTransform,$checkingWholeSale,$pricePerItem,$qty,$product_id) {

        if($getPriceTransform['arrayResponse']['type'] == 'GROSIR')
        {
             if($checkingWholeSale['arrayResponse']['one_level_above'] == null)
             {
                 $moreInformation  = null;
             }
             else
             {
                //get Price Per Item
                $marginPrice  = $pricePerItem-$checkingWholeSale['arrayResponse']['price'];
                $moreCheaper  = $qty*$marginPrice;
               
                if($checkingWholeSale['arrayResponse']['one_level_above']['difference_qty'] == 0)
                {
                    $upsale_info = 'Anda sudah mendapatkan harga grosir termurah!';
                }
                else 
                {
                    $upsale_info ='Tambah '.$checkingWholeSale['arrayResponse']['one_level_above']['difference_qty'].' pcs lagi untuk dapat harga <b> Rp'.number_format($checkingWholeSale['arrayResponse']['one_level_above']['price']).'</b>/pcs';
                }
                 $moreInformation = array(
                     'more_cheaper_info' => 'Hemat Rp'.number_format($moreCheaper),
                     'upsale_info'       => $upsale_info
                 );
             } 
        }
        else
        {
            $checkOneLevelAbove = Product_price::where('fk_product_id',$product_id)->where('start_qty','>=',$qty)->orderBy('start_qty','asc')->first();
            if($checkOneLevelAbove)
            {
                $upsale_info ='Tambah '.$checkOneLevelAbove->start_qty-$qty.' pcs lagi untuk dapat harga <b> Rp'.number_format($checkOneLevelAbove->price).'</b>/pcs';
                $moreInformation = array(
                    'more_cheaper_info' => null,
                    'upsale_info'       => $upsale_info
                );
            }
            else
            {
                $moreInformation = null;
            }
        }

        return $moreInformation;

    }
    private function variantDescription($checkingStock) {

        $variantDesc = null;

        if($checkingStock['arrayResponse']['attribute']['type'] != null)
        {
            $variantDesc = $checkingStock['arrayResponse']['attribute']['type'].': ';
        }
        if($checkingStock['arrayResponse']['attribute']['value'] != null)
        {
            $variantDesc .= $checkingStock['arrayResponse']['attribute']['value'];
        }

        if($checkingStock['arrayResponse']['attribute']['child'] != null)
        {
            if($checkingStock['arrayResponse']['attribute']['child']['type'] != null)
            {
                $variantDesc .= ', '.$checkingStock['arrayResponse']['attribute']['child']['type'].': ';
            }

            if($checkingStock['arrayResponse']['attribute']['child']['value'] != null)
            {
                $variantDesc .= $checkingStock['arrayResponse']['attribute']['child']['value'];
            }
        }

        return $variantDesc;
    }
}

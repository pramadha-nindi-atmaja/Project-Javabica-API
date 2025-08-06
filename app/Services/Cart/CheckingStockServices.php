<?php

namespace App\Services\Cart;

use App\Http\Controllers\BaseController;
use App\Models\Product_variant;

/**
 * Class CheckingStockServices
 * @package App\Services
 */
class CheckingStockServices extends BaseController
{
  public function variantStockChecking($variant_id, $qty)
  {

      $checkVariant = Product_variant::where('id', $variant_id)->where('status', 'ACTIVE')->first();

      if ($checkVariant != true) {

        return $this->handleArrayErrorResponse($checkVariant, 'variant tidak ditemukan', 'info');
      
      }

      //check if is ignore stock is active or not
      if ($checkVariant->is_ignore_stock == 'INACTIVE') {
        
        if ($qty <=  $checkVariant->stock) {

          $reformatData = $this->dataFormat($checkVariant,true);
          return $this->handleArrayResponse($reformatData,'stock tersedia');

        } else {

          $reformatData = $this->dataFormat($checkVariant,false);
          return $this->handleArrayResponse($reformatData,'stock variant tidak tersedia', 'info');
        }
      }

      $reformatData = $this->dataFormat($checkVariant,true);
      return $this->handleArrayResponse($reformatData,'stock tersedia');
  }
  private function dataFormat($checkVariant,$in_stock)
  {
      $dataFormat = array(
        'variant_id'     => $checkVariant->id,
        'sku'            => $checkVariant->sku,
        'price'          => $checkVariant->price,
        'discount'       => $checkVariant->discount,
        'stock'          =>  $checkVariant->stock,
        'is_ignore_stock' =>  $checkVariant->is_ignore_stock,
        'in_stock'       => $in_stock,
        'product'        =>[
          'id'           => $checkVariant->fk_product_id,
          'slug'         => $checkVariant->fk_product->slug,
          'name'         => $checkVariant->fk_product->name,
          'weight'       => $checkVariant->fk_product->weight,
          'type_weight'  => $checkVariant->fk_product->type_weight,
          'is_freeshiping'  => $checkVariant->fk_product->is_freeshiping,
          
          'image'        => $checkVariant->fk_product->hasMany_image_getPrimaryImage?->path
        ],
        'attribute'      => [
          'type'         => $checkVariant->fk_attribute_parent?->taxoParent?->taxonomy_name,
          'value'        => $checkVariant->fk_attribute_parent?->taxonomy_name,
          'child'        => [
            'type'        => $checkVariant->fk_attribute_child?->taxoParent?->taxonomy_name,
            'value'       => $checkVariant->fk_attribute_child?->taxonomy_name,
          ]
        ]
      );

      return $dataFormat;
  }
}

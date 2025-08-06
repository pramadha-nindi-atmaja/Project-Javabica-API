<?php

namespace App\Http\Resources\ProductResource;

use App\Models\Product_variant;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowThumbnailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource['data']->hasMany_image != null) {

            $image =  $this->resource['data']->hasMany_imageThumbnail->map(function ($query) {
               
                    return array(
                        'id'                   => $query->id,
                        'path'                 => $query->path,
                        'order_number'         => $query->order_number,
                    );
               
            });
        } else {

            $image = null;
        }
        //== variant
        if (count($this->resource['data']->hasMany_variantActive) > 0) 
        {    
          
            $type_parent =   Product_variant::select('fk_attribute_parent_id')->where('fk_product_id', $this->resource['data']->id)->where('status','ACTIVE')->groupby('fk_attribute_parent_id')->first();
            $type_child  =   Product_variant::select('fk_attribute_child_id')->where('fk_product_id', $this->resource['data']->id)->where('status','ACTIVE')->groupby('fk_attribute_child_id')->first();
            $listParent  =   Product_variant::select('fk_attribute_parent_id')->where('fk_product_id', $this->resource['data']->id)->where('status','ACTIVE')->groupby('fk_attribute_parent_id')->get();
           
            //** HANDLER APABILA TIDAK MEMILIKI VARIANT  */
            if ($type_parent->fk_attribute_parent_id == null &&  $type_child->fk_attribute_child_id == null) 
            { 
               
                $variantList = $this->resource['data']->hasMany_variantActive->map(function ($query) {
                  
                    $getPrice = $this->recalculatePrice($query->price,$query->discount);

                    return array(
                        'parent' => null,
                        'child' => [[
                            'id'        => $query->id,
                            'name'      => null,
                            'sku'       => $query->sku,
                            'stock'       => $query->stock,
                            'is_ignore_stock'       => $query->is_ignore_stock,
                            'price'     => $getPrice,
                        ]]
                    );
                });
            } else {
                
                $variantList =  $listParent->map(function ($query) {
                  
                    $child =   Product_variant::where('fk_product_id', $this->resource['data']->id)->where('fk_attribute_parent_id', $query->fk_attribute_parent_id)->where('fk_attribute_child_id','!=',null)->where('status','ACTIVE')->orderBy('price','asc')->get();
                   
                     //** HANDLER APABILA  MEMILIKI VARIANT 2 LEVEL  */
                    if (count($child) > 0)
                    {
                        
                        $getChild =  $child->map(function ($queryChild)   {
                           

                            $getPrice = $this->recalculatePrice($queryChild->price,$queryChild->discount);
                            
                            return array(
                                'id'        => $queryChild->id,
                                'sku'       => $queryChild->sku,
                                'stock'       => $queryChild->stock,
                                'is_ignore_stock'       => $queryChild->is_ignore_stock,
                                'name'      => $queryChild->fk_attribute_child['taxonomy_name'],
                                'price'     => $getPrice
                            );

                        });
                    
                        return array(
                            'parent'      => $query->fk_attribute_parent->taxonomy_name,
                            'child'       =>  $getChild,
                        );

                    } 
                    else //** HANDLER APABILA  MEMILIKI VARIANT 1 LEVEL  */
                    {
                    
                        $getPrice  =   Product_variant::select('*')->where('fk_product_id', $this->resource['data']->id)->where('fk_attribute_parent_id',$query->fk_attribute_parent_id)->where('status','ACTIVE')->orderBy('price','asc')->first();
                     
                          $getPriceData = $this->recalculatePrice($getPrice->price,$getPrice->discount);

                            return array(
                                'parent'      => $query->fk_attribute_parent->taxonomy_name,
                                'child'       => [[
                                    'id'        => $getPrice->id,
                                    'name'    => $getPrice->fk_attribute_child_id?->taxonomy_name,
                                    'sku'     => $getPrice->sku,
                                    'stock'   => $getPrice->stock,
                                    'price'   => $getPriceData,
                                    'is_ignore_stock'       => $getPrice->is_ignore_stock,
                                 
                                ]]
                            );
                       
                    }
                });
            }
          
            if($type_parent->fk_attribute_parent == null && $type_child->fk_attribute_child == null)
            {
                $variantCriteria = 'NO_VARIANT';
            }
            if($type_parent->fk_attribute_parent?->taxoParent['taxonomy_name'] != null)
            {
                $variantCriteria = 'VARIANT_LEVEL_1';
            }
            if($type_child->fk_attribute_child?->taxoParent['taxonomy_name'] != null)
            {
               
                $variantCriteria = 'VARIANT_LEVEL_2';
            }
            
            
               
                $variantAll = array(
                    'variant_criteria'  => $variantCriteria,
                    'type_parent'       => $type_parent->fk_attribute_parent?->taxoParent['taxonomy_name'],
                    'type_child'        => $type_child->fk_attribute_child?->taxoParent['taxonomy_name'],
                    'list'              => $variantList
                );
            
           

        } else {

            $variantAll = null;
        }
        //==
        if ($this->resource['data']->hasMany_category != null) {

            $category =  $this->resource['data']->hasMany_category->map(function ($query) {

                if ($query->fk_category != null) {
                    return array(
                        'id'                   => $query->fk_category->id,
                        'name'                 => $query->fk_category->taxonomy_name,
                        'slug'                 => $query->fk_category->taxonomy_slug,
                       
                    );
                } else {

                    return null;
                }
            });
        } else {

            $category = null;
        }
        //==
        if ($this->resource['data']->variant_active_or_inactive_cheapest_price != null) {

            $variant =  $this->resource['data']->variant_active_or_inactive_cheapest_price->map(function ($query) {

           
                if($query->discount == null || $query->discount <= 0)
                {
                    $price = array(
                        'purchase_price'      => $query->price,
                        'normal_price'        => $query->price,
                        'discount'            => null,
                        'discount_persentage' => null,
                        'discount_margin'     => null
                    );
                }
                else 
                {
                    //convert into persentage
                    $discount_margin     =  $query->price-$query->discount;
                    $getNominal          =  $query->price/$query->discount;
                    $getPersentage       =  100-(100/$getNominal);

                    $price = array(
                        'purchase_price'      => $query->discount,
                        'normal_price'        => $query->price,
                        'discount'            => $query->discount,
                        'discount_persentage' => round($getPersentage),
                        'discount_margin'     => $discount_margin
                    );
                }


                return array(
                    'id'                      => $query->id,
                    'sku'                     => $query->sku,                
                    'price'                   => $price,
                   
                );
            });
        } else {

            $variant = null;
        }
       
        return [
            'product'                 => [
                'id'                   => $this->resource['data']->id,
                'name'                 => $this->resource['data']->name,
                'slug'                 => $this->resource['data']->slug,
                'sort'                   =>   $this->resource['data']->sort,
            ],
            'image'                             => $image,
            'category'                          => $category,
            'variant_price'                     => $variant,
            'variant_list'                      => $variantAll,
        ];
    }
    
    private function recalculatePrice($pricePerItem,$discountPerItem) {
        
        if ($discountPerItem == null || $discountPerItem <= 0) {

            $price = array(
                'purchase_price'      => $pricePerItem,
                'normal_price'        => $pricePerItem,
                'discount'            => null,
                'discount_persentage' => null,
                'discount_margin'     => null
            );
        } else {

            $discount_margin     =  $pricePerItem - $discountPerItem;
            $discount_persentage =  $pricePerItem / $discountPerItem;
            $getPersentage       =  100-(100/$discount_persentage);

            $price = array(
                'purchase_price'      => $discountPerItem,
                'normal_price'        => $pricePerItem,
                'discount'            => $discountPerItem,
                'discount_persentage' => round($getPersentage),
                'discount_margin'     => $discount_margin
            );
        }

        return $price;
    }
}

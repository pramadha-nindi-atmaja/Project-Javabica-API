<?php

namespace App\Http\Resources\ProductVariantResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if( $this->resource['data']->fk_product) {

            $productData = array(
             'id'                   => $this->resource['data']->fk_product->id,
             'name'                 => $this->resource['data']->fk_product->name,
             'slug'                 => $this->resource['data']->fk_product->slug,
            );

        }
        else {
            $productData = null;
        }

        if( $this->resource['data']->fk_attribute_parent) {

            $productAttributeParent = array(
             'id'                   => $this->resource['data']->fk_attribute_parent->id,
             'name'                 => $this->resource['data']->fk_attribute_parent->taxonomy_name,
            );

        }
        else {
            $productAttributeParent = null;
        }

        if( $this->resource['data']->fk_attribute_child) {

            $productAttributeChild = array(
             'id'                   => $this->resource['data']->fk_attribute_child->id,
             'name'                 => $this->resource['data']->fk_attribute_child->taxonomy_name,
       
            );

        }
        else {
            $productAttributeChild = null;
        }
        return [
            'id'                              => $this->resource['data']->id,
            'sku'                             =>  $this->resource['data']->sku,
            'name'                            =>  $this->resource['data']->name,
            'stock'                           => $this->resource['data']->stock,
            'price'                           => $this->resource['data']->price,
            'discount'                        => $this->resource['data']->discount,
            'status'                          => $this->resource['data']->status,
            'is_ignore_stock'                 => $this->resource['data']->is_ignore_stock,
            'attribute_parent_value'          => $productAttributeParent,
            'attribute_child_value'           => $productAttributeChild,
            'product'                         => $productData,
        ];
    }
}

<?php

namespace App\Http\Resources\OrderProductResource;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductSummaryResource extends JsonResource
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

            $product = array(
             'id'                   => $this->resource['data']->fk_product->id,
             'slug'                 => $this->resource['data']->fk_product->slug,
             'name'                 => $this->resource['data']->fk_product->name,
            );

        }
        else {
            $product = null;
        }

        if( $this->resource['data']->fk_variant) {

            $variant = array(
             'id'                   => $this->resource['data']->fk_variant->id,
             'slug'                 => $this->resource['data']->fk_variant->slug,
             'name'                 => $this->resource['data']->fk_variant->name,
            );

        }
        else {
            $variant = null;
        }
       
        return [
            'id'                 => $this->resource['data']->id,
            'product'            => $product,
            'variant'            => $variant,
            'product_name'       => $this->resource['data']->product_name,
            'image'              => $this->resource['data']->image,
            'sku'                => $this->resource['data']->sku,
            'variant_description'   => $this->resource['data']->variant_description,
            'qty'                => $this->resource['data']->qty,
            'acctual_price'      => $this->resource['data']->acctual_price,
            'discount_price'     => $this->resource['data']->discount_price,
            'purchase_price'     => $this->resource['data']->purchase_price,
            'note'               => $this->resource['data']->note,
        ];
    }
}

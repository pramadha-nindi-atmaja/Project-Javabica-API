<?php

namespace App\Http\Resources\OrderProductResource;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if( $this->resource['data']->fk_order) {

            $order = array(
             'id'                   => $this->resource['data']->fk_order->id,
             'order_number'         => $this->resource['data']->fk_order->order_number,
             'contact_email'        => $this->resource['data']->fk_order->contact_email,
            );

        }
        else {
            $order = null;
        }

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
             'sku'                 => $this->resource['data']->fk_variant->sku,
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
            'variant_description'=> $this->resource['data']->variant_description,
            'attribute_child'    => $this->resource['data']->attribute_child,
            'qty'                => $this->resource['data']->qty,
            'acctual_price'      => $this->resource['data']->acctual_price,
            'discount_price'     => $this->resource['data']->discount_price,
            'purchase_price'     => $this->resource['data']->purchase_price,
            'note'               => $this->resource['data']->note,
            'order'              => $order,
        ];
    }
}

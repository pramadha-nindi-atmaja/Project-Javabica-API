<?php

namespace App\Http\Resources\ProductPriceResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceShowAllResource extends JsonResource
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
             'slug'                => $this->resource['data']->fk_product->slug,
             'name'                 => $this->resource['data']->fk_product->name,
            );

        }
        else {
            $product = null;
        }
        return [
            'id'                   => $this->resource['data']->id,
            'start_qty'            =>  $this->resource['data']->start_qty,
            'price'                =>  $this->resource['data']->price,
            'discount'             =>  $this->resource['data']->discount,
            'product'             => $product,
        ];
    }
}

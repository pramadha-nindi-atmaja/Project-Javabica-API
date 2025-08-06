<?php

namespace App\Http\Resources\ProductImageResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageShowAllResource extends JsonResource
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
        return [
            'id'                   => $this->resource['data']->id,
            'path'                 =>  $this->resource['data']->path,
            'order_number'                 =>  $this->resource['data']->order_number,
            'product'              => $productData,
        ];
    }
}

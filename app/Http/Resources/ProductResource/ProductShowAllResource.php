<?php

namespace App\Http\Resources\ProductResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->resource['data']->id,
            'name'                 => $this->resource['data']->name,
            'is_freeshipping'      => $this->resource['data']->is_freeshiping,
            'slug'                 => $this->resource['data']->slug,
            'product_description'  => $this->resource['data']->product_description,

            'product_information'  => $this->resource['data']->product_information,
            'meta_keywords'        => explode(",",$this->resource['data']->meta_keywords),
            'meta_description'     => $this->resource['data']->meta_description,
            'meta_title'           => $this->resource['data']->meta_title,

            'weight'              => $this->resource['data']->weight,
            'type_weight'         => $this->resource['data']->type_weight,
            'size_tall'           => $this->resource['data']->size_tall,
            'size_wide'           => $this->resource['data']->size_wide,
            'size_long'           => $this->resource['data']->size_long,
            'type_size'           => $this->resource['data']->type_size,
            'sort'                   =>   $this->resource['data']->sort,

            'tags'                 =>  explode(",",$this->resource['data']->tags),
            'status'               => $this->resource['data']->status,
             
        ];
    }
}

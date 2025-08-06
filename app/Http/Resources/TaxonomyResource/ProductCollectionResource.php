<?php

namespace App\Http\Resources\TaxonomyResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionResource extends JsonResource
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
            'id'                      => $this->resource['data']->id,
            'name'                    => $this->resource['data']->taxonomy_name,
            'image'                   => $this->resource['data']->taxonomy_image,
            'slug'                    => $this->resource['data']->taxonomy_slug,
           
            'type'                => [
                'id'              =>$this->resource['data']->taxoType?->id,
                'type_name'       =>$this->resource['data']->taxoType?->taxo_type_name,
            ],

            'status'              => $this->resource['data']->taxonomy_status,
           
        ];
    }
}

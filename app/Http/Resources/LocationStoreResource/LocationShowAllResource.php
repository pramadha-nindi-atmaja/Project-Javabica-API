<?php

namespace App\Http\Resources\LocationStoreResource;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationShowAllResource extends JsonResource
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
            'province'             => $this->resource['data']->fk_province,
            'province_name'        => $this->resource['data']->hasManyProvince->name,
            'name'                 => $this->resource['data']->name,
            'image'                => $this->resource['data']->image,
            'description'          => $this->resource['data']->description,
            'embed_map'            => $this->resource['data']->embed_map,
             
        ];
    }
}

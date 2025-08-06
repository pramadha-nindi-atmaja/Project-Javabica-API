<?php

namespace App\Http\Resources\TaxonomyResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource['data']->taxoChild_publics != null) {

            $subcategory =  $this->resource['data']->taxoChild_publics->map(function ($query) {
               
                    return array(
                        'id'                    => $query->id,
                        'name'                    => $query->taxonomy_name,
                        'image'                   => $query->taxonomy_image,
                        'slug'                    => $query->taxonomy_slug,
                    );
               
            });
        } else {

            $subcategory = null;
        }

        return [
            'id'                      => $this->resource['data']->id,
            'name'                    => $this->resource['data']->taxonomy_name,
            'image'                   => $this->resource['data']->taxonomy_image,
            'slug'                    => $this->resource['data']->taxonomy_slug,
            'sub_category'            => $subcategory,
            'status'              => $this->resource['data']->taxonomy_status,
           
        ];
    }
}

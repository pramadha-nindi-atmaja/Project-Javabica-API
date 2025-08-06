<?php

namespace App\Http\Resources\TaxonomyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        if($this->resource['data']->taxoParent != null) {

            $parentDetail = array(
                'id'                   => $this->resource['data']->taxoParent?->id,
                'parent_name'          => $this->resource['data']->taxoParent?->taxonomy_name,
                'parent_slug'          => $this->resource['data']->taxoParent?->taxonomy_name,
            );
        }
        else {

            $parentDetail= null;
        }

        return [
            'id'                      => $this->resource['data']->id,
            'name'                    => $this->resource['data']->taxonomy_name,
            'description'             => $this->resource['data']->taxonomy_description,
            'image'                   => $this->resource['data']->taxonomy_image,
            'slug'                    => $this->resource['data']->taxonomy_slug,
            'parent'                  => $parentDetail,

            'type'                => [
                'id'              =>$this->resource['data']->taxoType?->id,
                'type_name'       =>$this->resource['data']->taxoType?->taxo_type_name,
            ],

            'sort'                => $this->resource['data']->taxonomy_sort,
            'status'              => $this->resource['data']->taxonomy_status,
            'created_at'          => $this->resource['data']->created_at,
           
        ];

    }
}

<?php

namespace App\Http\Resources\ProductCollectionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionShowAllResource extends JsonResource
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

        if($this->resource['data']->fk_collection)  {
            
            $collectionData = array(
                'id' =>  $this->resource['data']->fk_collection->id,
                'name' =>  $this->resource['data']->fk_collection->taxonomy_name
            );

        }
        else {
            $collectionData = null;
        }
        return [
            'id'                   => $this->resource['data']->id,
            'product'              => $productData,
            'collection'           => $collectionData,
        ];
    }
}

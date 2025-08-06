<?php

namespace App\Http\Resources\ProductCategoryResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryShowAllResource extends JsonResource
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

        if($this->resource['data']->fk_category)  {
            
            if($this->resource['data']->fk_category->taxoParent)
            {
                $parent = array(
                    'id'                 => $this->resource['data']->fk_category->taxoParent->id,
                    'name'               => $this->resource['data']->fk_category->taxoParent->taxonomy_name,
                    'slug'               => $this->resource['data']->fk_category->taxoParent->taxonomy_slug,
                    'status'             => $this->resource['data']->fk_category->taxoParent->taxonomy_status,
                );
            }
            else
            {
                $parent =null;
            }
            $collectionData = array(
                'id' =>  $this->resource['data']->fk_category->id,
                'name' =>  $this->resource['data']->fk_category->taxonomy_name,
                'parent' =>  $parent
            );

        }
        else {
            $collectionData = null;
        }
        return [
            'id'                   => $this->resource['data']->id,
            'product'              => $productData,
            'category'             => $collectionData,
        ];
    }
}

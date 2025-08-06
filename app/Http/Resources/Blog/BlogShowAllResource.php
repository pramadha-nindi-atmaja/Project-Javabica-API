<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaxonomyResource\TaxonomyShowAllResource;

class BlogShowAllResource extends JsonResource
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
            'cover'                => $this->resource['data']->cover,
            'title'                => $this->resource['data']->title,
            'short_desc'           => $this->resource['data']->short_desc,
            'long_desc'            => $this->resource['data']->long_desc,
            'status'               => $this->resource['data']->status,
            'slug'                 => $this->resource['data']->slug,
            'created_at'           => $this->resource['data']->created_at,
            'hot_news'             => $this->resource['data']->hot_news,
            'fk_category'          => $this->resource['data']->fk_category,
            'category'             => $this->resource['data']->category,
            'meta_title'           => $this->resource['data']->meta_title,
            'meta_description'     => $this->resource['data']->meta_description,
        ];
    }
}

<?php

namespace App\Http\Resources\CategoryBlog;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryBlogShowAllResource extends JsonResource
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
            'id'          => $this->resource['data']->id,
            'name'        => $this->resource['data']->name,
            'slug'        => $this->resource['data']->slug,
            'description' => $this->resource['data']->description,
            'status'      => $this->resource['data']->status,
            'created_at'  => $this->resource['data']->created_at,
        ];
    }
}

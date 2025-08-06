<?php

namespace App\PipelineFilters\MasterProductPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  {

    if (request()->has('by_id') && request()->get('by_id')) {
     
      $query->where('id', request()->get('by_id'));
    }

    if (request()->has('by_status') && request()->get('by_status')) {
       $query->where('products.status', request()->get('by_status'));
    }
    if (request()->has('by_collection_id') && request()->get('by_collection_id')) {
     
      $collectionId = request()->get('by_collection_id');
     
      $query->WhereHas('hasMany_collection', function ($query) use ($collectionId) {
        $query->where('fk_collection_id', $collectionId);
      });
    }
    if (request()->has('by_category_id') && request()->get('by_category_id')) {
      $categoryId = request()->get('by_category_id');

      $query->WhereHas('hasMany_category', function ($query) use ($categoryId) {

        $query->where('fk_category_id', $categoryId);
      });
    }
    if (request()->has('except_product_id') && request()->get('except_product_id')) {
      $query->where('products.id', '!=', request()->get('except_product_id'));
    }



    return $next($query);
  }
}

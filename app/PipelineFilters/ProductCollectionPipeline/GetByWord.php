<?php

namespace App\PipelineFilters\ProductCollectionPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
          $query->WhereHas('fk_collection', function ($query) use ($keyword) {
            $query->where('taxonomy_name', 'like', '%'. $keyword .'%');
          });
          $query->WhereHas('fk_product', function ($query) use ($keyword) {
            $query->where('name', 'like', '%'. $keyword .'%');
            $query->orWhere('tags', 'like', '%'. $keyword .'%');
            $query->orWhere('slug', 'like', '%'. $keyword .'%');
          });
          
        }
      
    }

    return $next($query);
  }
}

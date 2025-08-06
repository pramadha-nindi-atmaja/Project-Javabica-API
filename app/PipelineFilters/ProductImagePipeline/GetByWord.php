<?php

namespace App\PipelineFilters\ProductImagePipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
            $query->where('path','like', '%' .$keyword.'%');
            $query->orWhereHas('fk_product', function ($query) use ($keyword) {
              $query->where('name', 'like', '%'. $keyword .'%');
            });
        }
      
    }

    return $next($query);
  }
}

<?php

namespace App\PipelineFilters\ProductVariantPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
          $query->where('sku','like', '%' .$keyword.'%');
          $query->orWhereHas('fk_product', function ($query) use ($keyword) {
  
              $query->where('name', 'like', '%'. $keyword .'%');
            });
        }
      
    }

    return $next($query);
  }
}

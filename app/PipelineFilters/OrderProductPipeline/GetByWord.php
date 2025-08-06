<?php

namespace App\PipelineFilters\OrderProductPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          $query->where('sku', 'like', '%'. $keyword .'%');
          $query->orWhere('attribute_parent', 'like', '%'. $keyword .'%');
          $query->orWhere('attribute_child', 'like', '%'. $keyword .'%');
          
          $query->orWhereHas('fk_order', function ($query) use ($keyword) {
            $query->where('order_number', 'like', '%'. $keyword .'%');
          });
         
        }
      
    }

    return $next($query);
  }
}

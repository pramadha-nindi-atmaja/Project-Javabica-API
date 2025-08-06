<?php

namespace App\PipelineFilters\ProductPricePipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      $keyword = request()->get('keyword');

      $query->where(function ($query)use ($keyword)  {

            $query->orWhere('price','like', '%' .$keyword.'%');
          
            $query->orWhereHas('fk_product', function ($query) use ($keyword) {
                $query->where('name', 'like', '%'. $keyword .'%');
               
            });
      });
    }

    return $next($query);
  }
}

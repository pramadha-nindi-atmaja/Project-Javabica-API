<?php

namespace App\PipelineFilters\ProductCategoryPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  { 
    
      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
    
      if (request()->has('by_product_id') && request()->get('by_product_id'))
      {   
          $query->where('fk_product_id', request()->get('by_product_id'));
      }

      if (request()->has('by_category_id') && request()->get('by_category_id'))
      {   
          $query->where('fk_category_id', request()->get('by_category_id'));
      }

    return $next($query);
  }
}

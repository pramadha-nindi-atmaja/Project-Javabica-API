<?php

namespace App\PipelineFilters\ProductVariantPipeline;

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
      if (request()->has('by_ignore_status') && request()->get('by_ignore_status'))
      {
          $query->where('is_ignore_stock', request()->get('by_ignore_status'));
      }
     

    return $next($query);
  }
}

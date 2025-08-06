<?php

namespace App\PipelineFilters\ShippingAddressPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  {

    if (request()->has('by_id') && request()->get('by_id')) {
     
      $query->where('id', request()->get('by_id'));
    }

    if (request()->has('by_user') && request()->get('by_user')) {
      $query->where('fk_user_id', request()->get('by_user'));
    }
   
    return $next($query);
  }
}

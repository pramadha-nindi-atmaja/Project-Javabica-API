<?php

namespace App\PipelineFilters\BlogCategoryPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  { 

      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
      if (request()->has('status') && request()->get('status') == 1)
      {
          $query->where('status', 1);
      }

    return $next($query);
  }
}
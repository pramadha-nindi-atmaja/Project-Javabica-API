<?php

namespace App\PipelineFilters\BlogPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  { 

      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
      if (request()->has('slug') && request()->get('slug'))
      {
          $query->where('slug', request()->get('slug'));
      }
      if (request()->has('hot_news') && request()->get('hot_news') === 'true')
      {
          $query->where('hot_news', 1);
      }
      if (request()->has('status') && request()->get('status') === 'PUBLISH')
      {
          $query->where('status', 'PUBLISH');
      }
      
    return $next($query);
  }
}
<?php

namespace App\PipelineFilters\UsersPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  {
      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
      if (request()->has('by_uuid') && request()->get('by_uuid'))
      {
          $query->where('uuid', request()->get('by_uuid'));
      }
      if (request()->has('by_email') && request()->get('by_email'))
      {
          $query->where('email', request()->get('by_email'));
      }

    return $next($query);
  }
}

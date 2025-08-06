<?php

namespace App\PipelineFilters\UsersPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      $keyword = request()->get('keyword');

      $query->where(function ($query)use ($keyword)  {

            $query->where('name','like', '%' .$keyword.'%');
            $query->orWhere('email','like', '%' .$keyword.'%');
      });
    }

    return $next($query);
  }
}

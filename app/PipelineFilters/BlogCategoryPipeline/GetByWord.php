<?php

namespace App\PipelineFilters\BlogCategoryPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
          $query->where(function ($query)use ($keyword)  {
              $query->where('name','like', '%' .$keyword.'%');
          });
          
        }
      
    }

    return $next($query);
  }
}

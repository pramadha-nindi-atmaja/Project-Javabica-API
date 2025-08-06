<?php

namespace App\PipelineFilters\MasterProductPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
            $query->where('name','like', '%' .$keyword.'%');
            $query->orWhere('slug','like', '%' .$keyword.'%');
            $query->orWhere('tags','like', '%' .$keyword.'%');
      
        }
      
    }

    return $next($query);
  }
}

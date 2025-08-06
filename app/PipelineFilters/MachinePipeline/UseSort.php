<?php

namespace App\PipelineFilters\MachinePipeline;

use Closure;

class UseSort
{

  public function handle($query, Closure $next)
  {
   
    if(request()->has('sort_type')){
        $query->orderBy('created_at',request()->get('sort_type'));
    }else{
        $query->orderBy('created_at','desc');
    }
    
    return $next($query);
  }
}

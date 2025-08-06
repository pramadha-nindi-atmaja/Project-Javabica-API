<?php

namespace App\PipelineFilters\ProductImagePipeline;

use Closure;

class UseSort
{

  public function handle($query, Closure $next)
  {
   
    if(request()->get('sort_by'))
    { 
        if(request()->has('sort_type'))
        {
          $sort_type = request()->get('sort_type');
        }
        else
        {
          $sort_type = 'desc';
        }


          
          $query->orderBy(''.request()->get('sort_by').'',$sort_type);
     
    }
    else  {
      
      $query->orderBy('created_at','desc');
    }
    
    return $next($query);
  }
}

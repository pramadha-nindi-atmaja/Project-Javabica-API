<?php

namespace App\PipelineFilters\ProductCollectionPipeline;

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


        if(request()->get('sort_by') == 'product_name')
        {
       
          $query->WhereHas('fk_product', function ($query) use ($sort_type) {
              $query->orderBy('name',$sort_type);
          });
          
        }
        if(request()->get('sort_by') == 'product_tags')
        {
       
          $query->WhereHas('fk_product', function ($query) use ($sort_type) {
              $query->orderBy('tags',$sort_type);
          });
          
        }
        if(request()->get('sort_by') == 'product_status')
        {
       
          $query->WhereHas('fk_product', function ($query) use ($sort_type) {
              $query->orderBy('status',$sort_type);
          });
          
        }
        else 
        {
          $query->orderBy(''.request()->get('sort_by').'',$sort_type);
        }

    }
    else  {
      $query->orderBy('created_at','desc');
    }
    
    return $next($query);
  }
}

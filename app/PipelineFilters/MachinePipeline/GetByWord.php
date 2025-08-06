<?php

namespace App\PipelineFilters\MachinePipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
          $query->with(['user', 'product'])->where(function ($query)use ($keyword)  {
              $query->orWhere('serial_number','like', '%' .$keyword.'%')
                ->orWhereHas('user', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('product', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
          });
          
        }
      
    }

    return $next($query);
  }
}

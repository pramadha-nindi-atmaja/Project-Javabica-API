<?php

namespace App\PipelineFilters\HistoryVoucherPipeline;

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
              $query->where('id','like', '%' .$keyword.'%');
          });
          
        }
      
    }

    return $next($query);
  }
}

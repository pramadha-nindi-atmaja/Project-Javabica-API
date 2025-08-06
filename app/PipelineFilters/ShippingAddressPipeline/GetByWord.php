<?php

namespace App\PipelineFilters\ShippingAddressPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
            $query->where('first_name','like', '%' .$keyword.'%');
            $query->orWhere('last_name','like', '%' .$keyword.'%');
            $query->orWhere('address','like', '%' .$keyword.'%');
            $query->orWhere('phone_number','like', '%' .$keyword.'%');
          
        }
      
    }

    return $next($query);
  }
}

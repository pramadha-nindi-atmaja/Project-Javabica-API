<?php

namespace App\PipelineFilters\OrderProductPipeline;

use Closure;
use Illuminate\Http\Request;

class GetByKey
{
  protected $request; 

  public function __construct(Request $request)
    {
        
        $this->request = $request; // Request becomes available for all the controller functions that call $this->request
    }


  public function handle($query, Closure $next)
  {
    $request = new Request;
    if ($this->request->get('by_id')) {
      
      $query->where('id', request()->get('by_id'));
    }

    if ($this->request->get('by_order_id') && $this->request->get('by_order_id')) {
  
      $query->where('fk_order_id', request()->get('by_order_id'));
    }


    return $next($query);
  }
}

<?php

namespace App\PipelineFilters\TaxonomyPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
    if (request()->has('keyword') )
    {
      
        $keyword = request()->get('keyword');

        if($keyword) {
          
              $query->where(function ($query) use ($keyword) {
              $query->where('taxonomy_name', 'like', '%' .$keyword.'%');
              $query->orWhere('taxonomy_slug','like', '%' .$keyword.'%');

              $query->orWhereHas('taxoParent', function ($query) use ($keyword) {
                $query->where('taxonomy_name', 'like', '%'. $keyword .'%');
              });

          });
        }
      
    }

    return $next($query);
  }
}

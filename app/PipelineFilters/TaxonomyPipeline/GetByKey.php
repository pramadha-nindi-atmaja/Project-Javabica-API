<?php

namespace App\PipelineFilters\TaxonomyPipeline;

use Closure;

class GetByKey
{

  public function handle($query, Closure $next)
  { 
    
      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
      if (request()->has('by_parent') && request()->get('by_parent'))
      {
     
          $query->where('parent', request()->get('by_parent'));
      }
      if (request()->has('by_taxo_type_id') && request()->get('by_taxo_type_id'))
      {    
          $query->where('taxonomy_type', request()->get('by_taxo_type_id'));
      }
      if (request()->has('only_parent') && request()->get('only_parent'))
      {   
        
          $query->where('parent', null);
      }
      if (request()->has('multi_taxo_type_id') && request()->get('multi_taxo_type_id'))
      {   
        
          $query->whereIn('taxonomy_type', request()->get('multi_taxo_type_id'));
      }
      if (request()->has('by_status') && request()->get('by_status'))
      {   
          $query->where('taxonomy_status', request()->get('by_status'));
      }

    return $next($query);
  }
}

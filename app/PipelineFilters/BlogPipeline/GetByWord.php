<?php

namespace App\PipelineFilters\BlogPipeline;

use Closure;

class GetByWord
{

  public function handle($query, Closure $next)
  {
        $keyword = request()->get('keyword');
        $category = request()->get('category_id');

        if($keyword && $category) 
        {
          $query->where(function ($query)use ($keyword, $category)  {
              $query->where('title','like', '%' .$keyword.'%')->where('fk_category', $category);
          });
          
        }
        elseif($keyword && !$category)
        {
            $query->where(function ($query)use ($keyword)  {
                $query->where('title','like', '%' .$keyword.'%');
            });
        }
        elseif(!$keyword && $category)
        {
            $query->where(function ($query)use ($category)  {
                $query->where('fk_category', $category);
            });
        }


    return $next($query);
  }
}

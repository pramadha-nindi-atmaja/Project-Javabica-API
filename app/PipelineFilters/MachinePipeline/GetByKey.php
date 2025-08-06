<?php

namespace App\PipelineFilters\MachinePipeline;

use Closure;
use App\Models\User;

class GetByKey
{

  public function handle($query, Closure $next)
  { 

      if (request()->has('by_id') && request()->get('by_id'))
      {
          $query->where('id', request()->get('by_id'));
      }
      if (request()->has('by_email') && request()->get('by_email'))
      {
          $user = User::where('email', request()->get('by_email'))->first();
          $query->where('user_id', $user->id);
      }

    return $next($query);
  }
}
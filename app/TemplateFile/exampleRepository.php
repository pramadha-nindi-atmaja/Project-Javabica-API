<?php

namespace App\Repositories;

use App\Http\Controllers\BaseController;
use Illuminate\Pipeline\Pipeline;

use App\PipelineFilters\changeThis\GetByWord;
use App\PipelineFilters\changeThis\GetByKey;
use App\PipelineFilters\changeThis\UseSort;
use App\Interfaces\changethis;
use App\Models\changethis;
use App\Http\Resources\changethisResource\changethisResource;


class changeThis extends BaseController implements changeThis 
{
    public function show($request,$getOnlyColumn) {

    }
    public function store($data) {

    }
    public function update($id,$data) {
      
    }
    public function destroy($id) {

        
    }
   
}
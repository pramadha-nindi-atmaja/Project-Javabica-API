<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface LocationStoreInterface {

    public function show($request,$getOnlyColumn,$returnCollection);
    public function store(array $data,$returnCollection);
    public function update($id,array $data,$return_collection);
    public function destroy(int $id);
   
}

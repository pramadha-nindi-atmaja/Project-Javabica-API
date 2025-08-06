<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProductInterface {

    public function show($request,$getOnlyColumn,$collection);
    public function store(array $data,$returnCollection);
    public function update($id,array $data);
    public function destroy(int $id);
   

}

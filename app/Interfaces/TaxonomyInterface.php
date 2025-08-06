<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface TaxonomyInterface {

    public function show(Request $request,$getOnlyColumn);
    public function show_type();
    public function store(array $data,$returnCollection);
    public function update($id,array $request,$returnCollection);
    public function destroy(int $id);
   

}

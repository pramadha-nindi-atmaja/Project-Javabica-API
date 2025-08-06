<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface MachineInterface {

    public function show($request,$getOnlyColumn);
    public function show_all_machine($request,$getOnlyColumn);
    public function store(array $data,$returnCollection);
    public function update($id,array $data,$return_collection);
    public function destroy(int $id);

}

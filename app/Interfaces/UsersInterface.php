<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UsersInterface {

    public function show($request,array $getOnlyColumn);
    public function store(array $data,$returnCollection);
    public function update($id,array $data,$returnCollection);
    public function destroy($id);
}

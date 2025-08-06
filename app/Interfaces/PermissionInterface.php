<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PermissionInterface {

    public function show( $request,$getOnlyColumn);
    public function showUserPermission($request);
    public function store(array $data);
    public function update($id,array $data);
    public function destroy(int $id);
   

}

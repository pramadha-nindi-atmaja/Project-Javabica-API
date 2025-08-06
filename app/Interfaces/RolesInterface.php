<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface RolesInterface {

    public function showRole( $request,$getOnlyColumn);
    public function showPermissionFromRoles($roles_name);
    public function store(array $data);
    public function update($id,array $data);
    public function destroy(int $id);
   

}

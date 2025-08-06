<?php

namespace App\Services\RolesPermission;

use App\Models\User;
use App\Http\Controllers\BaseController;
use \Spatie\Permission\Exceptions\RoleDoesNotExist;
/**
 * Class RemoveRoleAdmin.php
 * @package App\Services
 */
class RemoveRoleWeb extends BaseController
{
    public function removeWeb($data) {

       
        $users = User::where('uuid',$data['user_uuid'])->first();
       
        if($users) {
          
            try
            {
                $role =  $users->removeRole($data['role_name']);

                if(!$role) {

                    return $this->handleArrayErrorResponse($role, 'Role name tidak di temukan','warning');
                } 

                return $this->handleArrayResponse($role, 'Roles successfuly remove from user','info');
            }
            catch(RoleDoesNotExist)
            {
                return $this->handleArrayErrorResponse($users, 'error when remove role from user / roles not found','warning');

            }
        }

        return $this->handleArrayErrorResponse($users, 'service-users not found, please try other id','warning');
    }
}

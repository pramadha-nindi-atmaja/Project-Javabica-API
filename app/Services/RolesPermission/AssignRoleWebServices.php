<?php

namespace App\Services\RolesPermission;

use App\Models\User;
use App\Http\Controllers\BaseController;
use \Spatie\Permission\Exceptions\RoleDoesNotExist;
/**
 * Class AssignRoleWebServices
 * @package App\Services
 */
class AssignRoleWebServices extends BaseController
{
    public function assignRoleWeb($data) {
       
        $users = User::where('uuid',$data['user_uuid'])->first();
            
        if($users) {
        
                try
                {
                    $role =  $users->assignRole($data['role_name']);
              
                    if(!$role) {

                        return $this->handleArrayErrorResponse($role, 'role name not found','info');
                    } 

                    return $this->handleArrayResponse($role, 'Roles insert to users successfuly registered','info');
                }
                catch(RoleDoesNotExist)
                {
                    $data = array([
                        'field' =>'assign-role-user',
                        'message' =>'role not found, please try other role'
                    ]);

                    return $this->handleArrayErrorResponse($data, 'error when insert role / roles not found','emergency');

                }

        }
      
            return $this->handleArrayErrorResponse($users, 'users  not found, please try other id','emergency');
       
    }
}

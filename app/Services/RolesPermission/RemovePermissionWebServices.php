<?php

namespace App\Services\RolesPermission;

use App\Http\Controllers\BaseController;
use \Spatie\Permission\Exceptions\RoleDoesNotExist;
use App\Models\User;

/**
 * Class RemovePermissionWebServices
 * @package App\Services
 */
class RemovePermissionWebServices extends BaseController
{
    public function RemovePermissionWeb($data) {

        $user = User::where('uuid',$data['user_uuid'])->first();
        if($user) {
            try
            {
                $role =  $user->revokePermissionTo($data['permission_name']);

                if(!$role) {

                    return $this->handleArrayErrorResponse($role, 'permission name not found','warning');
                } 

                return $this->handleArrayResponse($role, 'permission remove successfuly','info');
            }
            catch(RoleDoesNotExist)
            {
                $data = array([
                    'field' =>'remove-permission-user',
                    'message' =>'permission not found, please try other permission'
                ]);

                return $this->handleArrayErrorResponse($data, 'error when remove permission / permission not found','warning');

            }
        }
        else {
            return $this->handleArrayErrorResponse($user, 'web id not found','warning');
        }
    }
}

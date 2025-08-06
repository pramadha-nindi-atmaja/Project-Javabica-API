<?php

namespace App\Services\RolesPermission;

use App\Http\Controllers\BaseController;
use \Spatie\Permission\Exceptions\PermissionDoesNotExist;
use App\Models\User;

/**
 * Class AssignpermissionWeb
 * @package App\Services
 */
class AssignpermissionWeb extends BaseController 
{
public function assignPermissionWeb($data) {
    
        $user = User::where('uuid',$data['user_uuid'])->first();
       
        if($user) {
            
                try 
                {
                    $permission =  $user->givePermissionTo($data['permission_name']);

                    if($permission) {

                        return $this->handleArrayResponse($permission, ' assign permission users insert successfuly');
        
                    } else {

                        
                        
                        return $this->handleArrayErrorResponse($permission, 'assign permission users fail / not found','info');
                    }      
                } 
                catch(PermissionDoesNotExist $e) 
                {
                    $data = array([
                        'field' =>'assign-permission-user',
                        'message' =>'Permission not found, please try other permission'
                    ]);
                    return $this->handleArrayErrorResponse($data, 'permission not found','warning');
                }
        } 
      
        
            return $this->handleArrayErrorResponse($user, 'service - users not found, please try other id','warning');
       

    }   
}

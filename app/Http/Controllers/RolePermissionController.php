<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesPermissionRequest\AssignPermissionUserValidationRequest;
use App\Http\Requests\RolesPermissionRequest\AssignRoleUserValidationRequest;
use App\Http\Requests\RolesPermissionRequest\CreateRolesRequestValidation;
use App\Http\Requests\RolesPermissionRequest\GiveAndRemovePermissionToRoleRequestValidation;
use App\Http\Requests\RolesPermissionRequest\RemovePermissionUserValidationRequest;
use App\Http\Requests\RolesPermissionRequest\RemoveRoleUserValidationRequest;
use App\Http\Requests\RolesPermissionRequest\ShowPermissionFromRoleValidationRequest;
use App\Http\Requests\RolesPermissionRequest\ShowRolesRequestValidation;
use App\Http\Requests\RolesPermissionRequest\ShowUsersPermissionValidationRequest;
use App\Http\Requests\RolesPermissionRequest\ShowUsersRolesValidationRequest;
use App\Http\Requests\RolesPermissionRequest\ShowPermissionRequestValidation;
use App\Http\Resources\RolePermissionResource\PermissionsResource;
use App\Http\Resources\RolePermissionResource\RolesHasUserResource;
use App\Interfaces\PermissionInterface;
use App\Interfaces\RolesInterface;
use App\Models\Model_has_permission;
use App\Models\Model_has_Role;
use App\Models\User;
use App\Services\RolesPermission\RemovePermissionWebServices;
use App\Services\RolesPermission\AssignpermissionWeb;
use App\Services\RolesPermission\AssignRoleWebServices;
use App\Services\RolesPermission\RemoveRoleWeb;
use Spatie\Permission\Models\Role;

class RolePermissionController extends BaseController
{
    private $rolesInterface;
    private $permissionInterface;

    public function __construct(RolesInterface $rolesInterface,PermissionInterface $permissionInterface)
    {
        $this->rolesInterface            = $rolesInterface;
        $this->permissionInterface       = $permissionInterface;
    }
    /**
     * @lrd:start
     * # untuk memberikan role kepada user
     * @lrd:end
     */
     public function assignRoleUser(AssignRoleUserValidationRequest $request,AssignRoleWebServices $assignRoleWebServices) {
        
      
   
        $assignRoleWebServicesProcess = $assignRoleWebServices->assignRoleWeb($request->all());

         if($assignRoleWebServicesProcess['arrayStatus']) {
            
             return $this->handleResponse( $assignRoleWebServicesProcess['arrayResponse'],$assignRoleWebServicesProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),201);
         
         }
             return   $this->handleError($assignRoleWebServicesProcess['arrayResponse'],'assign role to user fail',$request->all(),str_replace('/','.',$request->path()),422);
           
         
     }
    /**
     * @lrd:start
     * # untuk menghapus role terhadap user
     * @lrd:end
     */
     public function removeRoleUser(RemoveRoleUserValidationRequest $request,RemoveRoleWeb $removeRoleWeb,Model_has_Role $modelHasRole) {
       
        //$this->authorize('model_has_role_destroy',$modelHasRole);

        $RemoveRoleWebProcess =  $removeRoleWeb->removeWeb($request->all());

         if($RemoveRoleWebProcess['arrayStatus']) {

             return $this->handleResponse( $RemoveRoleWebProcess['arrayResponse'],$RemoveRoleWebProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),201);
         
        
            } else {

                return   $this->handleError($RemoveRoleWebProcess['arrayResponse'] ,$RemoveRoleWebProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

    }

     /**
     * @lrd:start
     * # untuk menambahkan permission user secara direct
     * @lrd:end
     */
     public function assignPermissionUser(AssignPermissionUserValidationRequest $request,AssignpermissionWeb $assignpermissionWeb) {
        
     

         $assignpermissionWebProcess =  $assignpermissionWeb->assignPermissionWeb($request->all());

         if($assignpermissionWebProcess['arrayStatus']) {
           
            return $this->handleResponse( $assignpermissionWebProcess['arrayResponse'],$assignpermissionWebProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),201);

         } else {

            return   $this->handleError($assignpermissionWebProcess['arrayResponse'] ,$assignpermissionWebProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
      
         }
    }
    /**
     * @lrd:start
     * # untuk menghapus permission yang sifatnya direct
     * @lrd:end
     */
     public function removePermissionUser(RemovePermissionUserValidationRequest $request,RemovePermissionWebServices $removePermissionWebServices) {
         
      

         $removePermissionWebServicesProcess =  $removePermissionWebServices->RemovePermissionWeb($request->all());

         if($removePermissionWebServicesProcess['arrayStatus']) {

             return $this->handleResponse( $removePermissionWebServicesProcess['arrayResponse'],$removePermissionWebServicesProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),201);

            } else {
             return   $this->handleError($removePermissionWebServicesProcess['arrayResponse'] ,$removePermissionWebServicesProcess['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }
    }
    /**
     * @lrd:start
     * # ini digunakan untuk menambahkan atau menghapus permission dari roles has permissions, 
     * jalankan ini apabila tidak bisa
     * php artisan cache:forget spatie.permission.cache 
     * php artisan cache:clear
     * @lrd:end
     */
   public function giveOrRemovePermissionToRole(GiveAndRemovePermissionToRoleRequestValidation $request) {

        $role = Role::findByName($request->roles_name);
        
        if($request->is_create_permission_to_roles == true) {
           
            $givePermissions =  $role->givePermissionTo($request->permission_name);
            
            if($givePermissions) {
                return $this->handleResponse( $givePermissions,'insert permission into role success',$request->all(),str_replace('/','.',$request->path()),201);
            }
            else {
                $data = array([
                    'field' =>'give-permission-to-role',
                    'message' =>'insert permission into role fail / permission not found'
                ]);
                return   $this->handleError($data ,'insert permission into role error',$request->all(),str_replace('/','.',$request->path()),422);
            }
        }
        else {
            $removePermission =   $role->revokePermissionTo($request->permission_name);

            if($removePermission) {

                return $this->handleResponse( $removePermission,'remove permission into role success',$request->all(),str_replace('/','.',$request->path()),201);
            }
            else {

                $data = array([
                    'field' =>'give-permission-to-role',
                    'message' =>'remove permission into role fail / permission not found'
                ]);
                return   $this->handleError($data ,'remove permission into role error',$request->all(),str_replace('/','.',$request->path()),422);
            }
        }
      
    }
    /**
     * 
     * @lrd:start
     * # getDirectPermissions untuk " get all permission when admin select direct permission "
     * # getPermissionsViaRoles untuk "  All permissions which apply on the user (inherited and direct) "
     * # getAllPermissions untuk " All permissions which apply on the user (inherited and direct)"
     * @lrd:end
     */
    public function showUsersPermission(ShowUsersPermissionValidationRequest $request) {

        $user = User::where('uuid',$request->user_uuid)->first();

        if($user)
        {
            if($request->show_type == 'getDirectPermissions') {
                
                // $getPermission =   $user->getDirectPermissions();
                $showPermission = $this->permissionInterface->showUserPermission($request->all());
                if($showPermission['queryStatus']) {

                    return $this->handleResponse( $showPermission['queryResponse'],'show permission success',$request->all(),str_replace('/','.',$request->path()),201);
                }
                else {

                    $data = array([
                        'field' =>'show-user-permission',
                        'message' =>'show user permission fail'
                    ]);


                    return  $this->handleError($data,$showPermission['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
                }
                
            }
            else if($request->show_type == 'getPermissionsViaRoles') {
               
                $getPermission =   $user->getPermissionsViaRoles();
               
            }
            else if($request->show_type == 'getAllPermissions') {

                $getPermission =   $user->getAllPermissions();
                  
            }

            if($getPermission) {
                if($request->use_pluk_permission_name == true) {
                    $getPermission = $getPermission->pluck('name');
                }
                else {
                    $getPermission = $getPermission->map(function($item) {  
                            
                            return new PermissionsResource([
                                'data' => $item,
                                'status' => true
                            ]);
                    });
                }

                return $this->handleResponse($getPermission,'show user permission success',$request->all(),str_replace('/','.',$request->path()),201);
            
            }
            else {
                
                $data = array([
                    'field' =>'show-permission-user',
                    'message' =>'something error when show user permission'
                ]);

                return   $this->handleError($data,'show user permission error',$request->all(),str_replace('/','.',$request->path()),422);
            
            }
        }

        $data = array([
            'field' =>'show-permission-user',
            'message' =>'user not found'
        ]);

        return   $this->handleError($data,'user not found',$request->all(),str_replace('/','.',$request->path()),422);
            
    }
    /**
     * @lrd:start
     * # untuk melihat daftar roles apa saja yang dimiliki suatu user
     * @lrd:end
     */
    public function showUserRoles(ShowUsersRolesValidationRequest $request) {
        
        $user = User::where('uuid',$request->user_uuid)->first();
        if($user)
        {
            $getdata =   $user->getRoleNames()->map(function($item) {  

                return new RolesHasUserResource([
                    'data' => $item,
                    'status' => true
                ]);

            }); 
            
            return $this->handleResponse($getdata,'get user roles success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        $data = array([
            'field' =>'show-permission-user',
            'message' =>'user not found'
        ]);

        return   $this->handleError($data,'user not found',$request->all(),str_replace('/','.',$request->path()),422);
       
    }

     /**
     * @lrd:start
     * # untuk melihat daftar permission berdasarkan id role terdiri dari 3 option output
     * @lrd:end
     */
    public function showPermissionFromRole(ShowPermissionFromRoleValidationRequest $request) {
        
       $showRoles = $this->rolesInterface->showPermissionFromRoles($request->roles_id);
       
        if($showRoles['queryStatus']) {

            return $this->handleResponse( $showRoles['queryResponse'],$showRoles['queryMessage'],$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            return   $this->handleError($showRoles['queryResponse'],$showRoles['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
       
    }
    /**
     * @lrd:start
     * # untuk melihat daftar role yang terdaftar di  database
     * @lrd:end
     */
    public function showRole(ShowRolesRequestValidation $request) {

        $selectedColumn = array('*');
        $showRoles = $this->rolesInterface->showRole($request->all(),$selectedColumn);
        
        if($showRoles['queryStatus']) {

            return $this->handleResponse( $showRoles['queryResponse'],'show role success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            return   $this->handleError($showRoles['queryResponse'],$showRoles['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
     /**
     * @lrd:start
     * # untuk menambahkan role di database
     * @lrd:end
     */
    public function createRole(CreateRolesRequestValidation $request) {
        $data = array(
            'name'  => $request->roles_name
        );

        $role = Role::create($data);

        if($role) {

            return $this->handleResponse($role,'show role success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data = array([
                'field' =>'crate-role',
                'message' =>'create role fail'
            ]);

            return   $this->handleError($data,'show role fail',$request->all(),str_replace('/','.',$request->path()),422);
        }
    }
     /**
     * @lrd:start
     * # untuk melihat daftar permission yang terdaftar di  database
     * @lrd:end
     */
    public function showPermission(ShowPermissionRequestValidation $request) {

        $selectedColumn = array('*');

        $showPermission = $this->permissionInterface->show($request->all(),$selectedColumn);
       
        if($showPermission['queryStatus']) {

            return $this->handleResponse( $showPermission['queryResponse'],'show permission success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data = array([
                'field' =>'show-permission',
                'message' =>'show permission fail'
            ]);


            return  $this->handleError($data,$showPermission['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
}

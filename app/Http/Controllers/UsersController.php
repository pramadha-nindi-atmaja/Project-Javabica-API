<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UsersRequest\UsersCreateValidation;
use App\Http\Requests\UsersRequest\UsersDestroyValidation;
use App\Http\Requests\UsersRequest\UsersGetRequest;
use App\Http\Requests\UsersRequest\UsersUpdateValidation;
use App\Models\User;
use App\Interfaces\UsersInterface;
use App\Services\RolesPermission\AssignRoleWebServices;
use App\Services\AuthServices\LoginService;
class UsersController extends BaseController
{
    private $usersInterfaces;
    private $LoginService;
    public function __construct(UsersInterface $usersInterfaces, LoginService $LoginService)
    {
            $this->usersInterfaces            = $usersInterfaces;
            $this->LoginService               = $LoginService;
    }
    
    /**
     * @lrd:start
     * #  country phone  adalah jenis kode nomer telefon berdasarkan negara, contoh seperti +62,+60, etc
     * kode tersebut berdasarkan format formatE164
     *
     * saat ini yang dapat di passing adalah kode phone Indonesia yaitu +62 dengan kode ID, sehingga pada 
     * contry_phone masukan parameter ID
     *
     * @lrd:end
     */
    public function create(UsersCreateValidation $request,AssignRoleWebServices $assignRoleWebServices) { //done

     
        
        
         $create =  $this->usersInterfaces->store($request->except(['country_phone']),'showBasic');
        
        if($create['queryStatus']) {

                //assign role
                $data =array(
                    'user_uuid' => $create['queryResponse']['data']->uuid,
                    'role_name' =>'users'
                );

                $assignRoleWebServices->assignRoleWeb($data);

                //login user
                $loginUser = $this->LoginService->emailPassword($request->only('email','password'));

                $data = array(
                    'user'         => $create['queryResponse'],
                    'login_detail' => $loginUser['arrayResponse']
                );

                $reformatRequest = $request->all();
                $reformatRequest['password'] = '[protected]';

                return $this->handleResponse( $data,'Create users success',$reformatRequest,str_replace('/','.',$request->path()),201);
         
             } else {

                $data  = array([
                    'field' =>'create-user',
                    'message'=> 'users create failed'
                ]);

             return   $this->handleError($data,$create['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
          }
      }
     /**
     * @lrd:start
     * # keyword untuk pencarian email dan nama
     *
     *
     * @lrd:end
     */
     public function show(UsersGetRequest $request,User $user) {
       
      

       if($request->collection_type == 'showAll') {

             $selectOnlyColumn = array('*');
        }

        $getData =  $this->usersInterfaces->show($request->all(),$selectOnlyColumn);
   
         if($getData['queryStatus']) {
            
             return $this->handleResponse($getData['queryResponse'],'show data user success',$request->all(),str_replace('/','.',$request->path()),200);
             
         } else {
            $data  = array([
                'field' =>'show-user',
                'message'=> 'error when show user'
            ]);
            return   $this->handleError($data,$getData['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }
     }
       
     public function destroy(UsersDestroyValidation $request,User $user) { //done
        
      //$this->authorize('destroy',$user);

      $destroy =  $this->usersInterfaces->destroy($request->user_id);

         if($destroy['queryStatus']) {
            
            $data  = array(
                'field' =>'destroy-user',
                'message'=> 'users successfuly destroyed'
            );

             return $this->handleResponse($data,'Destroy users success',$request->all(),str_replace('/','.',$request->path()),204);
         } else {

            $data  = array([
                'field' =>'destroy-user',
                'message'=> 'users destroyed failed'
            ]);

            return   $this->handleError($data,$destroy['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
         }

     }
     public function update(UsersUpdateValidation $request,User $user) { //done
       
        $updateUsers =  $this->usersInterfaces->update($request->user_id,$request->except(['country_phone','user_id']),'showBasic');

         if($updateUsers['queryStatus']) {
           
            $data  = array(
                'field' =>'update-user',
                'message'=> 'users successfuly updated'
            );

             return $this->handleResponse($data,'update users success',$request->all(),str_replace('/','.',$request->path()),201);

         } else {
            
            $data  = array([
                'field' =>'update-user',
                'message'=> 'update user fail'
            ]);

             return   $this->handleError( $data ,$updateUsers['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422); 
         }

     }
}

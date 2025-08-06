<?php

namespace App\Services\AuthServices;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\UsersInterface;

/**
 * Class ChangePasswordService
 * @package App\Services
 */
class ChangePasswordService extends BaseController
{

    private $usersInterfaces;

    public function __construct(UsersInterface $usersInterfaces)
    {
         
            $this->usersInterfaces = $usersInterfaces;
    }

    public function updatePassword($usersData,$data) {

        $checkCurrPassword =  Hash::check($data['current_password'], $usersData['registed_password']);

        if(!$checkCurrPassword) {

            return $this->handleArrayErrorResponse($checkCurrPassword, 'service-current password is wrong,please try again');
        }
        $data = array(
            'password' => Hash::make($data['password'])
        );
        $updatePasswordAdmin =   $this->usersInterfaces->update($usersData['user_id'],$data);

        return $this->handleArrayResponse($updatePasswordAdmin['queryResponse'],'service-update password success');
    }
   
}

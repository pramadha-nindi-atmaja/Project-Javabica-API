<?php

namespace App\Services\AuthServices;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;


class LoginService  extends BaseController
{

    public function emailPassword($datas) {

        if(Auth::attempt(['email' => $datas['email'], 'password' => $datas['password']]))
        {
            $auth                = Auth::user();

            $success['token']    =  $auth->createToken('loginAsUsers', ['api_access'])->plainTextToken;
            $success['name']     =  $auth->name;
            $success['verified_at']     =  $auth->email_verified_at;
           // $success['is_guest']        =  $auth->email_verified_at;

            return $this->handleArrayResponse($success,'success login users');
        }
        else
        {
            return $this->handleArrayErrorResponse('unauthenticate','service - email or password users not correct');
        }
    }
    public function phonePassword($datas) {

        if(Auth::attempt(['phone' => $datas['phone'], 'password' => $datas['password']]))
        {
            $auth                = Auth::user();

            $success['token']    =  $auth->createToken('loginAsUsers', ['api_access'])->plainTextToken;
            $success['name']     =  $auth->name;

            return $this->handleArrayResponse($success,'success with phone login users');
        }
        else
        {
            return $this->handleArrayErrorResponse('unauthenticate','service - phone or password users not correct');
        }
    }
   
}

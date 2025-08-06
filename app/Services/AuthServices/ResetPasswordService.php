<?php

namespace App\Services\AuthServices;

use App\Http\Controllers\BaseController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
/**
 * Class ResetPassword
 * @package App\Services
 */
class ResetPasswordService extends BaseController
{
   
    public function resetPassword($request) {

        $status = Password::sendResetLink(
            $request->only('email')
        );
       
        if($status == Password::RESET_LINK_SENT) {

            return $this->handleArrayResponse($status,'reset password users success','info');
           
        }
        throw ValidationException::withMessages([
            'email' =>[trans($status)]
        ]);

    }
    public function resetNewPassword($request) {
        
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));

            });
            

            if($status == Password::PASSWORD_RESET) {

                return $this->handleArrayResponse($status,'Reset new password suceess','info');
                
            }

            return $this->handleArrayErrorResponse($status,'service - Reset new password Fail','emergency');

    }
    
}

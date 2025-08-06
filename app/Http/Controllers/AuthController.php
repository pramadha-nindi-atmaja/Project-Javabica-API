<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest\LoginUsersRequest;
use App\Http\Requests\AuthRequest\ValidateVerificationAccountRequestUsers;
use App\Interfaces\UsersInterface;
use App\Mail\AccountVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthServices\LoginService;
use App\Services\AuthServices\ResetPasswordService;
use App\Http\Requests\AuthRequest\ResetPasswordRequest;
use App\Http\Requests\AuthRequest\ResetNewPasswordRequest;
use App\Http\Requests\AuthRequest\UpdateProfileUsersRequest;
use App\Http\Requests\UsersRequest\ChangePasswordRequest;
use App\Services\AuthServices\ChangePasswordService;
use App\Services\OtpServices;
use Illuminate\Support\Facades\Mail;


class AuthController extends BaseController
{

    private $LoginService;
    private $ResetPasswordService;
    private $ChangePasswordService;
    private $OtpServices;
    private $usersInterfaces;


    public function __construct(
        LoginService $LoginService,
        ResetPasswordService $ResetPasswordService,
        ChangePasswordService $ChangePasswordService,   
        OtpServices  $OtpServices,
        UsersInterface $usersInterfaces
    )
    {
            $this->LoginService               = $LoginService;
            $this->ResetPasswordService       = $ResetPasswordService;
            $this->ChangePasswordService      = $ChangePasswordService;
            $this->OtpServices                = $OtpServices;
            $this->usersInterfaces            = $usersInterfaces;

    }
     //==============login auth purpose
     public function loginDocs(Request  $request) {


        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('apps')->attempt($credentials)) {
          
            $request->session()->regenerate();
          
            return redirect('system-app/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }
    public function logoutDocs(Request $request)
    {
        Auth::guard('apps')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    //============================end of auth docs purpose
    
    
   
    public function logout(Request $request)
    {

        auth('sanctum')->user()->currentAccessToken()->delete();

        $data  = array(
            'field'=>'logout',
            'message'=>'logout account success'
        );

        return  $this->handleResponse($data,'logout users success',$request->all(),str_replace('/','.',$request->path()),200);
    
    }

    public function resetPassword(ResetPasswordRequest $request ) { //ok
        
        $resetPass =  $this->ResetPasswordService->resetPassword($request);
        
        if($resetPass['arrayStatus']) {

            $data  = array(
                'field' =>'reset-password',
                'message'=> 'reset password link to email success'
            );

            return $this->handleResponse($data,'reset link password success',$request->all(),str_replace('/','.',$request->path()),200);
        }
        else {
            $data  = array([
                'field' =>'reset-password',
                'message'=>'something wrong when sending link update password'
            ]);
            return   $this->handleError( $data,'reset link password fail',$request->all(),str_replace('/','.',$request->path()),422);
        }
        
    }

    public function resetNewPassword(ResetNewPasswordRequest $request)  { //ok

        $resetNewPass =   $this->ResetPasswordService->resetNewPassword($request);

        if($resetNewPass['arrayStatus']) {
            $data  = array(
                'field' =>'reset-new-password',
                'message'=> 'new password successfuly saved'
            );

            return $this->handleResponse($data,'reset new password success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {

            $data  = array([
                'field' =>'reset-new-password',
                'message'=> 'error when reset new password, please try again'
            ]);
            
             return   $this->handleError($data,$resetNewPass['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }

    /**
     * @lrd:start
     * # routes email verification hanya dapat dilakukan 1 menit sekali, konfigurasinya ada di route service provider
     * # email verification menggunakan 4 digit dan harus dalam keadaan login
     *
     * @lrd:end
     */
    public function AccountVerificationEmail(Request $request) { //ok

        $checkToGetData = auth('sanctum')->user();

       
            if($request->user()->hasVerifiedEmail()) {
                $data  = array(
                    'field' =>'check-user-verification',
                    'message' =>'your email has been verified'
                );
                return $this->handleResponse($data,'email has been verify',$request->all(),str_replace('/','.',$request->path()),200);
            }

            $getOtp =  $this->OtpServices->generateOTP($request->user()->email);

            if($getOtp['arrayStatus']) {

                    //send email
                    $data = array(
                        'OtpToken'   => $getOtp['arrayResponse']['otp'],
                        'email'      => $request->user()->email,
                        'name'       => $request->user()->name,
                        'expiredAt'  => $getOtp['arrayResponse']['expiredOTP']
                    );

                    try {
                            $sendMail =  Mail::to($request->user()->email)->queue(new AccountVerificationMail($data));
                            if($sendMail) {
                            $data = array(
                                'field' => 'sent-OTP-by_email',
                                'message'=> 'Verification TOKEN has been send to your email',
                            );
                                return $this->handleResponse($data,'OTP sent via email success',$request->all(),str_replace('/','.',$request->path()),200);
                            }
                    }
                    catch (Throwable $e) {
                        $data = array([
                            'field' => 'sent-OTP-by_email',
                            'message'=> 'Verification TOKEN fail to sen to your email',
                        ]);
                        return   $this->handleError($data,'OTP sent via email fail',$request->all(),str_replace('/','.',$request->path()),500);
                    }

            }

            $data  = array([
                'field' =>'generate-otp-token',
                'message'=> 'generate OTP fail'
            ]);

            return   $this->handleError($data,$getOtp['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
    }
    
    public function validateVerificationAccount(ValidateVerificationAccountRequestUsers $request)  
    {

        $checkToGetDataUsers = auth('sanctum')->user();

        if(Auth::user()->hasVerifiedEmail()) {
            
            return $this->handleResponse($checkToGetDataUsers,'account has been verify',$request->all(),str_replace('/','.',$request->path()),200);
        }

        $getOtp = $request->otp;
        $validate =  $this->OtpServices->validateOTP($getOtp,$request->user()->email);

        if($validate['arrayStatus']) {
            
            $payload = array(
                'email_verified_at' => now(),
            );


            $updateUser =      $this->usersInterfaces->update($checkToGetDataUsers->id,$payload);


            if(!$updateUser['queryStatus']) {

                $data  = array([
                    'field' =>'account-validate-email-OTP-fail',
                    'message'=> 'OTP validate account fail'
                ]);

                return   $this->handleError($data,$updateUser['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
            }

            $data  = array(
                'field' =>'account-validate-email-OTP-success',
                'message'=> 'validate account using OTP success'
            );

            return $this->handleResponse($data,'OTP validate account success',$request->all(),str_replace('/','.',$request->path()),201);

        }

        $data  = array([
            'field' =>'account-validate-email-OTP-Expired',
            'message'=> 'oops your OTP has been wrong / expired please try again'
        ]);

        return   $this->handleError($data,$validate['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422,'info');

    }
   
    /**
     * @lrd:start
     * # apabila login dari halaman ini, maka secara otomatis sistem akan menaro token dalam session sehingga tidak perlu dimasukan dalam bearer yang tertera  di atas, 
     * dan sistem akan mengambil session yang paling terakhir, sehingga apabila ingin authnya berdasarkan token, maka dapatkan token/login dari platform lain seperti postman lalu taro tokennya di bearear di atas kanan
     *
     * @lrd:end
     */
    public function login(LoginUsersRequest $request)
    {   
        if($request->isEmail == false){

            $loginUser = $this->LoginService->phonePassword($request->only('phone','password'));
        }
        else {
            $loginUser = $this->LoginService->emailPassword($request->only('email','password'));
        }
       
        if($loginUser['arrayStatus']) {
            
            $reformatRequest                 = $request->all();
            $reformatRequest['password']     = '[protected]';

            return  $this->handleResponse($loginUser['arrayResponse'],'login success',$reformatRequest,str_replace('/','.',$request->path()),200);
        }
        else {
            $data  = array([
                'field' =>'login',
                'message'=> 'username/password is not correct'
            ]);
            return   $this->handleError($data,$loginUser['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);

        }
    }
     public function changePassword(ChangePasswordRequest $request)  //done
    {

        $userdata = array(
            'registed_password' =>auth('sanctum')->user()->password,
            'user_id'           =>auth('sanctum')->user()->id,
        );

        $resetPass  =   $this->ChangePasswordService->updatePassword($userdata,$request->only('password','current_password'));
    
        if($resetPass['arrayStatus']) {
            $data  = array([
                'field' =>'change-password',
                'message'=> 'perubahan password baru berhasil'
            ]);
            $reformatRequest = $request->all();
            $reformatRequest['password']                 = '[protected]';
            $reformatRequest['password_confirmation']    = '[protected]';
            $reformatRequest['current_password']         = '[protected]';
            return  $this->handleResponse($data,'update password users success',$reformatRequest,str_replace('/','.',$request->path()),201);
        }
        else {
            
            $data  = array([
                'field' =>'change-password',
                'message'=> 'current password tidak benar, silahkan coba kembali'
            ]);

            
            return   $this->handleError($data,$resetPass['arrayMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }

    }
    public function profile(Request $request) //done
    {
       
        $request->request->add([
                'by_id' => auth('sanctum')->user()->id,
                'collection_type'=>'showBasic'
        ]);

        $columnRequest  = array('*');
        $getUserProfile =  $this->usersInterfaces->show($request->all(),$columnRequest);

        if($getUserProfile['queryStatus']) {

            return  $this->handleResponse($getUserProfile['queryResponse'],'show my profile users success',$request->all(),str_replace('/','.',$request->path()),200);
        }

        return $getUserProfile;
           
    }
    public function updateProfile(UpdateProfileUsersRequest $request) //done
    {
        
        $updateUser = $this->usersInterfaces->update(auth('sanctum')->user()->id,$request->except(['email']),'showBasic');

        if($updateUser['queryStatus']) {

            return $this->handleResponse($updateUser['queryResponse'],'update users success',$request->all(),str_replace('/','.',$request->path()),201);
        }
        else {
            $data  = array([
                'field' =>'update-profile',
                'message'=>'update profile tidak berhasil'
            ]);

            return   $this->handleError($data,$updateUser['queryMessage'],$request->all(),str_replace('/','.',$request->path()),422);
        }
       
    }
}

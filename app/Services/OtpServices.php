<?php

namespace App\Services;
use Carbon\Carbon;
use Tzsk\Otp\Facades\Otp;
use App\Http\Controllers\BaseController;


/**
 * Class OtpServices
 * @package App\Services
 * ingin melihat dokumentasi klik disini https://github.com/tzsk/otp
 */
class OtpServices extends BaseController
{
    private $OtpExpiredMinute;
    private $OtpTotalDigit;

    public function __construct()
    {
        $this->OtpExpiredMinute = 15; //in minute
        $this->OtpTotalDigit    = 6; //total digit
    }

    public function validateOTP($otp,$unique_secret) {

       $validate =  Otp::digits($this->OtpTotalDigit)->expiry($this->OtpExpiredMinute)->check($otp, $unique_secret);

       if(!$validate) {

            //if envoronment development has a special otp number
            if(config('app.env') == 'local' || config('app.env') == 'staging' )
            {
                if($otp == '121212') {
                    return $this->handleArrayResponse($validate,'service validate OTP success','info');
                }
            }
           
           return $this->handleArrayErrorResponse($validate,'service validate OTP fail / expired','info');
       }

        return $this->handleArrayResponse($validate,'service validate OTP success','info');
    }

    public function generateOTP($unique_secret) {

       $generateOtp    = Otp::digits($this->OtpTotalDigit)->expiry($this->OtpExpiredMinute)->generate($unique_secret);

       $requestOtp     = date('Y-m-d H:i:s');
       $expiredOtp     = strtotime(''.$requestOtp.' + '.$this->OtpExpiredMinute.' minute');
       $dateFormatExp  = date('Y-m-d H:i:s', $expiredOtp);

        if(!$generateOtp) {

            return $this->handleArrayErrorResponse($generateOtp,'service generate OTP fail','emergency');
        }

        $data = array(
            'otp'        => $generateOtp,
            'requestOTP' => $requestOtp,
            'expiredOTP' => $dateFormatExp
        );

        return $this->handleArrayResponse($data,'service generate OTP success','info');

    }
}

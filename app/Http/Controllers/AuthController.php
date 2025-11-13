<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest\{
    LoginUsersRequest,
    ValidateVerificationAccountRequestUsers,
    ResetPasswordRequest,
    ResetNewPasswordRequest,
    UpdateProfileUsersRequest
};
use App\Http\Requests\UsersRequest\ChangePasswordRequest;
use App\Interfaces\UsersInterface;
use App\Mail\AccountVerificationMail;
use App\Services\AuthServices\{
    LoginService,
    ResetPasswordService,
    ChangePasswordService
};
use App\Services\OtpServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Mail
};
use Throwable;

class AuthController extends BaseController
{
    protected LoginService $loginService;
    protected ResetPasswordService $resetPasswordService;
    protected ChangePasswordService $changePasswordService;
    protected OtpServices $otpServices;
    protected UsersInterface $usersInterface;

    public function __construct(
        LoginService $loginService,
        ResetPasswordService $resetPasswordService,
        ChangePasswordService $changePasswordService,
        OtpServices $otpServices,
        UsersInterface $usersInterface
    ) {
        $this->loginService = $loginService;
        $this->resetPasswordService = $resetPasswordService;
        $this->changePasswordService = $changePasswordService;
        $this->otpServices = $otpServices;
        $this->usersInterface = $usersInterface;
    }

    // ==================== LOGIN AUTH FOR DOCS ====================

    public function loginDocs(Request $request)
    {
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

    // ==================== LOGOUT ====================

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        $data = [
            'field' => 'logout',
            'message' => 'Logout account success'
        ];

        return $this->handleResponse(
            $data,
            'Logout user success',
            $request->validated() ?? $request->all(),
            str_replace('/', '.', $request->path()),
            200
        );
    }

    // ==================== PASSWORD RESET ====================

    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetPass = $this->resetPasswordService->resetPassword($request);

        if ($resetPass['arrayStatus']) {
            $data = [
                'field' => 'reset-password',
                'message' => 'Reset password link sent successfully'
            ];

            return $this->handleResponse(
                $data,
                'Reset link password success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'reset-password',
            'message' => 'Failed to send password reset link'
        ]];

        return $this->handleError(
            $data,
            'Reset link password failed',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    public function resetNewPassword(ResetNewPasswordRequest $request)
    {
        $resetNewPass = $this->resetPasswordService->resetNewPassword($request);

        if ($resetNewPass['arrayStatus']) {
            $data = [
                'field' => 'reset-new-password',
                'message' => 'New password successfully saved'
            ];

            return $this->handleResponse(
                $data,
                'Reset new password success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'reset-new-password',
            'message' => 'Failed to reset new password, please try again'
        ]];

        return $this->handleError(
            $data,
            $resetNewPass['arrayMessage'] ?? 'Unknown error',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    // ==================== EMAIL VERIFICATION ====================

    /**
     * @lrd:start
     * # Email verification can only be requested once per minute.
     * # The verification uses a 4-digit OTP and requires the user to be logged in.
     * @lrd:end
     */
    public function accountVerificationEmail(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user->hasVerifiedEmail()) {
            $data = [
                'field' => 'check-user-verification',
                'message' => 'Your email has already been verified'
            ];

            return $this->handleResponse(
                $data,
                'Email already verified',
                [],
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $otp = $this->otpServices->generateOTP($user->email);

        if ($otp['arrayStatus']) {
            $mailData = [
                'OtpToken' => $otp['arrayResponse']['otp'],
                'email' => $user->email,
                'name' => $user->name,
                'expiredAt' => $otp['arrayResponse']['expiredOTP']
            ];

            try {
                Mail::to($user->email)->queue(new AccountVerificationMail($mailData));

                $data = [
                    'field' => 'send-otp-email',
                    'message' => 'Verification token sent to your email'
                ];

                return $this->handleResponse(
                    $data,
                    'OTP sent via email success',
                    [],
                    str_replace('/', '.', $request->path()),
                    200
                );
            } catch (Throwable $e) {
                $data = [[
                    'field' => 'send-otp-email',
                    'message' => 'Failed to send verification token via email'
                ]];

                return $this->handleError(
                    $data,
                    'OTP email sending failed',
                    [],
                    str_replace('/', '.', $request->path()),
                    500
                );
            }
        }

        $data = [[
            'field' => 'generate-otp-token',
            'message' => 'Failed to generate OTP'
        ]];

        return $this->handleError(
            $data,
            $otp['arrayMessage'] ?? 'Unknown error',
            [],
            str_replace('/', '.', $request->path()),
            422
        );
    }

    public function validateVerificationAccount(ValidateVerificationAccountRequestUsers $request)
    {
        $user = auth('sanctum')->user();

        if ($user->hasVerifiedEmail()) {
            return $this->handleResponse(
                $user,
                'Account already verified',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $validate = $this->otpServices->validateOTP($request->otp, $user->email);

        if ($validate['arrayStatus']) {
            $payload = ['email_verified_at' => now()];

            $updateUser = $this->usersInterface->update($user->id, $payload);

            if (!$updateUser['queryStatus']) {
                $data = [[
                    'field' => 'account-validate-email-otp-fail',
                    'message' => 'Failed to verify account with OTP'
                ]];

                return $this->handleError(
                    $data,
                    $updateUser['queryMessage'] ?? 'Unknown error',
                    $request->validated(),
                    str_replace('/', '.', $request->path()),
                    422
                );
            }

            $data = [
                'field' => 'account-validate-email-otp-success',
                'message' => 'Account successfully verified using OTP'
            ];

            return $this->handleResponse(
                $data,
                'OTP account verification success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'account-validate-email-otp-expired',
            'message' => 'Your OTP is invalid or expired, please try again'
        ]];

        return $this->handleError(
            $data,
            $validate['arrayMessage'] ?? 'Invalid OTP',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    // ==================== LOGIN ====================

    /**
     * @lrd:start
     * # Login from this endpoint automatically stores the token in session.
     * # If using API token manually, obtain it from Postman or other platforms.
     * @lrd:end
     */
    public function login(LoginUsersRequest $request)
    {
        $loginUser = $request->boolean('isEmail')
            ? $this->loginService->emailPassword($request->only('email', 'password'))
            : $this->loginService->phonePassword($request->only('phone', 'password'));

        if ($loginUser['arrayStatus']) {
            $sanitizedRequest = $request->all();
            $sanitizedRequest['password'] = '[protected]';

            return $this->handleResponse(
                $loginUser['arrayResponse'],
                'Login success',
                $sanitizedRequest,
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'login',
            'message' => 'Incorrect username or password'
        ]];

        return $this->handleError(
            $data,
            $loginUser['arrayMessage'] ?? 'Login failed',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    // ==================== CHANGE PASSWORD ====================

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth('sanctum')->user();

        $userData = [
            'registed_password' => $user->password,
            'user_id' => $user->id,
        ];

        $resetPass = $this->changePasswordService->updatePassword(
            $userData,
            $request->only('password', 'current_password')
        );

        if ($resetPass['arrayStatus']) {
            $data = [[
                'field' => 'change-password',
                'message' => 'Password updated successfully'
            ]];

            $sanitized = $request->all();
            $sanitized['password'] = '[protected]';
            $sanitized['password_confirmation'] = '[protected]';
            $sanitized['current_password'] = '[protected]';

            return $this->handleResponse(
                $data,
                'Update user password success',
                $sanitized,
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'change-password',
            'message' => 'Incorrect current password'
        ]];

        return $this->handleError(
            $data,
            $resetPass['arrayMessage'] ?? 'Change password failed',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    // ==================== PROFILE ====================

    public function profile(Request $request)
    {
        $request->merge([
            'by_id' => auth('sanctum')->user()->id,
            'collection_type' => 'showBasic',
        ]);

        $getUserProfile = $this->usersInterface->show($request->all(), ['*']);

        if ($getUserProfile['queryStatus']) {
            return $this->handleResponse(
                $getUserProfile['queryResponse'],
                'Show profile success',
                $request->all(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        return $this->handleError(
            [],
            $getUserProfile['queryMessage'] ?? 'Failed to get user profile',
            $request->all(),
            str_replace('/', '.', $request->path()),
            422
        );
    }

    public function updateProfile(UpdateProfileUsersRequest $request)
    {
        $updateUser = $this->usersInterface->update(
            auth('sanctum')->user()->id,
            $request->except(['email']),
            'showBasic'
        );

        if ($updateUser['queryStatus']) {
            return $this->handleResponse(
                $updateUser['queryResponse'],
                'Update user profile success',
                $request->validated(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [[
            'field' => 'update-profile',
            'message' => 'Failed to update user profile'
        ]];

        return $this->handleError(
            $data,
            $updateUser['queryMessage'] ?? 'Unknown error',
            $request->validated(),
            str_replace('/', '.', $request->path()),
            422
        );
    }
}

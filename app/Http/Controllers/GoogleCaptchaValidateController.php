<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleCaptchaValidateController extends BaseController
{
    /**
     * Validate Google reCAPTCHA token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCaptcha(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->token,
        ]);

        $result = $response->json();

        if (isset($result['success']) && $result['success'] === true) {
            return $this->handleResponse(
                $result,
                'Captcha verification successful',
                $request->all(),
                str_replace('/', '.', $request->path()),
                200
            );
        }

        $data = [
            'field' => 'captcha-validation',
            'message' => 'Captcha verification failed',
        ];

        return $this->handleError(
            $data,
            $result,
            $request->all(),
            str_replace('/', '.', $request->path()),
            422
        );
    }
}

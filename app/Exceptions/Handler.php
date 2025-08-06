<?php

namespace App\Exceptions;

use App\Http\Controllers\BaseController;
use Illuminate\Database\QueryException;
use \Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;
use  Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    
    protected function invalidJson($request, ValidationException $exception)
    {
        $response =  new BaseController;
        $error = $this->transformErrors($exception);
        return $response->handleError(
            $error, 
            $exception->getMessage(),
            $request->all(),
            str_replace('/','.',$request->path()),
            $exception->status
        );
    }
    

// transform the error messages,
    private function transformErrors(ValidationException $exception)
    {
      
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
           $errors[] = [
               'field' => $field,
               'message' => $message[0],
           ];
        }
        return $errors;
    }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            $response =  new BaseController;

            $errorMessage = array([
                'field'=>'spatie authorization',
                'message'=>'you dont have this abilities'
            ]);

            return $response->handleError(
                $errorMessage,
                'abilities error',
                $request->all(),
                str_replace('/','.',$request->path()),
                403,
                'warning'
                );
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            
            $response =  new BaseController;

            $errorMessage = array([
                'field'=>'authorization',
                'message'=>'you dont have this abilities'
            ]);

            return $response->handleError(
                $errorMessage,
                'abilities error',
                $request->all(),
                str_replace('/','.',$request->path()),
                403,
                'warning'
            );
        });

        $this->renderable(function (QueryException $e, $request) {
            
            $response =  new BaseController;

            $errorMessage = array([
                'field'=>'process error',
                'message'=>'please contact administrator for asistense'
            ]);

            return $response->handleError(
                $errorMessage,
                'systemError',
                $request->all(),
                str_replace('/','.',$request->path()),
                500,
                'error'
            );
        });

        $this->renderable(function (HttpException $e, $request) {

          $data =  Auth::user($request->header('Authorization'));

            if($data) {

                $errorMessage = array([
                    'field'=>'account_verification',
                    'message'=>'forbidden access, verify users access only'
                ]);
                if (!$request->user()->hasVerifiedEmail()) {
                        $response =  new BaseController;                 
                        return $response->handleError(
                            $errorMessage,
                            'user verification access only',
                            $request->all(),
                            str_replace('/','.',
                            $request->path()),
                            403,
                            'warning'
                        ); 
                  }
            }
            
        });       
    }

}

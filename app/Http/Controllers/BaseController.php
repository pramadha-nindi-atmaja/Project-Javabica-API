<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    /**
     * Handle successful API responses.
     */
    public function handleResponse(
        mixed $result,
        string $message,
        ?array $params = null,
        ?string $title = null,
        int $code = 200,
        string $logType = 'info'
    ) {
        $requestNo = uniqid() . rand(1, 99999);

        $response = [
            'success'    => 'OK',
            'code'       => $code,
            'request_no' => $requestNo,
            'timestamp'  => Carbon::now()->toDateTimeString(),
            'message'    => $message,
            'title'      => $title,
            'data'       => $result ?: [],
            'params'     => $params ?? [],
        ];

        $response['params']['requested'] = $this->getRequesterIdentifier();

        $this->logProcess($logType, $response, __FUNCTION__, $requestNo);

        return response()->json($response, $code);
    }

    /**
     * Handle failed API responses.
     */
    public function handleError(
        array $result = [],
        string $message = 'Error occurred',
        ?array $params = null,
        ?string $title = null,
        int $code = 404,
        string $logType = 'emergency'
    ) {
        $requestNo = uniqid() . rand(1, 99999);
        $errorTitle = $this->getErrorTitle($code);

        $response = [
            'success'    => $errorTitle,
            'code'       => $code,
            'request_no' => $requestNo,
            'timestamp'  => Carbon::now()->toDateTimeString(),
            'message'    => $message,
            'title'      => $title,
            'data'       => $result ?: [],
            'params'     => $params ?? [],
        ];

        $response['params']['requested'] = $this->getRequesterIdentifier();

        $this->logProcess($logType, $response, __FUNCTION__, $requestNo);

        return response()->json($response, $code);
    }

    /**
     * Return a standardized array success response (internal service use).
     */
    public function handleArrayResponse(mixed $response, string $message = 'Process success', string $logType = 'info'): array
    {
        $formatted = [
            'arrayStatus'   => true,
            'arrayMessage'  => $message,
            'arrayResponse' => $response,
        ];

        $this->logProcess($logType, $formatted, __FUNCTION__);
        return $formatted;
    }

    /**
     * Return a standardized array error response (internal service use).
     */
    public function handleArrayErrorResponse(mixed $response, string $message = 'Process failed', string $logType = 'emergency'): array
    {
        $formatted = [
            'arrayStatus'   => false,
            'arrayMessage'  => $message,
            'arrayResponse' => $response,
        ];

        $this->logProcess($logType, $formatted, __FUNCTION__);
        return $formatted;
    }

    /**
     * Return a standardized query success response.
     */
    public function handleQueryArrayResponse(array $response = [], string $message = 'Query success', string $logType = 'info'): array
    {
        $formatted = [
            'queryStatus'   => true,
            'queryMessage'  => $message,
            'queryResponse' => $response,
        ];

        $this->logProcess($logType, $formatted, __FUNCTION__);
        return $formatted;
    }

    /**
     * Return a standardized query error response.
     */
    public function handleQueryErrorArrayResponse(array $response = [], string $message = 'Query failed', string $logType = 'emergency'): array
    {
        $formatted = [
            'queryStatus'   => false,
            'queryMessage'  => $message,
            'queryResponse' => $response,
        ];

        $this->logProcess($logType, $formatted, __FUNCTION__);
        return $formatted;
    }

    /**
     * Identify the requester (authenticated user or public).
     */
    private function getRequesterIdentifier(): string
    {
        return auth('sanctum')->user()->uuid ?? 'public';
    }

    /**
     * Centralized logging handler.
     */
    private function logProcess(string $logType, array $response, string $method, ?string $requestNo = null): void
    {
        $uuid = $this->getRequesterIdentifier();
        $prefix = "{$uuid}-[ {$requestNo} ]-{$method}";

        if (!method_exists(Log::class, $logType)) {
            $logType = 'info';
        }

        Log::$logType($prefix, $response);
    }

    /**
     * Map HTTP status code to a short human-readable title.
     */
    private function getErrorTitle(int $code): string
    {
        return match ($code) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            default => 'Error',
        };
    }
}

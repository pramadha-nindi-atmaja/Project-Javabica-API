<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller ;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
refrence

https://medium.com/@mwaysolutions/10-best-practices-for-better-restful-api-cbe81b06f291

200 — OK — Eyerything is working
201 — OK — New resource has been created
201 — OK — New resource has been updated
204 — OK — The resource was successfully deleted

400 — Bad Request — The request was invalid or cannot be served. The exact error should be explained in the error payload. E.g. „The JSON is not valid“
401 — Unauthorized — The request requires an user authentication
403 — Forbidden — The server understood the request, but is refusing it or the access is not allowed.
404 — Not found — There is no resource behind the URI.
422 — Unprocessable Entity — Should be used if the server cannot process the enitity, e.g. if an image cannot be formatted or mandatory fields are missing in the payload.
429 — Too Many Requests response status code indicates the user has sent too many requests in a given amount of time ("rate limiting").
            
500 — Internal Server Error — API developers should avoid this error. If an error occurs in the global catch blog, the stracktrace should be logged and not returned as response.

*/
class BaseController extends Controller
{

    public function handleResponse($result, $msg,$params=null,$title=null,$code=200,$logType='info')
    { 
        $requestNo = uniqid().rand(1,99999);
    	$res = [
            'success'    => 'OK',
            'code'       => $code,
            'request_no' => $requestNo,
            'timestamp'  => Carbon::now()->toDateTimeString(),
            'message'    => $msg,
            'title'      => $title,
        
        ];
        if(!empty($result)){
            $res['data'] = $result;
        }
        else {
            $res['data'] = [];
        }
        
        $res['params'] = $params;

        $publicOrPrivate = $this->publicOrPrivate();
        $res['params']['requested'] = $publicOrPrivate;
      
        $this->logProcess($logType,$res,'handleResponse',$requestNo);
        $data = response()->json($res, 200);
      
        return $data;
    
    }

    public function handleError($result = [], $msg, $params=null,$title=null,$code = 404,$logType='emergency')
    {
        $requestNo = uniqid().rand(1,99999);
        $errorTitle = $this->ErrorCode($code);

    	$res = [
            'success'    => $errorTitle,
            'code'       => $code,
            'request_no' => $requestNo,
            'timestamp'  => Carbon::now()->toDateTimeString(),
            'message'    => $msg,
            'title'      => $title,
          
           
        ];
        if(!empty($result)){
            $res['data'] = $result;
        }

        $res['params']   = $params;
        $publicOrPrivate = $this->publicOrPrivate();
        
        $res['params']['request'] = $publicOrPrivate;
     
        $this->logProcess($logType,$res,'handleError',$requestNo);
        return response()->json($res, $code);
    }
    
    public function handleArrayResponse($response,$message='process success',$logType='info') {

        $response  = array(
            'arrayStatus'   => true,
            'arrayMessage'  => $message,
            'arrayResponse' => $response
        );

     
       $this->logProcess($logType,$response,'handleArrayResponse');
        return $response;

    }
    public function handleArrayErrorResponse($response,$message='process fail',$logtype='emergency') {

        $response  = array(
            'arrayStatus'    => false,
            'arrayMessage'   => $message,
            'arrayResponse'  => $response
        );

       
        $this->logProcess($logtype,$response,'handleArrayErrorResponse');
        return $response;

    }

    public function handleQueryArrayResponse($reponse =[],$message='query success',$logtype='info') {

        $response =  array(
            'queryStatus'       => true,
            'queryMessage'      => $message,
            'queryResponse'     => $reponse
        );
     
        $this->logProcess($logtype,$response,'handleQueryArrayResponse');
        return $response;
    }
    public function handleQueryErrorArrayResponse($reponse =[],$message='query fail',$logtype='emergency') {

        $response =  array(
            'queryStatus'       => false,
            'queryMessage'      => $message,
            'queryResponse'     => $reponse
        );

      
       $this->logProcess($logtype,$response,'handleQueryErrorArrayResponse');
        return $response;
    }
    private function publicOrPrivate() {
      
        if(auth('sanctum')->user()) {
            $getusersUUid = auth('sanctum')->user()->uuid;
        }
        else {
            $getusersUUid = 'publics';
        }

        return  $getusersUUid;
    }
    private function logProcess($logType,$response,$methodType,$request_numb=null) {

        $getusersUUid = $this->publicOrPrivate();

        if($logType == 'emergency') {

            return   Log::emergency("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        }
        if($logType == 'info') {

            return Log::info("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        }
        if($logType == 'warning') {

            return Log::warning("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        
        }
        if($logType == 'notice') {

            return Log::notice("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        
        }
        if($logType == 'error') {

            return Log::error("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        
        }
        if($logType == 'critical') {

            return Log::critical("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        }else {

            return Log::info("".$getusersUUid."-[ ".$request_numb." ]-".$methodType."",$response);
        }

    }
    private function ErrorCode($code) {


        if($code == 400) {
            //400 — Bad Request — The request was invalid or cannot be served. The exact error should be explained in the error payload. E.g. „The JSON is not valid“
            $errorTitle = 'Bad Request';
        }
        else if($code == 401) {
            //401 — Unauthorized — The request requires an user authentication
            $errorTitle = 'Unauthorized';
        }
        else if($code == 403) {
            //403 — Forbidden — The server understood the request, but is refusing it or the access is not allowed.
            $errorTitle = 'Forbidden';
        }
        else if($code == 404) {
            //404 — Not found — There is no resource behind the URI.
            $errorTitle = 'Not found';
        }else if($code == 422) {
            //422 — Unprocessable Entity — Should be used if the server cannot process the enitity, e.g. if an image cannot be formatted or mandatory fields are missing in the payload.
            $errorTitle = 'Unprocessable Entity';
        }
        else if($code == 429) {
            //429 — 429 Too Many Requests response status code indicates the user has sent too many requests in a given amount of time ("rate limiting").
            $errorTitle = 'To many Request';
        }
        else if($code == 500) {
            //500 — Internal Server Error — API developers should avoid this error. If an error occurs in the global catch blog, the stracktrace should be logged and not returned as response.
            $errorTitle = 'Unprocessable Entity';
        }
        else {
            $errorTitle = 'ERROR';
        }

        return $errorTitle;
    }
}

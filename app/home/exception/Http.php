<?php
namespace app\home\exception;

use think\exception\Handle;
use think\Response;
use Throwable;

class Http extends Handle
{
    public $httpStatus = 500;

    public function render($request, Throwable $e): Response
    {
        if(method_exists($e, "getStatusCode")){
            $httpStatus = $e->getStatusCode();
        }else{
            $httpStatus = $this->httpStatus;
        }
        return json(['error' => 'Server Error', 'message' => $e->getMessage(), 'code' => $httpStatus]);        
    }

}

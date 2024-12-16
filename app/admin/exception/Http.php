<?php
namespace app\demo\exception;

use think\exception\Handle;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class Http extends Handle
{
    public $httpStatus = 500;
    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        if(method_exists($e, "getStatusCode")){
            $httpStatus = $e->getStatusCode();
        }else{
            $httpStatus = $this->httpStatus;
        }
        return show(config("status.error"),$e->getMessage(),[],$httpStatus);
    }
}

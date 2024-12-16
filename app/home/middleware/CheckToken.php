<?php
namespace app\home\middleware;
use think\Exception;
use think\facade\Request;
use think\facade\Cache;
class CheckToken
{
    public function handle($request, \Closure $next)
    {
        try {
            $token = Request::header('authorization') ?? false;
            if(!$token){
                throw new Exception('缺少Token',201);
            }
            $token = substr($token,7);
            $userinfo = checkToken($token);
            if($userinfo['code'] != 1){
                throw new Exception('Token验证失败',202);
            }
            $old_tokenName = "old_".$userinfo["data"]->id;
            $arr =  Cache::store('redis')->smembers($old_tokenName);
            if(in_array($token,$arr)){
                throw new Exception('Token已失效',203);
            }
            //$userinfo['data']['token'] = $token;
            // $request->userInfo = $userinfo['data'];
        }
        catch (Exception $err){
            return json(['code'=>$err->getCode(),'msg'=>$err->getMessage()]);
        }
        return $next($request);
    }
}
<?php
namespace app\admin\middleware;

class Auth 
{
	//初始
	public function handle($request,\Closure $next)
	{
		//前置中间件
		if(empty(session(config("admin.session_admin"))) && !preg_match("/login/", $request->pathinfo())){
			return redirect(url('login/index'));
		}
		$response =  $next($request);
		// if(empty(session(config("admin.session_admin"))) && $request->controller() != "login"){
		// 	return redirect(url('login/index'));
		// }
		return $response;
		//后置中间件
	}
	//结束
	public function end(\think\response $Response)
	{

	}
}
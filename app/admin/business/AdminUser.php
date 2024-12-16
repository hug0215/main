<?php
namespace app\admin\business;
use app\common\model\mysql\AdminUser as AdminUserModel;
use think\Request;
use think\Exception;
use think\facade\Log;
class AdminUser{
	public static function login($data){
		try{
			$adminUserObj = new AdminUserModel();
			$adminUser = self::getAdminUserByUsername($data['username']);
			if(empty($adminUser)){
				throw new Exception("不存在该用户");
			}
			//判断密码是否正常
			if($adminUser['password'] != $data['password']){
				throw new Exception("密码错误");
			}
			//更新数据
			$updateData = [
				"last_login_time" =>time(),
				"last_login_ip" =>request()->ip(),
				"update_time" =>time(),
			];
			$res = $adminUserObj->updateById($adminUser['id'],$updateData);
			if(empty($res)){
				throw new Exception("登录失败");
			}
		}catch(\Exception $e){
			Log::error("admin-login-exception".$e->getMessage());
			// todo 记录日志 $e->getMessage();
			throw new Exception("内部异常，登录失败");
			// throw new Exception($e->getMessage());
		}
		//记录session
		session(config("admin.session_admin"),$adminUser);
		return true;
	}

	//获取用户数据
	public static function getAdminUserByUsername($username){
		$adminUserObj = new AdminUserModel();
		$adminUser = $adminUserObj->getAdminUserByUsername($username);
		if(empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')){
			return false;
		}
		$adminUser = $adminUser->toArray();
		return $adminUser;
	}
} 
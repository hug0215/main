<?php
namespace app\home\model;
use think\Model;
class Sign extends Model
{
	//用户登录
    public function login($username,$password) {
    	return 1;
    	//查询当前用户是否存在
    	$user = db('userinfo')->where('account',$username)->find();
		if($user){
			if($user["password"] == md5($password)){
				//更新当前数据
				session('username', $user["username"]);
				session('account', $user["account"]);
				session('uid', $user["id"]);
				session('section', $user["section"]);
				session('limits', $user["limits"]);
				session('position', $user["position"]);
				return 1;
			}else{
				return 2;
			}
		}else{
			return 0;
		}
    }
    //用户注册
    public function register($account,$username,$password,$section,$daqu,$bumen,$zubie){
    	//判断是否有重复用户
    	$user = db('userinfo')->where('account',$account)->find();
    	if(!$user){
    		switch ($section) {
    			case '1'://市场部
    				$limits = "2";
    				break;
    			case '2'://客服部
    				$limits = "3";
    				break;
    			case '3'://导师部
    				$limits = "5";
    				break;
    			case '4'://服务部
    				$limits = "6";
    				break;		
    			default://策划部
    				$limits = "7";
    				break;
    		}
    		if(empty(input("bumen"))){
                $data = ['account' => $account, 'password' => md5($password),'username' => $username,'section' => $section,'daqu' => '0','bumen' => '0','zubie' => '0','limits' => $limits];
            }else{
                $data = ['account' => $account, 'password' => md5($password),'username' => $username,'section' => $section,'daqu' => $daqu,'bumen' => $bumen,'zubie' => $zubie,'limits' => $limits];
            }
			
			$userinfo = db('userinfo')->insert($data);
			//存数据库并且赋值session
			if($userinfo){
				return 1;
			}else{
				return 2;
			}
		}else{
			return 0;
		}
    }
}

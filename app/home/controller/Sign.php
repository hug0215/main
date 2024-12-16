<?php
namespace app\home\controller;
use app\home\model\Sign as slogin;
use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cache;
use think\Exception;
class Sign extends BaseController
{
    private $expire = 60;
    private $redis;

    public function initialize()
    {
        parent::initialize();
        $this->redis = Cache::store('redis')->handler();
    }

	public function logout(){
        $authorization =  Request::header("authorization");
        $token =  substr($authorization,7);
        $userinfo = checkToken($token);
        if($userinfo['code'] != 1){
            throw new Exception('Token验证失败',202);
        }
        $tokenName = "old_".$userinfo["data"]->id;
        $this->redis->sadd($tokenName,$userinfo["data"]->id,$token);
        $data = [
            "code" => 200,
            "msg" => "退出成功",
        ];
        return json($data);
    }

    public function test(){
        $data = [
            "code" => 200,
            "msg" => "进入成功",
        ];
        return json($data);
    }

    /*用户登录*/
    public function login() {
        $where["account"] = input("username");
        $where["password"] = input("password");
        $user = Db::name("userinfo")->where($where)->field('id,account,username')->find();
        if(!$user){
            $data = [
                "code" => 401,
                "msg" => "登录失败！",
            ];
            return json($data);
        }
        $tokenName = "dz_".$user["id"];
        $token = signToken($user);
        //单一登录,黑名单加入旧token
        $oldtoken = $this->redis->get($tokenName);
        if($oldtoken){
            $old_tokenName = "old_".$user["id"];
            $this->redis->sadd($old_tokenName,$user["id"],$oldtoken);
        }
        $this->redis->set($tokenName,$token);
        $data = [
            "code" => 200,
            "msg" => "登录成功！",
            "token"=>$token
        ];
        return json($data);
    }
    
    public function register(){
        $token =  Request::header("authorization");
        $token = substr($token,7);
        $tokens = checkToken($token);
        $data = [
            "code" => 200,
            "msg" => "获取成功！",
            "username"=>input("username"),
            "password"=>input("password"),
            "token"=>$token
        ];
        return $data;
        $uid = session("uid");
        if($uid){
            $map['id'] = $uid;
            $res = db("userinfo")->where($map)->find();
            $this->assign('msg',$res);
        }
        return $this->fetch();
    }

    public function regSubmit(){
        $file = request()->file('avatar');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS . 'image');
            if($info){
                // 成功上传后 获取上传信息
                $url = "/uploads/image/".$info->getSaveName();
                $data["avatar"] = $url;
            }else{
                // 上传失败获取错误信息
                // echo $file->getError();
                return 2;
            } 
        }
        $data["clubid"] = input("clubid");
        $data["account"] = input("tel");
        $data["studentid"] = input("studentid");
        $data["pxdate"] = input("pxdate");
        $data["username"] = input("username");
        $data["sex"] = input("sex");
        $data["birthday"] = input("birthday");
        $data["division"] = input("division");
        $data["tel"] = input("tel");
        $data["sfz"] = input("sfz");
        $data["password"] = substr(input("tel"), -6);
        $uid = session("uid");
        if($uid){
            $map['id'] = $uid;
            $res = db('userinfo')->where($map)->update($data);
            if($res){
                return 1;
            }else{
                return 0;
            }
        }else{
            $res = db('userinfo')->insertGetId($data);
            if($res){
                $uid = session("uid",$res);
                return 1;
            }else{
                return 0;
            }  
        }
    }

    public function demand(){
       
        return $this->fetch();
    }

    public function regFrom(){
        $data["qu"] = input("qu");
        $data["name"] = input("name");
        $data["activitys"] = json_encode(input("activitys/a"));
        $data["username"] = input("username");
        $data["address"] = input("address");
        $data["sex"] = input("sex");
        $data["day"] = input("day");
        $data["equipments"] = json_encode(input("equipments/a"));
        $data["tel"] = input("tel");
        $data["traintime"] = input("traintime");
        $data["lang"] = input("lang");
        $data["time"] = time();
        $res = db('demand')->insert($data);
        if($res){
            return 1;
        }else{
            return 0;
        }
    }

}

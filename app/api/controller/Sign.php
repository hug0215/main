<?php
namespace app\api\controller;
use app\BaseController;
use app\api\model\Sign as slogin;
use think\facade\View;
class Sign extends BaseController
{
	public function logout(){
        session(null);
        $this->redirect("Sign/login");
    }

    public function login() {
        header('Access-Control-Allow-Origin: *');
        // 允许的方法
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        // 允许的头部字段
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        if(request()->isPost()){
            $data = [
                "code" => 200,
                "msg" => "返回错误！错误代码：50009",
                "username"=>input("username"),
                "password"=>input("password"),
            ];
            return json_encode($data);
            $user = db('userinfo')->where('account',$username)->find();
            if($user){
                 if($user["password"] == md5($password)){
                     //更新当前数据
                     session('username', $user["username"]);
                     session('account', $user["account"]);
                     session('avatar', $user["avatar"]);
                     session('uid', $user["id"]);
                     session('sound', $user["sound"]);//提示音
                     session('popup', $user["popup"]);//弹窗
                     session('group_id', $user["group_id"]);//所属分组
                     session('is_admin', $user["is_admin"]);//所属分组
                     return 1;
                 }else{
                     return 2;
                 }
            }else{
                return 0;
            }
        }
        return View::fetch();
    }
    
    public function register(){
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

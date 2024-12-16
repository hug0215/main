<?php
namespace app\home\controller;
use app\BaseController;
class Index extends BaseController
{

    public function index(){
       	return View::fetch();
    }

    public function no_rule()
    {
        return $this->fetch();
    }

    public function tips(){
    	$input = input("pid");
        $push = db("live_push")->where("id",$input)->find();
        // dump($push);
        $this->assign('content',$push);
       	return $this->fetch();
    }

    public function change_password(){
        //判断初始密码是否正确
        $chushi = db("userinfo")->where("account",session("account"))->where("password",md5(input("pwd")))->find();
        if($chushi){
            db("userinfo")->where("account",session("account"))->where("password",md5(input("pwd")))->setField("password",md5(input("pwd_new")));
            session(null);
            return 1;
        }else{
            return 0;
        }
    }

    public function QueryPrice(){
        $url = "http://hq.sinajs.cn/list=s_sh000001,s_sh000016,s_sh000300,s_sh000905";
        $data = mb_convert_encoding(QueryPrice_curl($url), "UTF-8");
        $arr = explode(';', $data);
        return $arr;
        // var_dump($arr);
    }

}

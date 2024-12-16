<?php
namespace app\home\controller;
use think\Controller;
class Base extends Controller
{
    public function _initialize() 
    {
        $username = session("username");
        if (!isset($username)) {
            $this->redirect("Sign/login");
        }else{
        	$this->assign("username",$username);
        	$this->assign("account",session("account"));
            $this->assign("sound",session("sound"));
            $this->assign("popup",session("popup"));
            $this->assign("avatar",session("avatar"));
            $group_id = "Index";
            $this->assign("group_id",$group_id);
            $this->assign("uid",session("uid"));
            $this->assign("is_admin",session("is_admin"));
        }
  
    }
}

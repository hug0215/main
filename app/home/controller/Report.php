<?php
namespace app\home\controller;
use think\Controller;
class Report extends Base
{
    public function index(){
       	return $this->fetch();
    }
    public function zhoub(){
    	$list = db('live_trend')->where("status","2")->order('id desc')->paginate(11);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function zhoub_con(){
    	$id = input("id");
    	$list = db('live_trend')->where("id",$id)->find();
        $this->assign('list', $list);
        return $this->fetch();
    }
}

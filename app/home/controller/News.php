<?php
namespace app\home\controller;
use think\Controller;
class News extends Controller
{
	public function readadd(){
		$post = input("bid");
   		$list = db('live_news')->where(array('id'=>$post))->setInc('readnum',1);
   		if($list){
   			return "1";
   		}
	}

	public function goodadd(){
		$post = input("bid");
   		$list = db('live_news')->where(array('id'=>$post))->setInc('goodnum',1);
   		if($list){
   			return "1";
   		}
	}

    public function article()
    {
    	$input = input("tid");
    	$group_id = "10000";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlea()
    {
    	$input = input("tid");
    	$group_id = "10001";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articleb()
    {
    	$input = input("tid");
    	$group_id = "10002";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlec()
    {
    	$input = input("tid");
    	$group_id = "10003";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articled()
    {
    	$input = input("tid");
    	$group_id = "10004";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlee()
    {
    	$input = input("tid");
    	$group_id = "10005";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlef()
    {
    	$input = input("tid");
    	$group_id = "10006";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articleg()
    {
    	$input = input("tid");
    	$group_id = "10007";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articleh()
    {
    	$input = input("tid");
    	$group_id = "10008";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlei()
    {
    	$input = input("tid");
    	$group_id = "10009";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlej()
    {
    	$input = input("tid");
    	$group_id = "10010";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlek()
    {
    	$input = input("tid");
    	$group_id = "10011";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlel()
    {
    	$input = input("tid");
    	$group_id = "10012";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlem()
    {
    	$input = input("tid");
    	$group_id = "10013";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlen()
    {
    	$input = input("tid");
    	$group_id = "10014";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articleo()
    {
    	$input = input("tid");
    	$group_id = "10015";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articlep()
    {
    	$input = input("tid");
    	$group_id = "10016";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articleq()
    {
    	$input = input("tid");
    	$group_id = "10017";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function articler()
    {
    	$input = input("tid");
    	$group_id = "10018";
    	$list = db("live_news")->where(array('id'=>$input,'group_id'=>$group_id))->select();
    	if(!$list){
    		$this->error("访问失败！");
    	}
        $this->assign('title',$list[0]["title"]);
        $this->assign('list',$list);
        return $this->fetch();
    }
}

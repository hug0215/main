<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Backstage extends Controller
{
    public function login()
    {
    	return $this->fetch();
    }

    public function logout(){
        session('backstage', null);
        $this->redirect("/home/backstage/login");
    }

    public function admin()
    {
    	$backstage = session("backstage");
    	if(!$backstage){
    		$this->redirect("/home/backstage/login");
    	}
        $this->assign("backstage",$backstage);
    	return $this->fetch();
    }

    public function logindata()
    {
    	$post = input("");
    	//查询当前用户是否存在
        $user = db('live_user')->where(array('chat_login'=>$post["chat_login"],'password'=>$post["password"],"is_admin"=>"1"))->find();
        if($user){
            //更新当前数据
            session('backstage', $user["group_id"]);
            return "success";
        }else{
            return "error";
        }
    }

    public function lesson()
    {
    	$backstage = session("backstage");
    	if(!$backstage){
    		$this->redirect("/home/backstage/login");
    	}
    	$this->assign("backstage",$backstage);
    	return $this->fetch();
    }

    //策划文
    public function news()
    {
    	$backstage = session("backstage");
    	if(!$backstage){
    		$this->redirect("/home/backstage/login");
    	}
    	$this->assign("backstage",$backstage);
    	// 查询数据 并且每页显示10条数据
		$news = db('live_news')->where("group_id",$backstage)->order('id desc')->paginate(10);
		// 把分页数据赋值给模板变量list
		$this->assign('news', $news);
        return $this->fetch();
    }

    public function news_add()
    {
    	$backstage = session("backstage");
    	if(!$backstage){
    		$this->redirect("/home/backstage/login");
    	}
    	$this->assign("backstage",$backstage);
    	return $this->fetch();
    }

    public function news_addchange()
    {
    	$backstage = session("backstage");
    	if(!$backstage){
    		$this->redirect("/home/backstage/login");
    	}
    	$this->assign("backstage",$backstage);
    	$get = input("id");
    	if($get){
	    	$map['id'] = $get;
	    	$news = db('live_news')->where($map)->find();
	    	$this->assign('news', $news);
    	}
    	return $this->fetch();
    }

    public function news_Submit()
    {
    	if(request()->isPost()){
    		$data["title"] = input("title");
			$data["time"] = input("time");
			$data["readnum"] = input("readnum");
			$data["goodnum"] = input("goodnum");
			$data["content"] = input("content");
			$data["group_id"] = input("group_id");
			switch (input("group_id")) {
				case '10001':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlea/tid/';
					break;
				case '10002':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articleb/tid/';
					break;
				case '10003':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlec/tid/';
					break;
				case '10004':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articled/tid/';
					break;
				case '10005':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlee/tid/';
					break;
				case '10006':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlef/tid/';
					break;
				case '10007':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articleg/tid/';
					break;
				case '10008':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articleh/tid/';
					break;
				case '10009':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlei/tid/';
					break;
				case '10010':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlej/tid/';
					break;
				case '10011':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlek/tid/';
					break;
				case '10012':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlel/tid/';
					break;
				case '10013':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlem/tid/';
					break;
				case '10014':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlen/tid/';
					break;
				case '10015':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articleo/tid/';
					break;
				case '10016':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articlep/tid/';
					break;
				case '10017':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articleq/tid/';
					break;
				case '10018':
					$data["url"] = input('server.SERVER_NAME'). '/home/news/articler/tid/';
					break;									
				default:
					$data["url"] = input('server.SERVER_NAME'). '/home/news/article/tid/';
					break;
			}
    		if(!input("news_id")){
	    		$res = db('live_news')->insert($data);
	    		if($res){
	    			return 1;
	    		}else{
	    			return 0;
	    		}
    		}else{
    			$map['id'] = input("news_id");
    			$res = db('live_news')->where($map)->update($data);
    			if($res){
	    			return 1;
	    		}else{
	    			return 0;
	    		}
    		}
    	}
    }

    public function news_delete()
    {
    	if(request()->isPost()){
    		$map['id'] = input("news_id");
			$res = db('live_news')->where($map)->delete();
			if($res){
    			return 1;
    		}else{
    			return 0;
    		}
    	}
    }
    //策划文end
    
    //课程安排
    public function lesson_class()
    {
        $message = db("live_class")->order("id asc")->select();
        $this->assign("message",$message);
        return $this->fetch();
    }

    public function makeclass_save(){
        $post = input();
        $data["classname"] = $post["classname"];
        $data["teacher"] = $post["teacher"];
        $data["addtime"] = strtotime($post["addtime"]);
        $data["addlastime"] = strtotime($post["addlastime"]);
        $message = db("live_class")->insert($data);
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('live_class')->order("id asc")->select();
            cache($classcode,$s_classcode);
            return "1";
        }else{
            return "0";
        }
    }

    public function clChange(){
        $post = input();
        $data["classname"] = $post["classname"];
        $data["teacher"] = $post["teacher"];
        $data["addtime"] = strtotime($post["addtime"]);
        $data["addlastime"] = strtotime($post["addlastime"]);
        $message = db("live_class")->where(array("id"=>$post["uid"]))->update($data);
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('live_class')->order("id asc")->select();
            cache($classcode,$s_classcode);
            return "1";
        }else{
            return "0";
        }
    }

    public function clDelete(){
        $post = input("uid");
        $message = db("live_class")->where(array("id"=>$post))->delete();
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('live_class')->order("id asc")->select();
            cache($classcode,$s_classcode);
            return "1";
        }else{
            return "0";
        }
    }

    //欢迎词
    public function notic()
    {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
        $welcome = db("live_welcome")->where(array("group_id"=>$backstage))->order("id desc")->find();
        $this->assign("welcome",$welcome);
        $this->assign("group_id",$backstage);
        return $this->fetch();
    }

    public function postWelcome()
    {
      $post = input("");
      $data["welcome"] = $post["welcome"];
      $data["group_id"] = $post["group_id"];
      $data["time"] = time();
      $res = db("live_welcome")->insert($data);
      if($res){
        $group_id = $post["group_id"];
        $welcome = "welcome".$group_id;
        cache($welcome,null);
        $s_welcome = db('live_welcome')->where(array("group_id"=>$group_id))->order("id desc")->find();
        cache($welcome,$s_welcome);
        return "1";
      }else{
        return "0";
      }
    }

    //公告
    public function welcome()
    {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
        $welcome = db("live_notice")->where(array("group_id"=>$backstage))->order("id desc")->find();
        $this->assign("welcome",$welcome);
        $this->assign("group_id",$backstage);
        return $this->fetch();
    }
    public function postNotice()
    {
      $post = input("");
      $data["welcome"] = $post["welcome"];
      $data["group_id"] = $post["group_id"];
      $data["time"] = time();
      $res = db("live_notice")->insert($data);
      if($res){
        $group_id = $post["group_id"];
        $welcome = "notice".$group_id;
        cache($welcome,null);
        $s_welcome = db('live_notice')->where(array("group_id"=>$group_id))->order("id desc")->find();
        cache($welcome,$s_welcome);
        return "1";
      }else{
        return "0";
      }
    }

    //解封IP

    public function open_ip()
    {
      return $this->fetch();
    }

    public function ip_submit(){
        $post = input("ip");
        $find = db("live_ip")->where(array('ip'=>$post))->find();
        if($find){
            $html = "";
            $html .= '<tr>';
                $html .= '<td style="width:300px;background: #eff3f3;border:1px solid #dee4e4;text-align: right;">查询结果：';
                $html .= '</td>';
                $html .= '<td style="border:1px solid #dee4e4;background: #eff3f3;padding-left:30px;">'.$find["ip"].'';
                $html .= '</td>';
                $html .= '<td style="width: 500px;border:1px solid #dee4e4;background: #eff3f3;"><button type="button" style="width:130px;height:30px;margin-left:20px;" ipid='.$find["id"].' onClick="jiejin(this)">解禁</button></a>';
                $html .= '</td>';
            $html .= '</tr>';
          return $html;
        }else{
           return "0"; 
        }  
    }

    public function ip_del(){
        $post = input("ipid");
        $del = db("live_ip")->where(array('id'=>$post))->delete();
        if($del){
            cache('Yn_stop_ip',null);
            cache('stop_ip',null);
            $stop = db('live_ip')->field("ip")->select();
            cache('stop_ip',$stop);
            cache('Yn_stop_ip',$stop);
            return "1";
        }else{
            return "0";
        }
    }

    //二维码上传
    public function qrcode()
    {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
      $this->assign("group_id",$backstage);
      $ewmcode = db("live_ewm")->where(array("group_id"=>$backstage))->select();
      $this->assign("ewmcode",$ewmcode);
      return $this->fetch();
    }

    public function admin_post_ewmcode(){
        $post = input();
        $file = request()->file('head_path');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $url = "/public/uploads/".$info->getSaveName();
            $data["ewmcode"] = $url;
            $data["group_id"] = $post["group_id"];
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }   
        $change = db("live_ewm")->where(array("group_id"=>$post["group_id"]))->find();
        if($change){
            $result = db("live_ewm")->where(array("group_id"=>$post["group_id"]))->update($data);
            if($result){
                  $group_id = $post["group_id"];
                  $ewmcode = "ewmcode".$group_id;
                  cache($ewmcode,null);
                  $s_ewmcode = db("live_ewm")->where(array("group_id"=>$group_id))->order("id desc")->find();
                  cache($ewmcode,$s_ewmcode);
                  return "1";
            }else{
                $this->ajaxReturn("0");
            }
        }else{
        $result = db("live_ewm")->insert($data);
            if($result){
                $group_id = $post["group_id"];
                $ewmcode = "ewmcode".$group_id;
                cache($ewmcode,null);
                $s_ewmcode = db("live_ewm")->where(array("group_id"=>$group_id))->order("id desc")->find();
                cache($ewmcode,$s_ewmcode);
                return "1";
            }else{
                return "0";
            }
        }
   }

   //上传轮播图
   public function rotation_chart()
   {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
        $this->assign("group_id",$backstage);
        $lunboImg = db("live_imageb")->where(array("group_id"=>$backstage))->order("id desc")->limit(3)->select();
        $this->assign("lunboImg",$lunboImg);
        return $this->fetch();
   }

   public function lunboImg(){
        $post = input();
        $file = request()->file('head_path');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $url = "/public/uploads/".$info->getSaveName();
            $data["src"] = $url;
            $data["group_id"] = $post["group_id"];
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
        $result = db("live_imageb")->insert($data);
        if($result){
            $group_id = $post["group_id"];
            $imgcode = "imgcodeb".$group_id;
            cache($imgcode,null);
            $s_imgcode = db("live_imageb")->where(array("group_id"=>$group_id))->order("id desc")->limit(3)->select();
            cache($imgcode,$s_imgcode);
            return "1";
        }else{
            return "0";
        }
   }

   //盘中直击
   public function direct_attack()
   {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
        $this->assign("group_id",$backstage);
        $panz = db("live_message")->where(array("group_id"=>$backstage))->order("id desc")->limit(3)->select();
        $this->assign("panz",$panz);
        return $this->fetch();
   }

   public function message_Submit(){
        $post = input();
        $data["message"] = $post["content"];
        $data["group_id"] = $post["group_id"];
        $data["addtime"] = time();
        $message = db("live_message")->insert($data);
        if($message){
            $group_id = $post["group_id"];
            $s_panz = "panz_".$group_id;
            cache($s_panz,null);
            $panz = db('live_message')->where(array("group_id"=>$group_id))->order("id desc")->limit(3)->select();
            foreach ($panz as $key => $value) {
              $panz[$key]["message"] = htmlspecialchars_decode($value["message"]);
            }
            cache($s_panz,$panz);
            return "1";
        }else{
            return "error";
        }
    }

    public function direct_add()
    {
        $backstage = session("backstage");
        if(!$backstage){
            $this->redirect("/home/backstage/login");
        }
        $this->assign("group_id",$backstage);
        return $this->fetch();
    }
}

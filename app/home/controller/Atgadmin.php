<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Atgadmin extends Controller
{
    public function login()
    {
    	return $this->fetch();
    }

    public function changepwd()
    {
        return $this->fetch();
    }

    public function changepwd_c()
    {
        $user = db('userinfo')->where('account',input("account"))->find();
        if($user){
            if(input("username")){
            $data["username"] = input("username");
            }
            if(input("password")){
            $data["password"] = md5(input("password"));
            }
            $map['id'] = $user["id"];
            $res = db('userinfo')->where($map)->update($data);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 0;
        }
    }

    public function logout(){
        session('Atgadmin', null);
        $this->redirect("/home/Atgadmin/login");
    }

    public function admin()
    {
    	$Atgadmin = session("Atgadmin");
    	if(!$Atgadmin){
    		$this->redirect("/home/Atgadmin/login");
    	}
        $this->assign("Atgadmin",$Atgadmin);
    	return $this->fetch();
    }

    public function logindata()
    {
    	$post = input("");
    	//查询当前用户是否存在
        $user = db('live_user')->where(array('chat_login'=>$post["chat_login"],'password'=>$post["password"],"is_admin"=>"1"))->find();
        if($user){
            //更新当前数据
            session('Atgadmin', $user["id"]);
            session('autor', $user["username"]);
            return "success";
        }else{
            return "error";
        }
    }

    public function lesson()
    {
    	$Atgadmin = session("Atgadmin");
    	if(!$Atgadmin){
    		$this->redirect("/home/Atgadmin/login");
    	}
    	$this->assign("Atgadmin",$Atgadmin);
    	return $this->fetch();
    }

    //策划文
    public function news()
    {
    	$Atgadmin = session("Atgadmin");
    	if(!$Atgadmin){
    		$this->redirect("/home/Atgadmin/login");
    	}
    	$this->assign("Atgadmin",$Atgadmin);
    	// 查询数据 并且每页显示10条数据
		$news = db('atg_push')->order('id desc')->paginate(15);
		// 把分页数据赋值给模板变量list
		$this->assign('news', $news);
        return $this->fetch();
    }

    public function news_add()
    {
    	$Atgadmin = session("Atgadmin");
    	if(!$Atgadmin){
    		$this->redirect("/home/Atgadmin/login");
    	}
    	$this->assign("Atgadmin",$Atgadmin);
    	return $this->fetch();
    }

    public function news_addchange()
    {
    	$Atgadmin = session("Atgadmin");
    	if(!$Atgadmin){
    		$this->redirect("/home/Atgadmin/login");
    	}
    	$this->assign("Atgadmin",$Atgadmin);
    	$get = input("id");
    	if($get){
	    	$map['id'] = $get;
	    	$news = db('atg_push')->where($map)->find();
	    	$this->assign('news', $news);
    	}
    	return $this->fetch();
    }

    public function news_Submit()
    {
    	if(request()->isPost()){
            if(input("goodnum") == "" || input("goodnum") == "0"){return 0;}
            $data["day"] = input("day");
            $data["min"] = input("min");
			$data["time"] = time();
			$data["goodnum"] = input("goodnum");
			$data["content"] = input("content");
			$data["group_id"] = input("group_id");
            $data["autor"] = session("autor");
    		if(!input("news_id")){
	    		$res = db('atg_push')->insertGetId($data);
	    		if($res){
	    			return $res;
	    		}else{
	    			return 0;
	    		}
    		}else{
    			$map['id'] = input("news_id");
    			$res = db('atg_push')->where($map)->update($data);
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
			$res = db('atg_push')->where($map)->delete();
			if($res){
    			return 1;
    		}else{
    			return 0;
    		}
    	}
    }
    //策划文end
    
    //爱淘股导航栏
    public function atimg()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_atimg')->order('id desc')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function atimg_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function atimg_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_atimg')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function atimg_Submit()
    {
        if(request()->isPost()){
            if(input("goodnum") == "" || input("goodnum") == "0"){return 0;}
            $data["time"] = time();
            $data["goodnum"] = input("goodnum");
            $data["content"] = input("content");
            $data["group_id"] = input("group_id");
            $data["autor"] = session("autor");
            if(!input("news_id")){
                $res = db('atg_atimg')->insertGetId($data);
                if($res){
                    return $res;
                }else{
                    return 0;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_atimg')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function atimg_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_atimg')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //爱淘股导航栏end
    
    //社区动向
    public function sqmarket()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_shequ')->order('id desc')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function sqmarket_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function sqmarket_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_shequ')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function sqmarket_Submit()
    {
        if(request()->isPost()){
            $data["title"] = input("title");
            $data["day"] =  date("Y-m-d H:i:s",time());
            // $data["jianjie"] = input("jianjie");
            $data["time"] = time();
            $data["content"] = input("content");
            $data["autor"] = session("autor");
            if(!input("news_id")){
                $res = db('atg_shequ')->insertGetId($data);
                if($res){
                    return $res;
                }else{
                    return 0;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_shequ')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function sqmarket_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_shequ')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //社区end
    
    //市场动向
    public function market()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_trend')->order('id desc')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function market_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function market_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_trend')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function market_Submit()
    {
        if(request()->isPost()){
            $file = request()->file('head_path');
            if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    // 成功上传后 获取上传信息
                    $url = "/public/uploads/".$info->getSaveName();
                    $data["pic"] = $url;
                }else{
                    // 上传失败获取错误信息
                    $data["pic"] = "/public/static/home/img/aitao.jpg";
                } 
            }
            $data["title"] = input("title");
            $data["day"] = input("day");
            $data["time"] = time();
            $data["content"] = input("content");
            $data["autor"] = session("autor");
            if(!input("news_id")){
                $res = db('atg_trend')->insertGetId($data);
                if($res){
                    return $res;
                }else{
                    return 0;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_trend')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function market_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_trend')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //市场动向end
    //
    //研报
    public function yanbao()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_yanbao')->order('id desc')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function yanbao_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function yanbao_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_yanbao')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function yanbao_Submit()
    {
        if(request()->isPost()){
            $file = request()->file('head_path');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                $url = "/public/uploads/".$info->getSaveName();
                $data["url"] = $url;
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
            $data["title"] = input("title");
            $data["jianjie"] = input("jianjie");
            $data["day"] = input("day");
            $data["time"] = time();
            $data["autor"] = session("autor");
            if(!input("news_id")){
                $res = db('atg_yanbao')->insertGetId($data);
                if($res){
                    return $res;
                }else{
                    return 0;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_yanbao')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function yanbao_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_yanbao')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //市场动向end
    
    //语音播报
    public function music()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_music')->order('id desc')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function music_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function music_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_music')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function music_Submit()
    {
        if(request()->isPost()){
            $file = request()->file('head_path');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                $url = "/public/uploads/".$info->getSaveName();
                $data["url"] = $url;
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            } 
            $data["title"] = input("title");
            $data["jianjie"] = input("jianjie");
            $data["day"] = input("day");
            $data["time"] = time();
            $data["content"] = input("content");
            if(!input("news_id")){
                $res = db('atg_music')->insert($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_music')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function music_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_music')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //语音播报end
    
    //课程安排
    public function lesson_class()
    {
        $message = db("atg_class")->order("id asc")->select();
        $this->assign("message",$message);
        return $this->fetch();
    }

    public function makeclass_save(){
        $post = input();
        $data["classname"] = $post["classname"];
        $data["teacher"] = $post["teacher"];
        $data["addtime"] = strtotime($post["addtime"]);
        $data["addlastime"] = strtotime($post["addlastime"]);
        $message = db("atg_class")->insert($data);
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('atg_class')->order("id asc")->select();
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
        $message = db("atg_class")->where(array("id"=>$post["uid"]))->update($data);
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('atg_class')->order("id asc")->select();
            cache($classcode,$s_classcode);
            return "1";
        }else{
            return "0";
        }
    }

    public function clDelete(){
        $post = input("uid");
        $message = db("atg_class")->where(array("id"=>$post))->delete();
        if($message){
            $classcode = "classcode";
            cache($classcode,null);
            $s_classcode = db('atg_class')->order("id asc")->select();
            cache($classcode,$s_classcode);
            return "1";
        }else{
            return "0";
        }
    }

    //欢迎词
    public function notic()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $welcome = db("atg_welcome")->where(array("group_id"=>$Atgadmin))->order("id desc")->find();
        $this->assign("welcome",$welcome);
        $this->assign("group_id",$Atgadmin);
        return $this->fetch();
    }

    public function postWelcome()
    {
      $post = input("");
      $data["welcome"] = $post["welcome"];
      $data["group_id"] = $post["group_id"];
      $data["time"] = time();
      $res = db("atg_welcome")->insert($data);
      if($res){
        $group_id = $post["group_id"];
        $welcome = "welcome".$group_id;
        cache($welcome,null);
        $s_welcome = db('atg_welcome')->where(array("group_id"=>$group_id))->order("id desc")->find();
        cache($welcome,$s_welcome);
        return "1";
      }else{
        return "0";
      }
    }

    //公告
    public function welcome()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $welcome = db("atg_notice")->where(array("group_id"=>$Atgadmin))->order("id desc")->find();
        $this->assign("welcome",$welcome);
        $this->assign("group_id",$Atgadmin);
        return $this->fetch();
    }
    public function postNotice()
    {
      $post = input("");
      $data["welcome"] = $post["welcome"];
      $data["group_id"] = $post["group_id"];
      $data["time"] = time();
      $res = db("atg_notice")->insert($data);
      if($res){
        $group_id = $post["group_id"];
        $welcome = "notice".$group_id;
        cache($welcome,null);
        $s_welcome = db('atg_notice')->where(array("group_id"=>$group_id))->order("id desc")->find();
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
        $find = db("atg_ip")->where(array('ip'=>$post))->find();
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
        $del = db("atg_ip")->where(array('id'=>$post))->delete();
        if($del){
            cache('Yn_stop_ip',null);
            cache('stop_ip',null);
            $stop = db('atg_ip')->field("ip")->select();
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
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
      $this->assign("group_id",$Atgadmin);
      $ewmcode = db("atg_ewm")->where(array("group_id"=>$Atgadmin))->select();
      $this->assign("ewmcode",$ewmcode);
      return $this->fetch();
    }

    public function Atgadmin_post_ewmcode(){
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
        $change = db("atg_ewm")->where(array("group_id"=>$post["group_id"]))->find();
        if($change){
            $result = db("atg_ewm")->where(array("group_id"=>$post["group_id"]))->update($data);
            if($result){
                  $group_id = $post["group_id"];
                  $ewmcode = "ewmcode".$group_id;
                  cache($ewmcode,null);
                  $s_ewmcode = db("atg_ewm")->where(array("group_id"=>$group_id))->order("id desc")->find();
                  cache($ewmcode,$s_ewmcode);
                  return "1";
            }else{
                $this->ajaxReturn("0");
            }
        }else{
        $result = db("atg_ewm")->insert($data);
            if($result){
                $group_id = $post["group_id"];
                $ewmcode = "ewmcode".$group_id;
                cache($ewmcode,null);
                $s_ewmcode = db("atg_ewm")->where(array("group_id"=>$group_id))->order("id desc")->find();
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
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("group_id",$Atgadmin);
        $lunboImg = db("atg_image")->where(array("group_id"=>$Atgadmin))->order("id desc")->limit(5)->select();
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
        $result = db("atg_image")->insert($data);
        if($result){
            $group_id = $post["group_id"];
            $imgcode = "imgcode".$group_id;
            cache($imgcode,null);
            $s_imgcode = db("atg_image")->where(array("group_id"=>$group_id))->order("id desc")->limit(5)->select();
            cache($imgcode,$s_imgcode);
            return "1";
        }else{
            return "0";
        }
   }

   //盘中直击
   public function direct_attack()
   {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("group_id",$Atgadmin);
        $panz = db("atg_message")->where(array("group_id"=>$Atgadmin))->order("id desc")->limit(3)->select();
        $this->assign("panz",$panz);
        return $this->fetch();
   }

   public function message_Submit(){
        $post = input();
        $data["message"] = $post["content"];
        $data["group_id"] = $post["group_id"];
        $data["addtime"] = time();
        $message = db("atg_message")->insert($data);
        if($message){
            $group_id = $post["group_id"];
            $s_panz = "panz_".$group_id;
            cache($s_panz,null);
            $panz = db('atg_message')->where(array("group_id"=>$group_id))->order("id desc")->limit(3)->select();
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
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("group_id",$Atgadmin);
        return $this->fetch();
    }

    //权限
    public function limit()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        // 查询数据 并且每页显示10条数据
        $news = db('atg_pay')->order('id desc')->where('account|username','like','%'.input("search").'%')->paginate(15);
        // 把分页数据赋值给模板变量list
        $this->assign('news', $news);
        return $this->fetch();
    }

    public function limit_add()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        return $this->fetch();
    }

    public function limit_addchange()
    {
        $Atgadmin = session("Atgadmin");
        if(!$Atgadmin){
            $this->redirect("/home/Atgadmin/login");
        }
        $this->assign("Atgadmin",$Atgadmin);
        $get = input("id");
        if($get){
            $map['id'] = $get;
            $news = db('atg_pay')->where($map)->find();
            $this->assign('news', $news);
        }
        return $this->fetch();
    }

    public function limit_Submit()
    {
        if(request()->isPost()){
            //查询当前账号是否存在数据库
            $where["account"] = input('account');
            $where["group_id"] = "99999";
            $userinfo = db('userinfo')->where($where)->find();
            if(!$userinfo){return "nouser";}
            $data["account"] = $userinfo["account"];
            $data["uid"] = $userinfo["id"];
            $data["username"] = $userinfo["username"];
            $data["starday"] = input("starday");
            $data["byday"] = input("byday");
            $data["isby"] = input("isby");
            $data["issl"] = input("issl");
            $data["time"] = time();
            $data["autor"] = session("autor");
            if(!input("news_id")){
                //判断是否有值
                $result = db('atg_pay')->where("account",input('account'))->find();
                if(!$result){
                $res = db('atg_pay')->insertGetId($data);
                    if($res){
                        return $res;
                    }else{
                        return 0;
                    }
                }else{
                    return 2;
                }
            }else{
                $map['id'] = input("news_id");
                $res = db('atg_pay')->where($map)->update($data);
                if($res){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    public function limit_delete()
    {
        if(request()->isPost()){
            $map['id'] = input("news_id");
            $res = db('atg_pay')->where($map)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //权限end

}

<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Mobile extends Controller
{
    public function index(){
        $uid = session("uid");
        if (!isset($uid)) {
            $this->redirect("Sign/metrol_login");
        }else{
            $group_id = "Index";
            $this->assign("group_id",$group_id);
            $this->assign("uid",session("uid"));
        }
        // $s_mobile = "mobile_init".$uid;
        // $layim =  cache($s_mobile);
        //个人信息
        $result = db('userinfo')->where(array('id' => $uid))->find();
        $user_arr["username"] = $result["username"];
        $user_arr["id"] = $result["id"];
        $user_arr["status"] = $result["status"];
        $user_arr["sign"] = $result["sign"];
        $user_arr["avatar"] = $result["avatar"];
        // 朋友列表
        if($result["friend"]){
            foreach (json_decode($result["friend"]) as $key => $value) {
               $friend = db("friend")->where("id",$value)->find();
               $friend_arr[$key]["groupname"] = $friend["groupname"];
               $friend_arr[$key]["id"] = $friend["id"];
               if($friend["grouplist"]){
               foreach (json_decode($friend["grouplist"]) as $k => $v) {
                    $user = db('userinfo')->where(array('id' => $v))->find();
                    $friend_arr[$key]["list"][$k]["username"] = $user["username"];
                    $friend_arr[$key]["list"][$k]["id"] = $user["id"];
                    $friend_arr[$key]["list"][$k]["status"] = $user["status"];
                    $friend_arr[$key]["list"][$k]["sign"] = $user["sign"];
                    $friend_arr[$key]["list"][$k]["avatar"] = $user["avatar"];
               }
             }
            }
        }
        // //群组列表
        if($result["group"]){
            foreach (json_decode($result["group"]) as $key => $value) {
               $group = db("group")->where("id",$value)->find();
               $group_arr[$key]["groupname"] = $group["groupname"];
               $group_arr[$key]["id"] = $group["id"];
               $group_arr[$key]["avatar"] = $group["avatar"];
            }
        }    

        $this->assign("user_arr",json_encode($user_arr));  
        $this->assign("friend_arr",json_encode($friend_arr));  
        $this->assign("group_arr",json_encode($group_arr));  
       	return $this->fetch();
    }

    public function college()
    {
      $html = "";
      $html .= '<div class="row mt-tabpage-title navbar navbar-default" style="box-shadow: 1px 1px 1px #e6e6e9;">';
        $html .= '<div class="col-xs-12 text-left">';
            $html .= '<a href="javascript:;" id="content_t" class="mt-tabpage-item mt-tabpage-item-cur">股禅机</a>';
            $html .= '<a href="javascript:;" id="guzhi_t" class="mt-tabpage-item">雪豹</a>';
            $html .= '<a href="javascript:;" id="shifo_t" class="mt-tabpage-item">石佛</a>';
            $html .= '<a href="javascript:;" id="laobing_t" class="mt-tabpage-item">老兵</a>';
        $html .= '</div>';
      $html .= '</div>';
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="content">';
              $list = db('course')->where("lesson","0")->paginate(10);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10" style="cursor: pointer;">';
                      $html .= '<div class="thumbnail college_ch" vid="'.$value["id"].'" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="'.$value["pic_url"].'" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;">'.$value["vdo_name"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;">主讲老师：'.$value["teacher"].'</div>';
                          $html .= '<div><a href="#" class="btn btn-sm btn-primary" role="button">播放次数：'.$value["play_num"].'</a> <a href="#" class="btn btn-sm btn-danger" role="button">点赞次数：'.$value["good_num"].'</a></div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
          $html .= '<div class="row animated fadeInDown cursor" id="guzhi" style="display: none;">';
             $guzhi = db('course')->where("lesson","1")->paginate(10);
              foreach ($guzhi as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10" style="cursor: pointer;">';
                      $html .= '<div class="thumbnail college_ch" vid="'.$value["id"].'" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="'.$value["pic_url"].'" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;">'.$value["vdo_name"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;">主讲老师：'.$value["teacher"].'</div>';
                          $html .= '<div><a href="#" class="btn btn-sm btn-primary" role="button">播放次数：'.$value["play_num"].'</a> <a href="#" class="btn btn-sm btn-danger" role="button">点赞次数：'.$value["good_num"].'</a></div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
          $html .= '<div class="row animated fadeInDown cursor" id="shifo" style="display: none;">';
             $shifo = db('course')->where("lesson","2")->paginate(10);
              foreach ($shifo as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10" style="cursor: pointer;">';
                      $html .= '<div class="thumbnail college_ch" vid="'.$value["id"].'" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="'.$value["pic_url"].'" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;">'.$value["vdo_name"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;">主讲老师：'.$value["teacher"].'</div>';
                          $html .= '<div><a href="#" class="btn btn-sm btn-primary" role="button">播放次数：'.$value["play_num"].'</a> <a href="#" class="btn btn-sm btn-danger" role="button">点赞次数：'.$value["good_num"].'</a></div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
          $html .= '<div class="row animated fadeInDown cursor" id="laobing" style="display: none;">';
             $laobing = db('course')->where("lesson","3")->paginate(10);
              foreach ($laobing as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10" style="cursor: pointer;">';
                      $html .= '<div class="thumbnail college_ch" vid="'.$value["id"].'" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="'.$value["pic_url"].'" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;">'.$value["vdo_name"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;">主讲老师：'.$value["teacher"].'</div>';
                          $html .= '<div><a href="#" class="btn btn-sm btn-primary" role="button">播放次数：'.$value["play_num"].'</a> <a href="#" class="btn btn-sm btn-danger" role="button">点赞次数：'.$value["good_num"].'</a></div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function college_con()
    {
      $value = db('course')->where('id','=',input("vid"))->find();
      $html = "";
      $html .= '<div class="container-fiuld" style="cursor: pointer;">';
        $html .= '<div class="col-md-11" style="padding: 0;">';
            $html .= '<video id="myvedio" style="width:100%;" vid="'.$value["id"].'" src="'.$value["vdo_url"].'" controls="controls" controlslist="nodownload"></video>';
        $html .= '</div>';
        $html .= '<div class="col-md-11">';
            $html .= '<p>课程名称：'.$value["vdo_name"].'</p>';
            $html .= '<p>';
                $html .= '<span>播放次数：'.$value["play_num"].'</span> ';
                $html .= '<span id="right" vid="'.$value["id"].'" style="margin-left: 20px;position: relative;cursor: pointer;">';
                    $html .= '<img src="/public/static/home/img/finger.png">
                    点赞次数：<span id="good_num">'.$value["good_num"].'</span>';
                    $html .= '<span class="block" style="display: none;position: absolute;top:0;left:50px;color:red;">+1</span>';
                $html .= '</span>';
                $html .= '<p>演讲老师：'.$value["teacher"].'</p>';
            $html .= '</p>';
        $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

  public function ribao()
    {
      $result = db("live_image")->order("id desc")->limit(8)->field("src")->select();
      $html = "";
      $html .= '<div id="layer-photos-demo" class="layer-photos-demo">';
        foreach ($result as $key => $value) {
          $html .= '<div class="thumbnail" style="margin-bottom:5px;"><img layer-pid="图片id，可以不写" layer-src="'.$value["src"].'" src="'.$value["src"].'" alt="图片名"></div>';
        }
      $html .= '</div>';
      return $html;
    }  

    public function zhoub()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="content">';
              $list = db('yanbao')->order('id desc')->paginate(11);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10 zhoub_cl" pdf="'.$value["url"].'" title="'.$value["title"].'" style="cursor: pointer;">';
                      $html .= '<div class="thumbnail college_ch" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="/public/static/home/img/cfzb.jpg" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;text-align:left;">'.$value["title"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;text-align:left;">'.$value["jianjie"].'</div>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;text-align:left;">'.$value["day"].'</div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function jiepan()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="content">';
              $list = db('live_trend')->where("status","1")->order('id desc')->paginate(11);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-11 margin-top-10 jiepan_cl" title="'.$value["title"].'" style="cursor: pointer;">';
                      $html .= '<span style="display:none;">'.$value["content"].'</span>';
                      $html .= '<div class="thumbnail college_ch" style="margin:0;padding-bottom:0;">';
                        $html .= '<img src="/public/static/home/img/ssjp1.jpg" alt="...">';
                        $html .= '<div class="caption">';
                          $html .= '<h5 style="margin:0 0 5px 0;padding: 0;font-weight: bold;text-align:left;">'.$value["title"].'</h5>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;text-align:left;">'.$value["jianjie"].'</div>';
                          $html .= '<div style="font-size: 12px;margin-bottom: 5px;text-align:left;">'.$value["day"].'</div>';
                        $html .= '</div>';
                      $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function music()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="content">';
              $list = db('live_music')->order("id desc")->paginate(12);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12 music_box" mp3="'.$value["url"].'" title="'.$value["title"].'" style="padding:5px 15px 10px 15px;background: #fff;margin-bottom: 1px;">';    
                    $html .= '<div class="col-xs-12" style="padding:0;margin-top: 10px;">';
                        $html .= '<div class="col-xs-1 thumbnail" style="padding: 0;border:none;margin: 5px 0 0 0;">';
                            $html .= '<img src="/public/static/home/img/horn_r.png">';
                        $html .= '</div>';
                        $html .= '<div class="col-xs-10" style="color:#999999;">';
                            $html .= '<div class="col-xs-12 text-left" style="font-weight: bold;color:#000;">'.$value["title"].'</div>';
                            $html .= '<div class="col-xs-12 text-left">'.$value["jianjie"].'<span style="margin-left:10px;font-size: 12px;">'.$value["day"].'</span></div>';
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function guzhi()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor">';
              $html .= '<div class="col-xs-12" style="padding:10px 0 0 0"><button goodnum="2" id="hisbtn2" class="btn btn-sm btn-danger" style="line-height:1;">历史记录</button></div>';
              $list = db('live_push')->order('id desc')->where('goodnum','2')->whereTime('day', 'today')->paginate(15);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function guzhi_con()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="his_con">';
              $list = db('live_push')->order('id desc')->where('goodnum','2')->paginate(10);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function guzhi_con_add()
    {
      $html = "";
      $list = db('live_push')->order('id desc')->where('goodnum','2')->limit(input("page"),10)->select();
      foreach ($list as $key => $value) {
          $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
            $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
            $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
            $html .= '</div>';
            $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
        $html .= '</div>';
      }
      return $html;
    }

    public function qihuo()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor">';
              $html .= '<div class="col-xs-12" style="padding:10px 0 0 0"><button goodnum="3" id="hisbtn3" class="btn btn-sm btn-danger" style="line-height:1;">历史记录</button></div>';
              $list = db('live_push')->order('id desc')->where('goodnum','3')->whereTime('day', 'today')->paginate(15);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function qihuo_con()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="his_con">';
              $list = db('live_push')->order('id desc')->where('goodnum','3')->paginate(10);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function qihuo_con_add()
    {
      $html = "";
      $list = db('live_push')->order('id desc')->where('goodnum','3')->limit(input("page"),10)->select();
      foreach ($list as $key => $value) {
          $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
            $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
            $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
            $html .= '</div>';
            $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
        $html .= '</div>';
      }
      return $html;
    }

    public function beita()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor">';
              $html .= '<div class="col-xs-12" style="padding:10px 0 0 0"><button goodnum="1" id="hisbtn1" class="btn btn-sm btn-danger" style="line-height:1;">历史记录</button></div>';
              $list = db('live_push')->order('id desc')->where('goodnum','1')->whereTime('day', 'today')->paginate(15);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function beita_con()
    {
      $html = "";
      $html .= '<div class="container">';
          $html .= '<div class="row animated fadeInDown cursor" id="his_con">';
              $list = db('live_push')->order('id desc')->where('goodnum','1')->paginate(10);
              foreach ($list as $key => $value) {
                  $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
                    $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
                    $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
                $html .= '</div>';
              }
          $html .= '</div>';
      $html .= '</div>';
      return $html;
    }

    public function beita_con_add()
    {
      $html = "";
      $list = db('live_push')->order('id desc')->where('goodnum','1')->limit(input("page"),10)->select();
      foreach ($list as $key => $value) {
          $html .= '<div class="col-xs-12 his_con" style="padding:10px 0 10px 0;border-bottom:1px solid #d5d5d5; ">';
            $html .= '<div class="col-xs-2"><h4 style="color: #eb4537;font-weight: bold;margin-top:5px;">'.$value["min"].'</h4></div>';
            $html .= '<div class="col-xs-8" id="gpimg" style="text-align:left;">'.$value["content"].'';
            $html .= '</div>';
            $html .= '<div class="col-xs-10 col-xs-offset-3" style="text-align:left;color: #eb4537;">'.$value["day"].'</div>';
        $html .= '</div>';
      }
      return $html;
    }

    public function live()
    {
      $html = "";
      $html .= '<div class="col-xs-12" style="padding:0;margin:0;height:205px;" id="play"></div>';
      $html .= '<div class="container" id="live_nav" group_id="10000" style="position: relative;">';
        $html .= '<div class="row" id="user_box" style="line-height: 30px; height: 20px;">';
          $html .= '<div class="col-xs-12" style="background:#f3f3f3;">';
                  $html .= '当前用户：<span id="username" style="color:#5ea22f;">'.session("username").'</span>';
                  $html .= '&nbsp;&nbsp;&nbsp;&nbsp;在线人数：<span id="inlineNum" style="color:#5ea22f;">1</span> 人';
          $html .= '</div>';
        $html .= '</div>';
          $html .= '<div class="row" id="message_receive_list" style="overflow-y: auto;overflow-x:hidden; outline: none;border: 1px solid #e2e2e2;height:350px;">';
            $html .= '<div class="col-md-12 col-xs-12 margin-top-5" style="margin-top: 10px;">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 text-left" style="line-height:20px;padding:0;margin-bottom:5px;">';
                     $html .= '<span style="color:#ff8a00;font-weight: bold;">管理员：</span>';
                     $html .= '<span style="margin-left:10px; padding-right:10px; margin-bottom:3px; word-wrap:break-word;">欢迎进入本直播间！！</span>';
                 $html .= '</div>';
             $html .= '</div>';
         $html .= '</div>';
         $html .= '<div class="row" id="message_send" style="padding:3px 5px 3px 2px;line-height:35px;height: 45px;background:#f3f3f3;">';
            $html .= '<div class="col-xs-1" style="padding:0 0 0 10px; "><span class="emotion"><img style="height: 25px;margin-top: 10px;" src="/public/static/common/img/emotion.png"></span>';
            $html .= '</div>';
            $html .= '<textarea id="zb_textarea" name="zb_textarea" class="col-xs-8" style="resize:none;background:none;border:none;border-bottom: 1px solid red;height: 40px;padding-left:0;line-height:40px;" placeholder="说点什么吧..." rows="1"></textarea>';
            $html .= '<button class="btn btn-danger btn-sm col-xs-1" style="height: 25px;margin-top:5px;color:#fff;" id="sendmessage">发送</button>';
         $html .= '</div>';
      $html .= '<div>';
      return $html;
    }
}

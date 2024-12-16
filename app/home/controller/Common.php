<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Common extends Controller
{
    public function get_now_class(){
        //当前课程内容
        $classcode = "classcode";
        $s_classcode = cache($classcode);
        if(!$s_classcode){  
            $s_classcode = db('live_class')->select();
            cache($classcode,$s_classcode);
        }
        $time = date('Hi',time());
        $class_tip = array();
        foreach ($s_classcode as $key => $value) {
          //判断当前时间是哪个课程区间
          if($time>=date("Hi",$value["addtime"]) && $time<date("Hi",$value["addlastime"])){
              $class_tip["teacher"] = $value["teacher"];
              $class_tip["classname"] = $value["classname"];
          }
        }
        if(!$class_tip){
            $class_tip["teacher"] = "暂无";
            $class_tip["classname"] = "当前时间段无课程直播";
        }   
        return $class_tip;      
    }

    public function logindata(){
        $username = input("account");
        $password = input("password");
        $user = db('userinfo')->where('account',$username)->find();
        if($user){
             if($user["password"] == md5($password)){
                 //更新当前数据
                 session('username', $user["username"]);
                 session('account', $user["account"]);
                 session('avatar', $user["avatar"]);
                 session('uid', $user["id"]);
                 session('group_id', $user["group_id"]);//所属分组
                 session('is_admin', $user["is_admin"]);//所属分组
                 return "success";
             }else{
                 return 2;
             }
        }else{
            return "error";
        } 
    }

    /*用户注册*/
    public function atg_reg(){
        if(request()->isPost()){
            $where["account"] = input("account");
            $where["group_id"] = "99999";
            $user = db('userinfo')->where($where)->find();
            if($user){return "already_user";}
            $data = ['account' => input("account"), 'password' => md5(input("password")),'username' => input("username"),'group_id' => '99999'];
            $userinfo = db('userinfo')->insertGetId($data);
                $maps["groupname"] = "我的好友";
                $maps["belonger"] =  $userinfo;
                $aresult = db("friend")->insertGetId($maps);
                $arr[0] = "5";
                $arr[1] = "6";
                $arr[2] = "7";
                $arr[3] = $aresult;
                $ecdeu = json_encode($arr);
                db("userinfo")->where("id",$userinfo)->setField("friend",$ecdeu);
            //存数据库并且赋值session
            if($userinfo){
                return "success";
            }else{
                return 2;
            }
        }
        return $this->fetch();
    }

    public function ban(){
       $banip = input("banip");
       $this->assign("banip",$banip);
       return $this->fetch();
    }

    public function img_post(){
        $post = input();
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $url = "/public/uploads/".$info->getSaveName();
            return $url;
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
    }

    public function readclass()
    {
        $time = date("Hi",time());
        $message = db("live_class")->order("id asc")->select();
        $html = "";
        $html .= '<table class="table table-hover" style="padding:5px;">';
        $html .= '<tr class="warning">';
        $html .= '<td>课程时间</td>';
        $html .= '<td>课程名称</td>';
        $html .= '<td>课程讲师</td>';
        $html .= '</tr>';
        foreach ($message as $key => $value) {
          //判断当前时间是哪个课程区间
          if($time>=date("Hi",$value["addtime"]) && $time<date("Hi",$value["addlastime"])){
              $html .= '<tr class="success" style="text-align:left;">';
              $html .= '<td style="border:none;">'.date("H:i",$value["addtime"]).'-'.date("H:i",$value["addlastime"]).'</td>';
              $html .= '<td style="border:none;">'.$value["classname"].'</td>';
              $html .= '<td style="border:none;">'.$value["teacher"].'</td>';
              $html .= '</tr>';
          }else{
              $html .= '<tr style="text-align:left;">';
              $html .= '<td style="border:none;">'.date("H:i",$value["addtime"]).'-'.date("H:i",$value["addlastime"]).'</td>';
              $html .= '<td style="border:none;">'.$value["classname"].'</td>';
              $html .= '<td style="border:none;">'.$value["teacher"].'</td>';
              $html .= '</tr>';
          }
        }
         $html .='</table>';
        // $html = "";
        // //获取当前时间
        // $time=date('Hi',time());
        // $html .= '<table class="table table-hover" style="font-size:20px;">';
        // $html .= '<tr>';
        // $html .= '<th colspan="3" style="text-align:center;font-size:25px;font-weight:bold;">课程表</th>';
        // $html .= '</tr>';
        // $html .= '<tr style="background:#ffe699;">';
        // $html .= '<td style="text-align:center;font-size:20px;font-weight:bold;border:none;">时间</td>';
        // $html .= '<td style="text-align:center;font-size:20px;font-weight:bold;border:none;">课程</td>';
        // $html .= '<td style="text-align:center;font-size:20px;font-weight:bold;border:none;">讲师</td>';
        // $html .= '</tr>';
        // foreach ($message as $key => $value) {
        //   //判断当前时间是哪个课程区间
        //   if($time>=date("Hi",$value["addtime"]) && $time<date("Hi",$value["addlastime"])){
        //       $html .= '<tr class="warning" style="font-weight:bold;text-align:center;">';
        //       $html .= '<td style="border:none;">'.date("H:i",$value["addtime"]).'-'.date("H:i",$value["addlastime"]).'</td>';
        //       $html .= '<td style="border:none;">'.$value["classname"].'</td>';
        //       $html .= '<td style="border:none;">'.$value["teacher"].'</td>';
        //       $html .= '</tr>';
        //   }else{
        //       $html .= '<tr style="text-align:center;">';
        //       $html .= '<td style="border:none;">'.date("H:i",$value["addtime"]).'-'.date("H:i",$value["addlastime"]).'</td>';
        //       $html .= '<td style="border:none;">'.$value["classname"].'</td>';
        //       $html .= '<td style="border:none;">'.$value["teacher"].'</td>';
        //       $html .= '</tr>';
        //   }
        // }
        //  $html .='</table>';
        return $html;
    }

    public function readclass_mb(){
    $message = db("live_class")->order("id asc")->select();
    $html = "";
    //获取当前时间
    $time=date('Hi',time());
    $html .= '<table class="table table-hover" style="margin-bottom:0">';
    $html .= '<tr class="warning">';
    $html .= '<td style="padding:0;width:80px;line-height:30px;">课程名称</td>';
    $html .= '<td style="padding:0;line-height:30px;">课程讲师</td>';
    $html .= '<td style="padding:0;line-height:30px;">开始时间</td>';
    $html .= '<td style="padding:0;line-height:30px;">结束时间</td>';
    $html .= '</tr>';
    foreach ($message as $key => $value) {
      //判断当前时间是哪个课程区间
      if($time>=date("Hi",$value["addtime"]) && $time<date("Hi",$value["addlastime"])){
          $html .= '<tr class="success">';
          $html .= '<td>'.$value["classname"].'</td>';
          $html .= '<td>'.$value["teacher"].'</td>';
          $html .= '<td>'.date("H:i",$value["addtime"]).'</td>';
          $html .= '<td>'.date("H:i",$value["addlastime"]).'</td>';
          $html .= '</tr>';
      }else{
          $html .= '<tr>';
          $html .= '<td>'.$value["classname"].'</td>';
          $html .= '<td>'.$value["teacher"].'</td>';
          $html .= '<td>'.date("H:i",$value["addtime"]).'</td>';
          $html .= '<td>'.date("H:i",$value["addlastime"]).'</td>';
          $html .= '</tr>';
      }
    }
     $html .='</table>';
    return $html;
  }

    public function saveurl()
    {
        $post = input("id");
        $filename = '直播平台.url';
        switch($post){
          case "10000":
            $url = input('server.SERVER_NAME'). '/home/Live/index';
          break;
          case "10001":
            $url = input('server.SERVER_NAME'). '/home/Live/directa';
          break;
          case "10002":
            $url = input('server.SERVER_NAME'). '/home/Live/directb';
          break;
          case "10003":
            $url = input('server.SERVER_NAME'). '/home/Live/directc';
          break;
          case "10004":
            $url = input('server.SERVER_NAME'). '/home/Live/directd';
          break;
          case "10005":
            $url = input('server.SERVER_NAME'). '/home/Live/directe';
          break;
          case "10006":
            $url = input('server.SERVER_NAME'). '/home/Live/directf';
          break;
          case "10007":
            $url = input('server.SERVER_NAME'). '/home/Live/directg';
          break;
          case "10008":
            $url = input('server.SERVER_NAME'). '/home/Live/directh';
          break;
          case "10009":
            $url = input('server.SERVER_NAME'). '/home/Live/directi';
          break;
          case "10010":
            $url = input('server.SERVER_NAME'). '/home/Live/directj';
          break;
          case "10011":
            $url = input('server.SERVER_NAME'). '/home/Live/directk';
          break;
          case "10012":
            $url = input('server.SERVER_NAME'). '/home/Live/directl';
          break;
          case "10013":
            $url = input('server.SERVER_NAME'). '/home/Live/directm';
          break;
          case "10014":
            $url = input('server.SERVER_NAME'). '/home/Live/directn';
          break;
          case "10015":
            $url = input('server.SERVER_NAME'). '/home/Live/directo';
          break;
          case "10016":
            $url = input('server.SERVER_NAME'). '/home/Live/directp';
          break;
          case "10017":
            $url = input('server.SERVER_NAME'). '/home/Live/directq';
          break;
          case "10018":
            $url = input('server.SERVER_NAME'). '/home/Live/directr';
          break;
          case "10019":
            $url = input('server.SERVER_NAME'). '/home/Live/directs';
          break;
          case "10098":
            $url = input('server.SERVER_NAME'). '/home/Live/direct_dsz';
          break;
          case "10099":
            $url = input('server.SERVER_NAME'). '/home/Common/smlesson';
          break;
        }
        
        $icon = '/cflogo.ico';
        // 创建基本代码
        $shortCut = "[InternetShortcut]\r\nIDList=[{000214A0-0000-0000-C000-000000000046}]\r\nProp3=19,2\r\n";
        $shortCut .= "URL=".$url."\r\n";
        if($icon){
            $shortCut .= "IconFile=".$icon."";
        }

        header("content-type:application/octet-stream");

        // 获取用户浏览器
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $encode_filename = rawurlencode($filename);

        // 不同浏览器使用不同编码输出
        if(preg_match("/MSIE/", $user_agent)){
            header('content-disposition:attachment; filename="'.$encode_filename.'"');
        }else if(preg_match("/Firefox/", $user_agent)){
            header("content-disposition:attachment; filename*=\"utf8''".$filename.'"');
        }else{
            header('content-disposition:attachment; filename="'.$filename.'"');
        }
        echo $shortCut;
    }

    public function festival(){
      //判断当前用户用什么设备访问网页
      if(is_mobile_request()){
        $this->redirect("Common/festival_mbp");exit();
      }
      return $this->fetch();
    }
    public function festival_mbp(){
      //判断当前用户用什么设备访问网页
      if(!is_mobile_request()){
        $this->redirect("Common/festival");exit();
      }
      return $this->fetch();
    }

    public function notic(){
      return $this->fetch();
    }

    public function yugao(){
      return $this->fetch();
    }
}

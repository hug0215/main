<?php
namespace app\home\controller;
use think\Controller;
class Video extends Controller
{
    public function index(){
        $uid = session("uid");
        $vid = input("vid");
        if(!isset($vid)){
            $vid = 11;
        }
        if (!isset($uid)) {
            $this->redirect("/login");
        }
        $limit = cache('limit'.$uid);
        if($limit){
            $list = $limit;
        }else{
            $list = db('videolesson')->where('uid','=',$uid)->find();
        }
        $limits_arr = array();
        if($list && $list["video_id"]){
            foreach (json_decode($list["video_id"]) as $key => $value) {
               array_push($limits_arr,$value);
            }
            $in_arr = in_array($vid,$limits_arr);
            if($in_arr){
                $JPlayer = 1;
                $this->assign('JPlayer', $JPlayer);
            }else{
                $JPlayer = 0;
                $this->assign('JPlayer', $JPlayer);
            }
        }else{
            $JPlayer = 0;
            $this->assign('JPlayer', $JPlayer);
        }
        switch ($vid) {
            case '11':
                $url = "http://vdo.xhredcross.com/sv/51105a12-18ec20247d6/51105a12-18ec20247d6.mp4";
                break;
            case '21':
                $url = "http://vdo.xhredcross.com/sv/5e0e0801-18ec202482e/5e0e0801-18ec202482e.mp4";
                break;
            case '22':
                $url = "http://vdo.xhredcross.com/sv/1166f245-18ec20247d3/1166f245-18ec20247d3.mp4";
                break;
            case '31':
                $url = "http://vdo.xhredcross.com/sv/5bac3d79-18ec202482e/5bac3d79-18ec202482e.mp4";
                break;
            case '32':
                $url = "http://vdo.xhredcross.com/sv/4200c8cd-18ec20247c6/4200c8cd-18ec20247c6.mp4";
                break;
            case '33':
                $url = "http://vdo.xhredcross.com/sv/58d4a466-18ec2036280/58d4a466-18ec2036280.mp4";
                break;
            case '34':
                $url = "http://vdo.xhredcross.com/sv/1596dbc0-18ec203a334/1596dbc0-18ec203a334.mp4";
                break;
            case '35':
                $url = "http://vdo.xhredcross.com/sv/16c52e76-18ec2041a66/16c52e76-18ec2041a66.mp4";
                break;
            case '36':
                $url = "http://vdo.xhredcross.com/sv/ec158fe-18ec2044085/ec158fe-18ec2044085.mp4";
                break;
            case '37':
                $url = "http://vdo.xhredcross.com/sv/48d01cd0-18ec2047523/48d01cd0-18ec2047523.mp4";
                break;
            case '41':
                $url = "http://vdo.xhredcross.com/sv/5288dc63-18ec204a742/5288dc63-18ec204a742.mp4";
                break;
            case '42':
                $url = "http://vdo.xhredcross.com/sv/f07932-18ec204d543/f07932-18ec204d543.mp4";
                break;
            case '43':
                $url = "http://vdo.xhredcross.com/sv/528b3117-18ec2055c75/528b3117-18ec2055c75.mp4";
                break;
            case '51':
                $url = "http://vdo.xhredcross.com/sv/36710a3a-18ec2056039/36710a3a-18ec2056039.mp4";
                break;
            case '52':
                $url = "http://vdo.xhredcross.com/sv/4967d7a6-18ec205ec97/4967d7a6-18ec205ec97.mp4";
                break;
            case '53':
                $url = "http://vdo.xhredcross.com/sv/1af849c3-18ec2061686/1af849c3-18ec2061686.mp4";
                break;
            case '54':
                $url = "http://vdo.xhredcross.com/sv/57759707-18ec2062e52/57759707-18ec2062e52.mp4";
                break;
            case '55':
                $url = "http://vdo.xhredcross.com/sv/43e55965-18ec2062e7e/43e55965-18ec2062e7e.mp4";
                break;
            case '56':
                $url = "http://vdo.xhredcross.com/sv/20e37340-18ec206351d/20e37340-18ec206351d.mp4";
                break;
            case '57':
                $url = "http://vdo.xhredcross.com/sv/10302451-18ec207a9cd/10302451-18ec207a9cd.mp4";
                break;
            case '58':
                $url = "http://vdo.xhredcross.com/sv/8dcdfb-18ec207ddcf/8dcdfb-18ec207ddcf.mp4";
                break;
            case '61':
                $url = "http://vdo.xhredcross.com/sv/1fd242a5-18ec20824ce/1fd242a5-18ec20824ce.mp4";
                break;                            
            default:
                # code...
                break;
        }
        
        // $url = "/upload/video/".$vid.".mp4";
        $this->assign('url', $url);
        $this->assign('vid', $vid);
        $this->assign('limits_arr', json_encode($limits_arr));
       	return $this->fetch();
    }

    public function getClassnum(){
        $vid = input("vid");
        $uid = session("uid");
        if (!isset($uid)) {
            $this->redirect("/login");
        }
        $limits_arr = array();
        $list = db('videolesson')->where('uid','=',$uid)->find();
        if($list){
            if(!empty($list["video_id"])){
                foreach (json_decode($list["video_id"]) as $key => $value) {
                    array_push($limits_arr,$value);
                }
            }
            array_push($limits_arr,$vid);
            $data = ['id' => $list["id"],'uid' => $uid, 'video_id' => json_encode($limits_arr)];
            $res = db('videolesson')->update($data);
            if($res){
                $list = db('videolesson')->where('uid','=',$uid)->find();
                $limit = "limit".$uid;
                cache($limit,null);
                cache($limit,$list);
                return "success" ;
            }else{
                return "error";
            }
        }else{
            array_push($limits_arr,$vid);
            $data = ['uid' => $uid, 'video_id' => json_encode($limits_arr)];
            $res = db('videolesson')->insert($data);
            if($res){
                $list = db('videolesson')->where('uid','=',$uid)->find();
                $limit = "limit".$uid;
                cache($limit,null);
                cache($limit,$list);
                return "success" ;
            }else{
                return "error";
            }
        }
    }

    public function takeexam(){
        $uid = session("uid");
        if (!isset($uid)) {
            return 0;
        }
        $list = db('videolesson')->where('uid','=',$uid)->find();
        //查看课程是否全部看完
        $limits_arr = array();
        $text = json_decode($list['video_id']);
        foreach ($text as $key => $value) {
            array_push($limits_arr,$value);
        }
        $num = count($limits_arr);
        if($num = 22){
            return "success";
        }else{
            return "error";
        }
    }

    public function theory(){
        
        return $this->fetch();
    }

    public function calculate(){
        $uid = session("uid");
        if (!isset($uid)) {
            return 0;
        }
        $score = 0;
        //判断题
        intval(input("data.text1")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text2")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text3")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text4")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text5")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text6")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text7")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text8")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text9")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text10")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text11")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text12")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text13")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text14")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text15")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text16")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text17")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text18")) == 1 ? $score += 2.5 : $score;
        intval(input("data.text19")) == 2 ? $score += 2.5 : $score;
        intval(input("data.text20")) == 1 ? $score += 2.5 : $score;
        //单选题
        intval(input("data.single1")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single2")) == 1 ? $score += 2.5 : $score;
        intval(input("data.single3")) == 1 ? $score += 2.5 : $score;
        intval(input("data.single4")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single5")) == 2 ? $score += 2.5 : $score;
        intval(input("data.single6")) == 1 ? $score += 2.5 : $score;
        intval(input("data.single7")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single8")) == 2 ? $score += 2.5 : $score;
        intval(input("data.single9")) == 2 ? $score += 2.5 : $score;
        intval(input("data.single10")) == 1 ? $score += 2.5 : $score;
        intval(input("data.single11")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single12")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single13")) == 2 ? $score += 2.5 : $score;
        intval(input("data.single14")) == 1 ? $score += 2.5 : $score;
        intval(input("data.single15")) == 2 ? $score += 2.5 : $score;
        intval(input("data.single16")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single17")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single18")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single19")) == 3 ? $score += 2.5 : $score;
        intval(input("data.single20")) == 2 ? $score += 2.5 : $score;

        if($score < 80){
            return 2;
        }
        $list = db('score')->where('uid','=',$uid)->find();
        if($list){
            $data = ['id' => $list["id"],'uid' => $uid,'score' => $score ,'text' => json_encode(input("")),'time' => time()];
            $res = db('score')->update($data);
            if($res){
                session("score",$score);
                cache("scoreimg".$uid,null);
                return 1;
            }else{
                return 0;
            }
        }else{
            $data = ['uid' => $uid,'score' => $score ,'text' => json_encode(input("")),'time' => time()];
            $res = db('score')->insert($data);
            if($res){
                session("score",$score);
                cache("scoreimg".$uid,null);
                return 1;
            }else{
                return 0;
            }
        }  
    }

    public function scoreimglast(){
        $uid = session("uid");
        if (!isset($uid)) {
            $this->redirect("/login");
        }
        $url = cache("scoreimg".$uid);
        if($url){
            $img = $url;
        }else{
            $list = db('score')->where('uid','=',$uid)->find();
            if(empty($list["img"])){
                $this->error("您未通过考核，证书不存在！");
            }
        }
        $this->assign('img',$img);
        return $this->fetch();
    }

    public function scoreimg(){
        $uid = session("uid");
        if (!isset($uid)) {
            $this->redirect("/login");
        }
        $list = db('score')->where('uid','=',$uid)->find();
        $path = "./public/static/home/img/certificate.jpg";
        //将图片画布中
        $image = imagecreatefromjpeg($path);
        //创建字体颜色             
        $black = imagecolorallocate($image, 0, 0, 0);
        $text = session("username");
        $time = time();
        $date = date("Y",$time);
        $account = session("account");
        $score = session("score")."分";
        $dates = date("Y年m月d日",$time);
        //这里是字体文件的路径
        $font = './public/static/home/img/msyh.ttc';
        // $font = 'E:\phpstudy_pro\WWW\dongze\public\static\home\img\msyh.ttc';
        //添加文本，即用黑色画出文本
        imagettftext($image, 30, 0, 110, 530, $black, $font, $text);
        imagettftext($image, 24, 0, 260, 615, $black, $font, $date);
        imagettftext($image, 24, 0, 380, 700, $black, $font, $account);
        imagettftext($image, 24, 0, 730, 700, $black, $font, $score);
        imagettftext($image, 24, 0, 600, 930, $black, $font, $dates);
        //将画布保存到指定的jpg文件
        imagejpeg($image, "./public/uploads/certificate/".session("uid").time()."certificate.jpg");
        $img = "./public/uploads/certificate/".session("uid").time()."certificate.jpg";
        $data = ['id' => $list["id"], 'img' => $img];
        $res = db('score')->update($data);
        cache("scoreimg".$uid,null);
        cache("scoreimg".$uid,$img);
        $this->assign('img',$img);
        return $this->fetch();
    }

    public function errorimg(){
        
        return $this->fetch();
    }

}

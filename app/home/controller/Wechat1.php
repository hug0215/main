<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
require_once './vendor/workerman/GatewayClient/Gateway.php';
use GatewayClient\Gateway;
class Wechat extends Controller
{
	public function index()
    {
        // 用户连接websocket之后,绑定uid和clientid,同时进行分组,根据接收到的group_id进行分组操作
        $wx_openid = input("uid");
        $group_id = input("group_id");
        $clientId = trim(input("client_id"));
        session("clientId",$clientId);
        // 接受到上面的三个参数,进行分组操作
        Gateway::$registerAddress = '127.0.0.1:1288';
        // client_id与uid绑定
        Gateway::bindUid($clientId, $wx_openid);
        // 加入某个群组（可调用多次加入多个群组） 将clientid加入roomid分组中
        Gateway::joinGroup($clientId, $group_id);
        //从数据库中找到所有组，跟workerman进行绑定
        // $s_layimgroup = "layimgroup".$wx_openid;
        // $layimgroup = cache($s_layimgroup);
        // if(!$layimgroup){
        //     $group = db("group_list")->where('uid',$wx_openid)->select();
        //     if($group){
        //         cache($s_layimgroup,$group);
        //         foreach ($group as $key => $value) {
        //             Gateway::joinGroup($clientId, $value["fzid"]);
        //         }
        //     }
        // }else{
        //     foreach ($layimgroup as $key => $value) {
        //             Gateway::joinGroup($clientId, $value["fzid"]);
        //         }
        // }
        $group = db("group_list")->where('uid',$wx_openid)->select();
        if($group){
            foreach ($group as $key => $value) {
                Gateway::joinGroup($clientId, $value["fzid"]);
            }
        }
        // 返回ajax json信息
        $dataArr=[
            'status'=>true,
            'clientId'=>$clientId,
            'uid'=>$wx_openid,
            'group_id'=>$group_id
        ];
        return $dataArr;
    }
    //发送即时通讯提示消息
    public function notifyMe(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $group_id = input("group_id");
        $dataArr=[
            'type'=>"notifyMe",
            'group_id'=>$group_id,
            'goodnum'=>input("goodnum"),
            'min'=>input("min"),
            'pid'=>input("pid"),
            'img'=>"/public/static/home/img/profile_small2.jpg",
            'content' => htmlspecialchars_decode(input("content"))
        ];
        $YJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YJson);
    }

    public function AddwinStock(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $group_id = input("group_id");
        $dataArr=[
            'type'=>"AddwinStock",
            'group_id'=>$group_id,
            'goodnum'=>input("goodnum"),
            'min'=>input("min"),
            'pid'=>input("pid"),
            'img'=>"/public/static/home/img/profile_small2.jpg",
            'content' => htmlspecialchars_decode(input("content"))
        ];
        $YJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YJson);
    }

    public function market(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $group_id = input("group_id");
        $dataArr=[
            'type'=>"market",
            'group_id'=>$group_id,
            'title'=>input("title"),
            'day'=>input("day"),
            'mid'=>input("mid"),
            'jianjie'=>input("jianjie")
        ];
        $YJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YJson);
    }

    //添加好友
    public function addfriend()
    {
        $username = input("username");
        $avatar = input("avatar");
        $id = input("id");
        $sign = input("sign");
        $groupid = input("groupid");
        $type = input("type");
        $selfid = input("selfid");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $dataArr=[
            'type'=> "addfriend" //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'username'=> $username //消息来源用户名
            ,'avatar'=> $avatar //消息来源用户头像
            ,'id'=> $id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,'types'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'sign'=> $sign //消息内容
            ,'groupid'=> $groupid //消息id，可不传。除非你要对消息进行一些操作（如撤回）
        ];
        $layim = json_encode($dataArr);
        Gateway::sendToUid($selfid, $layim);
    }

    //添加分组
    public function addgroup()
    {
        $avatar = input("avatar");
        $id = input("id");
        $groupname = input("groupname");
        $type = input("type");
        $groupimg = input("groupimg");
        $sendid = input("sendid");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $dataArr=[
            'type'=> "addgroup" //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'groupname'=> $groupname //消息来源用户名
            ,'groupimg'=> $groupimg //消息来源用户名
            ,'avatar'=> $avatar //消息来源用户头像
            ,'id'=> $id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,'types'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
        ];
        $layim = json_encode($dataArr);
        Gateway::sendToUid($sendid, $layim);
    }

    public function bindgroup()
    {
        $id = input("id");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $clientId = session("clientId");
        Gateway::joinGroup($clientId, $id);
        return $clientId;
    }

    public function leavegroup()
    {
        $id = input("id");
        $type = input("type");
        $sendid = input("sendid");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $dataArr=[
            'type'=> "leavegroup" //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'id'=> $id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,'types'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'uid' => input("sendid")
        ];
        $layim = json_encode($dataArr);
        Gateway::sendToUid($sendid, $layim);
    }

    public function delbindgroup()
    {
        $id = input("id");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $clientId = session("clientId");
        Gateway::leaveGroup($clientId, $id);
        return $clientId;
    }

    public function sendMessage()
    {
        $username = input("mine.username");
        $avatar = input("mine.avatar");
        $id = input("mine.id");
        $content = input("mine.content");
        //发送给单人还是群组的ID
        $to = input("to.id");
        $type = input("to.type");
        Gateway::$registerAddress = '127.0.0.1:1288';
        //连接redis数据存储
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $redis->INCR("layim_num");  //+1 
        $layim_num = $redis->get("layim_num");
        if($type == "friend"){
            //把数据暂时存到redis数据库
            $layimArr=[
                 'username'=> $username //消息来源用户名
                ,'avatar'=> $avatar //消息来源用户头像
                ,'toid'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                ,'content'=> $content //消息内容
                ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                ,'timestamp'=> time() //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                ,'layim_num'=> $layim_num
            ];
            $layim_rds = json_encode($layimArr);
            $redis->hset("chatlog", $layim_num, $layim_rds);
            // 如果不在线就先存起来
            if(!Gateway::isUidOnline($to))
            {// 假设有个your_store_fun函数用来保存未读消息(这个函数要自己实现)
                $OnlineArr=[
                     'username'=> $username //消息来源用户名
                    ,'avatar'=> $avatar //消息来源用户头像
                    ,'id'=> $id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'content'=> $content //消息内容
                    ,'cid'=> 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                    ,'mine'=> false //是否我发送的消息，如果为true，则会显示在右方
                    ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    ,'timestamp'=> time()*1000 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                ];
                $Online = json_encode($OnlineArr);
                $unonline = "unonline".$to;
                $redis->hset($unonline, $layim_num, $Online);
            }
            else
            {// 在线就转发消息给对应的uid
                $dataArr=[
                     'username'=> $username //消息来源用户名
                    ,'avatar'=> $avatar //消息来源用户头像
                    ,'id'=> $id //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'content'=> $content //消息内容
                    ,'cid'=> 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                    ,'mine'=> false //是否我发送的消息，如果为true，则会显示在右方
                    ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    ,'timestamp'=> time()*1000 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                ];
                $layim = json_encode($dataArr);
               Gateway::sendToUid($to, $layim);
            }
        }else{
            //判断是否需要审核
            $s_examine = "examine".$to;//分组ID的缓存名字
            $examine = cache($s_examine);//获取到的审核人员
            $examin_shy = "examine".$examine;//审核人员缓存的名称
            if($examine){
                //需要审核：将审核的数据同步储存到redis
                $s_exam_num = "examine_num".$to;
                $redis->INCR($s_exam_num);  //+1 
                $exam_num = $redis->get($s_exam_num);
                $dataArr=[
                     'username'=> $username //消息来源用户名
                    ,'avatar'=> $avatar //消息来源用户头像
                    ,'id'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    ,'type'=> "shy" //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'types'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'content'=> $content //消息内容
                    ,'cid'=> 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                    ,'mine'=> false //是否我发送的消息，如果为true，则会显示在右方
                    ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    ,'timestamp'=> time() //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                    ,'exam_num' => $exam_num
                    ,'groupname' => input("to.name")
                ];
                $layim = json_encode($dataArr);
                $redis->hset($examin_shy, $exam_num, $layim);
                Gateway::sendToUid($examine, $layim);
            }else{
                //把数据暂时存到redis数据库
                $layimArr=[
                     'username'=> $username //消息来源用户名
                    ,'avatar'=> $avatar //消息来源用户头像
                    ,'toid'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'content'=> $content //消息内容
                    ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    ,'timestamp'=> time() //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                    ,'layim_num'=> $layim_num
                ];
                $layim_rds = json_encode($layimArr);
                $redis->hset("chatlog", $layim_num, $layim_rds);
                $dataArr=[
                     'username'=> $username //消息来源用户名
                    ,'avatar'=> $avatar //消息来源用户头像
                    ,'id'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
                    ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
                    ,'content'=> $content //消息内容
                    ,'cid'=> 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
                    ,'mine'=> false //是否我发送的消息，如果为true，则会显示在右方
                    ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
                    ,'timestamp'=> time()*1000 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
                ];
                $layim = json_encode($dataArr);
                Gateway::sendToGroup($to,$layim);
                // $group = Gateway::getUidListByGroup($to);
                // if(in_array($id,$group)){
                //    Gateway::sendToGroup($to,$layim);
                // } else {
                //    return 0;
                // }
                
            }
        }
    }

    public function sendMessage_shy()
    {
        // return input("");
        $username = input("username");
        $avatar = input("avatar");
        $id = input("fromid");
        $content = input("content");
        $timestamp = input("timestamp");
        //发送给单人还是群组的ID
        $to = input("toid");
        $type = input("type");
        Gateway::$registerAddress = '127.0.0.1:1288';
        //连接redis数据存储
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $redis->INCR("layim_num");  //+1 
        $layim_num = $redis->get("layim_num");
        //把数据暂时存到redis数据库
        $layimArr=[
             'username'=> $username //消息来源用户名
            ,'avatar'=> $avatar //消息来源用户头像
            ,'toid'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'content'=> $content //消息内容
            ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
            ,'timestamp'=> $timestamp*1 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
            ,'layim_num'=> $layim_num
        ];
        $layim_rds = json_encode($layimArr);
        $redis->hset("chatlog", $layim_num, $layim_rds);
        //转发给群组客户
        $post = input("exam_num");
        $s_examine = "examine".session("uid");
        $del = $redis->hdel($s_examine, $post);
        $dataArr=[
             'username'=> $username //消息来源用户名
            ,'avatar'=> $avatar //消息来源用户头像
            ,'id'=> $to //消息的来源ID（如果是私聊，则是用户id，如果是群聊，则是群组id）
            ,'type'=> $type //聊天窗口来源类型，从发送消息传递的to里面获取
            ,'content'=> $content //消息内容
            ,'cid'=> 0 //消息id，可不传。除非你要对消息进行一些操作（如撤回）
            ,'mine'=> false //是否我发送的消息，如果为true，则会显示在右方
            ,'fromid'=> $id //消息的发送者id（比如群组中的某个消息发送者），可用于自动解决浏览器多窗口时的一些问题
            ,'timestamp'=> $timestamp*1000 //服务端时间戳毫秒数。注意：如果你返回的是标准的 unix 时间戳，记得要 *1000
        ];
        $layim = json_encode($dataArr);
        Gateway::sendToGroup($to,$layim);
    }

    //发送消息提示给对应uid
    public function send_to_uid(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $uid = input("uid");
        $dataArr=[
            'type'=>"send_to_uid",
            'time'=>date("Y-m-d H:i",time()),
            'tips'=>input("tips")
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToUid($uid, $YnJson);
    }

    public function flashwin()
    {
        Gateway::$registerAddress = '127.0.0.1:1288';
        $uid = input("uid");
        $fid = input("fid");
        $dataArr=[
            'type'=>"flashwin",
            'uid'=>$uid,
            'fid'=>$fid,
            'time'=>date("Y-m-d H:i",time())
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToUid($uid, $YnJson);
    }

    //刷新当前页面
    public function refresh_url(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $group_id = "Index";
        $dataArr=[
            'type'=>"refresh_url",
            'time'=>date("Y-m-d H:i",time())
        ];
        $YJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YJson);
    }
    
    public function mar_tipa(){
        Gateway::$registerAddress = '127.0.0.1:1288';
        $group_id = "Index";
        $dataArr=[
            'type'=>"mar_tipa",
            'time'=>date("Y-m-d H:i",time())
        ];
        $YJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YJson);
    }

    public function del_jiaoliu(){
        $post = input("jiaoliu_num");
        // $redis = new \Redis();
        // $redis->connect('127.0.0.1', 6379);
        // $redis->hdel('jiaoliu_sh', $post);
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $redis->del('unonline20');
    } 

    public function del_exam_num(){
        $post = input("exam_num");
        $s_examine = "examine".session("uid");
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $del = $redis->hdel($s_examine, $post);
        return $del;
    }


    //直播间
    //管理员发送消息
    public function send_message_by_admin(){
        $post = input("");
        Gateway::$registerAddress = '127.0.0.1:1288';
        // 获得数据
        $group_id=intval(trim($post["group_id"]));
        $say=trim($post["say"]);
        $type = $post["type"];
        $head_img = session("avatar");
        $username = session("username");
        if(input("is_admin") == 1){
            $dataArr=[
                'type'=>"send_message_by_admin",
                'group_id'=>$group_id,
                'time'=>date("H:i:s",time()),
                'say'=>$say,
                'is_admin' => 1,
                'head_img' => $head_img,
                'username'=> $username
            ];
        }else{
            $dataArr=[
                'type'=>"send_message_by_admin",
                'group_id'=>$group_id,
                'time'=>date("H:i:s",time()),
                'say'=>$say,
                'is_admin' => 0,
                'head_img' => $head_img,
                'username'=> $username
            ];
        }
        $YnJson = json_encode($dataArr);
        $all_group_id = "10000";
        //像当前分组所有成员发送信息
        switch ($group_id) {
            case '10000':
                Gateway::sendToGroup($group_id,$YnJson);
                break;
            case '10001':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
                break;
            case '10002':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
                break;
            case '10003':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
                break;
            case '10004':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
                break;
            case '10005':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
                break;
            case '10006':
                Gateway::sendToGroup($group_id,$YnJson);
                Gateway::sendToGroup($all_group_id,$YnJson);
            break;  
            case '99999':
                Gateway::sendToGroup($group_id,$YnJson);
            break; 
        }
    }
    //发送信息给管理员
    public function send_message_to_admin(){
        $post = input("");
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        //记录用户消息同时发送给两个人管理员
        $redis->INCR("chat_num");  //+1
        $num = $redis->get("chat_num");
        $redis->sAdd('YnJson',$num);
        Gateway::$registerAddress = '127.0.0.1:1288';
        // 获得数据
        $group_id=intval(trim($post["group_id"]));
        $say=trim($post["say"]);
        $type = $post["type"];
        $user_ip = get_client_ip();
        if(session("avatar") == ""){
            $head_img = "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg";
        }else{
            $head_img = session("avatar");
        }
        if(session("username") == ""){
            $username = session("youke");
        }else{
            $username = session("username");
        }
        //组装数组
        $dataArr=[
            'type'=>"send_message_to_admin",
            'group_id'=>$group_id,
            'time'=>date("H:i:s",time()),
            'say'=>$say,
            'head_img' => $head_img,
            'username'=> $username,
            'user_ip'=>$user_ip,
            'arrNum' =>$num
        ];
        $YnJson = json_encode($dataArr);
        switch ($group_id) {
            case '10000':
                $admin = "22";
                break;
            case '99999':
                $admin = "28";
                break;
            break;   
        }
        Gateway::sendToUid($admin, $YnJson);
    }

    public function agree_message_by_admin(){
        $post = input("");
        Gateway::$registerAddress = '127.0.0.1:1288';
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        // 获得数据
        $group_id=intval(trim($post["group_id"]));
        $say=trim($post["say"]);
        $type = $post["type"];
        if($post["head_img"] == ""){
            $head_img = "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg";
        }else{
            $head_img = $post["head_img"];
        }
        $username = $post["username"];
        $arrNum = $post["arrNum"];
        //组装数组
        $dataArr=[
            'type'=>"agree_message_by_admin",
            'group_id'=>$group_id,
            'time'=>date("H:i:s",time()),
            'say'=>$say,
            'head_img' => $head_img,
            'username'=> $username
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YnJson);
    }

    //用户发送礼物给老师
    public function send_gift_to_teacher()
    {
        $post = input("");
        Gateway::$registerAddress = '127.0.0.1:1288';
        // 获得数据
        $group_id = intval(trim($post["group_id"]));
        $head_img = $post["head_img"];
        $type = $post["type"];
        $color = $post["color"];
        $username = $post["username"];
        $imgusername = $post["imgusername"];
        $imgchat_class = $post["imgchat_class"];
        //组装数组
        $dataArr=[
            'type'=>"send_gift_to_teacher",
            'group_id'=>$group_id,
            'head_img'=>$head_img,
            'color'=>$color,
            'username'=>$username,
            'imgusername'=>$imgusername,
            'imgchat_class'=>$imgchat_class
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YnJson);
    }

    public function send_to_user_img(){
        $post = input("");
        Gateway::$registerAddress = '127.0.0.1:1288';
        // 获得数据
        $group_id=intval(trim($post["group_id"]));
        $type = $post["type"];
        $say=$post["say"];
        $username = $post["username"];
        //组装数组
        $dataArr=[
            'type'=>"send_to_user_img",
            'group_id'=>$group_id,
            'say'=>$say,
            'time'=>date("H:i:s",time()),
            "username"=>$username
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YnJson);
    }

    public function send_tis_to_user(){
        $post = input("");
        Gateway::$registerAddress = '127.0.0.1:1288';
        // 获得数据
        $group_id=intval(trim($post["group_id"]));
        $say=$post["say"];
        //组装数组
        $dataArr=[
            'type'=>"send_tis_to_user",
            'group_id'=>$group_id,
            'say'=>$say
        ];
        $YnJson = json_encode($dataArr);
        Gateway::sendToGroup($group_id,$YnJson);
    }    

}

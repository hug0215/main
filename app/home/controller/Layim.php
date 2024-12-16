<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Layim extends Base
{
    public function init()
    {
        $uid = session("uid");
        $s_layim = "layim_init".$uid;
        cache($s_layim,null);
        $layim = cache($s_layim);
        if(!$layim){
            $arr = [];
            $arr["code"] = 0;
            $arr["msg"] = "";
            $arr["data"]["mine"] = [];
            $arr["data"]["friend"] = [];
            $arr["data"]["group"] = [];
            $result = db('userinfo')->where(array('id' => session("uid")))->find();
            $arr["data"]["mine"]["username"] = $result["username"];
            $arr["data"]["mine"]["id"] = $result["id"];
            $arr["data"]["mine"]["status"] = $result["status"];
            $arr["data"]["mine"]["sign"] = $result["sign"];
            $arr["data"]["mine"]["avatar"] = $result["avatar"];
            // 朋友列表
            if($result["friend"]){
                $friend_arr = array();
                foreach (json_decode($result["friend"]) as $key => $value) {
                   $friend = db("friend")->where("id",$value)->find();
                   $arr["data"]["friend"][$key]["groupname"] = $friend["groupname"];
                   $arr["data"]["friend"][$key]["id"] = $friend["id"];
                   if($friend["grouplist"]){
                   foreach (json_decode($friend["grouplist"]) as $k => $v) {
                        $user = db('userinfo')->where(array('id' => $v))->find();
                        $arr["data"]["friend"][$key]["list"][$k]["username"] = $user["username"];
                        $arr["data"]["friend"][$key]["list"][$k]["id"] = $user["id"];
                        $arr["data"]["friend"][$key]["list"][$k]["status"] = $user["status"];
                        $arr["data"]["friend"][$key]["list"][$k]["sign"] = $user["sign"];
                        $arr["data"]["friend"][$key]["list"][$k]["avatar"] = $user["avatar"];
                   }
                   }
                }
            }
            // //群组列表
            if($result["group"]){
                $group_arr = array();
                foreach (json_decode($result["group"]) as $key => $value) {
                   $group = db("group")->where("id",$value)->find();
                   $arr["data"]["group"][$key]["groupname"] = $group["groupname"];
                   $arr["data"]["group"][$key]["id"] = $group["id"];
                   $arr["data"]["group"][$key]["avatar"] = $group["avatar"];
                }
            }    
            cache($s_layim,$arr);
            $layim = $arr;
        }    
        // dump($arr);
        return $layim;
    }

    public function getMembers()
    {
        $arr = [];
        $arr["code"] = 0;
        $arr["msg"] = "";
        $arr["data"]["list"] = [];
        $result = db('group')->where(array('id' => input("id")))->find();
        if($result["grouplist"]){
            foreach (json_decode($result["grouplist"]) as $key => $value) {
               $userinfo = db("userinfo")->where("id",$value)->find();
               $arr["data"]["list"][$key]["username"] = $userinfo["username"];
               $arr["data"]["list"][$key]["id"] = $userinfo["id"];
               $arr["data"]["list"][$key]["avatar"] = $userinfo["avatar"];
               $arr["data"]["list"][$key]["sign"] = $userinfo["sign"];
            }
        } 
        return $arr;
    }

    //获取未发送的聊天数据
    public function unonline()
    {
        $uid = session("uid");
        if(!$uid){return 0;}
        $unonline = "unonline".$uid;
        //通过uid获取redis里面的数据
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $redis->INCR("layim_num");  //+1 
        $layim_num = $redis->get("layim_num");
        $jiaoliu = $redis->hvals($unonline);
        if(!$jiaoliu){return 0;}
        $redis->del($unonline);
        $arrUsers = array();
        foreach ($jiaoliu as $key => $value) {
          $arrUsers[$key] = json_decode($value,true);
        }
        $sort = array(  
          'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
          'field'     => 'timestamp',       //排序字段  
        );  
        $arrSort = array();  
        foreach($arrUsers AS $uniqid => $row){  
            foreach($row AS $key=>$value){  
                $arrSort[$key][$uniqid] = $value;  
            }  
        }  
        if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);  
        }  
        // $arrslice = array_slice($arrUsers, -30);// 倒数5个
        return $arrUsers;
    }

    //获取未发送的聊天数据
    public function unonline_atg()
    {
        $uid = "20";
        if(!$uid){return 0;}
        $unonline = "unonline".$uid;
        //通过uid获取redis里面的数据
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $redis->INCR("layim_num");  //+1 
        $layim_num = $redis->get("layim_num");
        $jiaoliu = $redis->hvals($unonline);
        if(!$jiaoliu){return 0;}
        dump($jiaoliu);
        // $redis->del($unonline);
        $arrUsers = array();
        foreach ($jiaoliu as $key => $value) {
          $arrUsers[$key] = json_decode($value,true);
        }
        $sort = array(  
          'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
          'field'     => 'timestamp',       //排序字段  
        );  
        $arrSort = array();  
        foreach($arrUsers AS $uniqid => $row){  
            foreach($row AS $key=>$value){  
                $arrSort[$key][$uniqid] = $value;  
            }  
        }  
        if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);  
        }  
        // $arrslice = array_slice($arrUsers, -30);// 倒数5个
        return $arrUsers;
    }

    public function uploadImage()
    {
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $url = "/public/uploads/".$info->getSaveName();
            $arr["code"] = 0;
            $arr["msg"] = "";
            $arr["data"]["src"] = $url;
            return json($arr);
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
    }

    public function uploadFile()
    {
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $url = "/public/uploads/".$info->getSaveName();
            $arr["code"] = 0;
            $arr["msg"] = "";
            $arr["data"]["src"] = $url;
            $arr["data"]["name"] = $info->getFilename();
            return json($arr);
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        } 
    }


    public function getBookimg()
    {
        $arr = [];
        $result = db("live_image")->order("id desc")->limit(8)->field("src")->select();
        foreach ($result as $key => $value) {
            $arr[$key] = $value["src"];
        }
        return($arr);
        // $user = db("userinfo")->where("id",session("uid"))->find();
        // $friend_arr = array();
        // foreach (json_decode($user["friend"]) as $key => $value) {
        //     array_push($friend_arr,$value);
        // }
        // dump($friend_arr);
        // array_push($friend_arr,"3");
        // dump($friend_arr);
        // // dump(json_encode($friend_arr));
        // $key = array_search(3, $friend_arr);
        // if($key !== false)
        // $bb = array_splice($friend_arr, $key, 1);
        // $marr = array();
        // $a = "10";
        // array_push($marr,$a);
        // $mecdef = json_encode($marr);
        // dump($mecdef);
        // $uid = session("uid");
        // $s_layim = "layim_init".$uid;
        // $layim = cache($s_layim);
        // if(!$layim){echo "string";}else{echo "stringaa";}
        // dump($layim);
    }

    public function savechatlog()
    {
        // $redis->del('chatlog');//删除数据
        $redis = new \Redis();
        $redis->connect('r-bp1bbfvsh2d1xbf5hppd.redis.rds.aliyuncs.com', 6379);
        $redis->auth('McswYSzh5L2qhTUI8JcI8'); //密码验证
        $bb = $redis->hvals("chatlog");
        // dump($bb);
        foreach ($bb as $key => $value) {
          $arrUsers[$key] = json_decode($value,true);
        }
        if($arrUsers != ""){
            var_dump($arrUsers);
            $result = db("chatlog")->insertAll($arrUsers);
            if($result){
                foreach ($arrUsers as $k => $v) {
                    $redis->hdel("chatlog", $v["layim_num"]);
                }
                
            }
        }
    }

    public function chatlog(){
        $input = input("");
        $arr = [];
        $arr["code"] = 0;
        $arr["msg"] = "";
        $arr["data"]= [];
        $map["toid"] = input("id");
        $map["type"] = input("type");
        $map["fromid"] = session("uid");
        $where["toid"] = session("uid");
        $where["type"] = input("type");
        $where["fromid"] = input("id");
        if(input("type") == "friend"){
            $result1 = db('chatlog')->where($map)->order("timestamp desc")->select();
            $result2 = db('chatlog')->where($where)->order("timestamp desc")->select();
            if($result1 && $result2){
               $result = array_merge($result1,$result2);
            }
            if($result1 && !$result2){
                 $result = $result1;
            }
            if(!$result1 && $result2){
                 $result = $result2;
            }
            if(!$result1 && !$result2){
                 $result = "";
            }
        }else{
            $whereg["toid"] = input("id");
            $whereg["type"] = input("type");
            $result = db('chatlog')->where($whereg)->order("timestamp desc")->select();
        }
        if($result){
        $arrUsers = array();
        foreach ($result as $key => $value) {
          $arrUsers[$key] = $value;
        }
        $sort = array(  
          'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
          'field'     => 'timestamp',       //排序字段  
        );  
        $arrSort = array();  
        foreach($arrUsers AS $uniqid => $row){  
            foreach($row AS $key=>$value){  
                $arrSort[$key][$uniqid] = $value;  
            }  
        }  
        if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);  
        } 
        // dump($result);
        // 朋友列表
        if($arrUsers){
            foreach ($arrUsers as $key => $value) {
               $arr["data"][$key]["username"] = $value["username"];
               $arr["data"][$key]["id"] = $value["fromid"];
               $arr["data"][$key]["avatar"] = $value["avatar"];
               $arr["data"][$key]["timestamp"] = $value["timestamp"]*1000;
               $arr["data"][$key]["content"] = $value["content"];
            }
        }
        $this->assign("arr",json_encode($arr));
        $this->assign("count",count($arrUsers));
        }
        return $this->fetch();
    }

    public function chatlog_mb(){
        $input = input("");
        $arr = [];
        $arr["code"] = 0;
        $arr["msg"] = "";
        $arr["data"]= [];
        $map["toid"] = input("id");
        $map["type"] = input("type");
        $map["fromid"] = session("uid");
        $where["toid"] = session("uid");
        $where["type"] = input("type");
        $where["fromid"] = input("id");
        if(input("type") == "friend"){
            $result1 = db('chatlog')->where($map)->order("timestamp desc")->limit(120)->select();
            $result2 = db('chatlog')->where($where)->order("timestamp desc")->limit(120)->select();
            if($result1 && $result2){
               $result = array_merge($result1,$result2);
            }
            if($result1 && !$result2){
                 $result = $result1;
            }
            if(!$result1 && $result2){
                 $result = $result2;
            }
            if(!$result1 && !$result2){
                 $result = "";
            }
        }else{
            $whereg["toid"] = input("id");
            $whereg["type"] = input("type");
            $result = db('chatlog')->where($whereg)->order("timestamp desc")->limit(120)->select();
        }
        $html = "";
        if($result){
            $arrUsers = array();
            foreach ($result as $key => $value) {
              $arrUsers[$key] = $value;
            }
            $sort = array(  
              'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
              'field'     => 'timestamp',       //排序字段  
            );  
            $arrSort = array();  
            foreach($arrUsers AS $uniqid => $row){  
                foreach($row AS $key=>$value){  
                    $arrSort[$key][$uniqid] = $value;  
                }  
            }  
            if($sort['direction']){  
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);  
            } 
            if($arrUsers){
                  $html .= '<div class="container-fulid">';
                      $html .= '<div class="row animated fadeInDown cursor">';
                          foreach ($arrUsers as $key => $value) {
                               $html .= '<div class="col-xs-11" style="cursor: pointer;margin-bottom:10px;">';
                                  $html .= '<div class="thumbnail col-xs-2" style="margin:0;padding-bottom:0;">';
                                    $html .= '<img src="'.$value["avatar"].'" style="border-radius: 50%;height:40px;width:40px;" alt="...">';
                                  $html .= '</div>';
                                  $html .= '<div class="col-xs-9">';
                                        $html .= '<div class="col-xs-11" style="font-size: 12px;text-align:left;color:#999;padding:5px 10px;">'.$value["username"].'</div>';
                                        $judge = substr($value["content"] , 0 , 3);
                                        if($judge  == "img"){
                                            $img = substr($value["content"], 4, -1);
                                            $html .= '<div class="col-xs-11 thumbnail thumbnail_no_bg" style="font-size: 14px;text-align:left;background:#fff;padding:8px 15px;border-radius:3px;word-break: break-all;line-height: 22px;min-height:22px;"><img src="'.$img.'"></div>';
                                        }else{
                                        $html .= '<div class="col-xs-11" style="font-size: 14px;text-align:left;background:#fff;padding:8px 15px;border-radius:3px;word-break: break-all;line-height: 22px;min-height:22px;">'.$value["content"].'</div>';
                                        }
                                        $html .= '<div class="col-xs-11" style="font-size: 12px;text-align:left;color:#999;padding:5px 10px;">'.date("Y-m-d H:i:s", $value["timestamp"]).'</div>';
                                        
                                  $html .= '</div>';
                               $html .= '</div>';
                          }
                      $html .= '</div>';
                  $html .= '</div>';
            }
        }    
        return $html;
    }

    public function atg_grouplist()
    {
        $whereg["id"] = input("id");
        $result = db('group')->where($whereg)->find();
        $html = '';
        $html .= '<div class="container-fulid">';
        $html .= '<div class="row animated fadeInDown cursor">';
        $html .= '<div class="col-xs-11" style="cursor: pointer;margin-bottom:10px;">';
        if($result["grouplist"]){
            $html .= '<div class="col-xs-11" style="margin-bottom:10px;">群成员：'.count(json_decode($result["grouplist"])).'</div>';
            foreach (json_decode($result["grouplist"]) as $key => $value) {
               $userinfo = db("userinfo")->where("id",$value)->find();
                  $html .= '<div class="thumbnail col-xs-4" style="margin:0;padding-bottom:0;text-align:center;">';
                    $html .= '<img src="'.$userinfo["avatar"].'" style="border-radius: 50%;height:40px;width:40px;" alt="...">';
                    $html .= '<div class="col-xs-12" style="font-size: 12px;text-align:center;color:#999;padding:5px 0px;">'.$userinfo["username"].'</div>';
                  $html .= '</div>';
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function juSearchFriend()
    {
        $friend = db("userinfo")->where('account|username','like','%'.input("SearchFriend").'%')->limit(8)->select();
        if($friend){
            $html = '';
            foreach ($friend as $key => $value) {
                $html .= '<div class="col-xs-12" style="padding:10px 0;line-height:35px;border-bottom:1px solid #eee;">';
                  $html .= '<div class="col-xs-2 col-xs-offset-1"><img src="'.$value["avatar"].'" style="height:40px;width:40px;" class="layui-circle layim-msgbox-avatar"></div>';
                  $html .= '<div class="col-xs-3" style="padding:0;"><span style="color: #000;">'.$value["username"].'</span></div>';
                  $html .= '<div class="col-xs-4" style="padding:0;text-align:right;margin-top:8px;">';
                    $html .= '<button type="button" fid="'.$value["id"].'" username="'.$value["username"].'" avatar="'.$value["avatar"].'" style="margin-left:10px;background: #019688;" class="btn btn-success btn-sm jusuresz" >设置</button>';
                  $html .= '</div>';
              $html .= '</div>';
            }
            return $html;
        }else{
            return 0;
        }
    }

    public function juAddFriend()
    {
        $where["groupname"] = input("groupname");
        $where["fzid"] = input("fzid");
        $where["belonger"] = session("uid");

        $map["username"] = input("username");
        $map["avatar"] = input("avatar");
        $map["fid"] = input("fid");
        $map["groupname"] = input("groupname");
        $map["fzid"] = input("fzid");
        $map["belonger"] = session("uid");
        //判断是否已设置了审核员
        $find = db("jurisdiction")->where($where)->find();
        if($find){
            return 0;
        }else{
            $js = db("jurisdiction")->insert($map);
            if($js){
                $s_examine = "examine".input("fzid");
                cache($s_examine,null);
                $examine = cache($s_examine,input("fid"));
                return 1;
            }else{
                return 2;
            }
        }
    }

    public function judelshy()
    {
        $where["id"] = input("jid");
        $del = db("jurisdiction")->where($where)->delete();
        if($del){
            $s_examine = "examine".input("fzid");
            cache($s_examine,null);
            return 1;
        }else{
            return 0;
        }
    }

    //查找好友分组组成员
    public function jurisdiction()
    {
        $map["fzid"] = input("fzid");
        $map["belonger"] = session("uid");
        $find = db("jurisdiction")->where($map)->find();
        // $allfriend = db("friend_list")->where('uid',session('uid'))->select();
        $html = '<div class="col-xs-12" style="cursor: pointer;padding:8px;">';
            $html .= '<div class="col-xs-6" style="border-right: 1px solid #eee;padding:0px 10px;">';
                $html .= '<div class="col-xs-12" style="border-bottom:1px solid #eee;padding:5px 0 10px 0;text-align:left;"><label class="checkbox-inline"><input style="width:250px;" class="form-control" placeholder="请输入要设置审核的好友名称" type="text" name="juSearchFriend"></label> <button type="button" style="margin-left:10px;background: #019688;" id="jursearch" class="btn btn-success btn-sm">查询</button></div>';
                $html .= '<div class="col-xs-12" id="juSearchcontent" style="height:230px;overflow-y:auto;"></div>';
              $html .= '</div>';
            $html .= '<div class="col-xs-6" style="padding:0px 10px;">';
                $html .= '<div class="col-xs-12" style="height:280px;overflow-y:auto;">';
                    $html .= '<div class="col-xs-12 margin-top-10">';
                    $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-3 control-label">头像：</label>';
                        $html .= '<div class="col-sm-9">';
                            $html .= '<img id="file_click" class="layui-circle" style="height:80px;cursor:pointer;" src="'.$find["avatar"].'">';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-12 margin-top-20">';
                    $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-3 control-label">昵称：</label>';
                        $html .= '<div class="col-sm-9">';
                            $html .= '<input name="username" value="'.$find["username"].'" placeholder="昵称" class="form-control" type="text">';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-12 margin-top-10">';
                    $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-3 control-label">群组：</label>';
                        $html .= '<div class="col-sm-9">';
                            $html .= '<input name="sign" value="'.$find["groupname"].'" placeholder="群" class="form-control" type="text">';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-xs-12 margin-top-20">';
                    $html .= '<div class="form-group">';
                        $html .= '<div class="col-sm-9 col-sm-offset-3">';
                            $html .= '<button class="btn btn-info" type="button" jid="'.$find["id"].'" fzid="'.$find["fzid"].'" id="delshy">删除审核员</button>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>'; 
        $html .= '</div>';
        return $html;
    }

    public function find(){
        //获取好友分组列表
        $friend = db("friend")->where(array('belonger' => session("uid")))->select();
        $this->assign("friend",$friend);
        //获取群分组列表
        $group = db("group")->where(array('belonger' => session("uid")))->select();
        $this->assign("group",$group);
        return $this->fetch();
    }

    //查找好友分组组成员
    public function SearchFriendList()
    {
        $map["uid"] = session("uid");
        $map["fzid"] = input("fzid");
        $allfriend = db("friend_list")->where($map)->select();
        // $allfriend = db("friend_list")->where('uid',session('uid'))->select();
        if($allfriend){
            $html = '<div class="col-xs-12" style="cursor: pointer;padding:8px;">';
                $html .= '<div class="col-xs-12" style="box-shadow: 1px 1px 1px #e6e6e9;padding:0px 10px;">';
                    $html .= '<div class="col-xs-12" style="border-bottom:1px solid #eee;padding:0 0 5px 0;text-align:center;"><label class="checkbox-inline"><input type="checkbox" id="checkall">全选/反选</label> <button type="button" style="margin-left:10px;background: #019688;" id="checkchangeall" class="btn btn-success btn-sm" >转移</button><button type="button" style="margin-left:5px;" id="checkdelall" class="btn btn-danger btn-sm">删除</button></div>';
                    $html .= '<div class="col-xs-12" id="checklist" style="height:340px;overflow-y:auto;">';
                        foreach ($allfriend as $key => $value) {
                            $html .= '<div class="col-xs-12" style="padding:7px 0;line-height:45px;border-bottom:1px solid #eee;">';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<input type="checkbox" value="'.$value["id"].'" name="check_id">';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<img src="'.$value["avatar"].'" style="height:40px;width:40px;" class="layui-circle layim-msgbox-avatar">';
                                $html .= '</div>';
                                $html .= '<div class="col-xs-3" style="padding:0;">';
                                $html .= '<span style="color: #000;">'.$value["username"].'</span>';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-4" style="padding:0;text-align:right;">';
                                    $html .= '<button type="button" uid="'.$value["id"].'" class="btn btn-primary btn-sm changezy" style="background: #019688;">转移</button> <button type="button" style="margin-left:5px;" uid="'.$value["id"].'" class="btn btn-danger btn-sm changedl">删除</button>';   
                                $html .= '</div>';
                            $html .= '</div>';
                        }
                    $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-xs-6" style="padding:0px 10px;">';
                    $html .= '<div class="col-xs-12" style="overflow-y:auto;">';
  
                    $html .= '</div>';
                $html .= '</div>'; 
            $html .= '</div>';
            return $html;
        }else{
            return 0;
        }
    }

    //添加好友分组
    public function addFriendgroup()
    {
        $map["groupname"] = input("addFriendgroup");
        $map["belonger"] = session("uid");
        $friend = db("friend")->where($map)->find();
        if(!$friend){
            $result = db("friend")->insertGetId($map);
            if($result){
                //更新userinfo里面的friend列表
                $user = db("userinfo")->where("id",session("uid"))->find();
                $arr = array();
                foreach (json_decode($user["friend"]) as $key => $value) {
                    array_push($arr,$value);
                }
                array_push($arr,$result);
                $ecdef = json_encode($arr);
                db("userinfo")->where("id",session("uid"))->setField("friend",$ecdef);
                $s_layim = "layim_init".session("uid");
                $layim = cache($s_layim,null);
                $html = '<div class="col-sm-4 col-md-4 col-lg-2" style="cursor: pointer;padding:8px;">';
                $html .= '<div class="bg-success col-xs-12" style="box-shadow: 1px 1px 1px #e6e6e9;padding:20px 10px;">';
                $html .= '<div class="col-xs-12">';
                $html .= '<h4 style="margin:0 0 5px 0;padding: 0;">'.input("addFriendgroup").'</h4>';
                    $html .= '</div>';
                $html .= '<div class="col-xs-12">';
                $html .= '<button type="button" class="btn btn-sm btn-success" onclick="addFriendList(this)" fzid="'.$result.'" style="background: #019688;" role="button">成员操作</button>';
                $html .= '<button type="button" class="btn btn-sm btn-default" onclick="delFriend(this)" fzid="'.$result.'" role="button">删除分组</button>';
                $html .= '</div>';
                    $html .= '</div>';   
                $html .= '</div>';
                return $html;
            }else{
                return 1;
            }
        }else{
            return 0;
        }
    }

    public function delallFriend()
    {
        // return input("");
        if(!input("fzid")){return 0;}
        // 启动事务
        db("friend_list")->startTrans();
        try{
            $woker = array();
            //找到之前的分组更新grouplist字段
            $friend = db("friend")->where("id",input("fzid"))->find();
            $farr = json_decode($friend["grouplist"]);
            $limits = json_decode(input("limits"));
            foreach ($limits as $key => $value) {                
                $friend_list = db("friend_list")->where("id",$value)->find();
                $data["type"] = "friend";
                $data["fzid"] = input("fzid");
                $data["fid"] = session("uid");
                $data["uid"] = $friend_list["fid"];
                array_push($woker,$data);
                //找到对应删除人的friend
                $map["fid"] = session("uid");
                $map["uid"] = $friend_list["fid"];
                $otherss = db("friend_list")->where($map)->find();
                // return $otherss;
                $other = db("friend")->where("id",$otherss["fzid"])->find();
                $othergroulist = json_decode($other["grouplist"]);
                // return $othergroulist;
                $okey = array_search(session("uid"), $othergroulist);
                if($okey !== false){array_splice($othergroulist, $okey, 1);}
                $o_arr = json_encode($othergroulist);
                db("friend")->where("id",$otherss["fzid"])->setField("grouplist",$o_arr);
                db("friend_list")->where($map)->delete();

                $fkey = array_search($friend_list["fid"], $farr);
                if($fkey !== false){array_splice($farr, $fkey, 1);}
                db("friend_list")->where("id",$value)->delete();
            }
            //将最后的grouplist数据更新到friend里面
            $f_arr = json_encode($farr);
            db("friend")->where("id",input("fzid"))->setField("grouplist",$f_arr);
            
            db("friend_list")->commit();
            return $woker;
        } catch (\Exception $e) {
            // 回滚事务
            db("friend_list")->rollback();
            return 0;
        } 
    }

    public function delFriend()
    {
        if(!input("fzid")){return 0;}
        //启动事务
        db("friend")->startTrans();
        try{
            $woker = array();
            $group = db("friend")->where("id",input("fzid"))->find();
            if(!$group){return 0;}
            if($group["grouplist"] != ""){
                $arr = json_decode($group["grouplist"]);
                foreach ($arr as $key => $value) {
                    $data["type"] = "friend";
                    $data["fzid"] = input("fzid");
                    $data["fid"] = session("uid");
                    $data["uid"] = $value;
                    array_push($woker,$data);
                    //删除friend_list里面对应的数据
                    $map["fid"] = session("uid");
                    $map["uid"] = $value;
                    $friend_list = db("friend_list")->where($map)->find();
                    $friend = db("friend")->where("id",$friend_list["fzid"])->find();
                    $farr = json_decode($friend["grouplist"]);
                    $fkey = array_search(session("uid"), $farr);
                    if($fkey !== false){array_splice($farr, $fkey, 1);}
                    $f_arr = json_encode($farr);
                    db("friend")->where("id",$friend_list["fzid"])->setField("grouplist",$f_arr);
                    db("friend_list")->where($map)->delete();
                }
                db("friend")->where("id",input("fzid"))->delete();
                db("friend_list")->where("fzid",input("fzid"))->delete();
            }else{
                db("friend")->where("id",input("fzid"))->delete();
            }
            //userinfo的group进行更新
            $userinfo = db("userinfo")->where("id",session("uid"))->find();
            $userarr = json_decode($userinfo["friend"]);
            $userkey = array_search(input("fzid"), $userarr);
            if($userkey !== false){array_splice($userarr, $userkey, 1);}
            $user_arr = json_encode($userarr);
            db("userinfo")->where("id",session("uid"))->setField("friend",$user_arr);
            // 提交事务
            db("friend")->commit();
            return $woker; 
        } catch (\Exception $e) {
            // 回滚事务
            db("friend")->rollback();
            return 0;
        }
    }

    public function SearchFriend()
    {
        $friend = db("userinfo")->where('account|username','like','%'.input("SearchFriend").'%')->limit(8)->select();
        if($friend){
            $html = '<ul class="layim-msgbox">';
            foreach ($friend as $key => $value) {
                $html .= '<li>';
                    $html .= '<img src="'.$value["avatar"].'" class="layui-circle layim-msgbox-avatar">';
                    $html .= '<p class="layim-msgbox-user">';
                        $html .= '<span style="color: #000;">'.$value["username"].'</span>';
                    $html .= '</p>';
                    $html .= '<p class="layim-msgbox-content">';
                        $html .= '<span>'.$value["sign"].'</span>';
                    $html .= '</p>';
                    $html .= '<p class="layim-msgbox-btn">';
                        $html .= '<button type="button" onclick="addFriend(this)" fid="'.$value["id"].'" class="layui-btn layui-btn-small" data-type="agree">添加</button>';   
                    $html .= '</p>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }else{
            return 0;
        }
    }


    public function addFriend()
    {
        $friend = db("friend")->where('belonger',session("uid"))->select();
        $friend_list = db("friend_list")->where(array("fid" => input("fid"),"uid"=>session("uid")))->find();
        if($friend_list){return 1;}
        if($friend){
            $html = '<div class="col-xs-12" style="margin-top:15px;">';
                $html .= '<div class="form-group">';
                    $html .= '<select class="form-control" id="selectaddFriend">';
                        $html .= '<option value="0">请选择</option>';
                        foreach ($friend as $key => $value) {
                            $html .= '<option value="'.$value["id"].'">'.$value["groupname"].'</option>';
                        }
                    $html .= '</select>';
                $html .= '</div>';
                $html .= '<div class="form-group" style="text-align:right">';
                    $html .= '<button class="layui-btn layui-btn-small" type="button" id="addFriendchange_a">确认添加</button>';
                $html .= '</div>';
            $html .= '</div>';
            return $html;
        }else{
            return 0;
        }
    }
    //确定添加好友
    public function addFriendchange()
    {
        //启动事务
        db("friend_list")->startTrans();
        try{
            $data["groupname"] = input("addFriendtext");
            $data["fzid"] = input("addFriendval");//本人好友列表组ID
            $data["uid"] = session("uid");//本人的UID
            $data["fid"] = input("fid");//好友的UID
            //好友的个人信息
            $user = db("userinfo")->where('id',input('fid'))->find();
            $data["avatar"] = $user["avatar"];
            $data["username"] = $user["username"];
            $data["sign"] = $user["sign"];
            $result = db("friend_list")->insertGetId($data);
                //更新friend里面的grouplist
            $now = db("friend")->where("id",input("addFriendval"))->find();
            if($now["grouplist"] != ""){
                $arr = json_decode($now["grouplist"]);
                array_push($arr,input("fid"));
                $now_arr = json_encode($arr);  
                $cs = db("friend")->where(array("id"=>input("addFriendval")))->setField('grouplist', $now_arr);
            }else{
                $arr = array();
                array_push($arr,input("fid"));
                $now_arr = json_encode($arr);
                db("friend")->where("id",input("addFriendval"))->setField("grouplist",$now_arr);
            }
            //同时同步更新好友的对应列表
            $friend = db("friend")->where(array("belonger"=>input("fid"),"groupname"=>"我的好友"))->find();
            if($friend){
                $otherfzid = $friend["id"];
                $map["groupname"] = $friend["groupname"];
                $map["fzid"] = $otherfzid;
                $map["uid"] = input("fid");
                $map["fid"] = session("uid");
                $muser = db("userinfo")->where('id',session("uid"))->find();
                $map["avatar"] = $muser["avatar"];
                $map["username"] = $muser["username"];
                $map["sign"] = $muser["sign"];
                $mresult = db("friend_list")->insertGetId($map);
                if($friend["grouplist"]){
                    $marr = json_decode($friend["grouplist"]);
                }else{
                    $marr = array();
                }
                array_push($marr,session("uid"));
                $mecdef = json_encode($marr);
                db("friend")->where("id",$friend["id"])->setField("grouplist",$mecdef);
                //更新userinfo里面的friend列表
                $s_layim = "layim_init".input("fid");
                $layim = cache($s_layim,null);
            }
            else{
                //创建一个好友列表的我的好友分组
                $maps["groupname"] = "我的好友";
                $maps["belonger"] = input("fid");
                $aresult = db("friend")->insertGetId($maps);
                $map["groupname"] = "我的好友";
                $otherfzid = $aresult;
                $map["fzid"] = $otherfzid;
                $map["uid"] = input("fid");
                $map["fid"] = session("uid");
                $muser = db("userinfo")->where('id',session("uid"))->find();
                $map["avatar"] = $muser["avatar"];
                $map["username"] = $muser["username"];
                $map["sign"] = $muser["sign"];
                $mresult = db("friend_list")->insertGetId($map);
                $marr = array();
                array_push($marr,session("uid"));
                $mecdef = json_encode($marr);
                db("friend")->where("id",$aresult)->setField("grouplist",$mecdef);
                //更新userinfo里面的friend列表
                $fuser = db("userinfo")->where('id',input("fid"))->find();
                $uarr = json_decode($fuser["friend"]);
                array_push($uarr,$aresult);
                $ecdeu = json_encode($uarr);
                db("userinfo")->where("id",input("fid"))->setField("friend",$ecdeu);
                $s_layim = "layim_init".input("fid");
                $layim = cache($s_layim,null);
            }
            // 提交事务
            db("friend_list")->commit();
            //自己添加好友返回信息
            $selfadd = ['type' => 'friend', 'avatar' => $user["avatar"],'username' => $user["username"],'groupid' => input("addFriendval"),'id' => input("fid"),'sign' => $user["sign"]];
            //好友添加自己的返回信息
            $otheradd = ['type' => 'friend', 'avatar' => $map["avatar"],'username' => $map["username"],'groupid' => $otherfzid,'id' => session("uid"),'sign' => $map["sign"],'selfid' => input("fid")];
            $woker[0] = $selfadd;
            $woker[1] = $otheradd;
            return $woker; 
        } catch (\Exception $e) {
            // 回滚事务
            db("friend_list")->rollback();
            return 0;
        } 
    }

    public function moveFriend()
    {
        $friend = db("friend")->where('belonger',session("uid"))->select();
        if($friend){
            $html = '<div class="col-xs-12" style="margin-top:15px;">';
                $html .= '<div class="form-group">';
                    $html .= '<select class="form-control" id="selectaddFriend">';
                        $html .= '<option value="0">请选择</option>';
                        foreach ($friend as $key => $value) {
                            $html .= '<option value="'.$value["id"].'">'.$value["groupname"].'</option>';
                        }
                    $html .= '</select>';
                $html .= '</div>';
                $html .= '<div class="form-group" style="text-align:right">';
                    $html .= '<button class="layui-btn layui-btn-small" type="button" id="addFriendchange_a">确认转移</button>';
                $html .= '</div>';
            $html .= '</div>';
            return $html;
        }else{
            return 0;
        }
    }

    //添加好友分组
    public function addgrouplist()
    {
        $map["groupname"] = input("addgrouplist");
        $map["belonger"] = session("uid");
        $groupimg = "http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg";
        $friend = db("group")->where($map)->find();
        if(!$friend){
            $result = db("group")->insertGetId($map);
            if($result){
                $msg = array();
                //更新userinfo里面的friend列表
                $user = db("userinfo")->where("id",session("uid"))->find();
                $gmap["groupname"] = input("addgrouplist");
                $gmap["uid"] = session("uid");
                $gmap["fzid"] = $result;
                $gmap["avatar"] = $user["avatar"];
                $gmap["username"] = $user["username"];
                $grouplist = db("group_list")->insertGetId($gmap);
                $garr[0] = session("uid");
                $gecdef = json_encode($garr);
                db("group")->where("id",$result)->setField("grouplist",$gecdef);
                if($user["group"] != ""){
                    $arr = json_decode($user["group"]);
                }else{
                    $arr = array();
                }
                array_push($arr,$result);
                $ecdef = json_encode($arr);
                db("userinfo")->where("id",session("uid"))->setField("group",$ecdef);
                $s_layim = "layim_init".session("uid");
                $layim = cache($s_layim,null);
                $html = '<div class="col-sm-4 col-md-4 col-lg-2" style="cursor: pointer;padding:8px;">';
                $html .= '<div class="bg-success col-xs-12" style="box-shadow: 1px 1px 1px #e6e6e9;padding:20px 10px;">';
                $html .= '<div class="col-xs-12">';
                $html .= '<h4 style="margin:0 0 5px 0;padding: 0;">'.input("addgrouplist").'</h4>';
                    $html .= '</div>';
                $html .= '<div class="col-xs-12">';
                $html .= '<button type="button" class="btn btn-sm btn-success" onclick="addFriendListgroup(this)" fzid="'.$result.'" groupname="'.input("addgrouplist").'" groupimg="'.$groupimg.'" style="background: #019688;margin-right:5px;" role="button">成员操作</button>';
                $html .= '<button type="button" class="btn btn-sm btn-danger" onclick="delFriendListgroup(this)" fzid="'.$result.'" role="button">删除分组</button>';
                $html .= '</div>';
                    $html .= '</div>';   
                $html .= '</div>';
                $msg[0]["groupname"] = input("addgrouplist");
                $msg[0]["id"] = $result;
                $msg[0]["avatar"] = $groupimg;
                $msg[1] = $html;
                return $msg;
            }else{
                return 1;
            }
        }else{
            return 0;
        }
    }

    public function delFriendListgroup()
    {
        if(!input("fzid")){return 0;}
        //启动事务
        db("group")->startTrans();
        try{
            $woker = array();
            $group = db("group")->where("id",input("fzid"))->find();
            if(!$group){return 0;}
            $arr = json_decode($group["grouplist"]);
            //userinfo的group进行更新
            foreach ($arr as $key => $value) {
                $userinfo = db("userinfo")->where("id",$value)->find();
                $userarr = json_decode($userinfo["group"]);
                $userkey = array_search(input("fzid"), $userarr);
                if($userkey !== false){array_splice($userarr, $userkey, 1);}
                $user_arr = json_encode($userarr);
                db("userinfo")->where("id",$value)->setField("group",$user_arr);
                $data["type"] = "group";
                $data["fzid"] = input("fzid");
                $data["uid"] = $value;
                array_push($woker,$data);
            }
            db("group")->where("id",input("fzid"))->delete();
            db("group_list")->where("fzid",input("fzid"))->delete();
            // 提交事务
            db("group")->commit();
            return $woker; 
        } catch (\Exception $e) {
            // 回滚事务
            db("group")->rollback();
            return 0;
        }
    }

    public function addFriendListgroup()
    {
        $map["uid"] = session("uid");
        $allfriend = db("friend_list")->where($map)->select();
        $group_list = db("group_list")->where('fzid',input("fzid"))->select();
        if($allfriend){
            $html = '<div class="col-xs-12" style="cursor: pointer;padding:8px;">';
                $html .= '<div class="col-xs-6" style="box-shadow: 1px 1px 1px #e6e6e9;padding:0px 10px;">';
                    $html .= '<span style="margin-right:10px;">我的好友列表</span>';
                    $html .= '<div class="col-xs-12" style="border-bottom:1px solid #eee;padding:0 0 5px 0;text-align:center;"><label class="checkbox-inline"><input type="checkbox" id="checkall">全选/反选</label> <button type="button" style="margin-left:10px;background: #019688;" id="checkchangeall" class="btn btn-success btn-sm" >添加群成员</button></div>';
                    $html .= '<div class="col-xs-12" id="checklist" style="height:320px;overflow-y:auto;">';
                        foreach ($allfriend as $key => $value) {
                            $html .= '<div class="col-xs-12" style="padding:7px 0;line-height:45px;border-bottom:1px solid #eee;">';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<input type="checkbox" value="'.$value["fid"].'" name="check_id">';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<img src="'.$value["avatar"].'" style="height:40px;width:40px;" class="layui-circle layim-msgbox-avatar">';
                                $html .= '</div>';
                                $html .= '<div class="col-xs-3" style="padding:0;">';
                                $html .= '<span style="color: #000;">'.$value["username"].'</span>';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-4" style="padding:0;text-align:right;">';
                                    $html .= '<button type="button" uid="'.$value["fid"].'" fzid="'.input("fzid").'"  class="btn btn-primary btn-sm addsinglegroup" style="background: #019688;">添加</button>';   
                                $html .= '</div>';
                            $html .= '</div>';
                        }
                    $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-xs-6" style="box-shadow: 1px 1px 1px #e6e6e9;padding:0px 10px;">';
                    $html .= '<span style="margin-right:10px;">我的群员列表</span>';
                    $html .= '<div class="col-xs-12" style="border-bottom:1px solid #eee;padding:0 0 5px 0;text-align:center;"><label class="checkbox-inline"><input type="checkbox" id="checkalldel">全选/反选</label> <button type="button" style="margin-left:5px;" id="checkchangealldel" class="btn btn-danger btn-sm">移除群成员</button></div>';
                    $html .= '<div class="col-xs-12" id="checklistdel" style="height:320px;overflow-y:auto;">';
                        foreach ($group_list as $key => $value) {
                            $html .= '<div class="col-xs-12" style="padding:7px 0;line-height:45px;border-bottom:1px solid #eee;">';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<input type="checkbox" value="'.$value["uid"].'" name="check_id">';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-2">';
                                $html .= '<img src="'.$value["avatar"].'" style="height:40px;width:40px;" class="layui-circle layim-msgbox-avatar">';
                                $html .= '</div>';
                                $html .= '<div class="col-xs-3" style="padding:0;">';
                                $html .= '<span style="color: #000;">'.$value["username"].'</span>';
                                $html .= '</div>';
                                 $html .= '<div class="col-xs-4" style="padding:0;text-align:right;">';
                                    $html .= '<button type="button" uid="'.$value["uid"].'" fzid="'.input("fzid").'" class="btn btn-danger btn-sm delsinglegroup">移除</button>';   
                                $html .= '</div>';
                            $html .= '</div>';
                        }
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';
            return $html;
        }else{
            return 0;
        }
    }

    //将好友添加到群组
    public function addgroupbyfriend()
    { 
        // 启动事务
        db("group_list")->startTrans();
        try{
            $woker = array();
            $limits = json_decode(input("limits"));
            foreach ($limits as $key => $value) {
                $map["fzid"] = input("fzid");
                $map["uid"] = $value;
                $group = db("group_list")->where($map)->find();
                if(!$group["groupname"]){
                    $user = db("userinfo")->where("id",$value)->find();
                    $data["groupname"] = input("groupname");
                    $data["fzid"] = input("fzid");
                    $data["uid"] = $value;
                    $data["avatar"] = $user["avatar"];
                    $data["username"] = $user["username"];
                    db("group_list")->insert($data);
                    $img["groupname"] = input("groupname");
                    $img["fzid"] = input("fzid");
                    $img["uid"] = $value;
                    $img["avatar"] = $user["avatar"];
                    $img["username"] = $user["username"];
                    $img["groupimg"] = input("groupimg");
                    array_push($woker,$img);
                    //将数据同步到group表
                    $now = db("group")->where("id",input("fzid"))->find();
                    if($now["grouplist"] != ""){
                        $arr = json_decode($now["grouplist"]);
                        array_push($arr,$value);
                        $now_arr = json_encode($arr);  
                        db("group")->where("id",input("fzid"))->setField("grouplist",$now_arr);
                    }else{
                        $arr = array();
                        array_push($arr,$value);
                        $now_arr = json_encode($arr);  
                        db("group")->where("id",input("fzid"))->setField("grouplist",$now_arr);
                    }
                    //将数据同步到userinfo表
                    $fuser = db("userinfo")->where('id',$value)->find();
                    if($fuser["group"] != ""){
                        $uarr = json_decode($fuser["group"]);
                        array_push($uarr,input("fzid"));
                        $ecdeu = json_encode($uarr);
                        db("userinfo")->where("id",$value)->setField("group",$ecdeu);
                    }else{
                        $uarr = array();
                        array_push($uarr,input("fzid"));
                        $ecdeu = json_encode($uarr);
                        db("userinfo")->where("id",$value)->setField("group",$ecdeu);
                    }
                    $s_layim = "layim_init".$value;
                    $layim = cache($s_layim,null);  
                }
            }
            db("group_list")->commit();
            return $woker;
        } catch (\Exception $e) {
            // 回滚事务
            db("group_list")->rollback();
            return 0;
        } 
    }

    //将好友移除群组
    public function addgroupbyfrienddel()
    { 
        // 启动事务
        db("group_list")->startTrans();
        try{
            $woker = array();
            $group = db("group")->where("id",input("fzid"))->find();
            $arr = json_decode($group["grouplist"]);
            $limits = json_decode(input("limitsdel"));
            foreach ($limits as $key => $value) {
                //判断是否群主
                if($group["belonger"] != $value){
                    $map["fzid"] = input("fzid");
                    $map["uid"] = $value;
                    db("group_list")->where($map)->delete();
                    //group里面的grouplist进行删除
                    $key = array_search($value, $arr);
                    if($key !== false){array_splice($arr, $key, 1);}
                    //userinfo的group进行更新
                    $userinfo = db("userinfo")->where("id",$value)->find();
                    $userarr = json_decode($userinfo["group"]);
                    $userkey = array_search(input("fzid"), $userarr);
                    if($userkey !== false){array_splice($userarr, $userkey, 1);}
                    $user_arr = json_encode($userarr);
                    db("userinfo")->where("id",$value)->setField("group",$user_arr);
                    $data["type"] = "group";
                    $data["fzid"] = input("fzid");
                    $data["uid"] = $value;
                    array_push($woker,$data);
                }   
            }
            //将最后的grouplist数据更新到group里面
            $now_arr = json_encode($arr);  
            db("group")->where("id",input("fzid"))->setField("grouplist",$now_arr);
            db("group_list")->commit();
            return $woker;
        } catch (\Exception $e) {
            // 回滚事务
            db("group_list")->rollback();
            return 0;
        } 
    }

    //移动好友到其他分组
    public function friendmove()
    {
        // return input("");
        // 启动事务
        db("friend_list")->startTrans();
        try{
            $woker = array();
            //找到之前的分组更新grouplist字段
            $friend = db("friend")->where("id",input("fzid"))->find();
            $farr = json_decode($friend["grouplist"]);
            $limits = json_decode(input("limits"));
            foreach ($limits as $key => $value) {                
                $friend_list = db("friend_list")->where("id",$value)->find();
                array_push($woker,$friend_list["fid"]);
                $fkey = array_search($friend_list["fid"], $farr);
                if($fkey !== false){array_splice($farr, $fkey, 1);}
                //将好友列表里面的分组更新成新的分组
                db("friend_list")->where("id",$value)->setField("fzid",input("xfzid"));
            }
            //将最后的grouplist数据更新到friend里面
            $f_arr = json_encode($farr);
            db("friend")->where("id",input("fzid"))->setField("grouplist",$f_arr);
            //新分组里面的grouplist字段更新
            $newfriend = db("friend")->where("id",input("xfzid"))->find();
            if($newfriend["grouplist"] != ""){
                $newcode = json_decode($newfriend["grouplist"]);
                foreach ($woker as $k => $v) {
                    array_push($newcode,$v);
                }
                $en_arr = json_encode($newcode);
                db("friend")->where("id",input("xfzid"))->setField("grouplist",$en_arr);
            }else{
                $en_arr = json_encode($woker);
                db("friend")->where("id",input("xfzid"))->setField("grouplist",$en_arr);
            }
            db("friend_list")->commit();
            return $woker;
        } catch (\Exception $e) {
            // 回滚事务
            db("friend_list")->rollback();
            return 0;
        } 
    }

}

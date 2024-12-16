<?php
namespace app\home\validate;
use think\validate;
class Myclass extends validate
{
    protected $rule = [
        'nicheng'  =>  'require|max:25',
        'phone'=>'regex:/^1[34578]\d{9}$/',
        'email' => 'email',
    ];

    protected $message = [
        'username.require'  =>  '用昵称必须填写',
        'username.max' =>  '用昵称长度不能大于25位',
        'phone' => '请输入正确的手机号码',
        'email' => '邮箱格式错误',
    ];

}
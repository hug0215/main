<?php
namespace app\home\validate;
use think\validate;
class Sign extends validate
{
    protected $rule = [
        'username'  =>  'require|max:25',
        'password' =>  'require|max:32',
        'passwordtwice' =>  'require|max:32',
        'verify' =>  'require|captcha',
    ];

    protected $message = [
        'username.require'  =>  '用户名必须填写',
        'username.max' =>  '用户名长度不能大于25位',
        'password.require' =>  '密码必须填写',
        'password.max' =>  '密码长度不能大于32位',
        'passwordtwice.require' =>  '二次密码必须填写',
        'passwordtwice.max' =>  '二次密码长度不能大于32位',
        'verify.require' =>  '验证码必须填写',
        'verify.captcha' =>  '验证码错误',
    ];

    protected $scene = [
        'edit'  =>  ['username','password'],
    ];
}
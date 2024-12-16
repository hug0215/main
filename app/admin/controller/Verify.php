<?php
namespace app\admin\controller;

use app\BaseController;
use think\captcha\facade\Captcha;

class Verify
{
    public function index()
    {
        return Captcha::create("verify");
    }

}

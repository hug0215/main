<?php
namespace app\controller;

use app\BaseController;

class Error 
{
	public function __call($name, $arguments)
    {
        return show(config('status.controller_not_found'),"找不到{$name}控制器",null,400);
    }
}
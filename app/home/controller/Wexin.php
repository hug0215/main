<?php
namespace app\home\controller;
use think\Controller;
use think\Cache;
class Wexin extends Controller{

  public function index()
  {
    return $this->fetch();
  }  

  public function mine()
  {
    return $this->fetch();
  }

  public function message()
  {
    return $this->fetch();
  }

    
}

<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\View;
use app\admin\validate\AdminUser as valAdminUser;
use think\Request;

class Login extends BaseController
{
	public function initialize(){
		parent::initialize();
	}
	/**
     * @var \think\Request Request实例
     */
    protected $request;
    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
		$this->request = $request;
    }

	public function index()
	{
		 return View::fetch();
	}

	public function check()
	{
		if(!$this->request->isPost()){
			return show(config("status.error"),'请求方式错误');
		}
		//参数校验
		$username = $this->request->param("username","","trim");
		$password = $this->request->param("password","","trim");
		$captcha = $this->request->param("captcha","","trim");

		$data = [
			'username'=>$username,
			'password'=>$password,
			'captcha'=>$captcha
		];
		$validate = new valAdminUser();
		if(!$validate->check($data)){
			return show(config("status.error"),$validate->getError());
		}

		try{
			$result = \app\admin\business\AdminUser::login($data);
		}catch(\Exception $e){
			return show(config("status.error"),$e->getMessage());
		}

		if($result){
			return show(config("status.success"),'登录成功');
		}else{
			return show(config("status.error"),'登录失败');
		}
				
	}
}
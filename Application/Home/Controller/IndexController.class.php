<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {
    // 框架首页
    public function index() {
		
		/*
		//测试数据
		$this->assign('name',$_SESSION['email']);
		
		// 实例化Data数据模型
		$Data = M('user'); 
        $this->data = $Data->select();
		$result = $Data->select();
		dump($result);
		$this->assign('A',I('session.email'));
		*/
		
		// dump(get_client_ip());
		
		$userModel = D('User');
		
		$this->assign('B',$_SESSION['loginUserName']);
		
		
        $this->display();
    }
	
	

}

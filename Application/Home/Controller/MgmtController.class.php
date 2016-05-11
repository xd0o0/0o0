<?php
namespace Home\Controller;
use Think\Controller;

class MgmtController extends CommonController {
    // 框架首页
    public function createuser() {	
	
		$this -> assign(title,"创建用户");
		$this -> assign(description,"新的朋友加入");
		
		if (IS_POST) {
			
			$userModel = D('User');
			$ftuuser = D('Ftu');
			
			if ($userModel->getEmailAddress($_POST['email']) || $ftuuser->getEmailAddress($_POST['email'])) {
				$this->error('帐号已存在或者邀请链接已发出！');
				
			}
			else {
				
				$host = $_SERVER['HTTP_HOST'];
				$data['ftukey'] = md5(time()); //生产随机码
				$data['email'] = $_POST['email'];
				
				//保存随机码
				$ftuuser-> add($data);
				
				
				$ftu_url = "http://$host/PHP/index.php/Home/Public/ftu/email/".$data['email']."/key/".$data['ftukey'] ;
				
				if(SendMail($_POST['email'],'XD新建用户',"请点击链接创建您的帐号:<a href='$ftu_url'>$ftu_url</a>"))
				{
					$this->success('新用户创建邮件已发送！');
				}
				else {
					$this->error('发送失败');
				}
					
			}
			
		}
		
		else {
			$this->display();
		}
	}
	
	
}

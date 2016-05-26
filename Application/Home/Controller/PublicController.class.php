<?php
namespace Home\Controller;

use Think\Controller;
use Org\Util\Rbac;


class PublicController extends Controller {
    
	    // 检查用户是否登录
    protected function checkUser() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error('没有登录','/PHP/Home/Public/login');
        }
    }

		
	// 用户登录页面
    public function login() {
		// dump($_COOKIE['xdaccount']);
		//isset 和 empty 最大区别是判断0字节的时候
		
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->redirect('/Home/index');
        }
		else if (!empty($_COOKIE['xdaccount']))
		{
			$User	=	D('user');
			$oneuser = $User -> getUserId($_COOKIE['xdaccount']);
			if ($_COOKIE['xdaccountkey'] == md5($oneuser['password']))
			{
				
				$_SESSION[C('USER_AUTH_KEY')]	=	$oneuser['id'];
				$_SESSION['email']				=	$oneuser['email'];
				$_SESSION['loginUserName']		=	$oneuser['nickname'];
				$_SESSION['lastLoginTime']		=	$oneuser['last_login_time'];
				$_SESSION['login_count']		=	$oneuser['login_count'];
				 
		
				//保存登录信息
				$ip		=	get_client_ip();
				$time	=	time();
				$userdata = array();
				$userdata['id']	=	$oneuser['id'];
				$userdata['last_login_time']	=	$time;
				$userdata['login_count']	=	array('exp','login_count+1');
				$userdata['last_login_ip']	=	$ip;
				$User->save($userdata);
				
				$this->redirect('/Home/index');
			}
			else {
				setcookie("xdaccount", null, time()-3600*24*365);
				setcookie("xdaccountkey", null, time()-3600*24*365); 	
				$this->display();
			}
		}		
		else {
            $this->display();
		}
		
    }

    public function index() {
        //如果通过认证跳转到首页
        redirect(__MODULE__);
    }

	    // 登录检测
    public function checkLogin() {


        //生成认证条件
        $map = array();
		
        // 支持使用绑定帐号登录
        $map['account']	= $_POST['account'];
        $map["status"]	=	array('gt',0);          
 
        $authInfo = RBAC::authenticate($map);
		
        //使用用户名、密码和状态 的方式进行认证
        if(false == $authInfo) {
            $this->error('帐号未激活或已禁用！');
        }
		else {
			
            // if($authInfo['password'] != $_POST['password']) {
                // $this->error('密码错误！');
        // }
		
			if(!($this->checkldap($_POST['account'],$_POST['password'])))
			{
				$this->error('密码错误！');
			}
			

			
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['email']				=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
            $_SESSION['login_count']		=	$authInfo['login_count'];
			 
	
			//记住登录信息
			if(!empty($_POST['remember'])){     //如果用户选择了，记录登录状态就把用户名加了密  
				$useridcookie=$_SESSION[C('USER_AUTH_KEY')];
				$keycookie = md5($_POST['password']);
				
				setcookie("xdaccount", $useridcookie, time()+3600*24*365); 
				setcookie("xdaccountkey", $keycookie, time()+3600*24*365);  				
			}  
			
			// dump($_COOKIE['xdaccount']);
		
			//保存登录信息
            $User	=	M('user');
            $ip		=	get_client_ip();
            $time	=	time();
            $userdata = array();
            $userdata['id']	=	$authInfo['id'];
            $userdata['last_login_time']	=	$time;
            $userdata['login_count']	=	array('exp','login_count+1');
            $userdata['last_login_ip']	=	$ip;
			
			//更新密码信息
			if($authInfo['password'] != $_POST['password']) {
				$userdata['password']	=  $_POST['password'];
			}
			
            $User->save($userdata);
		
		
            // 缓存访问权限
            RBAC::saveAccessList();
			
			$data['status'] = 1;
			$data['info'] = '登录成功！';
			$this->ajaxReturn($data);
        
			// $data['info'] = '登录成功！';
			// $this->ajaxReturn($data);
			
		//		$this->success('登录成功！',__MODULE__.'/Index/index');

		
		/*
		// 测试信息	
			$this->assign('name',$authInfo['email']);
			$this->assign('A','带我嗨');
			$this->assign('B',$_POST['password']);
			$this->display();
		
			        
			$data['info'] = $_POST['remember'];
			$this->ajaxReturn($data);
		*/	
        }
    }
	
	//重置密码发送邮件
	public function ForgetPassword() {
		
		$userModel = D('User');
		$userA = $userModel->getEmailAddress($_POST['forgetemail']);
		// dump($userModel->getEmailAddress($_POST['forgetemail']));
		if ($userA)
		{
			$host = $_SERVER['HTTP_HOST'];
			$key = md5(time()); //生产随机码
			$userid = $userA['id'];
			
			//保存随机码
			$userModel-> updateUserKey($userid,$key);
			
			$reset_url = "http://$host/PHP/index.php/Home/Public/ResetPage/id/$userid/key/$key";
			
			if(SendMail($_POST['forgetemail'],'XD重置密码',"请点击链接重置你的密码:<a href='$reset_url'>$reset_url</a>"))
			{
                $this->success('重置邮件已发送！');
			}
            else {
                $this->error('发送失败');
			}
			
		//	echo ($userModel->getLastSql());
		}
		else
		{
			$this->error('帐号不存在！');
		}
		
	}

	//重置密码页面	
	public function ResetPage() {
		$userModel = D('User');
		if (IS_POST && $_SESSION['repwuserid']) {
				
				$userCP = $userModel-> getUserId($_SESSION['repwuserid']);
				if ($userCP) {
				
				//更改密码
				$userModel-> updatePW($_SESSION['repwuserid'],$_POST['password1']);
				// $this->success("powershell -file D:\workspaces\PHP\Action\ad.ps1 1 ".$userCP['account']." '".$_POST['password1']."'");
				system("powershell -file D:\workspaces\PHP\Action\ad.ps1 1 ".$userCP['account']." ".$_POST['password1']);
				
				//删除时间码
				$userModel-> deleteUserKey($_SESSION['repwuserid']);
				
				unset($_SESSION);
				session_destroy();
			
				/* 测试信息
				$data['status'] = 1;
				$data['info'] = '密码修改成功！';
				$this->ajaxReturn($data);
				*/
				
				$this->success('密码设置成功');
				}
				
				else {
				$this->error('该链接已被使用或者用户不存在');
				}
			}
			
		else{
				//判断页面是否有效
				if ($userModel->getUserKey($_GET['id'],$_GET['key'])) {
					$_SESSION['repwuserid'] = $_GET['id'];
				//	dump($_SESSION['repwuserid']);
					$this -> display();
					
				}
				else {
					
					//测试信息
				//	$this -> display();
				//	dump($_SESSION['repwuserid']);
				
					//页面无效跳转
					$this->error('密码重置链接无效！','/PHP/Home/Public/login',3);
					}
			}
	}
		
	//首次使用
	public function FTU() {
		$userModel = D('User');
		$ftuModel = D('Ftu');
		if (IS_POST && $_SESSION['ftuemail']) {

			//进行注册
			if ($userModel->create() && $userModel->add()) {
			//	dump($userModel->getLastSql());
			
			//	删除缓存表
				$ftuModel-> deleteftuKey($_SESSION['ftuemail']);
				unset($_SESSION);
				session_destroy();
			//	dump($ftuModel->getLastSql());
			
				$this->success('注册成功',PHP_FILE.C('USER_AUTH_GATEWAY'),3);
            } 
            else {
                $this->assign('errors', $userModel->getError());
                $this->assign('old', I('post.'));
                $this->display();
			//	dump($userModel->getError());
			//	dump($userModel->getLastSql());
            }
		}
		else
		{
			//判断页面是否有效
			if (!($ftuModel->getftuKey($_GET['email'],$_GET['key']))) {
			//	dump('页面有效');
			//	$this -> display();
			//  dump('页面无效');
				$this->error('邀请链接无效！',PHP_FILE.C('USER_AUTH_GATEWAY'),2);
			}
			else {
				$_SESSION['ftuemail'] = $_GET['email'];
				$_SESSION['ftuusername'] = $ftuModel->getftuUser($_GET['key']);
				// dump('11111');
				// dump($ftuModel->getlastsql());
				// dump($_SESSION['ftuemail']);

				$this -> display();
			}
		}
	}
	
	    // 用户登出
    public function logout() {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
			
			if(isset($_COOKIE['xdaccount']) || isset($_COOKIE['xdaccountkey'])){  
				setcookie("xdaccount", null, time()-3600*24*365);
				setcookie("xdaccountkey", null, time()-3600*24*365); 
			}
			redirect(PHP_FILE .C('USER_AUTH_GATEWAY'));
			
        }else {
            redirect(PHP_FILE .C('USER_AUTH_GATEWAY'));
        }
    }

	
	public function checkldap($username,$password) {
		$ds=ldap_connect("172.16.80.248","389");  // must be a valid LDAP server!
		
		if ($ds) 
		{
			if (ldap_bind($ds, $username , $password))
			{
				return true;
			}
			else
			{
				return false;
			}
			
			// ldap_close($ds);
		}
		else
		{
			$this->error('无法连接域控');
		}
	}
	
	public function accesserror() {
		$this -> display();
	}
	
}

	
	

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
	
	
	public function test() {
		
		// system("powershell -file Action\ad.ps1 0 ccc @WSX3edc");
		
		$this->error('邀请链接无效！',PHP_FILE.C('USER_AUTH_GATEWAY'),2);
		/*
		$ldapusername='XD1\\administrator';
		$ldapuserpassword ='@WSX3edc4';
		$ds=ldap_connect("xd1ad.xd1.com","389");  // must be a valid LDAP server!
		
		if ($ds) 
		{
			dump('ds ok');
			if (ldap_bind($ds, $ldapusername , $ldapuserpassword))
			{
				dump('bind ok');
				
				// $this->ldapAddUser($ds,'CN=Users,DC=xd1,DC=com','ccc','@WSX3edc');
				
				// $ldaptree = "OU=Users,DC=xd1,DC=com";
				// add data to directory
				if(ldap_count_entries($ds, ldap_search($ds, "CN=Users,DC=xd1,DC=com", "samAccountName=wangliang")))
				{
					dump('有个用户');
				}
				else 
				{
					dump(2);
				}
				
			}
			else
			{
				dump('无法Bind');
			}
			
			// ldap_close($ds);
		}
		else
		{
			dump('无法连接域控');
		}
		
		
		if (ldap_bind($ds, 'ccc' , '@WSX3edc'))
		{
			dump('用户可用');
		}
		else
		{
			dump('用户不可用');
		}
		
		ldap_close($ds);
		
		 // dump(phpinfo());
		
		*/
        $this->display();

    }
	
	
	/*
	
	public function ldap(){
		$ldapusername='XD1\\administrator';
		$ldapuserpassword ='@WSX3edc4';
		$ds=ldap_connect("172.16.80.248","389");  // must be a valid LDAP server!
		if ($ds) 
		{
			if (ldap_bind($ds, $ldapusername , $ldapuserpassword))
			{
			
				// add data to directory
				$this->ldapAddUser($ds,'OU=Users,DC=xd1,DC=com','ccc','ccc','ccc','ccc','ccc@x1.com');
			}
			else
			{
				dump('无法Bind');
			}
			
			ldap_close($ds);
		}
		else
		{
			dump('无法连接域控');
		}
		
        $this->display();
	
	}
		
	public function ldapAddUser($ldap_conn, $ou_dn, $username, $pwdtxt){
		$dn = "CN=$username,".$ou_dn;

		## Create Unicode password
		$newPassword = "\"" . $pwdtxt . "\"";
		$len = strlen($newPassword);
		$newPassw = "";
		for($i=0;$i<$len;$i++) {
			$newPassw .= "{$newPassword{$i}}\000";
		}
		
		$ldaprecord['cn'] = $username;
		$ldaprecord['displayName'] = $username;
		$ldaprecord['name'] = $username;

		$ldaprecord['sn'] = $username;

		$ldaprecord['objectclass'] = array("top","person","organizationalPerson","user");
		$ldaprecord["sAMAccountName"] = $username;
		$ldaprecord["userprincipalname"] = $username.'@xd1.com';
		// $ldaprecord["unicodePwd"] = "\"". iconv('UTF-8','UTF-16LE',$pwdtxt) ."\"";
		$ldaprecord["UserAccountControl"] = "544"; 

		$r = ldap_add($ldap_conn, $dn, $ldaprecord);
		if($r){
			dump("succeded1");
		}
		// set password .. not sure if I need to base64 encode or not
		$encodedPass = array('userpassword' => base64_encode($newPassw));
		// $encodedPass = array('userpassword' => "{MD5}".base64_encode(pack("H*",md5('@WSX3edc'))));
		// $encodedPass = array('userpassword' => "@WSX3edc");
		//$encodedPass = array('unicodepwd' => $newPassw);

		if(ldap_mod_replace ($ldap_conn, $dn, $encodedPass)){
			dump("succeded2");
		}else{
			dump("failed");
		}
	}
	*/
}

	

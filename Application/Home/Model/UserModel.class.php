<?php
namespace Home\Model;

use Think\Model;

class UserModel extends Model
{
	
		protected $patchValidate = true;
	
	    protected $_validate = array(
        array(
            'nickname',
            'require',
            '用户名必须填写',
        ) ,
        array(
            'nickname',
            '',
            '用户名被别人占用了',
            self::EXISTS_VALIDATE,
            'unique',
			self::MODEL_BOTH
        ) ,
		array(
            'account',
            '',
            '登录名被别人占用了',
            self::EXISTS_VALIDATE,
            'unique',
            self::MODEL_INSERT
        ) ,
		array(
            'account',
            '/^[A-Za-z0-9\\-]+$/',
            '登录名必须由英文字符和数字组成'
        ) ,
        array(
            'password',
            'require',
            '密码必须填写'
        ) ,
        array(
			'password',
            'confirm_password',
            '确认密码不一致',
            self::MUST_VALIDATE,
            'confirm',
            self::MODEL_INSERT
        ) ,
        array(
            'email',
            'require',
            '电子邮件必须填写'
        ) ,
        array(
            'email',
            'email',
            '电子邮件格式不对'
        ) ,
        array(
            'email',
            '',
            '邮箱已经被注册过了',
            self::EXISTS_VALIDATE,
            'unique',
            self::MODEL_INSERT
        ) ,
        array(
            'birthday',
            'checkBirthday',
            '生日超出可能范围',
            self::VALUE_VALIDATE,
            'callback',
            self::MODEL_BOTH
        ) ,
        array(
            'birthday',
            'require',
            '生日必须填写'
        ) ,
		array(
            'birthday',
            '/^\d{4}\-(0[1-9]|1[0-2])-(3[01]|[12]\d|0[1-9])$/',
            '生日格式不对'
        ) ,
		array(
            'phone',
            '/^1\d{10}$/',
            '电话格式不对'
        ) ,
		array(
            'skype',
            '/^live:[a-zA-Z0-9_]+$/',
            'skype帐号是以live:开头'
        ) ,
		array(
            'employeeid',
            '/^P[0-9]{7}+$/',
            '员工号格式不对'
        ) ,
    );
	
	protected $_auto = array(
        array(
            'create_time',
            'time',
            self::MODEL_INSERT,
            'function'
        ) ,
        array(
            'status',
            1
        ) ,
    );
	
	//通过Email获取用户
	public function getEmailAddress($EmailAddress)
	{
		$condition=array(
				'email'=> $EmailAddress
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	//通过ID获取用户
	public function getUserId($UserId)
	{
		$condition=array(
				'id' => $UserId,
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	//保存随机码
    public function updateUserKey($userid,$key) {
    	$this->id=$userid;
    	$this->key=$key;

    	$this->save();
    }
	
	//删除随机码
    public function deleteUserKey($userid) {
    	$this->id=$userid;
    	$this->key=null;

    	$this->save();
    }
	
	//判断重置链接是否有效
	public function getUserkey($UserId,$Key)
	{
		$condition=array(
				'id' => $UserId,
				'key'=> $Key
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	//修改密码
    public function updatePW($userid,$password) {
    	$this->id=$userid;
    	$this->password=$password;

    	$this->save();
    }
	
    //检查生日
	function checkBirthday($value) 
	{
        
        $start = strtotime('1900-1-1');
        $end = NOW_TIME;
        $value_time = strtotime($value);
        
        return $value_time >= $start && $value_time <= $end;
    }
	
	//通过ID获取用户密码
    
}
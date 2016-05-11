<?php
namespace Home\Model;

use Think\Model;

class FtuModel extends Model
{
	//判断邀请链接是否已生成
	public function getEmailAddress($email)
	{
		$condition=array(
				'email'=> $email,
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	//判断重置链接是否有效
	public function getFtukey($email,$FtuKey)
	{
		$condition=array(
				'email'=> $email,
				'ftukey'=> $FtuKey
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	//删除随机码
    public function deleteftuKey($email) {
		$condition=array(
				'email'=> $email,
			);
    	$this-> where($condition) ->delete();
    }

}
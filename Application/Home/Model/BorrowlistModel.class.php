<?php
namespace Home\Model;

use Think\Model;

class BorrowlistModel extends Model
{
	
	//获取常用设备当前状态
	public function getBorrowList()
	{
		$result=$this->where('type <> "Phone / Pad"')->select();
		
		return $result;
	}
	
	//获取常用设备当前需审批的装备
	public function getApproList()
	{
		$result=$this->where('type <> "Phone / Pad" And (approved = 1 OR approved = 3)')->select();
		
		return $result;
	}
	
	//根据类型获取常用设备当前状态
	public function getBorrowListByType($type)
	{
		$result=$this->where('type ="'.$type.'"')->select();
		
		return $result;
	}
	
	
		//获取移动设备当前状态
	public function getMBorrowList()
	{
		$result=$this->where('type = "Phone / Pad"')->order('ID')->select();
		
		return $result;
	}
	
	//获取移动设备当前需审批的装备
	public function getMApproList()
	{
		$result=$this->where('type = "Phone / Pad" And (approved = 1 OR approved = 3)')->order('ID')->select();
		
		return $result;
	}
	
	//根据类型获取移动设备当前状态
	public function getMBorrowListByType($type)
	{
		$result=$this->where('SubType ="'.$type.'"')->order('ID')->select();
		
		return $result;
	}
	
	//根据用户获取设备当前状态
	public function getBorrowlistByUser($user)
	{
		$result=$this->where('UserId ="'.$user.'"')->order('ID')->select();
		
		return $result;
	}
	
	//获取所有设备当前需审批的装备
	public function getAllApproList()
	{
		$result=$this->where('approved = 1 OR approved = 3')->order('ID')->select();
		
		return $result;
	}
}
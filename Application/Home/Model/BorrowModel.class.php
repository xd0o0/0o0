<?php
namespace Home\Model;

use Think\Model;

class BorrowModel extends Model
{
	//通过number获得borrow条目信息
	public function getBorrowid($id)
	{
		$condition=array(
				'Number' => $id,
			);
		$result=$this->where($condition)->find();
		
		return $result;
	}
	
	
	//借设备
	public function borrowid($id)
	{
		$this->Number=$id;
		$this->UserID=$_SESSION[C('USER_AUTH_KEY')];
		$this->BorrowDate=date("Y-m-d", time());
		$this->Approved=1;
		
    	$this->save();
	}
	
	//取消借设备
	public function cancelborrowid($id)
	{
		$this->Number=$id;
		$this->UserID=Null;
		$this->BorrowDate=Null;
		$this->Approved=0;
		
    	$this->save();
	}
	
	//审核通过借设备
	public function brapprovedid($id)
	{
		$this->Number=$id;
		$this->BorrowApproUser=$_SESSION[C('USER_AUTH_KEY')];
		$this->BorrowApproDate=date("Y-m-d", time());
		$this->Approved=2;
		
    	$this->save();
	}
	
	//归还设备
	public function returnid($id)
	{
		$this->Number=$id;
		$this->ReturnDate=date("Y-m-d", time());
		$this->Approved=3;
		
    	$this->save();
	}

	//归还审核设备
	public function reapprovedid($id)
	{
		$this->Number=$id;
		$this->ReturnApproUser=$_SESSION[C('USER_AUTH_KEY')];
		$this->ReturnApproDate=date("Y-m-d", time());
		$this->Approved=4;
		
    	$this->save();
	}
	
	//取消还设备
	public function cancelreturnid($id)
	{
		$this->Number=$id;
		$this->ReturnDate=Null;
		$this->Approved=2;
		
    	$this->save();
	}
}
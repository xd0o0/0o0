<?php
namespace Home\Model;

use Think\Model;

class CabilityModel extends Model
{
				
	protected $_validate = array(
        array(
            'ID',
            'require',
            '设备编号必需写',
        ) ,
        array(
            'ID',
            '',
            '设备编号被别人占用了',
            self::EXISTS_VALIDATE,
            'unique',
			self::MODEL_BOTH
        ) ,
        array(
            'Paraeter',
            'require',
            '设备参数必须填写'
        ) ,
		array(
            'Type',
            'require',
            '设备类型必须填写'
        ) ,
        array(
            'Type',
            'checkType',
            '设备类型超出可能范围',
            self::VALUE_VALIDATE,
            'callback',
            self::MODEL_BOTH
        ) ,
		array(
            'SubType',
            'checkSubType',
            '设备类型超出可能范围',
            self::VALUE_VALIDATE,
            'callback',
            self::MODEL_BOTH
        ) ,
    );
	
	protected $_auto = array(
	    array(
            'BuyTime',
            'time',
            self::MODEL_INSERT,
            'function'
        ) ,
	); 
	
	//检查类型
	function checkType($value) 
	{
		$result=$this->where('type ="'.$value.'" and Damaged != 1')->find();
		if($result)
		{ 
			return true;
		}
		else {
			return false;
		}
    }
	
	//检查子类型
	function checkSubType($value) 
	{
		$result=$this->where('SubType ="'.$value.'" and Damaged != 1')->find();
		if($result)
		{ 
			return true;
		}
		else {
			return false;
		}
    }
	 
	//获取常用设备类型
	public function getTypeList()
	{
		$condition=array(
				'Damaged' => 0,
			);
		$result=$this->distinct(true)->field('Type')->where($condition)->where('type <> "Phone / Pad"')->order('Type')->select();
		
		return $result;
	}
	
	//获取移动设备类型
	public function getMTypeList()
	{
		$condition=array(
				'Damaged' => 0,
			);
		$result=$this->distinct(true)->field('SubType')->where($condition)->where('type = "Phone / Pad"')->order('SubType')->select();
		
		return $result;
	}
	
	//获取所有损坏设备列表
	public function getDamagedList()
	{
		$result=$this->where('Damaged = 1')->select();
		
		return $result;
	}
	
	
	//获取常用设备列表
	public function getDevicesList()
	{
		$result=$this->where('Type <> "Phone / Pad" and Damaged != 1')->select();
		
		return $result;
	}
	
	//获取移动设备列表
	public function getMDevicesList()
	{
		$result=$this->where('Type = "Phone / Pad" and Damaged != 1')->select();
		
		return $result;
	}
	
	//根据类型获取常用设备列表
	public function getDevicesListByType($type)
	{
		$result=$this->where('type ="'.$type.'" and Damaged != 1')->select();
		
		return $result;
	}
	
	//根据类型获取移动设备列表
	public function getMDevicesListByType($type)
	{
		$result=$this->where('SubType ="'.$type.'" and Damaged != 1')->select();
		
		return $result;
	}
	
	//设备损坏
	public function damageddevices($did)
	{
		$this->DID=$did;
		$this->DamagedDate=date("Y-m-d", time());
		$this->Damaged=1;
		
		$this->save();
	}
	
}
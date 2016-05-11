<?php
namespace Home\Model;

use Think\Model;

class OfficeCabilityModel extends Model
{
				
	protected $_validate = array (
	    array(
            'CabilityName',
            'require',
            '产品名称必需写',
        ) ,
        array(
            'CabilityName',
            '',
            '产品名称被占用了',
            self::EXISTS_VALIDATE,
            'unique',
			self::MODEL_BOTH
        ) ,
        array(
            'Brand',
            'require',
            '品牌必须填写'
        ) ,
		array(
            'Model',
            'require',
            '型号必须填写'
        ) ,
		array(
            'Price',
            'require',
            '价格必须填写'
        ) ,
		array(
            'Unit',
            'require',
            '单位必须填写'
        ) ,
        array(
            'Price',
            '/^\d+(\.\d+)?$/',
            '价格格式不对'
        ) ,
    );
	
	protected $_auto = array(
	); 

	//获取办公类型用于创建申请
	public function getTypeList()
	{
		$condition=array(
				'Disable' => 0,
			);
		$result=$this->where($condition)->order('CabilityID')->select();
		
		return $result;
	}
	
	//根据ID获取办公用品属性
	public function getOfficebyID($CabilityID)
	{
		$result=$this->where('CabilityID ="'.$CabilityID.'" and Disable != 1')->find();
		
		return $result;
	}
	
}
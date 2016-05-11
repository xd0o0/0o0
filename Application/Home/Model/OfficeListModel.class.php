<?php
namespace Home\Model;

use Think\Model;

class OfficeListModel extends Model
{
				
	//根据Userid和PID获取本月的已申请办公用品
	public function getNeedbyUserID($userid,$lastperiodid) {
		
		$result=0;
		$result=$this->where('UserID ="'.$userid.'" and PeriodID ="'.$lastperiodid.'"')->SUM('Count*binary(Price)');
		
		return $result;
	}
	
	//根据PID查询本期申请
	public function getNeedbyPeriodid($periodid) {
		
		$result=$this->where('PeriodID ="'.$periodid.'"')->order('UserID')->select();
		
		return $result;
	}
	
	//根据PID查询本期申请总和
	public function getTotalByPeriodid($periodid) {
		$result=$this->query('SELECT sum(Count) AS allcount,CabilityName,Brand,Model,Unit,Price from xd_office_list where PeriodID="'.$periodid.'" GROUP BY PID');
		
		return $result;
		
	}
}
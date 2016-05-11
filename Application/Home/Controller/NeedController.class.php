<?php
namespace Home\Controller;
use Think\Controller;

class NeedController extends CommonController {
    // 框架首页
    public function index() {
			$this -> assign(title,"办公用品");
			$this -> assign(description,"查看、申请办公用品");
			$data = array(
			array('name' => "申请首页", link =>  __CONTROLLER__."/index"),
			array('name' => "申请历史", link => __CONTROLLER__."/history"),
			array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
			array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
			);
			$this -> assign(othertitle,$data);

			$OfficePeriodModel = D('OfficePeriod');
			$OfficeListModel = D('OfficeList');

			$periodid = $OfficePeriodModel->getLatestPeriod();

			$this -> assign(typelist,$OfficePeriodModel->getOfficeByPeriodid($periodid));
			$this -> assign(needlist,$OfficeListModel->getNeedbyPeriodID($periodid));
			$this -> assign(periodname,$OfficePeriodModel->getPeriodnameByPeriodid($periodid));
			$this -> assign(periodid,$periodid);

			$this -> display();
	}

	//创建申请
	public function create() {
		$OCabilityModel = D('OfficeCability');
		$OfficePeriodModel = D('OfficePeriod');
		$OfficeListModel = D('OfficeList');
		$OfficeBalanceModel = M('OfficeBalance');

		if (IS_POST) {
			$lastperiodid = $OfficePeriodModel->getLatestPeriod();

			if($_POST['data']) {
				if($OfficeListModel->getNeedbyPeriodid($lastperiodid)){

					// dump($_POST['data']);
					foreach ($_POST['data'] as $Cabilitykey => $eachCabilityID) {
						$temp = $OCabilityModel->getOfficebyID($eachCabilityID);
						//数据库区分大小写，不合适配置
						$datalist[$Cabilitykey]['CabilityName'] = $temp['cabilityname'];
						$datalist[$Cabilitykey]['Brand'] = $temp['brand'];
						$datalist[$Cabilitykey]['Model'] = $temp['model'];
						$datalist[$Cabilitykey]['Price'] = $temp['price'];
						$datalist[$Cabilitykey]['Unit'] = $temp['unit'];
					}

					// dump($datalist);

					// foreach($datalist as $value){
						// dump($value);
					// }
					// dump($OfficePeriodModel -> getLatestPeriod());
					// dump($OfficePeriodModel -> getLastSQL());

					if ($OfficePeriodModel -> createNewPeriod($datalist,$_POST['pname']))
					{
						//统计超费人员
						$BalanceList = $OfficeBalanceModel->where('Balances > 2 AND PeriodID="'.($lastperiodid-1).'"')->select();

						// dump($BalanceList);
						// dump($OfficeBalanceModel -> getlastsql());

						//添加欠费信息
						foreach ($BalanceList as $eachbalance) {
							$dataBalance['UserID'] = $eachbalance['userid'];
							$dataBalance['Balances'] = $eachbalance['balances'] -2 ;
							$dataBalance['PeriodID'] = $lastperiodid;
							$dataBalance['Date'] = date('Y-m-d');

							$OfficeBalanceModel->data($dataBalance)->add();
							// dump($dataBalance);
						}
						$this->success('创建成功，快让小伙伴来申请吧');
					}
					else {
						$this->error('创建失败，请联系管理员');
					}

					// dump($OfficePeriodModel -> getLastSQL());
				}
				else {
					// $this->error($OfficePeriodModel->getLatestPeriod());
					$this->error('由于当前一期并没有人申请办公用品，出于某种脑残的理由，不让你创建新的申请...');
				}
			}
			else {
				$this->error('至少选择一个办公用品');
			}

		}
		else {
			$this -> assign(title,"创建申请");
			$this -> assign(description,"创建新的申请");
			$data = array(
			array('name' => "申请首页", link =>  __CONTROLLER__."/index"),
			array('name' => "申请历史", link => __CONTROLLER__."/history"),
			array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
			array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
			);
			$this -> assign(othertitle,$data);


			$this -> assign(typelist,$OCabilityModel->getTypeList());

			$this -> display();
		}
	}


	//提交申请
	public function apply() {
		if(IS_POST) {
			$OfficeModel = D('OfficeNeed');
			$OfficeBalanceModel = M('OfficeBalance');
			$OfficePeriodModel = D('OfficePeriod');
			$OfficeListModel = D('OfficeList');

			//获取本次的pid
			$lastperiodid=$OfficePeriodModel->getLatestPeriod();



			//获取本次申请信息
			$temp = $OfficePeriodModel->getOfficeByPID($_POST['PID']);

			//获取本月已申请信息(总价)
			$pn = $OfficeListModel-> getNeedbyUserID($_SESSION[C('USER_AUTH_KEY')],$lastperiodid);


			// dump($pn);
			// dump($OfficeModel->getlastsql());

			//获取欠费信息
			$balance = $OfficeBalanceModel->where('UserID ="'.$_SESSION[C('USER_AUTH_KEY')].'" AND PeriodID="'.($lastperiodid-1).'"')->getField('Balances');

			// dump($balance);

			// dump($temp);
			// dump($temp['cabilityid']);

			$bn = 2 - $balance -$pn;

			// dump($temp);
			// dump($pn);
			// dump($bn);
			// dump($temp['price']);

			//判断欠费信息	 溢价的申请用品超过2件 或者 非足额的用户 都禁止超额申请
			if (($bn == 2 && $_POST['Count'] == 1) || ($temp['price']*$_POST['Count'] <= $bn))
			{

				$adddata['Count'] = $_POST['Count'];
				$adddata['UserID'] = $_SESSION[C('USER_AUTH_KEY')];
				$adddata['PID'] = $_POST['PID'];
				$adddata['Date'] = date('Y-m-d');

				// dump ($OCabilityModel->data());
				// dump ($adddata);
				// dump ($OCabilityModel->getOfficebyID($_POST['CabilityID']));

				//添加欠费信息
				if($temp['price']*$_POST['Count'] > $bn)
				{
					$balancedata['UserID'] = $_SESSION[C('USER_AUTH_KEY')];
					$balancedata['Date'] = date('Y-m-d');
					$balancedata['Balances'] = $temp['price']*$_POST['Count']-$bn;
					$balancedata['PeriodID'] = $lastperiodid;
					$OfficeBalanceModel->data($balancedata)->add();
				}

				if ($OfficeModel->data($adddata)->add())
					{
					$data['status'] = 1;
					$data['info'] = "申请成功";
				//	$data['info'] = $OfficeModel->getLastSQL();
					$this->ajaxReturn($data);
					}
				else {
				//	$data['info'] = $OfficeModel->getLastSQL();
					$data['info'] = $OfficeModel->getError();
					$this->ajaxReturn($data);
				}
			}
			else {
				// dump($bn);
				// dump($temp['price']*$_POST['Count']+$balance);
				$data['info'] = "亲！你已经欠费停机，请勿超额申请(本月还可申请金额为".$bn.")。";
				$this->ajaxReturn($data);
			}
		}
		else {
			$this -> error('申请失败');
		}
	}

	//取消申请
	public function cancel() {
		if($_POST['number']) {
			$OfficeModel = D('OfficeNeed');
			if($_SESSION[C('USER_AUTH_KEY')] == $OfficeModel->getUserIDbyNumber($_POST['number'])){
				$OfficeModel-> deletebyNumber($_POST['number']);

				$data['status'] = 1;
				$data['info'] = "取消成功";
				$this->ajaxReturn($data);
			}
			else {
				$this -> error('取消失败');
			}
		}

	}

	//导出Excel
	public function excel() {
		$OfficePeriodModel = D('OfficePeriod');
		$OfficeListModel = D('OfficeList');


		$periodname = $OfficePeriodModel->getPeriodnameByPeriodid($_GET['tempdate']);

		// header("content-type:application/csv;charset=UTF-8");
		header("Content-type:application/vnd.ms-excel;charset=UTF-8");
		header("Content-Disposition:attachment;filename=".$periodname.".xls");

		//Excel转码输出
		print(chr(0xEF).chr(0xBB).chr(0xBF));


		$this -> assign(needlist,$OfficeListModel->getNeedbyPeriodid($_GET['tempdate']));


		$this -> assign(typelist,$OfficeListModel->getTotalByPeriodid($_GET['tempdate']));

		// dump ($OfficeListModel->getlastsql());
		$this -> display();
	}

	public function history() {
		$this -> assign(title,"往期申请");
		$this -> assign(description,"查询往期申请");
		$data = array(
		array('name' => "申请首页", link =>  __CONTROLLER__."/index"),
		array('name' => "申请历史", link => __CONTROLLER__."/history"),
		array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
		array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
		);
		$this -> assign(othertitle,$data);

		$OfficePeriodModel = D('OfficePeriod');
		$OfficeListModel = D('OfficeList');

		$this -> assign(periodlist,$OfficePeriodModel->getPeriodList());
		// dump($OfficePeriodModel->getPeriodList());


		if($_GET['periodid']) {
			$periodid = $_GET['periodid'];
		}
		else
		{
			$periodid = $OfficePeriodModel->getLatestPeriod();
		}

		$this -> assign(needlist,$OfficeListModel->getNeedbyPeriodID($periodid));

		$this -> assign(periodname,$OfficePeriodModel->getPeriodnameByPeriodid($periodid));
		$this -> assign(periodid,$periodid);
		$this -> display();

	}

	public function edit() {
		$this -> assign(title,"管理用品");
		$this -> assign(description,"编辑修改用品");
		$data = array(
		array('name' => "申请首页", link =>  __CONTROLLER__."/index"),
		array('name' => "申请历史", link => __CONTROLLER__."/history"),
		array('name' => "管理用品", link =>  __CONTROLLER__."/edit"),
		array('name' => "创建申请", link =>  __CONTROLLER__."/create"),
		);
		$this -> assign(othertitle,$data);

		$OCabilityModel = D('OfficeCability');

		if(IS_POST) {
			if ($OCabilityModel->create()) 
			{	
				if ($OCabilityModel->add()) 
				{
					$ajaxdata['status'] = 1;
					$ajaxdata['info'] = "修改成功";
					$this->ajaxReturn($ajaxdata);
				}
				else 
				{
					$ajaxdata['status'] = 0;
					$ajaxdata['info'] = "数据未修改";
					$this->ajaxReturn($ajaxdata);
				}
			}
			else 
			{
				$ajaxdata['status'] = 0;
				$ajaxdata['info'] = $OCabilityModel->getError();
				// dump ($OCabilityModel->getlastsql());
				$this->ajaxReturn($ajaxdata);
			}
			
		}
	
		$this -> assign(needlist,$OCabilityModel -> getTypeList());

		$this -> display();


	}

	public function save() {
		if(IS_POST){
		$OCabilityModel = D('OfficeCability');
			if ($OCabilityModel->create()) {
				if ($OCabilityModel->save()) {
					$data['status'] = 1;
					$data['info'] = "修改成功";
					$this->ajaxReturn($data);
				}
				else {
					$data['status'] = 1;
					$data['info'] = "数据未修改";
					$this->ajaxReturn($data);
				}
			}
			else {
				$data['status'] = 1;
				$data['info'] = $OCabilityModel->getError();
				$this->ajaxReturn($data);
			}
		}
		else {
			$this->error('输入有误');
		}
	}

}

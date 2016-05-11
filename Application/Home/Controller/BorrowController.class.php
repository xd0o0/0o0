<?php
namespace Home\Controller;
use Think\Controller;

class BorrowController extends CommonController {
    // 框架首页
    public function index() {
		$this -> assign(title,"常用设备");
		$this -> assign(description,"查看、借用常用设备");
		$data = array(
		array('name' => "设备首页", link => __CONTROLLER__."/index"),
		array('name' => "审核设备", link => __CONTROLLER__."/appro"),
		array('name' => "管理设备", link => __CONTROLLER__."/edit"),
		array('name' => "损坏设备", link =>  __CONTROLLER__."/damaged"),
		);
		$this -> assign(othertitle,$data);

		
		$CabilityModel = D('Cability');
		$BorrowlistModel = D('Borrowlist');
		
		$this -> assign(typelist,$CabilityModel->getTypeList());
		
		if($_GET['type'] && $_GET['type'] != "all")
		{
			$this -> assign(borrowlist,$BorrowlistModel->getBorrowListByType($_GET['type']));
		}
		else
		{
			$this -> assign(borrowlist,$BorrowlistModel->getBorrowList());
		}

		// dump($BorrowlistModel->getLastSql());
        $this->display();
    }
	
	//审核页
	public function appro() {
			
		$this -> assign(title,"审核设备");
		$this -> assign(description,"允许、拒绝借出常用设备");
		$data = array(
		array('name' => "设备首页", link => __CONTROLLER__."/index"),
		array('name' => "审核设备", link => __CONTROLLER__."/appro"),
		array('name' => "管理设备", link => __CONTROLLER__."/edit"),
		array('name' => "损坏设备", link =>  __CONTROLLER__."/damaged"),
		);
		$this -> assign(othertitle,$data);
		
		$BorrowlistModel = D('Borrowlist');
		$this -> assign(approlist,$BorrowlistModel->getApproList());
		
        $this->display();
    }
	
	//编辑设备页
	public function edit() {
			
		$this -> assign(title,"编辑设备");
		$this -> assign(description,"添加，编辑常用设备");
		$data = array(
		array('name' => "设备首页", link => __CONTROLLER__."/index"),
		array('name' => "审核设备", link => __CONTROLLER__."/appro"),
		array('name' => "管理设备", link => __CONTROLLER__."/edit"),
		array('name' => "损坏设备", link =>  __CONTROLLER__."/damaged"),
		);
		$this -> assign(othertitle,$data);
		
		$CabilityModel = D('Cability');
		
		$this -> assign(typelist,$CabilityModel->getTypeList());
		
		if($_GET['type'] && $_GET['type'] != "all")
		{
			$this -> assign(borrowlist,$CabilityModel->getDevicesListByType($_GET['type']));
		}
		else
		{
			$this -> assign(borrowlist,$CabilityModel->getDevicesList());
		}

		// dump($BorrowlistModel->getLastSql());
        $this->display();
    }
	
	//损坏设备页
	public function damaged() {
		$CabilityModel = D('Cability');
		if (IS_POST)
		{			
			$CabilityModel -> damageddevices($_POST['DID']);
			$data['status'] = 1;
			// $data['info'] = $_POST['DID'];
			$data['info'] = "修改成功";
			$this->ajaxReturn($data);
		}
		else
		{
			$this -> assign(title,"损坏设备");
			$this -> assign(description,"查看所有的损坏常用设备（包括移动设备）");
			$data = array(
			array('name' => "设备首页", link => __CONTROLLER__."/index"),
			array('name' => "审核设备", link => __CONTROLLER__."/appro"),
			array('name' => "管理设备", link => __CONTROLLER__."/edit"),
			array('name' => "损坏设备", link =>  __CONTROLLER__."/damaged"),
			);
			$this -> assign(othertitle,$data);
			
			$this -> assign(damagedlist,$CabilityModel->getDamagedList());
		
			$this->display();
		}
    }
	
	//编辑保存
	public function save() {
		
		$CabilityModel = D('Cability');
		
		// $ndata['DID'] = $_POST['did'];
		// $ndata['Type'] = $_POST['type'];
		// $ndata['ID'] = $_POST['id'];
		// $ndata['Parameter'] = $_POST['parameter'];
		
		
		if ($CabilityModel->create()) {
			if ($CabilityModel->save()) {
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
			$data['info'] = $CabilityModel->getError();
			$this->ajaxReturn($data);
		}
		
	}
	
	//添加设备
	public function add() {
		$CabilityModel = D('Cability');
	if ($CabilityModel->create()) {
			$CabilityModel->add();

			$data['info'] = "添加成功";
			
			// $data['info'] = $CabilityModel-> getLastSql();
			$this->ajaxReturn($data);
            } 
        else {
			$data['info'] = $CabilityModel->getError();
			$this->ajaxReturn($data);
		}
	}
	
	//操作
	public function action(){

		$BorrowModel = D('Borrow');
		//$BorrowOne = $BorrowModel-> getBorrowid($_GET['id']);
		$BorrowOne = $BorrowModel -> getBorrowid($_POST['id']);
		
		
		$borrowuserrole = D('RoleUser') -> where("role_id=1 and user_id=%d",$_SESSION[C('USER_AUTH_KEY')])-> find(); 
		
		//dump($borrowuserrole);
		//switch($_GET['Action'])
		switch($_POST['Action'])
		{
			/* 关于Approved的修改
			0为未借出
			1为借出中未审核
			2为已借出
			3为归还中未审核
			4为已归还
			*/
			
		case 'Brow':
			if($BorrowOne['approved'] == 0)
			{
				$BorrowModel -> borrowid($_POST['id']);
				
				$data['status'] = 1;
				$data['info'] = '请至设备处借用';
				//$data['info'] = $BorrowModel ->getLastSql();
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
				$data['info'] = '已被借出！';
				$this->ajaxReturn($data);
			}
			break;
		case 'Retn':
			if($BorrowOne['approved'] == 2 && $BorrowOne['userid'] == $_SESSION[C('USER_AUTH_KEY')])
			{		
				$BorrowModel -> returnid($_POST['id']);
				
				$data['status'] = 1;
				$data['info'] = '请至设备处归还';
				//$data['info'] = $BorrowModel ->getLastSql();
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
			}
			break;
		case 'BrApproved':
			if($BorrowOne['approved'] == 1 && $borrowuserrole)
			{
				$BorrowModel -> brapprovedid($_POST['id']);
				
				$data['status'] = 0;
			//	$data['info'] = $BorrowModel ->getLastSql();
			//	$data['info'] = $_POST['id'];
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
			}
			break;
		case 'ReApproved':
			if($BorrowOne['approved'] == 3 && $borrowuserrole)
			{
				$BorrowModel -> reapprovedid($_POST['id']);
				
				$adddata['DID'] = $BorrowOne['did'];
				$BorrowModel->add($adddata);
				
				$data['status'] = 0;
			//	$data['info'] = $BorrowModel ->getLastSql();
			//	$data['info'] = $_POST['id'];
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
			}
			break;
		case 'CBrow':
			if($BorrowOne['approved'] == 1 && ($BorrowOne['userid'] == $_SESSION[C('USER_AUTH_KEY')] || $borrowuserrole))
			{
				$BorrowModel -> cancelborrowid($_POST['id']);
				
				$data['status'] = 0;
			//	$data['info'] = $BorrowModel ->getLastSql();
			//	$data['info'] = $_POST['id'];
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
			//	$data['info'] = $BorrowModel ->getLastSql();
			//	$data['info'] = $BorrowOne ;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
			}
			break;
		case 'CRetn':
			if($BorrowOne['approved'] == 3 && ($BorrowOne['userid'] == $_SESSION[C('USER_AUTH_KEY')] || $borrowuserrole))
			{
				$BorrowModel -> cancelreturnid($_POST['id']);
				
				$data['status'] = 0;
			//	$data['info'] = $BorrowModel ->getLastSql();
			//	$data['info'] = $_POST['id'];
				$this->ajaxReturn($data);
			}
			else
			{
				$data['status'] = 1;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
			}
			break;
		}	
	}
}

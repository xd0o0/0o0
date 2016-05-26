<?php
namespace Home\Controller;
use Think\Controller;

class AutoController extends CommonController {
    // 框架首页
    public function index() {
		$this -> assign(title,"VDA自动化");
		$this -> assign(description,"XD VDA自动生成配置");
		$data = array(
		array('name' => "VDA", link => __CONTROLLER__."/index"),
		array('name' => "DDC", link => __CONTROLLER__."/"),
		array('name' => "AD", link => __CONTROLLER__."/"),
		array('name' => "XD", link =>  __CONTROLLER__."/"),
		);
		$this -> assign(othertitle,$data);
		
		// $cmd = 'C:/1.bat';
		// system("C:\\1.bat",$out);
		// dump($out);
		$this -> display();

    }
}

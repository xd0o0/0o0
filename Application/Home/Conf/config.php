<?php
return array(

	//'配置项'=>'配置值'
	'URL_MODEL'                 =>  1,
	'DEFAULT_CHARSET'       	=> 'utf8', // 默认输出编码
		
		
	//数据库
	// 'DB_DSN' => 'mysql://xd:123456@localhost:3306/xd',
	
	'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  'localhost',
    'DB_NAME'                   =>  'xd',
    'DB_USER'                   =>  'xd',
    'DB_PWD'                    =>  '123456',
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'xd.xd_',
	'DB_CHARSET'				=> 	'utf8',     // 数据库编码默认采用utf8
	
	//关闭字段缓存
	'DB_FIELDS_CACHE'			=>	'false',
	
	//跳转模版
	// 'TMPL_ACTION_SUCCESS' 		=>	'Public:success',

	
	//认证
	'SESSION_AUTO_START'        =>  true,
    'USER_AUTH_ON'              =>  true,
    'USER_AUTH_TYPE'			=>  2,		// 默认认证类型 1 登录认证 2 实时认证
    'USER_AUTH_KEY'             =>  'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>  'administrator',
    'USER_AUTH_MODEL'           =>  'User',	// 默认验证数据表模型
    'AUTH_PWD_ENCODER'          =>  'md5',	// 用户认证密码加密方式
    'USER_AUTH_GATEWAY'         =>  '/Home/Public/login',// 默认认证网关
    'NOT_AUTH_MODULE'           =>  '/Home/Public',	// 默认无需认证模块
    'REQUIRE_AUTH_MODULE'       =>  '',		// 默认需要认证模块
    'NOT_AUTH_ACTION'           =>  '',		// 默认无需认证操作
    'REQUIRE_AUTH_ACTION'       =>  '',		// 默认需要认证操作
    'GUEST_AUTH_ON'             =>  false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'             =>  0,        // 游客的用户ID
	
    'RBAC_ROLE_TABLE'    		=>  'xd.xd_role',       	//角色表
    'RBAC_USER_TABLE'    		=>  'xd.xd_role_user', 	//角色分配表
    'RBAC_ACCESS_TABLE'  		=>  'xd.xd_access',   		//权限分配表
    'RBAC_NODE_TABLE'     		=>  'xd.xd_node',     		//节点表

    //'SHOW_PAGE_TRACE'           =>  true ,   //显示调试信息
	
	//邮件服务器
	'MAIL_HOST' 				=>	'smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' 			=>	TRUE, //启用smtp认证
    'MAIL_USERNAME' 			=>	'xd0o0xd@163.com',//你的邮箱名
    'MAIL_FROM' 				=>	'xd0o0xd@163.com',//发件人地址
    'MAIL_FROMNAME'				=>	'XD Team',//发件人姓名
    'MAIL_PASSWORD' 			=>	'wojkelmalajmmouh',//邮箱密码
    'MAIL_CHARSET' 				=>	'utf-8',//设置邮件编码
    'MAIL_ISHTML' 				=>	TRUE, // 是否HTML格式邮件
	
);

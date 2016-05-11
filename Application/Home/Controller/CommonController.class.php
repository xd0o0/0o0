<?php 
namespace Home\Controller;

use Think\Controller;
use Org\Util\Rbac;

class CommonController extends Controller {

    function _initialize() {
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) { 
		
            if (!$_SESSION [C('USER_AUTH_KEY')]) {
                //跳转到认证网关
				redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
            }
			
        }

    }
	

	
	
}

<?php
// +----------------------------------------------------------------------
// | jxch168 金享财行
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.jxch168.com/ All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_carry_money
{
	public function index(){
		
		$root = array();
		
		$email = strim($GLOBALS['request']['email']);//用户名或邮箱
		$pwd = strim($GLOBALS['request']['pwd']);//密码
		
			
		//检查用户,用户密码
		$user = user_check($email,$pwd);
		$user_id  = intval($user['id']);
		if ($user_id >0){
			require APP_ROOT_PATH.'app/Lib/uc_func.php';
			$root['user_login_status'] = 1;
			
			$bid = intval($GLOBALS['request']['bid']);
			
			$bank_list = $GLOBALS['db']->getAll("SELECT u.id, u.bankcard,u.real_name, b.name as bank_name FROM ".DB_PREFIX."user_bank u left join ".DB_PREFIX."bank b on b.id = u.bank_id where u.user_id=".$user_id."  AND u.id=".$bid."  ORDER BY u.id ASC");
			foreach($bank_list as $k=>$v){
				$bank_list[$k]['bankcode'] = str_replace(" ","",$v['bankcard']);
				$bank_list[$k]['img'] = str_replace("/mapi","",SITE_DOMAIN.APP_ROOT.'/public/bank/'.$v['id'].'.jpg');
			}
			
			$root['bank_carry'] = $bank_list;

			$root['money'] = $user['money'];
			$carry_total_money = $GLOBALS['db']->getOne("SELECT sum(money) FROM ".DB_PREFIX."user_carry WHERE user_id=".$user_id." AND status=1");
			$root['carry_total_money'] = $carry_total_money;
			
			//手续费
			$fee_config = load_auto_cache("user_carry_config");
			$json_fee = array();
			foreach($fee_config as $k=>$v){
				$json_fee[] = $v;
				$fee_config[$k]['fee_format'] = format_price($v['fee']);
			}
				
			$root['fee_config'] = $fee_config;
			
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>

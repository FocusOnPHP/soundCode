<?php
// +----------------------------------------------------------------------
// | jxch168 金享财行
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.jxch168.com/ All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class collocationModule extends SiteBaseModule
{
	public function __construct(){
		/*
		if(ACTION_NAME!="reponse" && ACTION_NAME!="notify"){
			$adm_session = es_session::get(md5(app_conf("AUTH_KEY")));
			$adm_id = intval($adm_session['adm_id']);
			$user_info =  es_session::get("user_info");
			$user_id = intval($user_info['id']);
			if($adm_id == 0 && $user_id==0){
				showErr("请先登录");
			}
		}
		*/
		
		if(intval(app_conf("OPEN_IPS"))==0){
			showErr('未开启资金托管功能',0);
		}
		
	}
		
	public function c(){
		echo urldecode('O75eJ5spQphliWkDyBTXItr9RH%2flz3O0Qj08cPVAFYjmboeyvbx3itLYU80BmCwtvw469bjl4h5PmKs%2fF6OUgQet9WdvyfXJmAGRwrawKHdqNV7OEJgRaKV5%2bX5grDuKV%2b9qhv4VhO%2f7o8YwqXfl%2fS6EYo4md%2bnZPHDF6KMFyXUuA5eiOs%2bRLsRlOdfsMFp35aMiJR8aDn%2bT4aKUK7zWimDu1GxHTYetYMX9SSzmKeQ8j2ozmYi%2fdbazfWApVR3bJ%2ff62r9wayweF4G8VfUUrFSsjtg3Tui0iGU4gGA53L3ca5fTvl65KC4s7ny2FWZVfpA%2fYhrRbKpE5%2bEMgVSix4MMKbM4W8cgmrA%2bxYGK0ylExxYyCL%2fVlcTfopOVEOtr9AQr7W5TT7KZrRD3QiF7u1c%2bdvk49iNDfdj9NyBJDXHOrGENu4w2KoC9I20OZKanFXIfaNiJBs81ZUELlNjBub2FEcYhNq5L7s49jBz6r0Jd%2bm8DOEoYFwV2ByzZ%2feHtOYrW7d4rP5TrpIK9Tx91p%2fze7MRfHAenUxRyQV0M6ChNAzP1mDsGDKowVon6TVyWGhDA%2foicqZ0jQhrkxVXQsmpfZjlsD44xq8USVJ1j2eS%2fcpQtTnTr85b%2fRxrd3Mp0k7FkSeRwqgBdZuIHMA07N38sMZTrHZzKskp3H5MMynepiLpC8b46WrhZ6YLTLfK8EYi84%2bKyjWCfFDnE3CVZPsdswj00QQPsO3nrTm%2ftorXieWgdQka9s92NBvkBKWdroWuJJ5M5Bq%2bIezSEhPayEIWq6sEuc0XLD0Pq1W0JmiZRGD4LZva1WrqB1Ib14lU9k6o3JW4lDqBIKRLQ844J4AobH6J782zDHGqa3O8c0YAo9HNyfIh7SMoakHXtr%2bepetnAwYcGF1DkFa0kBdhxEKa4yrCHaPpu%2fEvKC7Q6hmL7h6kq5Ys05x1I4L41jiQidAIe1hwUtdKcmbcRGjPMrx%2bAiObvr9I0Qk95W9xPi5QK3pCIznfraIqSK5PFWffZRNGrdlDPybZ2RxJogbskAmiYg7zFbSImDuzaxoMqM%2bO6%2bIdXZysYHArp511NQ6Qx8TwH59x3XvRY1AzaUIlgN0kfEeJzvjrBod2PQwTlt8M0lTLlMS6vu1tSbxyt7vQ6AZGlvYxqT3BZn%2beWnbvKC8cioXE1N2iHFkExYG9plfwqpayRtEILYx4OVd2W6CHYF%2bR38RxieLWwnw9GeXAHz3338OhRDdYHZ1gpeSIlNj6aM05VRCS99nJ4WTe1fNHtHGwRogNLQxsJROslsj8tQdzevSm6DfXSdmP8O5uwJqU34DaRoMu1jHkh7QKeZNNQl2nD%2bvoW0WaV%2bkdivi2uxFX06qtbih5tqHob0FF9sl3iAaAB%2b0P42hi2a0Y7lDk6dNUNLOPpluSjOisp6s6iE2ciNB%2f2DbH9LtWt%2fhceAlUV%2fVDP%2bnIkdyFS2OL9RL318QhnE9SK%2fK402Ut870jYTBIUQvlRBj4IwDUWZIq5slm9RqQAN%2bVtGWsjz3C%2fsr%2b%2fdWj3cU5mE%2f76mMlJ0mr5jWIOaXQjxpmrmV776tcj8%2b93osQZtbjAEp92Z5nZdD%2fBG%2fNzFOm8QCfZTLjYVMuJRGHW%2bW%2beSWE0Z7LuX6YTNvg%2fWU3FGJ3s3%2fIm0VEmb4TnD2ts6t0JO3W5mMW8tF6rIUQ8DKK0%2bjh6TsfXpxDV%2fnahLUlAlKjt3PWjNLHdsqWvQbKrX2MPPFF%2b2o2OFlSKBVq6ySaW5Pqb8BsX9PLdP%2fL3UkOQnmJ6qPQSz0HAjH8VzEwFHGUFlEWEd2tfPdReShPSxAEq7Biyw%2fiPuw%2bYMSgw1DypDIp7gdZ%2fqyio2l2J%2fYxhAJ0fBXm3o9qWInerQhnJV2lTuf2bbc20aw4pzPGHXiYD1Taw96e73mDETN%2fBcK8BIzxaZiIU%2fhVOFXhTQAtxmol6bvOrv%2fDaq3Ix0Ol9P%2fBUTeGlK%2bfUk%2bNGpCViBlbvZysU7Z6vJLGYq2NugMK1Ek1VP6B0gphRHX4kT0BzP2AnTAsGxhjybbBsbBnOHN3eKQ0NRJXg2mnYIfHFiqXJ3zXDL3ZG%2b%2bIvyLzLmwQparnL2MKS6W7YLxi2ISi3wKhnR7cejUHLGKH0V2LJbTxs8t9XPZ2LZ8YWRpC4IZu4Qk6jBAcCiiha85k9UdVmj7DBpOpK9Bez1K0aU671bZv%2bCy6bWxjwxVikVMPwliwBO6y7vRz503tuNgdsZEztZY3APb3X5gWjiK84Vo7ElQ4naQglG5BkoGlPv58m98oTO1x7XN5FxeHzVIP12Y0uDMmnJm6mwjSCgm4S6qHCI3DSgKjIEANOI0xvB5vWBLvOZ0AzmQ18M%2fyq%2bF2oCd7aY5o4Zi35OzCh6bBf2vM5uEsWjQ5u7o3Qebhv0cKLltDtHXGSTXDtORuPKQq5d1gbHEsGPC9F8uXe5wcR2of2EOsiwkekUlUcl8GaAGxfGAn4xbo54ePMdC1%2fT0eGWhY3zxuJ8kOHEY5Q4TVoONGOpat%2bxaqxXN822VXR6OH%2f5vRAQLjVpo%2fViOExAHUChbw5ZkjuzKuIHqGmGnJ%2f5qX%2bMsM4omwNXDiNEB8PJAHHTZxuiNYznv8MCVUHCQhJlDOA7NnPaNueu1%2b%2b8IyzRt%2f%2bEHkU%2bXMYxmXBihmPGQ7o7OSf83RklrmdBBViAqleRnlIW5Ud9FT41ZpnHjIRC5GP%2bBnuxmth%2fF403uw75a0XLn0DeHTWW5hhORB%2fOBnwIv39DCfpLOfhQcXqTSRBsi6vBKc0y9Kqth3zVRw0IS2w7MHabZPkmsY%2fIs570JteQ465ziBPv%2bNrgmhHYQYcfW9%2byXOfsQxS9YR7');
	
	}
	
	/**
	 * 创建ips资金托管帐户
	 * user_id 用户ID
	 * user_type  0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 */
	public function CreateNewAcct(){
		$user_id = intval(strim($_REQUEST['user_id']));
		$user_type = intval(strim($_REQUEST['user_type']));
	
		$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		if (empty($data)){
			showErr('用户不存在',0);
		}else{		
			if (empty($data['ips_acct_no'])){					
				if (empty($data['idno'])){
					showErr('身份证号码不能为空,请补充完资料后再申请',0);
				}else if (empty($data['real_name'])){
					showErr('真实姓名不能为空,请补充完资料后再申请',0);
				}else if (empty($data['mobile'])){
					showErr('手机号码不能为空,请补充完资料后再申请',0);
				}else if (empty($data['email'])){
					showErr('邮箱不能为空,请补充完资料后再申请',0);
				}else{	
					$class_name = getCollName();					
					
					require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
					$collocation_class = $class_name."_collocation";
					$collocation_object = new $collocation_class();
					$collocation_code = $collocation_object->CreateNewAcct($user_id,$user_type);
					print_r($collocation_code);
				}			
			}else{
				showErr('该用户已经申请过资金托管帐户:'.$data['ips_acct_no'],0,SITE_DOMAIN.APP_ROOT,30);
			}
		}
	}
		
	
	/**
	 * 商户端获取银行列表查询(WS)
	 * @return	 
	 * 		  pErrCode 4 返回状态 否 0000成功； 9999失败；
	 * 		  pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
	 * 		  pBankList 银行名称|银行卡别名|银行卡编号#银行名称|银行卡别名|银行卡编号
	 * 		  BankList[] = array('name'=>银行名称,'sub_name'=>银行卡别名,'id'=>银行卡编号);
	 */
	function GetBankList($return_result = false){
		
		$result = GetIpsBankList();
		//print_r($result);
		if ($return_result){
			return $result;
		}else{
			echo json_encode($result);
		}
	}
	
	/**
	 * 用户充值
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 充值金额
	 * @param string $pTrdBnkCode 银行编号
	 */
	public function DoDpTrade(){
		$user_id = intval(strim($_REQUEST['user_id']));
		$user_type = intval(strim($_REQUEST['user_type']));
		$pTrdAmt = floatval(strim($_REQUEST['pTrdAmt']));
		$pTrdBnkCode = strim($_REQUEST['pTrdBnkCode']);

		$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
	
		if (empty($data)){
			showErr('用户不存在',0);
		}else{			
			if (!empty($data['ips_acct_no'])){
				$class_name = getCollName();
				require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
				$collocation_class = $class_name."_collocation";
				$collocation_object = new $collocation_class();
				$collocation_code = $collocation_object->DoDpTrade($user_id,$user_type,$pTrdAmt,$pTrdBnkCode);
				print_r($collocation_code);				
			}else{
				showErr('该用户还未申请过资金托管帐户',0);
			}
		}
	}
		
	/**
	 * 绑定银行卡
	 */
	public function BindBankCard(){
		$user_id = intval(strim($_REQUEST['user_id']));
		
		$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		if (empty($data)){
			showErr('用户不存在',0);
		}else{
			if (!empty($data['ips_acct_no'])){
				$class_name = getCollName();
				require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
				$collocation_class = $class_name."_collocation";
				$collocation_object = new $collocation_class();
				$collocation_code = $collocation_object->BindBankCard($user_id);
				print_r($collocation_code);
			}else{
				showErr('该用户还未申请过资金托管帐户',0);
			}
		}		
		
	}	
	/**
	 * 用户提现
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param float $pTrdAmt 充值金额	 
	 */
	public function DoDwTrade(){
		
		//判断是否是黑名单会员
    	if($GLOBALS['user_info']['is_black']==1){
    		showErr("您当前无权限提现，具体联系网站客服",0,url("index","uc_center"));
    	}
		
		$user_id = intval(strim($_REQUEST['user_id']));
		$user_type = intval(strim($_REQUEST['user_type']));
		$pTrdAmt = floatval(strim($_REQUEST['pTrdAmt']));
		
	
		$data = array();
		if ($user_type == 0){
			$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}else{
			$data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		}
	
		if (empty($data)){
			showErr('用户不存在',0);
		}else{
			if (!empty($data['ips_acct_no'])){
				$class_name = getCollName();
				require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
				$collocation_class = $class_name."_collocation";
				$collocation_object = new $collocation_class();
				$collocation_code = $collocation_object->DoDwTrade($user_id,$user_type,$pTrdAmt);
				print_r($collocation_code);
			}else{
				showErr('该用户还未申请过资金托管帐户',0);
			}
		}
	}
		
	/**
	 * 账户余额查询(WS)
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $MerCode
	 * @param unknown_type $cert_md5
	 * @param unknown_type $ws_url
	 * @return	 
			 pErrCode 4 返回状态 否 0000成功； 9999失败；
			 pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因
			 pIpsAcctNo 30 IPS账户号 否 查询时提交
			 pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数
			 pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数
			 pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数
	 */
	public function QueryForAccBalance(){
		$user_id = intval(strim($_REQUEST['user_id']));
		$user_type = intval(strim($_REQUEST['user_type']));
		$is_ajax = intval(strim($_REQUEST['is_ajax']));
		
		$data = array();
		if ($user_type == 0){
			$data = $GLOBALS['db']->getRow("select id,ips_acct_no from ".DB_PREFIX."user where id = ".$user_id);
			$argIpsAccount = $data['ips_acct_no'];
		}else{
			$data = $GLOBALS['db']->getRow("select id,ips_acct_no,acct_type,ips_mer_code from ".DB_PREFIX."user where id = ".$user_id);			
			//acct_type 担保方类型 否 0#机构；1#个人
			if ($data['acct_type'] == 0){
				$argIpsAccount = $data['ips_mer_code'];
			}else{
				$argIpsAccount = $data['ips_acct_no'];
			}
		}
		if (empty($data)){
			showErr('用户不存在',$is_ajax);
		}else{
			if (!empty($data['ips_acct_no'])){
				$result = GetIpsUserMoney($user_id,$user_type);
				foreach($result as $k=>$v){
					if($k=="pNeedstl" || $k=="pBalance" || $k=="pLock")
						$result[$k] = round($v,2);
				}
				if($is_ajax==1){
					ajax_return($result);
				}
				else
					print_r($result);
			}else{
				showErr('该用户还未申请过资金托管帐户',$is_ajax);
			}
		}		
	}
	
	/**
	 * 标的登记 及 流标
	 * @param int $deal_id
	 * @param int $pOperationType 标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻
	 * @param int $status; 0:新增; 1:标的正常结束; 2:流标结束
	 * @param string $status_msg 主要是status_msg=2时记录的，流标原因
	 */
	public function RegisterSubject(){
		$deal_id = intval(strim($_REQUEST['deal_id']));
		$pOperationType = intval(strim($_REQUEST['pOperationType']));
		$status = intval(strim($_REQUEST['status']));
		$status_msg = strim($_REQUEST['status_msg']);
		
		if ($pOperationType == 0) $pOperationType = 1;
				
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id,0);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		if (!empty($deal)){
			if (empty($deal['ips_bill_no']) || $pOperationType == 2){
				if ($pOperationType == 2 || floatval($deal['load_money']) == 0){
									
					$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($deal['user_id']));
					
					if (!empty($user['ips_acct_no'])){				
						$class_name = getCollName();
						require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
						$collocation_class = $class_name."_collocation";
						$collocation_object = new $collocation_class();
						$collocation_code = $collocation_object->RegisterSubject($deal_id,$pOperationType,$status,$status_msg);
						print_r($collocation_code);
					}else{
						showErr('借款人未申请资金托管帐户:'.$user['user_name'],0);
					}
				}else{
					showErr('已经有投资者投资,不能再发布成托管;当前投资金额:'.$deal['load_money'],0);
				}
			}else{
				showErr('该标已经发布登记:'.$deal['ips_bill_no'],0);
			}
		}else{
			showErr('标的ID不存在:'.$deal_id,0);
		}		
	}
	
	/**
	 * 登记担保方
	 * @param int $deal_id
	 */	
	public function RegisterGuarantor(){
		$deal_id = intval(strim($_REQUEST['deal_id']));
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id,0);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		if (!empty($deal)){
			if (!empty($deal['ips_bill_no'])){
				if (empty($deal['ips_guarantor_bill_no'])){
					
					$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($deal['agency_id']));	
					//print_r($user); exit;				
					if (!empty($user) && !empty($user['ips_acct_no'])){
									
						$class_name = getCollName();
						require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
						$collocation_class = $class_name."_collocation";
						$collocation_object = new $collocation_class();
							
						$collocation_code = $collocation_object->RegisterGuarantor($deal_id);
						print_r($collocation_code);
						
					}else{
						showErr('担保公司不存在,或未申请资金托管帐户',0);
					}
				}else{
					showErr('该标已经登记担保:'.$deal['ips_guarantor_bill_no'],0);
				}				
			}else{
				showErr('该标还未发布登记',0);
			}
		}else{
			showErr('标的ID不存在:'.$deal_id,0);
		}	
	}	
		
	/**
	 * 登记债权人
	 * @param int $user_id  用户ID
	 * @param int $deal_id  标的ID
	 * @param float $bid_money 投资金额
	 * @param float $bid_paypassword 支付密码
	 * @return string
	 */
	public function RegisterCreditor(){
		$user_id = intval(strim($_REQUEST['user_id']));
		$deal_id = intval(strim($_REQUEST['deal_id']));
		$bid_money = floatval(strim($_REQUEST['bid_money']));
		$bid_paypassword = strim($_REQUEST['bid_paypassword']);
		
		$this->check_user($user_id,$bid_paypassword);
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		
		//print_r($_REQUEST);
		//常用状态检查
		$result = check_dobid2($deal_id,$bid_money,$bid_paypassword);
		//print_r($result); exit;
		
		if ($result['status'] == 0){
			showErr($result['show_err'],0);
		}else{	
			
			$deal = get_deal($deal_id,0);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
			if (!empty($deal)){
				if (!empty($deal['ips_bill_no'])){
					
					$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
	
					if (!empty($user) && !empty($user['ips_acct_no'])){
								
						$class_name = getCollName();
						require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
						$collocation_class = $class_name."_collocation";
						$collocation_object = new $collocation_class();
								
						$collocation_code = $collocation_object->RegisterCreditor($user_id,$deal_id,$bid_money);
						print_r($collocation_code);
		
					}else{
						showErr('用户不存在,或未申请资金托管帐户',0);
					}
					
				}else{
					showErr('该标还未发布托管登记',0);
				}
			}else{
				showErr('标的ID不存在:'.$deal_id,0);
			}
		}
	}	
		
	
	function check_user($user_id,$pwd){
		$user_id = intval($user_id);
		if($user_id > 0)
		{
			if (!$GLOBALS['user_info'] || $GLOBALS['user_info']['id'] != $user_id){
				//$sql = "select *,id as uid from ".DB_PREFIX."user where (user_name='".$username_email."' or email = '".$username_email."') and is_delete = 0";
				$sql = "select *,id as uid from ".DB_PREFIX."user where  id = ".$user_id." and is_delete = 0";
				$user_info = $GLOBALS['db']->getRow($sql);
		
				$is_use_pass = false;
				if (strlen($pwd) != 32){					
					if($user_info['paypassword']==md5($pwd.$user_info['code']) || $user_info['paypassword']==md5($pwd)){
						$is_use_pass = true;
						
					}
				}
				else{
					if($user_info['paypassword']==$pwd){
						$is_use_pass = true;
					}
				}
				if($is_use_pass)
				{
					$GLOBALS['user_info'] = $user_info;
					return $user_info;
				}
				else
					return null;
			}else{
				return null;
			}
		}else{
			return null;
		}
	}
	
	/**
	 * 登记债权转让
	 * @param int $transfer_id  转让id
	 * @param int $t_user_id  受让用户ID
	 * @return string
	 */
	public function RegisterCretansfer(){
		//echo 'sss'; exit;
		$transfer_id = intval(strim($_REQUEST['id']));
		$t_user_id = intval(strim($_REQUEST['t_user_id']));
		$paypassword = strim($_REQUEST['paypassword']);
		
		$this->check_user($t_user_id,$paypassword);
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
	
		//print_r($_REQUEST);
		//常用状态检查
		$result = check_trans($transfer_id,$paypassword);
		//print_r($result); exit;

		if ($result['status'] == 0){
			showErr($result['show_err'],0);
		}else{
			$transfer = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_load_transfer where id = ".$transfer_id);
			
			$deal_id = intval($transfer['deal_id']);
			$user_id = intval($transfer['user_id']);
			
			$deal = get_deal($deal_id,0);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	
			if (!empty($deal)){
				if (!empty($deal['ips_bill_no'])){					
					$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
					$tuser = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$t_user_id);
					
					if (empty($user['ips_acct_no']) || empty($tuser['ips_acct_no'])){
						showErr('有一方未申请 ips 帐户',0);
					}else{					
					
						$class_name = getCollName();
						
						require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
						$collocation_class = $class_name."_collocation";
						$collocation_object = new $collocation_class();
	
						$collocation_code = $collocation_object->RegisterCretansfer($transfer_id,$t_user_id);
						print_r($collocation_code);
					}						
				}else{
					showErr('该标还未发布托管登记',0);
				}
			}else{
				showErr('标的ID不存在:'.$deal_id,0);
			}
		}
	}
	/**
	 * 还款
	 * @param int $deal_id  标的id	 
	 * @param int $l_key  还款期号
	 * @return string
	 */
	function RepaymentNewTrade(){
		$deal_id = intval(strim($_REQUEST['deal_id']));
		//$deal_repay_id = intval(strim($_REQUEST['deal_repay_id']));
		if (strim($_REQUEST['l_key']) === 'all'){
			//提前还款
		}
		$l_key = intval(strim($_REQUEST['l_key']));
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id);// 		
		
		if (!empty($deal)){
			if (!empty($deal['ips_bill_no'])){
				/*
				//$deal = get_deal($deal_id);
				if(!$deal || $deal['user_id']!=$GLOBALS['user_info']['id'] || $deal['deal_status']!=4){
					$root["show_err"] = "操作失败！";
					return $root;
				}
				*/
				//$ids = explode(",",$ids);
				//has_repay 0未还,1已还 2部分还款

				//判断上期是否已经还完
				$pl_key = $l_key - 1;
				$sql = "select id from ".DB_PREFIX."deal_repay where has_repay in(0,2) and l_key = ".$pl_key." and deal_id =".$deal_id;
				$deal_repay_id = intval($GLOBALS['db']->getOne($sql));
				
				if (intval($deal_repay_id) == 0){
									
					$sql = "select id from ".DB_PREFIX."deal_repay where has_repay in(0,2) and l_key = ".$l_key." and deal_id =".$deal_id;					
					$deal_repay_id = intval($GLOBALS['db']->getOne($sql));
					
					if ($deal_repay_id > 0){				
						//echo $deal_repay_id;exit;
						
						$class_name = getCollName();
						require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
						$collocation_class = $class_name."_collocation";
						$collocation_object = new $collocation_class();
						
					
						$page = 0;
						if ($class_name == 'Ips'){
							$page_num = 290;//最大为300条
						}else{
							$page_num = 99999;//一次操作完成,没有数据限制
						}
						
						do {
							$page = $page + 1;
							$limit = (($page-1)*$page_num).",".$page_num;
							
							$result = get_deal_user_load_list($deal,0, $l_key , -1,0,1, 1, $limit);
		
							if (count($result['item']) > 0){
								$collocation_code = $collocation_object->RepaymentNewTrade($deal, $result['item'], $deal_repay_id);
								print_r($collocation_code);
							}
						}while(count($result['item']) >= $page_num);
					}else{
						showErr("未找到还款计划或该期($l_key)已还清",0);
					}
				}else{					
					showErr("上期($pl_key)还未还清:".$deal_repay_id,0);
				}
			}else{
				showErr('该标已经发布登记:'.$deal['ips_bill_no'],0);
			}
		}else{
			showErr('标的ID不存在:'.$deal_id,0);
		}		
	}
	
	/**
	 * 转帐
	 * @param int $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）
	 * @param int $deal_id  标的id
	 * @param string $ref_data 逗号分割的,代偿，代偿还款列表; 债权转让: id; 结算担保收益:金额，如果为0,则取fanwe_deal.guarantor_pro_fit_amt ;
	 * @return string
	 */
	function Transfer(){
		$pTransferType = intval(strim($_REQUEST['pTransferType']));
		$deal_id = intval(strim($_REQUEST['deal_id']));
		$ref_data = strim($_REQUEST['ref_data']);
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		if (!empty($deal)){
			if (!empty($deal['ips_bill_no'])){
				/*
				 //$deal = get_deal($deal_id);
				if(!$deal || $deal['user_id']!=$GLOBALS['user_info']['id'] || $deal['deal_status']!=4){
				$root["show_err"] = "操作失败！";
				return $root;
				}
				*/
				//$ids = explode(",",$r_ids);
		
				$class_name = getCollName();
				require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
				$collocation_class = $class_name."_collocation";
				$collocation_object = new $collocation_class();
				
				$collocation_code = $collocation_object->Transfer($pTransferType, $deal_id, $ref_data);
				
				print_r($collocation_code);
			}else{
				showErr('该标未发布登记:'.$deal['ips_bill_no'],0);
			}
		}else{
			showErr('标的ID不存在:'.$deal_id,0);
		}
	}	
	
	/**
	 * 解冻保证金
	 * @param int $deal_id 标的号
	 * @param int $pUnfreezenType 解冻类型 否 1#解冻借款方；2#解冻担保方
	 * @param float $money 解冻金额;默认为0时，则解冻所有未解冻的金额
	 * @return string
	 */
	function GuaranteeUnfreeze(){
		$pTransferType = intval(strim($_REQUEST['pTransferType']));
		$deal_id = intval(strim($_REQUEST['deal_id']));
		$money = floatval($_REQUEST['money']);
		
		require_once APP_ROOT_PATH."app/Lib/deal_func.php";
		$deal = get_deal($deal_id);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		if (!empty($deal)){
			if (!empty($deal['ips_bill_no'])){
				$class_name = getCollName();
				require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
				$collocation_class = $class_name."_collocation";
				$collocation_object = new $collocation_class();
		
				$collocation_code = $collocation_object->GuaranteeUnfreeze($deal_id,$pTransferType,$money);
		
				print_r($collocation_code);
			}else{
				showErr('该标未发布登记:'.$deal['ips_bill_no'],0);
			}
		}else{
			showErr('标的ID不存在:'.$deal_id,0);
		}		
	}
	
	
	public function response()
	{
		//支付跳转返回页	
		$class_name = strim($_REQUEST['class_name']);
		$class_act = strim($_REQUEST['class_act']);
		$log_html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><form action="'.SITE_DOMAIN.APP_ROOT.'/index.php" method="post"  >';

		foreach($_REQUEST as $k=>$v){
			$log_html .= $k.':<input type="text" name="'.$k.'" value="'.$v.'"><br>';
		}
		
		$log_html .= '<button type="submit">submit</button></form>';
		
		file_put_contents(APP_ROOT_PATH."system/collocation/log/".$class_act."_response_".strftime("%Y%m%d%H%M%S",time()).".html",$log_html);
				
		
		
		$collocation_info = array('MerCode' => 808801);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($collocation_info)
		{
			require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
			$collocation_class = $class_name."_collocation";
			$collocation_object = new $collocation_class();
			adddeepslashes($_REQUEST);
			
			$collocation_code = $collocation_object->response($_REQUEST,$class_act);
			
		}
		else
		{
			showErr($GLOBALS['lang']['PAYMENT_NOT_EXIST']);
		}
	}
	
	public function notify()
	{
		
		
		//支付跳转返回页	
		$class_name = strim($_REQUEST['class_name']);
		$class_act = strim($_REQUEST['class_act']);
		
		$log_html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><form action="'.SITE_DOMAIN.APP_ROOT.'/index.php" method="post"  >';

		foreach($_REQUEST as $k=>$v){
			$log_html .= $k.':<input type="text" name="'.$k.'" value="'.$v.'"><br>';
		}
		
		$log_html .= '<button type="submit">submit</button></form>';
		
		file_put_contents(APP_ROOT_PATH."system/collocation/log/".$class_act."_notify_".strftime("%Y%m%d%H%M%S",time()).".html",$log_html);
		
		
		$collocation_info = array('MerCode' => 808801);// $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name = '".$class_name."'");
		if($collocation_info)
		{
			require_once APP_ROOT_PATH."system/collocation/".$class_name."_collocation.php";
			$collocation_class = $class_name."_collocation";
			$collocation_object = new $collocation_class();
			adddeepslashes($_REQUEST);
			
			$collocation_code = $collocation_object->notify($_REQUEST,$class_act);
			
		}
		else
		{
			//showErr($GLOBALS['lang']['PAYMENT_NOT_EXIST']);
		}
	}
}
?>
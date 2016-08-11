<?php
// +----------------------------------------------------------------------
// | jxch168 金享财行
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.jxch168.com/ All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

require APP_ROOT_PATH.'app/Lib/uc.php';

class uc_centerModule extends SiteBaseModule
{
	private $space_user;
	public function init_main()
	{
//		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
//		require_once APP_ROOT_PATH."system/extend/ip.php";
//		$iplocation = new iplocate();
//		$address=$iplocation->getaddress($user_info['login_ip']);
//		$user_info['from'] = $address['area1'].$address['area2'];
		$GLOBALS['tmpl']->assign('user_auth',get_user_auth());
	}

	public function init_user(){
		$this->user_data = $GLOBALS['user_info'];

		$province_str = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."region_conf where id = ".$this->user_data['province_id']);
		$city_str = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."region_conf where id = ".$this->user_data['city_id']);
		if($province_str.$city_str=='')
			$user_location = $GLOBALS['lang']['LOCATION_NULL'];
		else
			$user_location = $province_str." ".$city_str;

		$this->user_data['fav_count'] = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."topic where user_id = ".$this->user_data['id']." and fav_id <> 0");
		$this->user_data['user_location'] = $user_location;
		$this->user_data['group_name'] = $GLOBALS['db']->getOne("select name from ".DB_PREFIX."user_group where id = ".$this->user_data['group_id']." ");

		$this->user_data['user_statics'] =sys_user_status($GLOBALS['user_info']['id'],false);
		//$GLOBALS['tmpl']->assign('user_statics',$this->user_data['user_statics']);
	}

        public function index()
	{
		$this->init_user();
		$user_info = $this->user_data;

		$ajax =intval($_REQUEST['ajax']);
		if($ajax==0)
		{
			$this->init_main();
		}
		$user_id = intval($GLOBALS['user_info']['id']);


		/***统计***/
		$user_statics = $user_info['user_statics'];


		//投资收益
		//$user_statics["load_earnings"] = number_format($user_statics['load_earnings'] + $user_statics['reward_money'] + $user_statics['load_tq_impose'] + $user_statics['load_yq_impose'] + $user_statics['rebate_money'] + $user_statics['referrals_money'] - $user_statics['carry_fee_money']- $user_statics['incharge_fee_money'], 2);
		$user_statics["load_earnings"] = number_format($user_statics['load_earnings'], 2);


		//已赚收益
		$user_statics["need_repay_amount"] = floatval($user_statics["need_repay_amount"])+floatval($user_statics["need_manage_amount"]);
		//待收本金
		$user_statics["load_wait_self_money"] = floatval($user_statics["load_wait_self_money"]);

		$user_statics["clear_total_money"] = number_format((round($user_statics["load_wait_self_money"],2) + round($user_info["money"],2) + round($user_info["lock_money"],2) - round($user_statics["need_repay_amount"],2)),2);

		$user_statics["load_wait_self_money"] = number_format($user_statics["load_wait_self_money"],2);

                //重新定义待收收益 所有收益 - 已经还的收益
                /*
                $all_deal_loads = $GLOBALS['db']->getAll("SELECT (d.rate/100/360) * dl.money * d.yield_ratio * d.repay_time as pre_interests,dl.create_time FROM ".DB_PREFIX."deal_load dl left join ".DB_PREFIX."deal d on dl.deal_id = d.id WHERE  dl.user_id = ".$GLOBALS['user_info']['id']." ORDER BY dl.create_time desc");
                //获取活动收益 并加入到总收益中
                $mid_autumn_act = require_once APP_ROOT_PATH.'data_conf/mid_autumn_active.php';
                $mid_autumn_act = $mid_autumn_act['mid_autumn_acting'];
                $all_interests = 0;
                foreach($all_deal_loads as $key=>$value){
                    $active_interest_money = get_act_income($mid_autumn_act,$value['pre_interests'],$value['create_time']);
                    if($active_interest_money){
                        $active_interest_money = sprintf("%.2f", substr(sprintf("%.4f", $active_interest_money), 0, -2));
                        $all_deal_loads[$key]['pre_interests'] += $active_interest_money;
                    }
                    $all_interests += $all_deal_loads[$key]['pre_interests'];
                }
                $all_interests = num_format($all_interests); //利息总额
                $all_repay_interests = $GLOBALS['db']->getOne("SELECT sum(interest_money) FROM ".DB_PREFIX."deal_load_repay  WHERE has_repay = 1 AND user_id = ".$GLOBALS['user_info']['id']);
                $user_statics["deal_load_wait_earnings"] = $all_interests - $all_repay_interests;
                */

		$user_statics["ltotal_money"] = number_format(floatval($user_statics["load_wait_repay_money"]) + floatval($user_statics["load_repay_money"]),2);
                //查询优惠券使用的金额
                $coupon_money_total = $GLOBALS['db']->getOne("select sum(coupon_cash) as coupon_cash_total from ".DB_PREFIX."deal_load where is_auto = 0 AND is_has_loans = 0 AND user_id = '".$GLOBALS['user_info']['id']."'");

                $user_info["total_money"] = number_format(floatval($user_info["money"]) + floatval($user_info["lock_money"]) + floatval($user_statics["load_wait_repay_money"]) + $coupon_money_total, 2);
                //待收收益
		$user_statics["load_wait_earnings"] = number_format(floatval($user_statics["load_wait_earnings"]), 2);

		$user_info["lock_money"] = number_format(floatval($user_info["lock_money"]), 2);
		$user_statics["money"] = number_format(floatval($user_info["money"]), 2);

		$user_statics["need_repay_amount"]= number_format(floatval($user_statics["need_repay_amount"]), 2);

		//投标中的
		$invest_sql = "SELECT count(*) as l_count,sum(money) as l_money FROM ".DB_PREFIX."deal_load dl LEFT JOIN ".DB_PREFIX."deal d ON dl.deal_id = d.id WHERE dl.user_id=".$user_id." and d.deal_status in(1,2) group by dl.user_id";

		$invest = $GLOBALS['db']->getRow($invest_sql);//var_dump($invest);die;
		$user_statics["invest_count"] = $invest["l_count"];
		$user_statics["invest_money"] = number_format($invest["l_money"],2);
		$user_statics["total_money"] = number_format(round($invest["l_money"],2)+ round($user_statics["load_wait_repay_money"],2)+round($user_statics["load_repay_money"],2),2);
		//待回收本息
		$user_statics["load_wait_repay_money"] = number_format(floatval($user_statics["load_wait_repay_money"]), 2);
		//已回收本息
		$user_statics["load_repay_money"] = number_format(floatval($user_statics["load_repay_money"]), 2);
		//本月
		$this_wait_deals = $this->get_loadlist($user_id,"  AND DATE_FORMAT(FROM_UNIXTIME(repay_time),'%Y年%m月')  = date_format(curdate(),'%Y年%m月') ");
		$user_statics["this_month_money"] = 0.00;
		$user_statics["this_month_count"] = 0;

		foreach($this_wait_deals as $k=>$v)
		{
			$user_statics["this_month_money"] += $v["repay_money"];
			$user_statics["this_month_count"] ++;
		}
		//下月
		$next_wait_deals = $this->get_loadlist($user_id," AND DATE_FORMAT(FROM_UNIXTIME(repay_time),'%Y年%m月')  = date_format(DATE_ADD(curdate(), INTERVAL 1 MONTH),'%Y年%m月')");
		$user_statics["next_month_money"] = 0.00;
		$user_statics["next_month_count"] = 0;

		foreach($next_wait_deals as $k=>$v)
		{
			$user_statics["next_month_money"] += $v["repay_money"];
			$user_statics["next_month_count"] ++;
		}

		//本年
		$year_wait_deals = $this->get_loadlist($user_id," AND DATE_FORMAT(FROM_UNIXTIME(repay_time),'%Y')  =  DATE_FORMAT(curdate(),'%Y')");

		$user_statics["year_money"] = 0.00;
		$user_statics["year_count"] = 0;

		foreach($year_wait_deals as $k=>$v)
		{
			$user_statics["year_money"] += $v["repay_money"];
			$user_statics["year_count"] ++;
		}

		$user_statics["year_money"] = number_format(round($user_statics["year_money"],2),2);
		$user_statics["this_month_money"] = number_format(round($user_statics["this_month_money"],2),2);
		$user_statics["next_month_money"] = number_format(round($user_statics["next_month_money"],2),2);

		//总计
		$all_wait_deals = $this->get_loadlist($user_id,'');
		$user_statics["total_invest_money"] = 0.00;
		$user_statics["total_invest_count"] = 0;

		foreach($all_wait_deals as $k=>$v)
		{
			$user_statics["total_invest_money"] += $v["repay_money"];
			$user_statics["total_invest_count"] ++;
		}
		$user_statics["total_invest_money"] = number_format($user_statics["total_invest_money"],2);
		//$user_statics["total_invest_count"] = $user_statics["this_month_count"]+$user_statics["next_month_count"]+$user_statics["year_count"];

		$load_list_sql = "SELECT * FROM ".DB_PREFIX."deal_load WHERE user_id = ".$GLOBALS['user_info']['id']." ORDER BY id DESC limit 0,4";
		//最近交易
		$load_list = $GLOBALS['db']->getAll($load_list_sql,false);
		$GLOBALS['tmpl']->assign("load_list",$load_list);

		//$user_statics["total_money"] =  number_format(floatval($user_info["load_wait_repay_money"]) - floatval($user_info["need_repay_amount"]));
		$GLOBALS['tmpl']->assign("user_statics",$user_statics);
            //我的红包
                $red_money = $GLOBALS['db']->getRow("SELECT sum(money)as red_money FROM ".DB_PREFIX."user_bonus WHERE status = 2 AND user_id = ".$user_id);
                if(empty($red_money['red_money'])){
                    //echo 123;die;
                    $red_money['red_money'] = '0.00';
                }
                $GLOBALS['tmpl']->assign("red_money",$red_money['red_money']);
            //累计总收益(与上 投资收益 相等)
                $sum_interest_money = $GLOBALS['db']->getRow("SELECT sum(true_interest_money) as sum_interest_money FROM ".DB_PREFIX."deal_load_repay WHERE user_id = ".$user_id);

            //回款中 最近六个月投资记录(2015年一月到现在的投资数)
		$month = array();

		//select month(FROM_UNIXTIME(time)) from table_name group by month(FROM_UNIXTIME(time))
		$result['lend'] = $GLOBALS['db']->getAll("SELECT count(*) as l_count,sum(money) as l_money,DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月') as l_month FROM ".DB_PREFIX."deal_load dl LEFT JOIN ".DB_PREFIX."deal d ON dl.deal_id = d.id WHERE dl.is_repay = 0 AND dl.user_id=".$user_id." and d.deal_status in (4) group by DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月')",false);
		//2015年一月到现在的投资数
                $now_month = date('m',time());
                for($i=0;$i<$now_month;$i++){
                    $months[$now_month-$i-1]["time"] = to_date(next_replay_month(TIME_UTC,-$i),'Y年m月');
                }


/*
$months[0]["time"] = to_date(next_replay_month(TIME_UTC,-5),'Y年m月');
$months[1]["time"] = to_date(next_replay_month(TIME_UTC,-4),'Y年m月');
$months[2]["time"] = to_date(next_replay_month(TIME_UTC,-3),'Y年m月');
$months[3]["time"] = to_date(next_replay_month(TIME_UTC,-2),'Y年m月');
$months[4]["time"] = to_date(next_replay_month(TIME_UTC,-1),'Y年m月');
$months[5]["time"] = to_date(TIME_UTC,'Y年m月');
*/
		$max_money = 100;
		foreach($result['lend']  as $k=>$v)
		{
			if(round($max_money)<round($v["l_money"]))
			{
				$max_money = $v["l_money"];
			}
			foreach($months as $kk => $vv)
			{
				if($vv["time"] == $v["l_month"])
				{
					$months[$kk]["l_money"] = $v["l_money"];
					$months[$kk]["show_money"] = number_format(floatval($v["l_money"]), 2);
				}
			}
		}
		foreach($months as $k => $v)
		{
			$months[$k]["height"] = $v["l_money"]/$max_money*325;
			$months[$k]["bottom"] = $v["l_money"]/$max_money*325+35;
		}
		$GLOBALS['tmpl']->assign("max_money",$max_money);
		$GLOBALS['tmpl']->assign("months",$months);

                $huikuan_list = array();
                foreach($months as $key=>$val){
                    if($val['l_money']){
                        $huikuan_list[$key] = (int)$val['l_money'];
                    }else{
                        $huikuan_list[$key] = 0;
                    }
                }
                ksort($huikuan_list);
                $huiKuan = json_encode($huikuan_list);
                $GLOBALS['tmpl']->assign("huiKuan",$huiKuan);
            //end 回款中 最近六个月投资记录(2015年一月到现在的投资数)

            //投标中 最近六个月投资记录(2015年一月到现在的投资数)
		$montha = array();

		//select month(FROM_UNIXTIME(time)) from table_name group by month(FROM_UNIXTIME(time))
		$resulta['lend'] = $GLOBALS['db']->getAll("SELECT count(*) as l_count,sum(money) as l_money,DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月') as l_month FROM ".DB_PREFIX."deal_load dl LEFT JOIN ".DB_PREFIX."deal d ON dl.deal_id = d.id WHERE dl.is_repay = 0 AND dl.user_id=".$user_id." and d.deal_status in(1,2) group by DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月')",false);
		//2015年一月到现在的投资数
                $now_montha = date('m',time());
                for($i=0;$i<$now_montha;$i++){
                    $monthsa[$now_montha-$i-1]["time"] = to_date(next_replay_month(TIME_UTC,-$i),'Y年m月');
                }
		$max_moneya = 100;
		foreach($resulta['lend']  as $k=>$v)
		{
			if(round($max_moneya)<round($v["l_money"]))
			{
				$max_moneya = $v["l_money"];
			}
			foreach($monthsa as $kk => $vv)
			{
				if($vv["time"] == $v["l_month"])
				{
					$monthsa[$kk]["l_money"] = $v["l_money"];
					$monthsa[$kk]["show_money"] = number_format(floatval($v["l_money"]), 2);
				}
			}
		}
		foreach($monthsa as $k => $v)
		{
			$monthsa[$k]["height"] = $v["l_money"]/$max_moneya*325;
			$monthsa[$k]["bottom"] = $v["l_money"]/$max_moneya*325+35;
		}
		$GLOBALS['tmpl']->assign("max_money",$max_moneya);
		$GLOBALS['tmpl']->assign("months",$monthsa);

                $toubiao_list = array();
                foreach($monthsa as $key=>$val){
                    if($val['l_money']){
                        $toubiao_list[$key] = (int)$val['l_money'];
                    }else{
                        $toubiao_list[$key] = 0;
                    }
                }
                ksort($toubiao_list);
                $touBiao = json_encode($toubiao_list);
                $GLOBALS['tmpl']->assign("touBiao",$touBiao);
            //end 投标中 最近六个月投资记录(2015年一月到现在的投资数)

            //已完成 最近六个月投资记录(2015年一月到现在的投资数)
		$monthb = array();

		//select month(FROM_UNIXTIME(time)) from table_name group by month(FROM_UNIXTIME(time))
		$resultb['lend'] = $GLOBALS['db']->getAll("SELECT count(*) as l_count,sum(money) as l_money,DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月') as l_month FROM ".DB_PREFIX."deal_load dl LEFT JOIN ".DB_PREFIX."deal d ON dl.deal_id = d.id WHERE dl.is_repay = 0 AND dl.user_id=".$user_id." and d.deal_status = 5 group by DATE_FORMAT(FROM_UNIXTIME(dl.create_time),'%Y年%m月')",false);
		//2015年一月到现在的投资数
                $now_monthb = date('m',time());
                for($i=0;$i<$now_monthb;$i++){
                    $monthsb[$now_monthb-$i-1]["time"] = to_date(next_replay_month(TIME_UTC,-$i),'Y年m月');
                }
		$max_moneyb = 100;
		foreach($resultb['lend']  as $k=>$v)
		{
			if(round($max_moneyb)<round($v["l_money"]))
			{
				$max_moneyb = $v["l_money"];
			}
			foreach($monthsb as $kk => $vv)
			{
				if($vv["time"] == $v["l_month"])
				{
					$monthsb[$kk]["l_money"] = $v["l_money"];
					$monthsb[$kk]["show_money"] = number_format(floatval($v["l_money"]), 2);
				}
			}
		}
		foreach($monthsb as $k => $v)
		{
			$monthsb[$k]["height"] = $v["l_money"]/$max_moneyb*325;
			$monthsb[$k]["bottom"] = $v["l_money"]/$max_moneyb*325+35;
		}

		$GLOBALS['tmpl']->assign("max_money",$max_moneyb);
		$GLOBALS['tmpl']->assign("months",$monthsb);

                $wancheng_list = array();
                foreach($monthsb as $key=>$val){
                    if($val['l_money']){
                        $wancheng_list[$key] = (int)$val['l_money'];
                    }else{
                        $wancheng_list[$key] = 0;
                    }
                }
                ksort($wancheng_list);
                $yiHuan = json_encode($wancheng_list);
                $GLOBALS['tmpl']->assign("yiHuan",$yiHuan);
            //end 已完成 最近六个月投资记录(2015年一月到现在的投资数)

		/***右侧统计结束***/

		$GLOBALS['tmpl']->assign("user_data",$user_info);
		if($ajax==0)
		{
			//近期待还款
			$day_deal_repay = getUcDealRepay($user_id,10,"");
			//近期待收款
			$day_repay_list = getUcRepayPlan($user_id,1,3,"");
			//推荐的标
			require APP_ROOT_PATH."app/Lib/deal_func.php";
                        //只显示招标中的标
			$where = " is_recommend = 1 and deal_status = 1";
			$deals_list = get_deal_list(10,0,$where);

                        //删除过期的标
			$time = TIME_UTC;
			foreach($deals_list['list'] as $ke => $vl){
				//开始时间
				$start_time = $vl['start_time'];
				//筹标期限
				$enddate = $vl['enddate'];
				//标的有效时间 是否过期
				$remain_time = intval($start_time + $enddate*24*3600 - $time);
				//两天时间时间戳表示
				if($remain_time <= 0){
					unset($deals_list['list'][$ke]);
				}
			}
			foreach($deals_list['list'] as $k=>$v){
				$deals_list['list'][$k]['repay_time_format'] = $v['repay_time']."天";
				$deals_list['list'][$k]['start_time_format'] = to_date($v['start_time'],"Y-m-d");

				if($v['is_delete'] == 2)
					$deals_list['list'][$k]['deal_status_format'] = "待发布";
				elseif($v['is_wait'] == 1)
					$deals_list['list'][$k]['deal_status_format'] = "未开始";
				elseif ($v['deal_status'] == 5)
					$deals_list['list'][$k]['deal_status_format'] = "还款完毕";
				elseif($v['deal_status'] == 4)
					$deals_list['list'][$k]['deal_status_format'] = "还款中";
				elseif($v['deal_status'] == 0)
					$deals_list['list'][$k]['deal_status_format'] = $v['need_credit']==0 ? "等待审核" : "等待材料";
				elseif($v['deal_status'] == 1 && $v['remain_time'] > 0)
					$deals_list['list'][$k]['deal_status_format'] ="筹款中";
				elseif($v['deal_status'] == 2)
					$deals_list['list'][$k]['deal_status_format'] ="满标";
				elseif($v['deal_status'] ==3 || $v['remain_time'] <= 0)
					$deals_list['list'][$k]['deal_status_format'] ="流标";
				//只显示投标中产品
				if($v['deal_status'] != 1){
					unset($deals_list['list'][$k]);

				}
				$deals_list['list'][$k]['deal_status_format'] ="招标中";

			}

                        //我的投资
                        $result = getInvestList($mode,intval($GLOBALS['user_info']['id']),intval($_REQUEST['p']));
                        $list = $result['list'];
                        foreach($list as $k=>$v){
                                //当为天的时候
                                        if($v['repay_time_type'] == 0){
                                                $true_repay_time = 1;
                                        }
                                        else{
                                                $true_repay_time = $v['repay_time'];
                                        }
                                        $deal['id'] = $v['id'];
                                        $deal['borrow_amount'] = $v['u_load_money'];
                                        $deal['rate'] = $v['rate'];
                                        $deal['loantype'] = $v['loantype'];
                                        $deal['repay_time'] = $v['repay_time'];
                                $deal['repay_time_type'] = $v['repay_time_type'];
                                $deal['repay_start_time'] = $v['repay_start_time'];
                                        $deal_repay_rs = deal_repay_money($deal);

                                $v['interest_amount'] = $deal_repay_rs['month_repay_money'];

                                $list[$k] = $v;
                        }
                        $count = $result['count'];
                        $list = array_slice($list,0,3);// 取最新的的三条投资记录

                        $GLOBALS['tmpl']->assign("list",$list);
                        //end 我的投资



			$GLOBALS['tmpl']->assign('day_deal_repay',$day_deal_repay['list']);
			$GLOBALS['tmpl']->assign('day_repay_list',$day_repay_list['list']);
			$GLOBALS['tmpl']->assign('deals_list',$deals_list['list']);
			//页面关键词描述处理
			$GLOBALS['tmpl']->_var['site_info']['SHOP_TITLE'] = $GLOBALS['lang']['GOLD_UCENTER_TITLE'];
			$GLOBALS['tmpl']->_var['site_info']['SHOP_KEYWORD'] = $GLOBALS['lang']['GOLD_UCENTER_KEYWORD'];
			$GLOBALS['tmpl']->_var['site_info']['SHOP_DESCRIPTION'] = $GLOBALS['lang']['GOLD_UCENTER_DESCRIPTION'];
			$page_title = $GLOBALS['lang']['GOLD_UCENTER_TITLE'];

			$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_CENTER_INDEX']);
			$GLOBALS['tmpl']->assign("post_title",$GLOBALS['lang']['UC_CENTER_INDEX']);
			$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_index.html");
			$GLOBALS['tmpl']->display("page/uc.html");
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo $GLOBALS['tmpl']->fetch("inc/topic_col_list.html");
		}
	}

	public function focustopic()
	{
		$this->init_user();
		$user_info = $this->user_data;
		$ajax =intval($_REQUEST['ajax']);
		if($ajax==0)
		{
			$this->init_main();
		}
		$user_id = intval($GLOBALS['user_info']['id']);
		//输出发言列表
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");

		//开始输出相关的用户日志
		$uids = $GLOBALS['db']->getOne("select group_concat(focused_user_id) from ".DB_PREFIX."user_focus where focus_user_id = ".$user_info['id']." ");

		if($uids)
		{
			$uids = trim($uids,",");
			$result = get_topic_list($limit," user_id in (".$uids.") ");
		}

		$GLOBALS['tmpl']->assign("topic_list",$result['list']);
		$page = new Page($result['total'],app_conf("PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);
		$GLOBALS['tmpl']->assign('user_data',$user_info);
		if($ajax==0)
		{
			$list_html = $GLOBALS['tmpl']->fetch("inc/topic_col_list.html");
			$GLOBALS['tmpl']->assign("list_html",$list_html);
			$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_CENTER_MYFAV']);
			$GLOBALS['tmpl']->assign("post_title",$GLOBALS['lang']['UC_CENTER_MYFAV']);
			$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_index.html");
			$GLOBALS['tmpl']->display("page/uc.html");
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo $GLOBALS['tmpl']->fetch("inc/topic_col_list.html");
		}
	}


	public function lend()
	{
		$this->init_user();
		$user_info = $this->user_data;
		$ajax =intval($_REQUEST['ajax']);
		if($ajax==0)
		{
			$this->init_main();
		}
		$user_id = intval($user_info['id']);
		//输出发言列表
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");

		$result['total'] = $GLOBALS['db']->getOne("SELECT count(*) FROM ".DB_PREFIX."deal_load WHERE user_id=".$user_id);
		if($result['total'] >0)
			$result['list'] = $GLOBALS['db']->getAll("SELECT dl.*,d.rate,d.repay_time,d.repay_time_type,d.deal_status,d.name FROM ".DB_PREFIX."deal_load dl LEFT JOIN ".DB_PREFIX."deal d ON dl.deal_id = d.id WHERE dl.user_id=".$user_id." LIMIT ".$limit);

		$page = new Page($result['total'],app_conf("PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);

		$GLOBALS['tmpl']->assign("lend_list",$result['list']);
		$GLOBALS['tmpl']->assign("user_data",$user_info);

		if($ajax==0)
		{
			$list_html = $GLOBALS['tmpl']->fetch("inc/uc/uc_center_lend.html");
			$GLOBALS['tmpl']->assign("list_html",$list_html);
			$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_CENTER_LEND']);
			$GLOBALS['tmpl']->assign("post_title",$GLOBALS['lang']['UC_CENTER_LEND']);
			$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_index.html");
			$GLOBALS['tmpl']->display("page/uc.html");
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo $GLOBALS['tmpl']->fetch("inc/uc_center_lend.html");
		}
	}


	public function deal()
	{
		$this->init_user();
		$user_info = $this->user_data;
		$ajax =intval($_REQUEST['ajax']);
		if($ajax==0)
		{
			$this->init_main();
		}
		$user_id = intval($user_info['id']);

		//输出借款记录
		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*app_conf("PAGE_SIZE")).",".app_conf("PAGE_SIZE");

		require_once (APP_ROOT_PATH."app/Lib/deal.php");

		$result = get_deal_list($limit,0,"user_id=".$user_id,"id DESC");

		$GLOBALS['tmpl']->assign("deal_list",$result['list']);

		$page = new Page($result['count'],app_conf("PAGE_SIZE"));   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);

		$GLOBALS['tmpl']->assign('user_data',$user_info);
		if($ajax==0)
		{
			$list_html = $GLOBALS['tmpl']->fetch("inc/uc/uc_center_deals.html");
			$GLOBALS['tmpl']->assign("list_html",$list_html);
			$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['UC_CENTER_MYDEAL']);
			$GLOBALS['tmpl']->assign("post_title",$GLOBALS['lang']['UC_CENTER_MYDEAL']);
			$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_index.html");
			$GLOBALS['tmpl']->display("page/uc.html");
		}
		else
		{
			header("Content-Type:text/html; charset=utf-8");
			echo $GLOBALS['tmpl']->fetch("inc/uc/uc_center_deals.html");
		}
	}



	public function mayfocus()
	{
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));
		$GLOBALS['tmpl']->assign("user_data",$user_info);
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['YOU_MAY_FOCUS']);
		$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_mayfocus.html");
		$GLOBALS['tmpl']->display("page/uc.html");
	}
	public function fans()
	{
		$user_info = $this->user_data;

		$page_size = 24;

		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;

		$user_id = intval($GLOBALS['user_info']['id']);

		//输出粉丝
		$total = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_focus where focused_user_id = ".$user_id);
		$fans_list = array();
		if($total > 0){
			$fans_list = $GLOBALS['db']->getAll("select focus_user_id as id,focus_user_name as user_name from ".DB_PREFIX."user_focus where focused_user_id = ".$user_id." order by id desc limit ".$limit);

			foreach($fans_list as $k=>$v)
			{
				$focus_uid = intval($v['id']);
				$focus_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id." and focused_user_id = ".$focus_uid);
				if($focus_data)
				$fans_list[$k]['focused'] = 1;
			}
		}
		$GLOBALS['tmpl']->assign("fans_list",$fans_list);

		$page = new Page($total,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);


		$GLOBALS['tmpl']->assign("user_data",$user_info);
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['MY_FANS']);
		$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_fans.html");
		$GLOBALS['tmpl']->display("page/uc.html");
	}


	public function focus()
	{
		$this->init_user();
		$user_info = $this->user_data;

		$page_size = 24;

		$page = intval($_REQUEST['p']);
		if($page==0)
		$page = 1;
		$limit = (($page-1)*$page_size).",".$page_size;

		$user_id = intval($GLOBALS['user_info']['id']);

		//输出粉丝
		$total = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id);
		$focus_list = array();
		if($total > 0){
			$focus_list = $GLOBALS['db']->getAll("select focused_user_id as id,focused_user_name as user_name from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id." order by id desc limit ".$limit);

			foreach($focus_list as $k=>$v)
			{
				$focus_uid = intval($v['id']);
				$focus_data = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_focus where focus_user_id = ".$user_id." and focused_user_id = ".$focus_uid);
				if($focus_data)
				$focus_list[$k]['focused'] = 1;
			}
		}
		$GLOBALS['tmpl']->assign("focus_list",$focus_list);

		$page = new Page($total,$page_size);   //初始化分页对象
		$p  =  $page->show();
		$GLOBALS['tmpl']->assign('pages',$p);


		$list_html = $GLOBALS['tmpl']->fetch("inc/uc/uc_center_focus.html");
		$GLOBALS['tmpl']->assign("list_html",$list_html);
		$GLOBALS['tmpl']->assign("user_data",$user_info);
		$GLOBALS['tmpl']->assign("user_id",$user_id);
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['MY_FOCUS']);


		$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_index.html");
		$GLOBALS['tmpl']->display("page/uc.html");
	}


	public function setweibo()
	{
		$user_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".intval($GLOBALS['user_info']['id']));

		$apis = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."api_login ");

		foreach($apis as $k=>$v)
		{
			if($user_info[strtolower($v['class_name'])."_id"])
			{
				$apis[$k]['is_bind'] = 1;
				if($user_info["is_syn_".strtolower($v['class_name'])]==1)
				{
					$apis[$k]['is_syn'] = 1;
				}
				else
				{
					$apis[$k]['is_syn'] = 0;
				}
			}
			else
			{
				$apis[$k]['is_bind'] = 0;
			}

//			if(file_exists(APP_ROOT_PATH."system/api_login/".$v['class_name']."_api.php"))
//			{
//				require_once APP_ROOT_PATH."system/api_login/".$v['class_name']."_api.php";
//				$api_class = $v['class_name']."_api";
//				$api_obj = new $api_class($v);
//				$url = $api_obj->get_bind_api_url();
//				$apis[$k]['url'] = $url;
//			}
		}
		$GLOBALS['tmpl']->assign("apis",$apis);
		$GLOBALS['tmpl']->assign("user_data",$user_info);
		$GLOBALS['tmpl']->assign("page_title",$GLOBALS['lang']['SETWEIBO']);
		$GLOBALS['tmpl']->assign("inc_file","inc/uc/uc_center_setweibo.html");
		$GLOBALS['tmpl']->display("page/uc.html");
	}

	private function get_loadlist($user_id,$where) {
		$condtion = "   AND dlr.has_repay = 0  ".$where." ";
    	$sql = "select dlr.*,u.user_name,u.level_id,u.province_id,u.city_id from ".DB_PREFIX."deal_load_repay dlr LEFT JOIN ".DB_PREFIX."user u ON u.id=dlr.user_id  where ((dlr.user_id = ".$user_id." and dlr.t_user_id = 0) or dlr.t_user_id = ".$user_id.") $condtion order by dlr.repay_time desc ";
		$list = $GLOBALS['db']->getAll($sql);

		return $list;
    }
}
?>
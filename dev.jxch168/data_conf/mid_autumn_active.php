<?php

/*
 * 功能：中秋提“钱”过 活动信息配置 活动配置
 * 时间：2015年08月25日
 *
 */
return array(
            //中秋提“钱”过     活动配置
            'mid_autumn_acting'     => array(
                                        'ACT_STATUS'     => 1,//中秋提“钱”过活动状态 1代表活动开启 0代表关闭
                                        'ACT_NAME'            => '中秋提“钱”过  收益再加10%',//活动名称
                                        'ACT_START_TIME'     => '2015-08-27 00:00:00',//活动开始日期
                                        'ACT_END_TIME'       => '2015-09-27 23:59:59', //活动结束日期
                                        'ACT_RATE'     => 0.1, //原收益上再多获得10%的收益奖励 10% <==> 0.1
                                     )
    );
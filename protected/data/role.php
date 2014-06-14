<?php

/*
 * 数据级别data：0级为最高级（不受任何限制），
 *               1级为受部分限制（目前和0级的区别在于查看交易明细时交易账号被加*号）；
 *               2级为客户经理或维护人员：只能看该客户经理所发展或维护的终端；
 * */
return array(
    'smanager' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '超级管理员',
        'display' => true,
        'children' => array(
            'mchtm', //商户管理
            'mchtm_r',
            'systemlog', //系统日志
            'log',//交易日志
            'branch',//品牌
            'operator', //操作员管理
            'coupon', //促销管理
            'vipcard', //会员卡
            'vipcard_r',//会员卡查看
            'rpt', //报表管理
            'user', //用户管理
            'top', //推送管理
            'tophis', //推送历史
            'voucher', //代金券管理
            'voucher_r',//代金券管理查看
            'goods',//商品管理
            'goods_r',//商品查看
            'order',  //订单管理
        	'order_r', //订单查看
        	'menu',   //菜单管理
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'bank_m' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '银行管理员',
        'display' => true,
        'children' => array(
            'mchtm', //商户管理
            'mchtm_r',//商户管理查看
            'coupon', //促销管理
            'vipcard', //会员卡
            'vipcard_r',//会员卡查看
            'rpt', //报表管理
            'user', //用户管理
            'top', //推送管理
            'tophis', //推送历史
            'voucher', //代金券管理
            'voucher_r',//代金券管理查看
            'private_info',//个人信息维护
        	'goods',//商品管理
        	'goods_r',//商品查看
        	'order',  //订单管理
        	'order_r', //订单查看
        	'menu',   //菜单管理
        ),
        'bizRules' => '',
        'data' => ''
    ),
);
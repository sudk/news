<?php

return array(
    'mchtm' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '商户管理',
        'children' => array(
            'mchtm/mcht/grid', 'mchtm/mcht/list', 'mchtm/mcht/new', 'mchtm/mcht/edit', 'mchtm/mcht/del', 'mchtm/mcht/detail',
            'mchtm/mcht/querySmallTypes', 'mchtm/mcht/photo', 'mchtm/mcht/gettempimg', 'mchtm/mcht/delimg', 'mchtm/mcht/ptupload',
        	'mchtm/mcht/batchnew','mchtm/mcht/tmpdown','mchtm/mcht/batchup','mchtm/mcht/batchdel',
        	'mchtm/mchtphoto/list','mchtm/mchtphoto/new','mchtm/mchtphoto/del','mchtm/mchtphoto/cover'
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'mchtm_r' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '商户管理-查看',
        'children' => array(
            'mchtm/mcht/grid', 'mchtm/mcht/list', 'mchtm/mcht/detail', 'mchtm/mcht/gettempimg',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'branch' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => false,
        'description' => '品牌管理',
        'children' => array(
            'sys/branch/grid', 'sys/branch/list', 'sys/branch/new', 'sys/branch/edit',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'operator' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => false,
        'description' => '操作员管理',
        'children' => array(
            'operator/operator/grid', 'operator/operator/list', 'operator/operator/new', 'operator/operator/edit',
            'operator/operator/editpri', 'operator/operator/checkid', 'operator/operator/checkloginid', 'operator/operator/del',
            'operator/operator/detail','operator/operator/auth','operator/auth/new','operator/auth/delete',
        	'operator/operator/pwd'
        	
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'private_info' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => false,
        'description' => '个人信息维护',
        'children' => array(
            'operator/operator/editpri'
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'coupon' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => false,
        'description' => '促销管理',
        'children' => array(
            'coupon/coupon/grid', 'coupon/coupon/list', 'coupon/coupon/detail', 'coupon/coupon/new', 'coupon/coupon/edit',
            'coupon/coupon/del', 'coupon/coupon/autobranch','coupon/coupon/picupload','coupon/coupon/pic',
        	'coupon/coupon/batchdel','coupon/couvcher/list','coupon/couvcher/grid',
        	'coupon/mcht/list','coupon/mcht/grid'

        ),
        'bizRules' => '',
        'data' => ''
    ),
    'vipcard' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '会员卡管理',
        'children' => array(
            'vipcard/vipcard/grid', 'vipcard/vipcard/list', 'vipcard/vipcard/detail', 'vipcard/vipcard/new', 'vipcard/vipcard/edit',
            'vipcard/vipcard/stop', 'vipcard/vipcard/start',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'vipcard_r' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '会员卡管理-查看',
        'children' => array(
            'vipcard/vipcard/grid', 'vipcard/vipcard/list', 'vipcard/vipcard/detail',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'rpt' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '报表管理',
        'children' => array(
            'rpt/branch/grid', 'rpt/branch/list', 'rpt/branch/download',
            'rpt/coupon/grid', 'rpt/coupon/list', 'rpt/coupon/download',
            'rpt/merchant/grid', 'rpt/merchant/list', 'rpt/merchant/download',
            'rpt/user/grid', 'rpt/user/list', 'rpt/user/download',
            'rpt/voucher/grid', 'rpt/voucher/list', 'rpt/voucher/download',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'user' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '用户管理',
        'children' => array(
            'user/coupon/grid', 'user/coupon/list', 'user/coupon/detail',
            'user/favaction/grid', 'user/favaction/list', 'user/favaction/detail',
            'user/favmer/grid', 'user/favmer/list', 'user/favmer/detail',
            'user/memcard/grid', 'user/memcard/list', 'user/memcard/detail','user/memcard/merdetail',
            'user/usr/grid', 'user/usr/list', 'user/usr/detail',
            'user/voucher/grid', 'user/voucher/list', 'user/voucher/detail',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'systemlog' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '系统操作日志',
        'children' => array(
            'sys/ophis/grid', 'sys/ophis/list', 'sys/ophis/detail',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'log' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '交易日志',
        'children' => array(
            'sys/usertrans/detail', 'sys/usertrans/list', 'sys/usertrans/grid'
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'top' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '推送管理',
        'children' => array(
            'top/top/grid', 'top/top/list', 'top/top/detail', 'top/top/groupdetail',
        	'top/top/new', 'top/top/edit', 'top/top/del','top/top/groupdel',
        	'top/top/sgrid', 'top/top/slist','top/top/picupload','top/top/ajaxupdate',
        	'top/top/ajaxsave','top/top/pic','top/top/ajaxpic','top/top/copyfile',
        	'top/top/batchdel'
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'tophis' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '推送历史',
        'children' => array(
            'tophis/tophis/grid', 'tophis/tophis/list',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'voucher' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '代金券管理',
        'children' => array(
            'voucher/activity/new','voucher/activity/edit','voucher/activity/set',
            'voucher/getvou/grid','voucher/getvou/list',
            'voucher/mcht/grid','voucher/mcht/list',
            'voucher/voucher/grid','voucher/voucher/list','voucher/voucher/detail','voucher/voucher/new','voucher/voucher/edit',
            'voucher/voucher/gettempimg','voucher/voucher/delimg','voucher/voucher/ptupload',
            'voucher/vouhis/grid','voucher/vouhis/list','voucher/voucher/picupload'
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'voucher_r' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display' => true,
        'description' => '代金券管理-查看',
        'children' => array(
            'voucher/getvou/grid','voucher/getvou/list',
            'voucher/mcht/grid','voucher/mcht/list',
            'voucher/voucher/grid','voucher/voucher/list','voucher/voucher/detail',
            'voucher/voucher/gettempimg',
            'voucher/vouhis/grid','voucher/vouhis/list',
        ),
        'bizRules' => '',
        'data' => ''
    ),
		
	'goods' => array(
			'type' => CAuthItem::TYPE_TASK,
			'display' => true,
			'description' => '商品管理',
			'children' => array(
				'goods/goods/grid', 'goods/goods/list', 'goods/goods/new', 'goods/goods/edit',
				'goods/mcht/list','goods/mcht/grid','goods/goods/more','goods/goods/picupload',
				'goods/goods/del','goods/goods/batchdel','goods/goods/detail',
				'goods/goods/mcht','goods/goods/pic','goods/goods/task'
			),
			'bizRules' => '',
			'data' => ''
	),
	'goods_r' => array(
			'type' => CAuthItem::TYPE_TASK,
			'display' => true,
			'description' => '商品查看看',
			'children' => array(
				'goods/goods/grid', 'goods/goods/list','goods/goods/detail'
			),
			'bizRules' => '',
			'data' => ''
		),
	'order' => array(
				'type' => CAuthItem::TYPE_TASK,
				'display' => true,
				'description' => '订单管理',
				'children' => array(
						'order/order/grid', 'order/order/list','order/order/detail', 'order/order/price',
				),
				'bizRules' => '',
				'data' => ''
		),
	'order_r' => array(
				'type' => CAuthItem::TYPE_TASK,
				'display' => true,
				'description' => '订单查看',
				'children' => array(
						'order/order/grid', 'order/order/list', 'order/order/detail',
				),
				'bizRules' => '',
				'data' => ''
		),
		
	'menu' => array(
			'type' => CAuthItem::TYPE_TASK,
			'display' => true,
			'description' => '菜单管理',
			'children' => array(
				'menu/menu/grid', 'menu/menu/list','menu/menu/detail', 
				'menu/menu/new','menu/menu/edit',
			),
			'bizRules' => '',
			'data' => ''
	),
	
);


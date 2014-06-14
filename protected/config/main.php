<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'新闻管理系统',
    'language' => 'zh_cn',
    'preload'=>array('log'),
    'runtimePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'/runtime',
	//'runtimePath'=>'/usr/local/webapp/qllife/runtime',   //正式
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.components.widgets.*',
		'ext.PHPExcel.*',
    ),
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'loginUrl' => 'index.php?r=site/login',
        ),
        'authManager' => array(
            'class' => 'CPhpAuthManager',
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;port=3306;dbname=news',
            'emulatePrepare' => true,
            'enableProfiling'=>true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        'errorHandler'=>array(
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, info',
                ),
            ),
        ),
		 'cache'=>array(
            'class'=>'system.caching.CFileCache'
            ),
    ),
    'modules'=>array(
        'operator'       =>array(),
        'news'       =>array(),
        'mobile'       =>array(),
    ),
    'params' => require(dirname(__FILE__) . '/params.php'),
);

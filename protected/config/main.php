<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
    'language' => 'zh_cn', //中文显示
    //默认的controller
    'defaultController' => 'home',
    //主题
    'theme' => 'basic',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.logics.*',
        'application.payments.*',
        'application.util.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        
        'widgetFactory'=>array(
            'class'=>'CWidgetFactory',
        ),
        
        'goods'=>array(
            'class'=>'GoodsComponent',//加载类库
        ),

		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,    // 将代码里链接的index.php隐藏掉。  
            'urlSuffix'=>'.html',  
			'rules'=>array(
                //首页
                'index'=>array('home/index','urlSuffix'=>'.html'),
                //登录页
                'login'=>array('user/login','urlSuffix'=>'.html'),
                //退出页
                'logout'=>array('user/logout','urlSuffix'=>'.html'),
                //注册页
                'reg'=>array('user/register','urlSuffix'=>'.html'),
                //详情页
                'item_<id:\d+>'=>array('home/products','urlSuffix'=>'.html'), 
                //成功信息页
                'success'=>array('common/success','urlSuffix'=>'.html'),
                //404
                'error'=>array('common/error','urlSuffix'=>'.html'),
                //警告
                'warning'=>array('common/warning','urlSuffix'=>'.html'),
                //支付同步信息返回页
                'callback'=>array('pay/callback'),
                //支付异步信息通知页
                'notify'=>array('pay/notify'),
			),
		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
//			'errorAction'=>'common/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
                array(
                    'class'=>'CProfileLogRoute',
                    'levels'=>'error,warning',
                ),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'auto_login_time' => time() + 60 * 60 * 24 * 30,  //有限期30天
        'auto_login_cookie_name' => 'shop_login_auto',
	),
);

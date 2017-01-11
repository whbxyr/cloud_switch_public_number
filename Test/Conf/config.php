<?php
	// 项目配置文件
	return array(
		// '配置项'=>'配置值'
		// 'TMPL_ENGINE_TYPE'    =>'PHP',
		'TMPL_TEMPLATE_SUFFIX' => '.php',
		// 'URL_TEMPLATE_SUFFIX' => ''php|shtml|html|xml',
		// 'URL_HTML_SUFFIX'   => 'php|shtml|html|xml',
		'DB_TYPE'           => 'mysql', // 数据库类型
	    'DB_HOST'           => 'localhost', // 服务器地址
	    'DB_NAME'           => 'article', // 数据库名
	    'DB_USER'           => 'root', // 用户名
	    'DB_PWD'            => '111', // 密码
	    'DB_PORT'           => 3306, // 端口
	    'DB_PREFIX'         => '', // 数据表前缀
	    // 配置项目的资源文件目录
	    'TMPL_PARSE_STRING' => array(
	    	// __ROOT__是网站www根目录下的项目根目录
	    	'__JS__'  => __ROOT__.'/Public/js', // 配置项目的js文件根目录
	    	'__CSS__' => __ROOT__.'/Public/css', // 配置项目的css文件根目录
	    	'__PHP__' => __ROOT__.'/Public/php', // 配置项目的php文件目录
	    	'__IMG__' => __ROOT__.'/Public/img', // 配置项目的img即图片目录
	    ),
	    'yuming' => 'whbxyr.tunnel.qydev.com/TP3.1.3/',
	);
?>
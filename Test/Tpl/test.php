<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>模板</title>
    <!-- <script src="__PUBLIC__/js/test.js"></script>
    <link rel="stylesheet" href="__PUBLIC__/css/test.css"> -->
    <script src="__JS__/test.js"></script>
    <link rel="stylesheet" href="__CSS__/test.css">
</head>
<body>
	<?php
	echo 'hello world';
	// 面向对象的建立数据库连接的方法
	$db = new mysqli('localhost','root','111');

	if (mysqli_connect_errno()) {
	    echo '连接数据库失败，请稍后重试！';
	    exit;
	}
	// 判断数据库是否存在
	$select = $db->select_db('article');
	if ($select) {
		echo 'haha';
	}
	// include_once('__ROOT__/Public/php/test.php');
	// require 'http://wechat.3w.dkys.org/TP3.1.3/Public/php/test.php';
	// require './uc_denglu/config.inc.php';
	?>
    <p>test</p>
</body>
</html>
<!-- <import type='js' file='Js.test'> -->
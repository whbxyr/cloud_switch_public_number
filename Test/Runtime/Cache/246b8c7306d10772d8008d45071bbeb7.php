<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
 echo 'hello world'; $db = new mysqli('localhost','root','111'); if (mysqli_connect_errno()) { echo '连接数据库失败，请稍后重试！'; exit; } $select = $db->select_db('article'); if ($select) { echo 'haha'; } ?>
    <p>test</p>
</body>
</html>
<!-- <import type='js' file='Js.test'> -->
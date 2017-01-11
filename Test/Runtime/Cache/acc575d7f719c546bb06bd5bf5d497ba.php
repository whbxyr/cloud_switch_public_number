<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($errorType); ?></title>
</head>
<style>
p {
	margin-top: 100px;
	text-align: center;
	font-size: 40px;
	color: #f00;
	line-height: 50px;
}
</style>
<body>
    <p><?php echo ($errorMsg); ?></p>
</body>
</html>
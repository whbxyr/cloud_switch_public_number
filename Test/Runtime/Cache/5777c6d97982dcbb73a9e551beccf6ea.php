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
<body><!--
    -->
    <?php
 include './Public/php/include.php'; ?><p>test</p>
    <button id="sure">sure</button>
</body>
</html>
<script>
document.getElementById('sure').onclick = function () {
	var request = new XMLHttpRequest();
	request.open('GET', '__PHP__/test.php');
	request.send(null);
	request.onreadystatechange = function () {
		if (request.readyState === 4) {
			if (request.status === 200) {
				var result = document.createElement('div');
				result.innerHTML = request.responseText;
				document.body.appendChild(result);
				alert(request.responseText);
			}
		}
	}
}
</script>
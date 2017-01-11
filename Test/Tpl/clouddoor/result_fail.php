<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>开门失败</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="./css/style.css">

	<script src="http://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<div id="container">
		<header id="information">
			<div id="photo"><img src="./images/images.jpg" alt="头像"></div>
			<div id="nickname"><h3>钙世英雄</h3></div>
		</header>
		<div class="door">
			<div class="door_icon_area">
				<i class="icon_fail icon_msg"></i>
			</div>
			<div class="door_msg"><h2>开门失败</h2></div>
			<div class="btn_area">
			<!-- to add a url to a tag -->
				<a href="./signup.html" class="btn btn_primary">注册</a>
				<a class="btn btn_default">取消</a>
			</div>
		</div>
		<footer>
			<p class="footer_txt">Copyright © 2016 云开关</p>
		</footer>
	</div>
	<script>
		jQuery(document).ready(function($) {
			$('.btn').on('click', function(event) {
				event.preventDefault();
				window.close();
			});
		});


	</script>
</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>开门成功</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="__CSS__/signupPage.css">

	<script src="http://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
	<div id="container">
		<header id="information">
			<div id="photo"><img src="<?php echo ($headimgurl); ?>" alt="头像"></div>
			<div id="nickname"><h3><?php echo ($nickname); ?></h3></div>
		</header>
		<div class="door">
			<div class="door_icon_area">
				<i class="icon_success icon_msg"></i>
			</div>
			<div class="door_msg"><h2>成功开门</h2></div>
			<div class="btn_area">
				<a class="btn btn_primary">确认</a>
			</div>
		</div>
		<footer>
			<p class="footer_txt">Copyright © 2016 云开关</p>
		</footer>
	</div>
	<script>
		// wx.config({
		//     debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
		//     appId: 'wx1765e06898bf0f1b', // 必填，公众号的唯一标识
		//     timestamp: '<?php echo ($timestamp); ?>', // 必填，生成签名的时间戳
		//     nonceStr: '<?php echo ($noncestr); ?>', // 必填，生成签名的随机串
		//     signature: '<?php echo ($signature); ?>',// 必填，签名，见附录1
		//     jsApiList: [
		//     	'hideOptionMenu',
		//     	'closeWindow',
		//     	], // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		// });
		// wx.ready(function(){
		// 	wx.hideOptionMenu();
		// });
		jQuery(document).ready(function($) {
			$('.btn').on('click', function(event) {
				event.preventDefault();
				window.close();
				wx.closeWindow();
				// WeixinJSBridge.call('closeWindow');
			});
		});


	</script>
	<?php echo ($customer); ?>
</body>
</html>
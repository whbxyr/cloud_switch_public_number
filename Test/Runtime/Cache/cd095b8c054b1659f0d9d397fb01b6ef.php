<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>注册</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="__CSS__/signupPage.css">
	<script src="http://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<div id="loadingToast" class="weui_loading_toast" style="display: none;">
		<div class="weui_mask_transparent"></div>
			<div class="weui_toast">
				<div class="weui_loading">
				    <div class="weui_loading_leaf weui_loading_leaf_0"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_1"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_2"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_3"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_4"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_5"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_6"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_7"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_8"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_9"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_10"></div>
				    <div class="weui_loading_leaf weui_loading_leaf_11"></div>
				</div>
				<p class="weui_toast_content">正在提交注册信息</p >
			</div>
		</div>
	</div>
	<div id="container">
		<header id="information">
			<div id="photo"><img src="<?php echo ($headimgurl); ?>" alt="头像"></div>
			<div id="nickname"><h3><?php echo ($nickname); ?></h3></div>
		</header>
		<div class="body" id="form" role="form">
			<div class="cell">
				<div class="cell_hd">
					<label class="label">姓名<em>*</em></label>
				</div>
				<div class="cell_bd">
					<input id="name" type="text" pattern="isName" placeholder="姓名" autofocus required>
					<div class="warn_area" style="display:none;" id="forname"><p class="warn_msg">hello world</p></div>
				</div>
			</div>
			<div class="cell">
				<div class="cell_hd">
					<label class="label">手机<em>*</em></label>
				</div>
				<div class="cell_bd">
					<input id="phone" type="text" pattern="isPhone" placeholder="手机号码" required>
					<div class="warn_area" style="display:none;" id="forphone"><p class="warn_msg">hello world</p></div>
				</div>
			</div>
			<div class="cell">
				<div class="cell_hd">
					<label class="label">e-mail<em>*</em></label>
				</div>
				<div class="cell_bd">
					<input id="email" type="email" pattern="isEmail" placeholder="邮箱">
					<div class="warn_area" style="display:none;" id="foremail"><p class="warn_msg">hello world</p></div>
				</div>
			</div>
			<div class="cell">
				<div class="cell_hd">
					<label class="label">家庭地址<em>*</em></label>
				</div>
				<div class="cell_bd">
					<input id="address" type="text" pattern="isAddress" placeholder="住址" required>
					<div class="warn_area" style="display:none;" id="foraddress"><p class="warn_msg">hello world</p></div>
				</div>
			</div>
			<div class="btn_area">
				<span class="btn btn_primary btn_disabled">确认</span>
			</div>
		</div>
		<footer>
			<p class="footer_txt">Copyright © 2016 云开关</p>
		</footer>
		<!-- <p id="wait" style="width: 100%; height: 100%; opacity: 0.9; color: #000; margin: 0; padding: 0; text-align: center; background-color: #3C3C3C; margin-top: -100%;">注册请求发送中。。。。。。</p> -->
		<div id="tip" style="color: #000; width: 300px; height: 300px; margin-top: -500px; border-radius: 5px; opacity: 0.9; background-color: #3C3C3C; z-index: 100; display: none; text-align: center;"></div>
	</div>
	<script type="text/javascript">
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
		/**
		 * 
		 * 用户头像url："#photo img"
		 * 用户昵称text: "#nickname h3"
		 */
		// 标志变量，阻止forBtn函数执行两次
		var send = false;
		//表单信息
		var user = {
					'name': '',
					'phone': '',
					'email': '',
					'address': '',
					'length': 4
					};
		// 验证表单信息
		var validator = {
			flag: [],
			setOrignalFlag: function (length) {
				for (var i = length - 1; i >= 0; i--) {
					this.flag[i] = false;
				}
			},
			setSingleFlag: function (i, v) {
				this.flag[i] = v;
			},
			isAllLegal: function () {
				for (var i = this.flag.length - 1; i >= 0; i--) {
					if (!this.flag[i]) {return false;}
				}
				return true;
			},
		};

		validator.isNonEmpty = {
			validate: function (v) {
				return v !== '';
			},
			msg: function (v) {
				return v + "不能为空";
			},
		};

		validator.isPhone = {
			validate: function (v) {
				return /^1(3|4|5|7|8)\d{9}$/g.test(v);
			},
			msg: function () {
				return "手机格式不合法";
			}
		};

		validator.isEmail = {
			validate: function (v) {
				return /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/g.test(v);
			},
			msg: function () {
				return "邮箱格式不合法";
			}
		};

		validator.isName = {
			validate: function (v) {
				return /^[\u4E00-\u9FA5A-Za-z]+$/g.test(v); 
			},
			msg: function () {
				return "姓名只能由中文和英文字母组成";
			}
		};

		validator.isAddress = {
			validate: function (v) {
				return /^[\u4E00-\u9FA5A-Za-z0-9]+$/g.test(v); 
			},
			msg: function () {
				return "地址只能由中文、数字和英文字母组成";	
			}
		};
					 
		jQuery(document).ready(function($) {
			// 表单验证通过项为0
			validator.setOrignalFlag(user.length);
			//输入框添加事件，检查输入是否有误
			$('input').each(function(index, el) {
				$(el).bind('keyup blur', function(event) {
					// 输入框的输入值
					var value = $(this).val();
					// 获取该输入框的id
					var oId = $(this).attr('id');
					var placeholder = $(this).attr('placeholder');
					// 获得错误显示控件的id
					var warnId = '#for' + $(this).attr('id');
					// 将输入框的值放user对象中对应的值中
					user[oId] = value;
					// 判断是否为空值
					if (!validator.isNonEmpty.validate(value)) {  //空值时
						// 提示错误信息显示
						$(warnId + ' .warn_msg').text(validator.isNonEmpty.msg(placeholder));
						$(warnId).attr('style', 'display:block;');
						validator.setSingleFlag(index, false);
					}
					else {
						var pattern = $(this).attr('pattern');
						if (!validator[pattern].validate(value)) {
							$(warnId + ' .warn_msg').text(validator[pattern].msg());
							$(warnId).attr('style', 'display:block;');
							validator.setSingleFlag(index, false);
						}
						else {
							$(warnId).attr('style', 'display:none;'); 
							validator.setSingleFlag(index, true);
						}
					}
					var ifPass = validator.isAllLegal();
					if (ifPass === true) {
						// 当信息输入格式没问题时，为按钮添加点击事件,变为可用状态
						$('.btn')
							.click(forBtn)
							.removeClass('btn_disabled');
						$('body').keyup(function(event) {
									if (event.which == 13) {
										forBtn();
									}
								});
					}
					else {
						$('.btn').addClass('btn_disabled').unbind('click');
					}
				});
			});

			// (function () {
			// 	$('document').width()
			// })();
		});
		
		// $('#loadingToast').css('display', 'block');
		/**
		 * 按钮事件
		 * @param  {object} v [按钮对象]
		 * @return {[type]}   [description]
		 */
		function forBtn() {
			if (!send) {
				$('#loadingToast').css('display', 'block');
				// 将按钮置为不可用，除掉click事件
				$('.btn').addClass('btn_disabled').unbind('click');
				// 将user对象发送到服务器
				$.post('__ROOT__/test.php/Native/signupCustomer', {
					openid: '<?php echo ($openid); ?>',
					nickname: '<?php echo ($nickname); ?>',
					headimgurl: '<?php echo ($headimgurl); ?>',
					name: user.name,
					phone: user.phone,
					email: user.email,
					address: user.address
				}, function (data, textStatus) {
					$('#loadingToast').css('display', 'none');
					$('#tip').css("display", "block");
					var i = 5;
					showTip(i);
					function tipAnimate() {
						var timer;
						timer = setTimeout(function () {
							showTip(--i);
							if (i === 0) {
								WeixinJSBridge.call('closeWindow');
								return;
							}
							tipAnimate();
						}, 1000);
					}
					function showTip(second) {
						$('#tip').html(data + '<strong style="font-size: 50px;">' + second + '</strong>' + '秒后退出此页');
					}
					tipAnimate();
				});
				send = true;
			}
		}
	</script>
</body>
</html>
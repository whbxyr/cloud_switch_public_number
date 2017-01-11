<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>注册</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="__CSS__/style.css">
	<script src="http://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
	<div id="container">
		<header id="information">
			<div id="photo"><img src="./images/images.jpg" alt="头像"></div>
			<div id="nickname"><h3>钙世英雄</h3></div>
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
				<a class="btn btn_primary btn_disabled">确认</a>
			</div>
		</div>
		<footer>
			<p class="footer_txt">Copyright © 2016 云开关</p>
		</footer>
	</div>
	<script type="text/javascript">
		/**
		 * 
		 * 用户头像url："#photo img"
		 * 用户昵称text: "#nickname h3"
		 */
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
					} else {
						var pattern = $(this).attr('pattern');
						if (!validator[pattern].validate(value)) {
							$(warnId + ' .warn_msg').text(validator[pattern].msg());
							$(warnId).attr('style', 'display:block;'); 
							validator.setSingleFlag(index, false);
						} else {
							$(warnId).attr('style', 'display:none;'); 
							validator.setSingleFlag(index, true);
						}
					}
					var ifPass = validator.isAllLegal();
					if (ifPass === true) {
						// 当信息输入格式没问题 时，为按钮添加点击事件,变为可用状态
						$('.btn')
							.click(forBtn)
							.removeClass('btn_disabled');
						$('body').keyup(function(event) {
									if (event.which == 13) {
										forBtn();
									}
								});
					} else {
						$('.btn').addClass('btn_disabled').unbind('click');
					}
				});
			});
		});
			
		/**
		 * 按钮事件
		 * @param  {object} v [按钮对象]
		 * @return {[type]}   [description]
		 */
		function forBtn() {				
			// 将按钮置为不可用，除掉click事件
			$('.btn').addClass('btn_disabled').unbind('click');
			// 将user对象发送到服务器
			// to do it
			$.post('__PHP__/signupHandler.php', user, function(data, textStatus, xhr) {
			});
			// 服务器反馈信息显示在页面
		}
	</script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员授权</title>
    <link rel="stylesheet" href="__CSS__/managerPage.css">
</head>
<style>
p {
	text-align: center;
	color: #fff;
	font-size: 18px;
	line-height: 26px;
}
</style>
<body>
	<p>微信云开关管理员授权处</p>
	<p>点击相应的白框给相应的用户授权，使用户可以扫码开门！</p>
    <table cellspacing="4" class="contentshow">
        <tr><td>姓名</td><td>手机</td><td>邮箱</td><td>家庭地址</td><td>授权</td></tr>
        <?php
        	include('./Public/php/connect.php');
			$select_query = 'select * from request_customer';
			$select_result = $db->query($select_query);
			$num_select_result = $select_result->num_rows;

			for ($i = 0; $i < $num_select_result; $i++) {
				$row = $select_result->fetch_assoc();
				echo '<tr><td style="display: none;">'.$row['openid'].'</td><td id="name'.$i.'">'.$row['name'].'</td><td>'.$row['phone'].'</td><td>'
				    .$row['email'].'</td>'.'<td>'.$row['address'].'</td><td><input id="$'.$i.'" type="checkbox" style="cursor: pointer;"></td></tr>';
			}
			$select_result->free();
  			// 关闭连接
  			$db->close();
        ?>
    </table>
</body>
</html>
<script>
window.onload = function () {
	var ifMsg = document.getElementById('name0');
	if (!ifMsg) {
		var noneMsgTip = document.createElement('p');
		noneMsgTip.innerText = '目前没有任何用户请求注册！不用授权！';
		noneMsgTip.style.color = '#f00';
		noneMsgTip.style.marginTop = '100px';
		noneMsgTip.style.fontSize = '50px';
		document.body.appendChild(noneMsgTip);
		var table = document.getElementsByTagName('table')[0];
		table.style.display = 'none';
	}
	else {
		var inputelts = document.getElementsByTagName('input');
		for (var i = 0, len = inputelts.length; i < len; i++) {
			if (inputelts[i].id.substring(0, 1) === '$') {
				inputelts[i].onclick = (function (i) {
					return function () {
						var sure = window.confirm('您确定要为用户"' + document.getElementById('name' + i).innerText + '"授权吗？');
						if (sure) {
							inputelts[i].disabled = true;
							var request = new XMLHttpRequest();
							var openid = inputelts[i].parentNode.parentNode.firstChild.innerText;
							var name = inputelts[i].parentNode.parentNode.firstChild.nextSibling.innerText;
							var phone = inputelts[i].parentNode.parentNode.firstChild.nextSibling.nextSibling.innerText;
							var email = inputelts[i].parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.innerText;
							var address = inputelts[i].parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.innerText;
							// var url = '__PHP__/valid.php?openid=' + openid;
							var url = '__ROOT__/test.php/Native/validCustomer?openid=' + openid;
							request.open('GET', url);
							request.send(null);
							request.onreadystatechange = function () {
								if (request.readyState === 4) {
									if (request.status === 200) {
										var text = document.createTextNode(request.responseText);
										inputelts[i].parentNode.appendChild(text);
										inputelts[i].checked = true;
										// inputelts[i].disabled = true;
										// location.href = 'http://whbxyr.tunnel.qydev.com/TP3.1.3/test.php/Native/sendMsgAll?openid=' + openid + '&jump=true';
										var template_id = '2xFCp_FdpfT5YjAxPFCf1L-gKfagGhpyCG3uCXv4puM';
										location.href = 'http://{$yuming}test.php/Native/sendValidTemplateMsg?openid=' + openid
										    + '&name=' + name + '&phone=' + phone + '&email=' + email + '&address=' + address + '&template_id=' + template_id;
										// location.href = 'http://whbxyr.tunnel.qydev.com/TP3.1.3/test.php/Native/sendValidTemplateMsg?openid=' + openid
										//     + '&name=' + name + '&phone=' + phone + '&email=' + email + '&address=' + address + '&template_id=' + template_id;
										// window.open('http://whbxyr.tunnel.qydev.com/TP3.1.3/test.php/Index/sendMsgAll?openid=' + openid);
									}
	 							}
							}
						}
						else {
							inputelts[i].checked = false;
						}
					}
				})(i);
			}
		}
	}
}
</script>
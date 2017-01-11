<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员授权</title>
    <link rel="stylesheet" href="__CSS__/managerPage.css">
</head>
<body>
    <table cellspacing="4" class="contentshow">
        <tr><td>姓名</td><td>手机</td><td>邮箱</td><td>家庭地址</td><td>授权</td></tr>
        <?php
 include('./Public/php/connect.php'); $select_query = 'select * from request_customer'; $select_result = $db->query($select_query); $num_select_result = $select_result->num_rows; for ($i = 0; $i < $num_select_result; $i++) { $row = $select_result->fetch_assoc(); echo '<tr><td style="display: none;">'.$row['openid'].'</td><td id="name'.$i.'">'.$row['name'].'</td><td>'.$row['phone'].'</td><td>' .$row['email'].'</td>'.'<td>'.$row['address'].'</td><td><input id="$'.$i.'" type="checkbox" style="cursor: pointer;"></td></tr>'; } $select_result->free(); $db->close(); ?>
    </table>
</body>
</html>
<script>
window.onload = function () {
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
						var url = '__PHP__/valid.php?openid=' + openid;
						request.open('GET', url);
						request.send(null);
						request.onreadystatechange = function () {
							if (request.readyState === 4) {
								if (request.status === 200) {
									// inputelts[i].parentNode.innerHTML = '已授权';
									// alert(request.responseText);
									var text = document.createTextNode(request.responseText);
									inputelts[i].parentNode.appendChild(text);
									inputelts[i].checked = true;
									// inputelts[i].disabled = true;
									location.href = 'http://wechat.3w.dkys.org/TP3.1.3/test.php/Index/sendMsgAll?openid=' + openid + '&jump=true';
									// window.open('http://wechat.3w.dkys.org/TP3.1.3/test.php/Index/sendMsgAll?openid=' + openid);
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
</script>
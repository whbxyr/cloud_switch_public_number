<?php
header('Content-Type: text/plain; charset=utf-8');
$openid = $_POST['openid'];
$nickname = $_POST['nickname'];
$headimgurl = $_POST['headimgurl'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
include('./connect.php');

$insert_query = 'insert into request_customer values("'.$openid.'", "'.$nickname.'", "'.$headimgurl.'", "'.$name.'", "'.$phone.'", "'.$email.'", "'.$address.'")';
$insert_result = $db->query($insert_query);
// 对返回结果进行判断
if ($insert_result) {
    echo '<br><br>您的注册请求已发送<br><br>请耐心等待管理员授权<br><br>';
    header('Location: http://wechat.3w.dkys.org/TP3.1.3/test.php/Index/sendMsgAll?openid='.$openid.'&jump=false');
}
else {
    echo '失败';
}

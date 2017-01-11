<?php
$openid = $_GET['openid'];
include('./connect.php');

$insert_query = 'insert into verified_customer '.'select * from request_customer where openid="'.$openid.'"';
$insert_result = $db->query($insert_query);

$delete_query = 'delete from request_customer where openid="'.$openid.'"';
$delete_result = $db->query($delete_query);

if ($insert_result && $delete_result) {
    echo '授权成功';
}
else {
    echo '授权失败';
}

// 关闭连接
$db->close();

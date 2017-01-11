<?php
// header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/plain; charset=utf-8');
if (!isset($_POST['time']) || empty($_POST['time'])) {
    echo '无法获取文章标识';
    exit;
}

// 连接数据库
include("connect.php");
$time = $_POST['time'];
$delete_query = 'delete from text where time='.$time;
$delete_result = $db->query($delete_query);
if (!$delete_result) {
    echo '删除失败';
    }
else {
    echo '成功删除了一篇文章！';
}

<?php
header('Content-Type: application/json; charset=utf-8');
// 面向对象的建立数据库连接的方法
$db = new mysqli('localhost','root','111');

if (mysqli_connect_errno()) {
    echo '连接数据库失败，请稍后重试！';
    exit;
}
// 判断数据库是否存在
$select = $db->select_db('article');
if (!$select) {
    echo '文章数据库不存在';
    exit;
}
// 设置数据库编码
$db->query('set names utf8');
$select_query = 'select * from text2';
$select_result = $db->query($select_query);
$num_select_result = $select_result->num_rows;
echo ceil($num_select_result/10);
exit;
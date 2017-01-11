<?php

// 面向对象的建立数据库连接的方法
$db = new mysqli('localhost','root','111');

// 设置数据库编码
$db->query('set names utf8');

if (mysqli_connect_errno()) {
    echo '连接数据库失败，请稍后重试！';
    exit;
}

// 判断数据库是否存在
$select = $db->select_db('article');
if (!$select) {
    echo '警告：数据库缺失！！！';
    exit;
}

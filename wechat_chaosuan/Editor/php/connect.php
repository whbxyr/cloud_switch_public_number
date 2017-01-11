<?php
// 面向对象的建立数据库连接的方法
$db = new mysqli('localhost','root','111');

if (mysqli_connect_errno()) {
    echo '连接数据库失败，请稍后重试！';
    exit;
}
// 判断数据库是否存在
$select = $db->select_db('article');
if (!$select) {
    // 创建数据库失败后的处理提示
    $createDB_query = 'create database article';
    $createDB = $db->query($createDB_query);
    if (!$createDB) {
        echo '创建数据库失败';
        exit;
    }
    // 使用数据库失败后的处理提示
    $useDB_query = 'use article';
    $useDB = $db->query($useDB_query);
    if (!$useDB) {
        echo '使用数据库失败';
        exit;
    }
    // 在数据库article中创建数据表text
    $createTB_query = "CREATE TABLE `text` (
                        `id` int(4) NOT NULL AUTO_INCREMENT COMMENT '序号',
                        `time` char(10) NOT NULL COMMENT '时间戳',
                        `kind` char(10) NOT NULL COMMENT '类别',
                        `title` char(50) NOT NULL COMMENT '标题',
                        `cover` text NOT NULL COMMENT '封面',
                        `article` text NOT NULL COMMENT '文章',
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8 COMMENT='文章测试表'";
    $createTB = $db->query($createTB_query);
    // 创建数据表失败后的处理提示
    if (!$createTB) {
        echo "创建数据表失败";
        exit;
    }
}
// 设置数据库编码
$db->query('set names utf8');

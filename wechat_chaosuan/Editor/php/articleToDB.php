<?php
// 设置页面内容是文本，编码格式是utf-8
header('Content-Type: text/plain; charset=utf-8');
// 若取不到值，那么进行相应的信息反馈，全空格的情况默认为没有内容
if (!isset($_POST['article']) || empty($_POST['article']) || trim($_POST['article']) === '') {
    echo $_POST['article'].'您还没有编辑文章';
    exit;
}
if (!isset($_POST['kind']) || empty($_POST['kind']) || trim($_POST['kind']) === '') {
    echo '您还没有选择文章种类';
    exit;
}
if (!isset($_POST['title']) || empty($_POST['title']) || trim($_POST['title']) === '') {
    echo '您还没有编辑标题';
    exit;
}
if (!isset($_POST['cover']) || empty($_POST['cover']) || trim($_POST['cover']) === '') {
    echo '您还没有编辑文章封面图片';
    exit;
}
// 连接数据库
include("connect.php");
// 此处在接收到经过编码的article后，通过=操作符，实现了解码
$time = (string)time();
$kind = $_POST['kind'];
$title = $_POST['title'];
$cover = $_POST['cover'];
$article = $_POST['article'];
$insert_query = 'insert into text values(NULL, "'.$time.'", "'.$kind.'", "'.$title.'", "'.$cover.'", "'.$article.'")';
$insert_result = $db->query($insert_query);
// 对返回结果进行判断
if ($insert_result) {
    echo '成功！管理员文章发布成功！';
}
else {
    echo '失败！管理员文章发布失败！';
}

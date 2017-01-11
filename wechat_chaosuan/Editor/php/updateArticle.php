<?php
// header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/plain; charset=utf-8');
// 若取不到值，那么进行相应的信息反馈，全部为空格时也默认为取不到值
if (!isset($_POST['article']) || empty($_POST['article']) || trim($_POST['article']) === '') {
    echo $_POST['article'].'警告:您编辑的文章为空';
    exit;
}
if (!isset($_POST['kind']) || empty($_POST['kind']) || trim($_POST['kind']) === '') {
    echo '警告:文章种类为空';
    exit;
}
if (!isset($_POST['title']) || empty($_POST['title']) || trim($_POST['title']) === '') {
    echo '警告:文章标题为空';
    exit;
}
if (!isset($_POST['cover']) || empty($_POST['cover']) || trim($_POST['cover']) === '') {
    echo '警告:文章封面图片为空';
    exit;
}

// 连接数据库
include("connect.php");

$time = $_POST['time'];
$kind = $_POST['kind'];
$title = $_POST['title'];
$cover = $_POST['cover'];
$article = $_POST['article'];

// 通过文章时间戳更新文章
$update_query = 'update text set kind="'.$kind.'", title="'.$title.'", cover="'.$cover.'", article="'.$article.'" where time="'.$time.'"';

// 获得更新结果
$update_result = $db->query($update_query);
// echo gettype($update_result);
// echo $update_result;
// if (mysql_error()) {
// 	echo mysql_error();
// 	exit;
// }
if ($update_result) {
	echo '文章修改成功保存！';
}
else {
	echo '发生错误，文章更新失败！';
}
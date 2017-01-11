<?php
// header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/plain; charset=utf-8');
if (!isset($_GET['time']) || empty($_GET['time'])) {
    exit;
}

// 连接数据库
include("connect.php");
$article_time = $_GET['time'];

// 通过文章序号查询文章
$select_query = 'select * from text where time='.$article_time;
// 获得查询结果
$select_result = $db->query($select_query);
// 获得查询结果数量
$num_select_result = $select_result->num_rows;

for ($i = 0; $i < $num_select_result; $i++) {
    $row = $select_result->fetch_assoc();
    // $kind = str_replace('\\', '\\\\', $row['kind']);
    // $title = str_replace('\\', '\\\\', $row['title']);
    // $cover = str_replace('\\', '\\\\', $row['cover']);
    // $article = str_replace('\\', '\\\\', $row['article']);
    // 将各字符串中的双引号"进行转义处理，否则前端用JSON.parse的时候
    // 会将双引号"误认为包裹对象名的双引号"
    // 之所以不将此步骤放在前端中处理，是因为后台传到前端时，
    // 不仅是json值中会含有双引号"，对象名也有包裹它的双引号"
    // 这时如果用前端去处理转义，就会误将包裹对象名的双引号"也转义了
    // 导致不能成功使用JSON.parse函数
    $kind = str_replace('"', '\"', $row['kind']);
    $title = str_replace('"', '\"', $row['title']);
    $cover = str_replace('"', '\"', $row['cover']);
    $article = str_replace('"', '\"', $row['article']);
    echo '{"kind": "'.$kind.'", "title": "'.$title.'", "cover": "'.$cover.'", "article": "'.$article.'"}';
}
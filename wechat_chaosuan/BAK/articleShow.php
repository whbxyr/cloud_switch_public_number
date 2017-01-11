<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>展示文章</title>
    <script src="../Editor/js/htmlcode.js"></script>
</head>
<?php
$article_num = $_GET['num'];
// 面向对象的建立数据库连接的方法
$db = new mysqli('localhost','root','111');

if (mysqli_connect_errno()) {
    echo '连接数据库失败，请稍后重试！';
    exit;
}
$db->query("set names utf8");
// 判断数据库是否存在
$select = $db->select_db('article');
if (!$select) {
    echo '数据库不存在，请先建立好数据库！';
    exit;
}
// 通过文章序号查询文章
$select_query = 'select * from text2 where id='.$article_num;
// 获得查询结果
$select_result = $db->query($select_query);
// 获得查询结果数量
$num_select_result = $select_result->num_rows;

for ($i = 0; $i < $num_select_result; $i++) {
    $row = $select_result->fetch_assoc();
    echo '<div id="article" style="visibility: visible;">'.$row['article'].'</div>';
}
?>
</html>
<script>
var article = document.getElementById('article');
if (article) {
	var content = article.innerHTML;
    article.innerHTML = HtmlUtil.htmlDecode(content);
}
else {
	console.log('nothing');
}
</script>

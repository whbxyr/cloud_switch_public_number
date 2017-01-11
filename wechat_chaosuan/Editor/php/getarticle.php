<?php
// header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/plain; charset=utf-8');
if (!isset($_GET['page']) || empty($_GET['page'])) {
    echo $_GET['page'].'参数错误';
    exit;
}
// 连接数据库
include("connect.php");
$page = $_GET['page'];
$limit = ($page - 1) * 10;
$select_all = 'select * from text';
$select_all_result = $db->query($select_all);
$num_select_all_result = $select_all_result->num_rows;
// 计算并打印出当前数据库文章的总页数，每页10条文章
echo ceil($num_select_all_result/10);
// 从数据库中查询当前页的文章详细信息
$select_query = 'select * from text limit '.$limit.',10';
// 打印表头
echo '<tr style="color: #C3C3C3; font-weight: bold; font-size: 30px;"><td style="padding: 10px 15px;">序号</td><td style="padding: 10px 15px;">编辑日期</td><td style="padding: 10px 15px;">文章类别</td><td style="padding: 10px 15px;">文章标题</td><td style="padding: 10px 15px;">删除？</td><td style="padding: 10px 15px;">修改？</td><td style="padding: 10px 15px;">预览？</td></tr>';
$select_result = $db->query($select_query);
// 获得查询结果数量
$num_select_result = $select_result->num_rows;
// 打印文章列表，没有则什么都不会打印
for ($i = 0; $i < $num_select_result; $i++) {
    $row = $select_result->fetch_assoc();
    $date = date('Y-m-d H:i:s', $row['time']);
    $time = $row['time'];
    if ($page === '1' && $i < 9) {
        echo '<tr style="color: #C3C3C3; font-weight: bold;"><td>0'.(($page - 1) * 10 + $i + 1).'</td><td>'.$date.'</td><td>'.$row['kind'].'</td><td>'.$row['title'].'</td><td id="remove'.$time.'" style="cursor: pointer;">删除</td><td style="cursor: pointer;" id="update'.$time.'">修改</td><td id="preview'.$time.'"><a href="./articleShow.php?time='.$time.'" target="_blank">预览</a></td></tr>';
    }
    else {
        echo '<tr style="color: #C3C3C3; font-weight: bold;"><td>'.(($page - 1) * 10 + $i + 1).'</td><td>'.$date.'</td><td>'.$row['kind'].'</td><td>'.$row['title'].'</td><td id="remove'.$time.'" style="cursor: pointer;">删除</td><td style="cursor: pointer;" id="update'.$time.'">修改</td><td id="preview'.$time.'"><a href="./articleShow.php?time='.$time.'" target="_blank">预览</a></td></tr>';
    }
}
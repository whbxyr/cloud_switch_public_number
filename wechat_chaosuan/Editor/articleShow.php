<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="../Editor/js/htmlcode.js"></script>
    <link rel="shortcut icon" href="./images/favicon.ico">
    <style>
    body {
    	font-family: "Helvetica Neue", Helvetica, "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif;
        font-size: 30px;
    }
    #all {
        margin: 40px;
    }
    .title {
        margin: 0;
        font-size: 70px;
        font-weight: bold;
        padding-bottom: 50px;
    }
    .time {
        font-style: normal;
        color: #8c8c8c;
    }
    #backMain {
    	text-decoration: none;
    }
    #article {
        /*border-top: 1px solid #E7E7EB;*/
    	/*text-indent: 2em;*/
       	/*font-size: 25px;*/
    }
    #qrcode {
        /*background-image: url(./images/QRcode.jpg);*/
        /*width: 500px;*/
        /*height: 500px;*/
        /*background-repeat: no-repeat;*/
        /*background-size: 100% 100%;*/
    }
    #comment {
        box-shadow: 1px 1px 0 #E7E7E7;
        box-sizing: border-box;
        border-color: #CCCCCC #999999 #999999 #CCCCCC;
        border-style: solid;
        border-width: 1px;
        font-family: arial,sans-serif;
        font-size: 25px;
        height: 70px;
        margin: 10px auto;
        /*outline-color: -moz-use-text-color;*/
        outline-color: rgba(169, 169, 169, 0.5);
        outline-style: none;
        outline-width: medium;
        padding: 5px;
        width: 100%;
    }
    @media only screen and (min-width: 1025px) {
        body {
            font-size: 16px;
        }
        #all {
    	    width: 50%;
    	    margin: 0 auto;
        }
        .title {
            margin: default;
            margin-bottom: 10px;
            font-size: 30px;
            border-bottom: 1px solid #E7E7EB;
            padding-bottom: 10px;
        }
    }
    </style>
    <script>
    </script>
<?php
$article_time = $_GET['time'];

// 连接数据库
include("./php/connect.php");
// 通过文章序号查询文章
$select_query = 'select * from text where time='.$article_time;
// 获得查询结果
$select_result = $db->query($select_query);
// 获得查询结果数量
$num_select_result = $select_result->num_rows;

for ($i = 0; $i < $num_select_result; $i++) {
    $row = $select_result->fetch_assoc();
    echo '<title>'.$row['title'].'-广州超算科普基地</title></head><body>';
    echo '<div id="all">';
    echo '<p class="title">【'.$row['kind'].'】'.$row['title'].'</p>';
    echo '<span class="time">';
    echo date('Y-m-d h:i:s', $row['time']).'<br>'.'<a id="backMain" href="../science/index.php?kind=0&page=1" target="_blank">广州超算科普基地</a>';
    echo '</span>';
    echo '<div id="article" style="visibility: visible;">'.$row['article'].'</div>';
    echo '<p>——本项目受广州市教育局资助</p>';
    echo '<p>天河二号将触手可及！</p>';
    echo '<p>超级计算将不再神秘！</p>';
    echo '<p>快来向大家推荐吧~</p>';
    echo '<p>欢迎搜索“广州超算科普基地”，点击识别下方图片中二维码来关注我们。</p>';
    echo '<p id="qrcode"><img src="./images/QRcode.jpg"></p>';
}
?>
<div id="commentdiv" style="width: 100%; display: none; background-color: #f00;">
    <textarea id="comment" placeholder="单击此处留言" style="display: none;"></textarea>
    <button type="submit" style="float: right;">发送</button>
</div>
<?php
    echo '</div>';
?>
</body>
</html>
<script>
var openid = location.href.substring(location.href.indexOf('openid') + 7);
// console.log(location.href.indexOf('openid'));
var commentdiv;
var comment;
if (openid && location.href.indexOf('openid') !== -1) {
    document.getElementById('backMain').href += '&openid=' + openid;
    document.getElementById('commentdiv').style.display = 'block';
    document.getElementById('comment').style.display = 'block';
}
// console.log(openid + '?');
var article = document.getElementById('article');
if (article) {
	var content = article.innerHTML;
    article.innerHTML = HtmlUtil.htmlDecode(content);
}
else {
	console.log('nothing');
}
</script>

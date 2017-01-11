<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>成功授权</title>
</head>
<body>
    <p style="font-size: 20px; text-align: center; color: #2F2F2F;">成功授权给用户开门！</p>
    <br><br>
    <p id="back" style="text-align: center; font-size: 20px;"></p>
</body>
</html>
<script>
	var second = 3;
	var back = document.getElementById("back");
    back.innerHTML = second + "秒后跳回管理页面";
    function backAnimate() {
        var timer;
        timer = setTimeout(function () {
            if (second === 0) {
                // history.go(-1);
                // 跳回管理员的页面
                window.location.href = '../Native/manager';
                return;
            }
            back.innerHTML = --second + "秒后跳回管理页面";
            backAnimate();
        }, 1000);
    }
    backAnimate();
</script>
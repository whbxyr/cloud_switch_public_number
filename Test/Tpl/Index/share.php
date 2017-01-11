<!DOCTYPE html>
<html>
<head>
    <title>share页面</title>
    <meta name="viewpoint" content="initial-scale=1.0;width=device-width"/>
    <meta http-equiv="content" content="text/html;charset=utf-8"/>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
    {$name}
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: 'wx1765e06898bf0f1b', // 必填，公众号的唯一标识
            timestamp: '{$timestamp}' , // 必填，生成签名的时间戳
            nonceStr: '{$noncestr}', // 必填，生成签名的随机串
            signature: '{$signature}',// 必填，签名，见附录1
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '朋友圈', // 分享标题
                link: 'http://www.imooc.com', // 分享链接
                imgUrl: 'http://www.imooc.com/static/img/common/logo.png', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数

                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: '朋友', // 分享标题
                desc: '分享给朋友', // 分享描述
                link: 'http://www.imooc.com', // 分享链接
                imgUrl: 'http://www.imooc.com/static/img/common/logo.png', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                    alert("分享给朋友成功");
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    alert("请重新分享");
                }
            });
            // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        });
        wx.error(function(res){
            // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。

        });
    </script>
</body>
</html>
<?php

class AuthorizeAction extends Action {

    public function __construct() {
        parent::__construct();
    }

	// 获取用户的openid必须从这里开始，这里并没有直接得到用户的openid
    function getBaseInfo() {
        // TP内置的获取url参数的方法
        $appid = 'wx1765e06898bf0f1b';
        // 将是否开门的标志量值绑定到url后
        $redirect_uri = urlencode('http://'.C('yuming').'test.php/Authorize/getUserOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        header('location:'.$url);
    }
    
    function getUserOpenId() {
        // 获取当前的url
        // echo $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $appid = 'wx1765e06898bf0f1b';
        $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $actionIndex = new IndexAction();
        $res = $actionIndex->http_curl($url, 'get');
        echo $res['openid'];
        // $data['openid'] = $res['openid'];
        // $getUserDetailurl = 'http://'.C('yuming').'test.php/Index/getUserDetail';
        // header('location:'.$getUserDetailurl);
    }
    // 获取用户的详细信息必须从这里开始，这里并没有直接得到用户的详细信息
    function getUserDetail() {
        $appid = 'wx1765e06898bf0f1b';
        $redirect_uri = urlencode( 'http://'.C('yuming').'test.php/Authorize/getUserInfo');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        header('location:'.$url);
    }
    // 此处最终获得用户详细信息，并插入数据库，带openid跳转到超算科普网站
    function getUserInfo() {
        $actionIndex = new IndexAction();
        $appid = 'wx1765e06898bf0f1b';
        $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $res = $actionIndex->http_curl($url,'get');
        // $openid = md5(md5($res['openid'], true));
        $openid = $res['openid'];
        $access_token = $res['access_token'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $res = $actionIndex->http_curl($url);
        // var_dump($res);
        return $res;
    }
}
?>
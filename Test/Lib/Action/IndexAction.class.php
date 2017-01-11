<?php
define('TOKEN', 'weixin');

class IndexAction extends Action {
    public function __construct() {
        parent::__construct();
    }

	public function index() {
        // 获得参数 signature nonce token timestamp echostr
        $signature = I('get.signature');
        $timestamp = I('get.timestamp');
        $nonce = I('get.nonce');
        $echostr = I('get.echostr');
        $token = TOKEN;
		// 形成数组，然后按字典序排序
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		// 拼接成字符串,sha1加密 ，然后与signature进行校验
        $tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $signature && $echostr) {
            ob_clean();
			// 第一次接入weixin api接口的时候
			echo $echostr;
            // return true;
		}
        else {
			$this->responseMsg();
		}
	}

    function insertTextToDB($text, $kind) {
        $chatDB = M('chat');
        $data['content'] = $text;
        $data['time'] = time();
        $data['kind'] = $kind;
        $chatDB->add($data);
    }

	// 接收事件推送并回复
	public function responseMsg() {
		// 1.获取到微信推送过来post数据（xml格式）
		$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
		$postObj = simplexml_load_string($postArr);
		// 判断该数据包是否是订阅的事件推送
		if (strtolower($postObj->MsgType) == 'event') {
			// 如果是关注 subscribe 事件
			if (strtolower($postObj->Event == 'subscribe')) {
                $arr = array(
                    array(
                        'title'=>'This is an experimental project from the lab-717 lab!',
                        'description'=>"Thanks for your subscribing!",
                        'picUrl'=>'http://img.25pp.com/uploadfile/app/icon/20160614/1465899368466176.jpg',
                        'url'=>'http://'.C('yuming').'test.php/Index/getBaseInfo?kaimen=false',
                    )
                );
                $indexModel = new IndexModel();
                $indexModel->responseSubscribe($postObj, $arr);
			}
            if (strtolower($postObj->Event == 'SCAN')) {
                if ($postObj->EventKey == 2000) {
                    $tmp = '临时二维码欢迎你';
                }
                if ($postObj->EventKey == 3000) {
                    $tmp = '永久二维码欢迎您';
                }
                $arr = array(
                    array(
                        'title'=>$tmp,
                        'description'=>"扫二维码浏览",
                        'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                        'url'=>'http://www.imooc.com',
                    )
                );
                $indexModel = new IndexModel();
                $indexModel->responseNews($postObj, $arr);
            }
            if (strtolower($postObj->Event == 'CLICK')) {
                if (strtolower($postObj->EventKey) === 'test') {
                    $content = "测试click事件1";
                }
                else {
                    $content = "测试click事件2";
                }
                $indexModel = new IndexModel();
                $indexModel->responseText($postObj, $content);
            }
		}

		// 用户发送tuwen1关键字的时候，回复一个单图文
		if (strtolower($postObj->MsgType) == 'text'
            && trim($postObj->Content) == '超算') {
            $this->insertTextToDB(trim($postObj->Content), '问');
			$arr = array(
				array(
					'title'=>'广州超算科普基地',
					'description'=>"supercomputer is very cool",
					'picUrl'=>'http://www.qstheory.cn/zhuanqu/llkhd/2014-06/28/1111388394_14041232921031n.jpg',
					'url'=>'http://www.whbxyr.cn/2016/09/02/jieshao/',
				),
				array(
					'title'=>'基地简介',
					'description'=>"supercomputer is very cool",
					'picUrl'=>'http://sqb.ynet.com/images/2015-01/22/dsm03/3bt2_b.jpg',
					'url'=>'http://www.hao123.com',
				),
				array(
					'title'=>'基地人才',
					'description'=>"supercomputer is very cool",
					'picUrl'=>'http://i2.hexunimg.cn/2013-06-18/155245746.jpg',
					'url'=>'http://www.qq.com',
				),
			);
            $this->insertTextToDB('公众号推荐图文', '答');
            $indexModel = new IndexModel();
            $indexModel->responseNews($postObj ,$arr);

			//注意：进行多图文发送时，子图文个数不能超过10个
		}
        if(strtolower($postObj->MsgType) == 'text'
            && trim($postObj->Content) != '超算') {

            // if(substr($postObj->Content, 0, 6) == "翻译") {
            //     $content = $this->trans($postObj);
            // }
            // elseif (substr($postObj->Content,strlen($postObj->Content)-6) == '天气') {
            //     $content = $this->getWeather($postObj);
            // }
            // else {
            //     switch(trim($postObj->Content)) {
            //         case '许源锐':
            //             $content = '欢迎关注广州超算科普基地，项目成员许源锐向您表示感谢';
            //         break;
            //         case '何靖怡':
            //             $content = '欢迎关注广州超算科普基地，项目成员何靖怡向您表示感谢';
            //         break;
            //         case '杨灿钦':
            //             $content = '欢迎关注广州超算科普基地，项目成员李天赐向您表示感谢';
            //         break;
            //         default:
            $this->insertTextToDB(trim($postObj->Content), '问');
            $content = $this->tulinChat($postObj);
            $this->insertTextToDB($content, '答');
                    // break;
                // }
            // }
            $indexModel = new IndexModel();
            $indexModel->responseText($postObj, $content);
		}
	}
    // 返回access_token,此处使用了SESSION解决方法，还有存储到mysql、memcache的方法
	function getWxAccessToken() {
        // // 将access_token存储在session/cookie中
        // if ($_SESSION['access_token'] && $_SESSION['expire_time'] > time()) {
        //     // 如果access_token在SESSION中且未过期
        //     // return $_SESSION['access_token'];
        //     // $appid = 'wx1765e06898bf0f1b';
        //     // $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        //     // $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
        //     // .$appid.'&secret='.$appsecret;
        //     // $res = $this->http_curl($url, 'get', 'json');
        //     // $access_token = $res['access_token'];
        //     // $_SESSION['access_token'] = $access_token;
        //     // $_SESSION['expire_time'] = time() + 7000;
        //     return $_SESSION['access_token'];
        // }
        // else {
        //     // 如果access_token不在SESSION中或者已经过期
        //     $appid = 'wx1765e06898bf0f1b';
        //     $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        //     $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
        //     .$appid.'&secret='.$appsecret;
        //     $res = $this->http_curl($url, 'get', 'json');
        //     $access_token = $res['access_token'];
        //     $_SESSION['access_token'] = $access_token;
        //     $_SESSION['expire_time'] = time() + 7000;

        //     return $access_token;
        // }
		// 1.请求url地址
		$appid = 'wx1765e06898bf0f1b';
		$appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
            .$appid.'&secret='.$appsecret;
		// 2初始化
		$ch = curl_init();
		// 3.设置参数
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 4.调用接口 
		$res = curl_exec($ch);
        if (curl_errno($ch)) {
            var_dump(curl_error($ch));
        }
		// 5.关闭curl
		curl_close($ch);
		$arr = json_decode($res, true);
        return $arr['access_token'];
	}

	function getWxServerIp(){
		// $accessToken = "FXg-Nrk86ccDZUgUNLsirmueC4Yx-3yHo9Q9qUz08N1BydwGUpudVAi8V3jKbvsW_bFksa4Uah2J6JP-ndYBu_5Gknk_xu88U0imzGRL8mSW54uC_SNHR4NurqtlIxSnXGCfAGAHQM";
        $accessToken = $this->getWxAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$res = curl_exec($ch);
        if (curl_errno($ch)) {
            var_dump(curl_error($ch));
        }
		curl_close($ch);
		$arr = json_decode($res,true);
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
	}
    // 万能curl函数
    function http_curl($url, $type='get', $res='json', $arr='', $header=''){
        // 1. 初始化curl
        $ch = curl_init();
        // 2. 设置curl的参数
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($header != '') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        $output = curl_exec($ch);
        if ($res == 'json') {
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            else {
                return json_decode($output,true);
            }
        }
    }
    //生成临时二维码接口
    function getTempQrCode(){
        header('content-type:text/html;charset=utf-8');
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        $postArr = array(
            'expire_seconds'=>604800,
            'action_name'=>"QR_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>2000),
            ),
        );
        $postJson = json_encode($postArr,true);
        $res = $this->http_curl($url,'post','json',$postJson);
        //$res = $this->http_curl($url,$postJson);
        //echo $res;
        //echo $access_token;
        var_dump($res);
        $ticket = $res['ticket'];
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        //$res = $this->http_curl($url);
        //返回的url为图片，可以直接展示
        echo "临时二维码";
        echo "<img src='".$url."'>";
    }
    //生成永久二维码接口
    function getForeverQrCode(){
        header('content-type:text/html;charset=utf-8');
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        $postArr = array(
            'action_name'=>"QR_LIMIT_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>3000),
            ),
        );
        $postJson = json_encode($postArr,true);
        $res = $this->http_curl($url,'post','json',$postJson);
        //$res = $this->http_curl($url,$postJson);
        //echo $res;
        //echo $access_token;
        var_dump($res);
        $ticket = $res['ticket'];
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        //$res = $this->http_curl($url);
        //返回的url为图片，可以直接展示
        echo "永久二维码";
        echo "<img src='".$url."'>";
    }

    // 获取用户的openid必须从这里开始，这里并没有直接得到用户的openid
    function getBaseInfo() {
        // TP内置的获取url参数的方法
        $kaimen = I('get.kaimen');
        $appid = 'wx1765e06898bf0f1b';
        // 将是否开门的标志量值绑定到url后
        $redirect_uri = urlencode('http://'.C('yuming').'test.php/Index/getUserOpenId?kaimen='.$kaimen);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        header('location:'.$url);
    }
    // 这里直接得到用户的openid，在这里将该openid与数据库对比，假如数据库有这个openid
    // 则直接带openid跳转到科普网站
    // 否则跳到'http://'.C('yuming').'test.php/Index/getUserDetail'
    // 经过用户确认授权后才将用户的数据插入我们的用户数据库
    // 紧接着带openid跳转到科普网站
    function getUserOpenId() {
        // 获取当前的url
        // echo $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        $kaimen = $_GET['kaimen'];
        $appid = 'wx1765e06898bf0f1b';
        $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $res = $this->http_curl($url, 'get');
        // $openid = md5(md5($res['openid'], true));
        // $openid = $res['openid'];
        // $data['openid'] = $openid;
        $data['openid'] = $res['openid'];
        // 如果标志量$kaimen为'true'，则进入开门处理
        if ($kaimen === 'true') {
            $verified_customerDB = M('verified_customer');
            $verified_customer = $verified_customerDB->where($data)->find();
            // 若用户没有在数据库中，则先授权再开门
            if (!$verified_customer) {
                $getUserDetailurl = 'http://'.C('yuming').'test.php/Index/getUserDetail?kaimen='.$kaimen;
                header('location:'.$getUserDetailurl);
                exit;
            }
            // 否则直接开门
            else {
                // $kaiguan = 'http://'.C('yuming').'/lock_open.php';
                // header('location:'.$kaiguan);
                $indexModel = new IndexModel();
                $indexModel->openDoor();
                // $this->assign('customer', $customer);
                // ob_clean();
                $jsapi_ticket = $this->getJsApiTicket();
                $timestamp = time();
                $noncestr = $this->getRandCode();
                //获取signature
                $url = urlencode('http://'.C('yuming').'test.php/Index/shareWx');
                $signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
                $signature = sha1($signature);
                $this->assign('timestamp', $timestamp);
                $this->assign('noncestr', $noncestr);
                $this->assign('signature', $signature);
                $this->assign('headimgurl', $verified_customer['headimgurl']);
                $this->assign('nickname', $verified_customer['nickname']);
                $this->display('result_success');
            }
        }
        // 如果标志量$kaimen为'false'，则进入超算公众号科普网站
        else {
            $customerDB = M('customer');
            $customer = $customerDB->where($data)->find();
            // 若用户没有在数据库中，则先授权再进入科普网站
            if (!$customer) {
                $getUserDetailurl = 'http://'.C('yuming').'test.php/Index/getUserDetail?kaimen='.$kaimen;
                header('location:'.$getUserDetailurl);
                exit;
            }
            // 否则直接进入科普网站
            else {
                $chaosuanurl = 'http://'.C('yuming').'/wechat_chaosuan/science/index.php?openid='.$openid;
                header('location:'.$chaosuanurl);
            }
        }
        return $openid;
    }
    // 获取用户的详细信息必须从这里开始，这里并没有直接得到用户的详细信息
    function getUserDetail() {
        $kaimen = I('get.kaimen');
        $appid = 'wx1765e06898bf0f1b';
        $redirect_uri = urlencode('http://'.C('yuming').'test.php/Index/getUserInfo?kaimen='.$kaimen);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        header('location:'.$url);
    }
    // 此处最终获得用户详细信息，并插入数据库，带openid跳转到超算科普网站
    function getUserInfo() {
        $kaimen = I('get.kaimen');
        $appid = 'wx1765e06898bf0f1b';
        $appsecret = 'f2d823c9982bb58b3f1d742f890049b2';
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $res = $this->http_curl($url,'get');
        // $openid = md5(md5($res['openid'], true));
        $openid = $res['openid'];
        $access_token = $res['access_token'];
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $res = $this->http_curl($url);
        if ($kaimen === 'true') {
            $request_customerDB = M('request_customer');
            $data['openid'] = $openid;
            $customer = $request_customerDB->where($data)->find();
            if ($customer) {
                $this->assign('errorType', '请耐心等待');
                $this->assign('errorMsg', '您的注册请求已发送，请耐心等待管理员授权！');
                $this->display('error');
                // $this->display('wait');
            }
            else {
                // $jsapi_ticket = $this->getJsApiTicket();
                // $timestamp = time();
                // $noncestr = $this->getRandCode();
                // //获取signature
                // $url = urlencode('http://'.C('yuming').'test.php/Index/shareWx');
                // $signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
                // $signature = sha1($signature);
                // $this->assign('timestamp', $timestamp);
                // $this->assign('noncestr', $noncestr);
                // $this->assign('signature', $signature);
                // ob_clean();
                $this->assign('openid', $openid);
                $this->assign('headimgurl', $res['headimgurl']);
                $this->assign('nickname', $res['nickname']);
                $this->display('signup');
            }
            // $kaiguan = 'http://'.C('yuming').'/lock_open.php';
            // header('location:'.$kaiguan);
            // $indexModel = new IndexModel();
            // $indexModel->openDoor();
        }
        else {
            // $this->assign('errorType', '数据错误');
            // $this->assign('errorMsg', '科普网站用户信息插入数据库失败！');
            // $this->display('error');
            $customerDB = M('customer');
            $data['openid'] = $openid;
            $data['nickname'] = $res['nickname'];
            $data['sex'] = $res['sex'];
            $data['headimgurl'] = $res['headimgurl'];
            $addcustomer = $customerDB->add($data);
            // 这里有问题
            if ($addcustomer) {
                $chaosuanurl = 'http://'.C('yuming').'/wechat_chaosuan/science/index.php?openid='.$openid;
                header('location:'.$chaosuanurl);
            }
            else {
                $this->assign('errorType', '数据错误');
                $this->assign('errorMsg', '科普网站用户信息插入数据库失败！');
                $this->display('error');
            }
        }
    }
    // 获取getJsApiTicket
    function getJsApiTicket() {
        if ($_SESSION['jsapi_ticket_expire_time'] > time() && $_SESSION['jsapi_ticket']) {
            $jsapi_ticket = $_SESSION['jsapi_ticket'];
            // $_SESSION['jsapi_ticket'] = null;
        }
        else {
            $access_token = $this->getWxAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $res = $this->http_curl($url);
            // var_dump($res);
            $jsapi_ticket = $res['ticket'];
            $_SESSION['jsapi_ticket'] = $jsapi_ticket;
            $_SESSION['jsapi_ticket_expire_time'] = time() + 7000;
        }
        // echo $jsapi_ticket;
        return  $jsapi_ticket;
    }
    /*获取16位随机码*/
    function getRandCode($num=16){
        $array = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N',
            'O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n',
            'o','p','q','r','s','t','u','v','w','x','y','z',
            '0','1','2','3','4','5','6','7','8','9'
        );
        $tmpstr = '';
        $max = count($array);
        for($i = 1; $i <= $num; $i++){
            $key = rand(0, $max-1);
            $tmpstr .= $array[$key];
        }
        // echo $tmpstr;
        return $tmpstr;
    }

    function shareWx(){
        $jsapi_ticket = $this->getJsApiTicket();
        $timestamp = time();
        $noncestr = $this->getRandCode();
        //获取signature
        $url = urlencode('http://'.C('yuming').'test.php/Index/shareWx');
        $signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
        $signature = sha1($signature);
        /*
        echo $signature;
        $this->name="imooc";
        $this->timestamp=$timestamp;
        $this->noncestr=$noncestr;
        $this->signature=$signature;
        $this->display('share');
        */

        $this->assign("name", "imooc");
        $this->assign("timestamp", $timestamp);
        $this->assign("noncestr", $noncestr);
        $this->assign("signature", $signature);
        $this->display('share');

        /*
        $this->assign("name","imooc")->assign("timestamp",$timestamp)->assign("noncestr",$noncestr)->assign("signature",$signature)->display('share');
        */
    }
    function trans($postObj){
        //trim函数去除字符串两端的一些字符串
        //strstr函数查找 指定字符串 在 原字符串 中的第一次出现，并返回 指定字符串 和 字符串的剩余部分
        //$text = substr(strstr(trim($postObj->Content),'翻译'),6);
        $text = substr(trim($postObj->Content),6);
        $url = 'http://www.apijingling.com/index.php?c=trans&a=query&text='.$text;
        $res = $this->http_curl($url);
        return '翻译结果为: '.$res['translation'][0];
    }
    // 自定义菜单函数
    public function definedItem() {
        header('Content-Type: text/plain; charset=utf-8');
        $access_token = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $postArr = array(
            'button'=>array(
                array(
                    'name'=>urlencode('科普网站'),
                    'type'=>'view',
                    'url'=>'http://'.C('yuming').'test.php/Index/getBaseInfo?kaimen=false',
                ),
                array(
                    'name'=>urlencode('资源下载'),
                    'type'=>'view',
                    'url'=>'http://'.C('yuming').'wechat_chaosuan/source/download.html',
                ),
                array(
                    'name'=>urlencode('开门'),
                    'type'=>'view',
                    'url'=>'http://'.C('yuming').'test.php/Index/getBaseInfo?kaimen=true',
                ),
            ),
        );
        $postJson = urldecode(json_encode($postArr));
        // echo $postJson;
        $res = $this->http_curl($url, 'post', 'json', $postJson);
        var_dump($res);
    }

    // 图灵智能机器人聊天
    function tulinChat($postObj){
        $info = urlencode($postObj->Content);
        // $url = 'http://apis.baidu.com/turing/turing/turing?key=879a6cb3afb84dbf4fc84a1df2ab7319&info='.$info.'&userid=eb2edb736';
        $url = 'http://op.juhe.cn/robot/index?info='.$info.'&key=3405164b7f7bd108c1eae2dc791ec4ff';
        // $header = array(
        //     'apikey: 569779949b9b814ae1ebc180417cc3e1',
        // );
        $res = $this->http_curl($url,'get','json','',$header);
        // return $res['text'];
        return $res['result']['text'];
    }
    function test() {
    	$test = C('yuming').'???';
    	echo $test;
    }
}

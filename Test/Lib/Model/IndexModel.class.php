<?php
class IndexModel {
	//回复多图文类型的微信消息
	public function responseNews($postObj ,$arr) {
		$toUser = $postObj->FromUserName;
		$fromUser = $postObj->ToUserName;
		$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>".count($arr)."</ArticleCount>
					<Articles>";
		foreach($arr as $k=>$v) {
			$template .="<item>
						<Title><![CDATA[".$v['title']."]]></Title> 
						<Description><![CDATA[".$v['description']."]]></Description>
						<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
						<Url><![CDATA[".$v['url']."]]></Url>
						</item>";
		}
		
		$template .="</Articles>
					</xml> ";
		echo sprintf($template, $toUser, $fromUser, time(), 'news');
	}

	// 回复单文本
	public function responseText($postObj,$content){
		$template = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
		//注意模板中的中括号 不能少 也不能多
		$fromUser = $postObj->ToUserName;
		$toUser   = $postObj->FromUserName; 
		$time     = time();
		$msgType  = 'text';
		echo sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
	}

	// 回复微信用户的关注事件
	public function responseSubscribe($postObj, $arr){
		// $content = '感谢关注';
        //只能选择一种回复关注方式，即以下可供选择的方式中的一种
		// $this->responseNews($postObj,$arr);
        // $this->responseText($postObj,$content);
        $this->responseNews($postObj,$arr);
	}

    // 开门 
	function openDoor() {
        // $ip   = gethostbyname('4s.dkys.org');
        $ip = '10.21.71.155';
        $port = 3000;
        // $ip = "10.21.71.155";//Modify the domain name for your board IP or peanut shell

        $sendBuf_o1 = "\x55\x01\x12\x00\x00\x00\x01\x69"; //open 1way
        $sendBuf_o2 = "\x55\x01\x12\x00\x00\x00\x02\x6A"; //open 2way
        $sendBuf_o3 = "\x55\x01\x12\x00\x00\x00\x03\x6B"; //open 3way
        $sendBuf_o4 = "\x55\x01\x12\x00\x00\x00\x04\x6C"; //open 4way
        $sendBuf_o5 = "\x55\x01\x12\x00\x00\x00\x05\x6D"; //open 5way
        $sendBuf_o6 = "\x55\x01\x12\x00\x00\x00\x06\x6E"; //open 6way
        $sendBuf_o7 = "\x55\x01\x12\x00\x00\x00\x07\x6F"; //open 7way
        $sendBuf_o8 = "\x55\x01\x12\x00\x00\x00\x08\x60"; //open 8way
        $sendBuf_c1 = "\x55\x01\x12\x00\x00\x00\x01\x68"; //close 1way
        $sendBuf_c2 = "\x55\x01\x12\x00\x00\x00\x02\x69"; //close 2way
        $sendBuf_c3 = "\x55\x01\x12\x00\x00\x00\x03\x6A"; //close 3way
        $sendBuf_c4 = "\x55\x01\x12\x00\x00\x00\x04\x6B"; //close 4way
        $sendBuf_c5 = "\x55\x01\x12\x00\x00\x00\x05\x6C"; //close 5way
        $sendBuf_c6 = "\x55\x01\x12\x00\x00\x00\x06\x6D"; //close 6way
        $sendBuf_c7 = "\x55\x01\x12\x00\x00\x00\x07\x6E"; //close 7way
        $sendBuf_c8 = "\x55\x01\x12\x00\x00\x00\x08\x6F"; //close 8way
        $sendBuf_ca = "\x55\x01\x13\x00\x00\x00\x00\x69"; //close all
        $sendBuf_oa = "\x55\x01\x13\x00\x00\xFF\xFF\x67"; //open all
        set_time_limit(5);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "<br />";
        }else {
            echo "Create OK.<br />";
        }

        echo "try to connect'$ip' from '$port'...<br />";
        $result = socket_connect($socket, $ip,$port);
        if ($result < 0) {
            echo "socket_connect() failed.Reason: ($result) " . socket_strerror($result) . "<br />";
        }else {
            echo "connect OK <br />";
        }

        //open 
        if(!socket_write($socket,$sendBuf_oa,8)) {
            echo "socket_write() failed: reason: " . socket_strerror($socket) . "<br />";
        }else {
             $sendStr = bin2hex($sendBuf_oa);
            echo "send open information succeed <br />";
            echo "send:".$sendStr."<br />";
        }
        while($out = socket_read($socket, 8192)) {
            echo "receive open information succeed <br />";
            $recvStr = bin2hex($out);
            echo "receive :<font color='red'>$recvStr</font> <br />";
            break;
        }

        sleep(3);

        //close
        if(!socket_write($socket,$sendBuf_ca,8)) {
            echo "socket_write() failed: reason: " . socket_strerror($socket) . "<br />";
            }else {
                $sendStr = bin2hex($sendBuf_ca);
                echo "send close information succeed <br />";
                echo "send:".$sendStr."<br />";
            }

        while($out = socket_read($socket, 8192)) {
            echo "receive close information succeed <br />";
            $recvStr = bin2hex($out);
            echo "receive :<font color='red'>$recvStr</font> <br />";
            break;
        }


        echo "close socket...<br />";
        socket_close($socket);
        echo "close succeed <br />";
        exit;
    }
}
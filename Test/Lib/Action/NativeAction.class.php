<?php
class NativeAction extends Action {
    public function __construct() {
        parent::__construct();
    }

    function signupCustomer() {
        $openidforCustomer = $_POST['openid'];
        $nickname = $_POST['nickname'];
        $headimgurl = $_POST['headimgurl'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        $request_customerDB = M('request_customer');
        $data['openid'] = $openidforCustomer;
        $data['nickname'] = $nickname;
        $data['headimgurl'] = $headimgurl;
        $data['name'] = $name;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['address'] = $address;
        $addcustomer = $request_customerDB->add($data);
        $template_idforCustomer = 'JahIVwGRThAzlMXiK4vPXtfLSx7LwyhvV3WC1CYL62s';
        if ($addcustomer) {
        	// $actionIndex = new IndexAction();
            $this->sendSignupTemplateMsg($openidforCustomer, $name, $phone, $email, $address, $template_idforCustomer);
            $template_idforManager = 'JIAx13vUepwhp7-6CPQiQnNm_P7K9i9q5Sid1sP4Gnk';
            $openidForManager = 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE';
            $this->sendManagerTemplateMsg($openidForManager, $name, $phone, $email, $address, $template_idforManager, true);
            echo '<br><br>您的注册请求已发送<br><br>请耐心等待管理员授权<br><br>';
        }
        else {
            echo '失败';
        }
    }

    public function validCustomer() {
        // 获取openid参数
        $openid = $_GET['openid'];
        // 连接微信云开关请求注册用户表request_customer
        $request_customerDB = M('request_customer');
        // 连接微信云开关被授权后的用户表verified_customer
        $verified_customerDB = M('verified_customer');
        // $Model = new Model();
        // $insert_query = 'insert into verified_customer '.'select * from request_customer where openid="'.$openid.'"';
		// $insert_result = $Model->query($insert_query);
        // 查找条件
		$data['openid'] = $openid;
        // 在微信云开关请求注册用户表request_customer中查找相应的元组
        $request_customer = $request_customerDB->where($data)->select();
        // 查询返回结果是二维数组，第一个下标是自然数
        $data['openid'] = $request_customer[0]['openid'];
        $data['nickname'] = $request_customer[0]['nickname'];
        $data['headimgurl'] = $request_customer[0]['headimgurl'];
        $data['name'] = $request_customer[0]['name'];
        $data['phone'] = $request_customer[0]['phone'];
        $data['email'] = $request_customer[0]['email'];
        $data['address'] = $request_customer[0]['address'];
        // 如果查找不到相应的元组则要有错误提示
        if (!$request_customer) {
            echo '查找请求数据失败！';
        }
        // 将查询到的请求注册用户元组插入到微信云开关被授权后的用户表verified_customer中
        $insert_result = $verified_customerDB->add($data);
		// $delete_query = 'delete from request_customer where openid="'.$openid.'"';
		// $Model->query($delete_query);
        // 将查询到的请求注册用户元组从微信云开关请求注册用户表request_customer中删除
        // 表示这个用户不再需要注册
        $delete_result = $request_customerDB->where($data)->delete();
		// 对相应的查询、操作结果进行相应的错误提示
		if ($insert_result && $delete_result) {
		    echo '授权成功';
		}
		else {
		    echo '授权失败';
		}
    }
    // 管理员控制授权模块
    function manager() {
        $this->assign('yuming', C('yuming'));
        $this->display('manager');
    }
    // // 群发消息
    // function sendMsgAll() {
    //     $openid = I('get.openid');
    //     $jump = I('get.jump');
    //     $actionIndex = new IndexAction();
    //     // 1. 获取全局access_token
    //     $access_token = $actionIndex->getWxAccessToken();
    //     $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
    //     // 2. 组装数组数据
    //     $array = array(
    //         // 微信用户的openid
    //         'touser' => array(
    //             'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE',
    //             // 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE',
    //             'o_GxCwRz46VKMXZoE0MP8b2Z216M',
    //             // 'o_GxCwdOlODRy18PiDzfjvez9BCA',
    //             // 'o_GxCwRQOrelvQLGQQ5UuGQLmLHk',
    //             // 'o_GxCwYPBTa2tx5Kod6QsTu2iaPQ',
    //         ),
    //         'text' => array(
    //             'content' => urlencode('您的注册已经获取管理员授权！\n\n可以直接扫码开门！'),
    //         ),
    //         'msgtype' => 'text',
    //     );
    //     // 3. 将array->json
    //     $postJson = urldecode(json_encode($array));
    //     // 4. 调用curl
    //     $res = $actionIndex->http_curl($url, 'post', 'json', $postJson);
    //     if ($res && $jump === 'true') {
    //         $this->display('validTip');
    //     }
    // }

    // 给管理员发送相关用户的注册请求提示
    function sendManagerTemplateMsg($openid, $name, $phone, $email, $address, $template_id, $manager) {
        $this->sendTemplateMsg($openid, $name, $phone, $email, $address, $template_id, $manager);
    }

    // 发送成功注册模板消息
    function sendSignupTemplateMsg($openid, $name, $phone, $email, $address, $template_id) {
        $this->sendTemplateMsg($openid, $name, $phone, $email, $address, $template_id);
    }

    // 发送管理员成功授权的模板消息
    function sendValidTemplateMsg() {
        $openid = $_GET['openid'];
        $name = $_GET['name'];
        $phone = $_GET['phone'];
        $email = $_GET['email'];
        $address = $_GET['address'];
        $template_id = $_GET['template_id'];
        $this->sendTemplateMsg($openid, $name, $phone, $email, $address, $template_id);
        $openidForManager = 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE';
        $template_idforManager = 'juQKLyMrRVHIMjYJHqdtS-4pngVttgqw5KcYrIgm5js';
        $this->sendTemplateMsg($openidForManager, $name, $phone, $email, $address, $template_idforManager);
        $this->display('validTip');
    }

    // 发送模板消息
    function sendTemplateMsg($openid, $name, $phone, $email, $address, $template_id, $manager = false) {
        header('content-type:text/html;charset=utf-8');
        $actionIndex = new IndexAction();
        $access_token = $actionIndex->getWxAccessToken();
        //$openid = $this->getUserOpenId();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
        $array = array(
            // 'touser' => 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE',
            'touser' => $openid,
            // 'template_id' => 'IpKF6EYQjgB9O_0uX1_p6LBzGwB--ch4s6ePXo0WJKA',
            'template_id' => $template_id,
            // 'url' => '',
            'data' => array(
                'name' => array(
                    'value' => '姓名：    '.$name,
                    'color' => '#173177',
                ),
                'phone' => array(
                    'value' => '手机：    '.$phone,
                    'color' => '#173177',
                ),
                'email' => array(
                    'value' => '邮箱：    '.$email,
                    'color' => '#173177',
                ),
                'address' => array(
                    'value' => '住址：    '.$address,
                    'color' => '#173177',
                ),
                'date' => array(
                    'value' => '日期：    '.date('Y-m-d H:i:s'),
                    'color' => '#173177',
                ),
            ),
        );
        if ($openid === 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE' && $manager) {
            $array['url'] = 'http://'.C('yuming').'test.php/Native/manager';
        }
        $postJson = json_encode($array);
        $res = $actionIndex->http_curl($url,'post','json',$postJson);
    }
    
    // 获取关注着列表
    function getFanslist() {
        // https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID
        $actionIndex = new IndexAction();
        $access_token = $actionIndex->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token;
        $res = $actionIndex->http_curl($url);

        return $res['data']['openid'];
        // var_dump($res['data']['openid']);
        // $this->sendMsgAll($res['data']['openid']);
    }

    // 群发消息
    function sendMsgAll() {
        $openid = $this->getFanslist();
        // $openid = I('get.openid');
        $actionIndex = new IndexAction();
        // 1. 获取全局access_token
        $access_token = $actionIndex->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
        // 2. 组装数组数据
        $array = array(
            // 微信用户的openid
            'touser' => $openid,
            // 'touser' => array(
                // 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE',
                // 'o_GxCwXSwxUNJ78Oy1R1_TmAMgwE',
                // 'o_GxCwRz46VKMXZoE0MP8b2Z216M',
                // 'o_GxCwdOlODRy18PiDzfjvez9BCA',
                // 'o_GxCwRQOrelvQLGQQ5UuGQLmLHk',
                // 'o_GxCwYPBTa2tx5Kod6QsTu2iaPQ',
            // ),
            'text' => array(
                'content' => urlencode('煎鱼 == 筷子？？？'),
            ),
            'msgtype' => 'text',
        );
        // 3. 将array->json
        $postJson = urldecode(json_encode($array));
        // 4. 调用curl
        $res = $actionIndex->http_curl($url, 'post', 'json', $postJson);

        var_dump($res);
    }
}
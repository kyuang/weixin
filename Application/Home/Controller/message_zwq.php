<?php
 
        header("content-type:text/html;charset=utf-8");
        /**
         * 微信自定义菜单
         *
         */
        // 企业号ID
        $corpid = 'wx9a046bd2f2118032';
        $secrect = 'grbr_h7RrCPhzm-2WyYY6BHHyGfkPqawsin6q2g3EG1kUjv9yIiUtwNdJl6Q0WAB';
        // 调用接口凭证 
        $access_token = getAccessToken($corpid,$secrect);
        // 应用ID
        $agentid      = 1;
        // 自定义菜单的请求地址
        // $url = 'https://qyapi.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token.'&agentid='.$agentid;
        // 主动发消息请求的地址
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token='.$access_token;
        // 要发送的数据
        $sendData = '{
           "touser": "@all",
           "toparty": "@all",
           "totag": "@all",
           "msgtype": "text",
           "agentid": '.$agentid .',
           "text": {
               "content": "大大?"
           },
           "safe":"0"
        }';
        $res = sendMsg( $url, $sendData );
        var_dump($res);

/**
 * 发送消息的方法
 * @param  $url      String  要发送数据的URL地址
 * @param  $sendData String  要发送的数据 json 字符串
 * @param  $type     String  请求的方式 默认是 get请求
 * @return $return   String  发送请求的回调值
 */
function sendMsg( $url, $sendData )
{
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $sendData );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return;
}


/**
 * 获取企业号的access_token
 * @param  $corpid        String   公司企业号ID
 * @param  $secrect       String   管理组秘钥
 * @return $access_token  String   access_token
 */
function getAccessToken($corpid,$secrect)
{
    session_start();
    if($_SESSION['access_token'] && $_SESSION['expires_in']+7200>time() ){
            return $access_token['access_token'];
            die;
    }
    $url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$corpid.'&corpsecret='.$secrect;
    $data = file_get_contents($url);
    $access_token = json_decode($data,true);
    if($access_token['access_token']){
            $_SESSION['access_token'] = $access_token['access_token'];
            $_SESSION['expires_in'] = $access_token['expires_in'];
            return $access_token['access_token'];
    }else{
            // 获取异常
            return $access_token['errmsg'];
    }
}



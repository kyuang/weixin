<?php
namespace Home\Controller;
use Think\Controller;

$lifeTime = 20 * 60;  // 保存一天 
session_set_cookie_params($lifeTime); 
session_start();
class AdminController extends Controller {
    public function Sample(){

       include 'Sample.php';
    }

    public function Sample1(){
       include 'Sample1.php';
    }

    public function Sample2(){
       include 'Sample2.php';
    }
    public function Sample_lyy(){
       include 'Sample_lyy.php';
    }

    //发布话题
    public function addhua()
    {
      $corpid = 'wxdfb65973db0deb4d';
      $secrect = 'HXOk8frb4AaAjXvNKXNxfMegKvwQCrTVZmmeJt2HIk4mjkDtNILOMLRCF9Fw5OVV';
      // 调用接口凭证 
      if(empty($_SESSION['access_token']))
      {
        $access_token = $this->getAccessToken($corpid,$secrect);
        $_SESSION['access_token'] = $access_token;
      }

      $code = $_GET['code'];
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=$access_token&code=$code";
      $res = $this->curl($url,'');
      $userid = json_decode($res)['UserId'];
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&userid=$userid";
      $res = $this->curl($url,'');
      echo $res;die;
      $user = json_decode($res);
      var_dump($user);die;
    }

    function curl( $url, $sendData)
    {
      $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt ( $ch, CURLOPT_POST, 1 );
      curl_setopt ( $ch, CURLOPT_HEADER, 0 );
      if($sendData!=""){//5.post方式的时候添加数据 
      curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
      }
      curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
      $return = curl_exec ( $ch );
      curl_close ( $ch );
      return $return;
    }

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


}



?>
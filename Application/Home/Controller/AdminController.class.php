<?php
namespace Home\Controller;
use Think\Controller;
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
      $access_token = getAccessToken($corpid,$secrect);
      $code = $_GET['code'];
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=$access_token&code=$code";
      $res = curl($url,'');
      $userid = json_decode($res)['UserId'];
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&userid=$userid";
      $res = curl($url,'');
      echo $res;die;
      $user = json_decode($res);
      var_dump($user);die;
    }

    function curl( $url, $sendData )
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

}



?>
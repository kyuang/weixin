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
      }else{
	      $access_token = $_SESSION['access_token'];
      }
      $code = $_GET['code'];
      echo $code.'<br>'; 

$url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=$access_token&code=$code";
      //$res = $this->curl($url,'');
      $res = file_get_contents($url);
      print_r($res);
echo "<br>";
echo $access_token;
$userid = json_decode($res);
     
$userid = $userid->UserId;
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&userid=$userid";
      $res = file_get_contents($url);
      echo $res;
      $user = json_decode($res);
      
      $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$access_token";
      $jastk = file_get_contents($url);
      $jsapiTicket = json_decode($jastk)->ticket;
      $nonceStr = "Wm3WZYTPz0wzccnW";
      $timestamp = time();
      $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
      
      $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

      $signature = sha1($string);

      $signPackage = array(
      "appId"     => 'wxdfb65973db0deb4d',
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
      echo json_decode($signPackage);
      $this->assign('data',$signPackage);
      //var_dump($user);die;
      $this->display('addhua');  
  }

  public function tongxun()
  {
    $access_token = $_SESSION['access_token'];
    $url = "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=$access_token";
    $bumen = file_get_contents($url);
    $bumen = json_decode($bumen);
    $bumenid = $bumen->department[0]->id;
    // echo $bumenid;

    $url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token=$access_token&department_id=$bumenid&status=1";
    $res = file_get_contents($url);
echo $res;    
$res = json_decode($res);
    
    echo "<table>";
    foreach($res->userlist as $v){

    echo '<label class="weui_cell weui_check_label" for="s11">
            <div class="weui_cell_hd">
                <input type="checkbox" class="weui_check" name="checkbox1"  checked="" value="'.$v->userid.'">
                <i class="weui_icon_checked"></i>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <p>'.$v->name.'</p>
            </div>
        </label>';     

    }

    echo "<input type='checkbox' id='ckall' value='@all'>选择全部";
    echo "<input type='submit' value='确定'>";
    echo "</table>";

  }

  public function show()
  {
    $this->display('addhua');
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

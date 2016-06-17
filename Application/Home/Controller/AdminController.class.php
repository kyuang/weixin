<?php
namespace Home\Controller;
use Think\Controller;

$lifeTime = 20 * 60;  // 保存一天 
session_set_cookie_params($lifeTime); 
session_start();
class AdminController extends Controller {
    public $appId = 'wxdfb65973db0deb4d';
    public $appSecret = 'HXOk8frb4AaAjXvNKXNxfMegKvwQCrTVZmmeJt2HIk4mjkDtNILOMLRCF9Fw5OVV';

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
      // 调用接口凭证
      $access_token = $this->getAccessToken();
      echo $access_token;

      $code = $_GET['code'];

        echo $code.'<br>';

      $userid = $this->getUserid($code);
     
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&userid=$userid";
      $res = file_get_contents($url);
      echo $res;
      $user = json_decode($res);
      

    $signPackage = $this->getSignPackage();
      $this->assign('data',$signPackage);
      //var_dump($user);die;
      $this->display('addhua');  
  }

  //话题入库
  public function add()
  {
    // var_dump($_POST);die;
    $data = [
    'u_id' => 1,
    'title' => I('post.title'),
    'content' => I('post.content'),
    'canyu' => I('post.canyu'),
    'img' => I('post.img'),
    'type' => 1,
    'time' => time()
    ];
    // var_dump($data);die;
    $User = M("huati");
    // var_dump($User);die;
    // $User->data($data)->add();
    $User->add($data);

  }


  public function tongxun()
  {
    $access_token = $this->getAccessToken();
    $url = "https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=$access_token";
    $bumen = file_get_contents($url);
    $bumen = json_decode($bumen);
    $bumenid = $bumen->department[0]->id;
    // echo $bumenid;

    $url = "https://qyapi.weixin.qq.com/cgi-bin/user/simplelist?access_token=$access_token&department_id=$bumenid&status=1";
    $res = file_get_contents($url);
    // echo $res;    
    $res = json_decode($res);
        
    echo "<table>";
    foreach($res->userlist as $v){

    echo '<label class="weui_cell weui_check_label" for="s11">
            <div class="weui_cell_hd">
                <input type="checkbox" class="weui_check" name="canyu[]"  checked="" value="'.$v->userid.'">
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

/**
 * function
 */
  private function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  function getAccessToken()
  {
      session_start();
      if($_SESSION['access_token'] && $_SESSION['expires_in']+7200>time() ){
              return $access_token['access_token'];
              die;
      }
      $url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$this->appId.'&corpsecret='.$this->appSecret;
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

    public function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode($this->get_php_file("jsapi_ticket.php"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $this->set_php_file("jsapi_ticket.php", json_encode($data));
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }


  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

  private function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
  }

  private function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }

  private function getUserid($code) {   

    $accessToken = $this->getAccessToken();
    $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=$accessToken&code=$code";

    $res = json_decode($this->httpGet($url));
   
    $str = $res->UserId;
    return $str;
  }

}



?>

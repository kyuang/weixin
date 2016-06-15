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
      $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=ACCESS_TOKEN&code=CODE";

      $this->display('addhua');
    }

}



?>
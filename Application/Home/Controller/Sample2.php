<?php

include_once "WXBizMsgCrypt.php";

// 假设企业号在公众平台上设置的参数如下
$encodingAesKey = "KbQkTWVLOlFupNn3NiTbL9aQqgRgk5QJCJK9qF8HfWb";
$token = "ujYMROAH2vOQEPd0TG9";
$corpId = "wx9a046bd2f2118032";

/*
------------使用示例一：验证回调URL---------------
*/

//$sVerifyMsgSig = $_GET["msg_signature"];

//$sVerifyTimeStamp = $_GET["timestamp"];

//$sVerifyNonce = $_GET["nonce"];

//$sVerifyEchoStr = $_GET["echostr"];


// 需要返回的明文
$sEchoStr = "";

$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);

$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
/*if ($errCode == 0) {
	//
	// 验证URL成功，将sEchoStr返回
	echo $sEchoStr;die;
} else {
	print("ERR: " . $errCode . "\n\n");
}
*/

/*
------------使用示例二：对用户回复的消息解密---------------
*/

$sReqMsgSig = $_GET["msg_signature"];

$sReqTimeStamp = $_GET["timestamp"];

$sReqNonce = $_GET["nonce"];

// post请求的密文数据
// $sReqData = HttpUtils.PostData();

$sReqData = file_get_contents("php://input");

$sMsg = "";  // 解析之后的明文
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
	// 解密成功，sMsg即为xml格式的明文
	// TODO: 对明文的处理
	// For example:
	/*
	$xml = new DOMDocument();
	$xml->loadXML($sMsg);
	$content = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
	print("content: " . $content . "\n\n");
	*/
	$data = simplexml_load_string($sMsg, 'SimpleXMLElement', LIBXML_NOCDATA);

	$reply_msg = "<xml>
	 <ToUserName><![CDATA[{$data->FromUserName}]]></ToUserName>
	 <FromUserName><![CDATA[{$corpId}]]></FromUserName>
	 <CreateTime>{$sReqTimeStamp}</CreateTime>
	 <MsgType><![CDATA[text]]></MsgType>
	 <Content><![CDATA[{$data->Content}!!!]]></Content>
	</xml>";
	$sEncryptMsg = ""; //xml格式的密文
	$errCode = $wxcpt->EncryptMsg($reply_msg, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
	echo $sEncryptMsg;

} else {
	print("ERR: " . $errCode . "\n\n");
	//exit(-1);
}

/*
------------使用示例三：企业回复用户消息的加密---------------
企业被动回复用户的消息也需要进行加密，并且拼接成密文格式的xml串。
假设企业需要回复用户的明文如下：
<xml>
<ToUserName><![CDATA[mycreate]]></ToUserName>
<FromUserName><![CDATA[wx5823bf96d3bd56c7]]></FromUserName>
<CreateTime>1348831860</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[this is a test]]></Content>
<MsgId>1234567890123456</MsgId>
<AgentID>128</AgentID>
</xml>

为了将此段明文回复给用户，企业应：
1.自己生成时间时间戳(timestamp),随机数字串(nonce)以便生成消息体签名，也可以直接用从公众平台的post url上解析出的对应值。
2.将明文加密得到密文。
3.用密文，步骤1生成的timestamp,nonce和企业在公众平台设定的token生成消息体签名。
4.将密文，消息体签名，时间戳，随机数字串拼接成xml格式的字符串，发送给企业号。
以上2，3，4步可以用公众平台提供的库函数EncryptMsg来实现。
*/

// 需要发送的明文
$sRespData = "<xml><ToUserName><![CDATA[mycreate]]></ToUserName><FromUserName><![CDATA[wx5823bf96d3bd56c7]]></FromUserName><CreateTime>1348831860</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[this is a test]]></Content><MsgId>1234567890123456</MsgId><AgentID>128</AgentID></xml>";
$sEncryptMsg = ""; //xml格式的密文
$errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
if ($errCode == 0) {
	// TODO:
	// 加密成功，企业需要将加密之后的sEncryptMsg返回
	// HttpUtils.SetResponce($sEncryptMsg);  //回复加密之后的密文
} else {
	print("ERR: " . $errCode . "\n\n");
	// exit(-1);
}


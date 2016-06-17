<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加话题</title>
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="/weixin_tongshi/Public/bootstrap/css/bootstrap.min.css">

	<!-- 可选的Bootstrap主题文件（一般不用引入） -->
	<link rel="stylesheet" href="/weixin_tongshi/Public/bootstrap/css/bootstrap-theme.min.css">

	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="/weixin_tongshi/Public/bootstrap/js/jquery.js"></script>

	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="/weixin_tongshi/Public/bootstrap/js/bootstrap.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>

	<link rel="stylesheet" href="/weixin_tongshi/Public/dist/style/weui.css"/>
    <link rel="stylesheet" href="/weixin_tongshi/Public/dist/example.css"/>
</head>
<body>
<div style='margin: 0 auto;width: 60%;'>
	<form>
	  <div class="form-group">
	    <label for="exampleInputEmail1">话题标题</label>
	    <input type="email" class="form-control" id="exampleInputEmail1">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputPassword1">话题内容</label>
	    <input type="password" class="form-control" id="exampleInputPassword1" >
	  </div>
	  <div class="form-group">
	    <label for="exampleInputFile">上传图片</label>
	    <input type="file" id="exampleInputFile">
	    <p class="help-block">Example block-level help text here.</p>
	  </div>
	  <div class="form-group">
	    <label for="exampleInputFile">选择参与人员</label>
	    <a href="javascript:;"><input type="button" id="button" value="通讯录"></a>
	    <div id='tong'></div>
	  </div>
	  <button type="submit" class="btn btn-default">保存为待提交</button>
	  <button type="submit" class="btn btn-default">直接提交</button>
  </form>
</div>
<?php echo $data['timestamp'] ?>
</body>

<script type="text/javascript">
wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wxdfb65973db0deb4d', // 必填，企业号的唯一标识，此处填写企业号corpid
    timestamp: "<?php echo $data['timestamp'] ?>", // 必填，生成签名的时间戳
    nonceStr: "<?php echo ($data['nonceStr']); ?>", // 必填，生成签名的随机串
    signature: "<?php echo ($data['signature']); ?>",// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});



$(function(){
	$('#button').click(function(){
		$.get("/weixin_tongshi/index.php/Home/Admin/tongxun",function(msg){
			$('#tong').html(msg);
		});
	})
});
</script>
</html>
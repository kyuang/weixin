<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加话题</title>
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="/weixin/Public/bootstrap/css/bootstrap.min.css">

	<!-- 可选的Bootstrap主题文件（一般不用引入） -->
	<link rel="stylesheet" href="/weixin/Public/bootstrap/css/bootstrap-theme.min.css">

	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="/weixin/Public/bootstrap/js/jquery.js"></script>

	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="/weixin/Public/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div style='margin: 0 auto;width: 300px;'>
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
	  <button type="submit" class="btn btn-default">保存为待提交</button>
	  <button type="submit" class="btn btn-default">直接提交</button>
  </form>
</div>
</body>
</html>
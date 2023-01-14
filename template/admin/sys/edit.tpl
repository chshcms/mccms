<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理员列表</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?=Web_Base_Path?>admin/css/style.css">
    <script src="<?=Web_Base_Path?>jquery/jquery.min.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="<?=Web_Base_Path?>jquery/jquery-1.9.1.min.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/html5.min.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/respond.min.js"></script>
    <![endif]-->
    <script src="<?=Web_Base_Path?>layui/layui.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/common.js"></script>
</head>
<body class="bsbg">
<div class="layui-fluid">
    <form class="layui-form" action="<?=links('sys','save')?>">
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">账号</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入登陆账号">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">昵称</label>
            <div class="layui-input-block">
                <input type="text" name="nichen" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$nichen?>" placeholder="请输入昵称">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label<?php if($id==0) echo ' layui-form-required';?>">密码</label>
            <div class="layui-input-block">
                <input type="password" name="pass"<?php if($id==0){echo ' required="" lay-verify="required" placeholder="请输入登陆密码"';}else{echo ' placeholder="不修改密码请留空"';}?> autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item text-right">
            <input type="hidden" name="id" value="<?=$id?>">
            <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
</body>
</html>
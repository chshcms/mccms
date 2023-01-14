<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>资源库新增修改</title>
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
    <form class="layui-form" action="<?=links('caiji','zysave',$type)?>">
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">资源名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入资源名称">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">资源地址</label>
            <div class="layui-input-block">
                <input type="text" name="url" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$url?>" placeholder="请输入资源api接口地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">资源标示</label>
            <div class="layui-input-block">
                <input type="text" name="ly" required lay-verify="ly" autocomplete="off" class="layui-input" value="<?=$ly?>" placeholder="请输入资源标示，只能是字母开头+数字">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">解析地址</label>
            <div class="layui-input-block">
                <input type="text" name="jxurl" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$jxurl?>" placeholder="请输入资源api解析地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">解析秘钥</label>
            <div class="layui-input-block">
                <input type="text" name="token" autocomplete="off" class="layui-input" value="<?=$token?>" placeholder="请输入资源api解析秘钥">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">资源状态</label>
            <div class="layui-input-block">
                <input type="radio" name="zt" value="0" title="开启"<?php if($zt == 0) echo ' checked';?>>
                <input type="radio" name="zt" value="1" title="关闭"<?php if($zt == 1) echo ' checked';?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">资源简介</label>
            <div class="layui-input-block">
                <textarea name="text" placeholder="资源简介,控制在60个字符以内" class="layui-textarea"><?=$text?></textarea>
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
<script>
layui.use('form', function(){
    var form = layui.form;
    //自定义验证规则
    form.verify({
        ly: [
          /^[a-zA-Z][a-zA-Z0-9]*$/
          ,'只能字母或者数字，且字母开头'
        ]
    });
});
</script>
</body>
</html>
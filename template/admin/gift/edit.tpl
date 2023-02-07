<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>礼物修改</title>
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
    <form class="layui-form" action="<?=links('gift','save')?>">
        <div class="layui-form-item">
            <label class="layui-form-label">礼物名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入礼物名称">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">礼物图片</label>
            <div class="layui-input-block">
                <input id="pic" type="text" name="pic" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$pic?>" placeholder="请上传礼物图片">
                <div class="layui-btn layui-btn-normal uppic" style="position: absolute;top: 0px;right: 0;">图片上传</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?=Pay_Cion_Name?>数量</label>
            <div class="layui-input-block">
                <input type="number" name="cion" autocomplete="off" class="layui-input" value="<?=$cion?>" placeholder="请输入<?=Pay_Cion_Name?>数量">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序ID</label>
            <div class="layui-input-block">
                <input type="number" name="xid" autocomplete="off" class="layui-input" value="<?=$xid?>" placeholder="请输入排序ID，越小越靠前">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">礼物简介</label>
            <div class="layui-input-block">
                <input type="text" name="text" autocomplete="off" class="layui-input" value="<?=$text?>" placeholder="一句话来介绍下礼物">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">礼物状态</label>
            <div class="layui-input-block">
                <select name="yid">
                    <option value="0">正常</option>
                    <option value="1"<?php if($yid == 1) echo 'selected';?>>停用</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item text-right">
            <input type="hidden" name="id" value="<?=$id?>">
            <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
<script>
layui.use(['form','upload'], function(){
    var upload = layui.upload;
    upload.render({
        elem: '.uppic',
        url: '<?=links('ajax','upload')?>?dir=<?=sys_auth('gift')?>&sy=no',
        accept: 'file',
        acceptMime: 'image/*',
        exts: '<?=Annex_Ext?>',
        done: function(res){
            if(res.code == 0){
                layer.msg(res.msg,{icon: 1});
                $('#pic').val(res.url);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        }
    });
})
</script>
</body>
</html>
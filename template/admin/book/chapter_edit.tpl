<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>章节修改</title>
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
<style type="text/css">
.layui-form-item .layui-input-inline{
    width: auto;
    margin-left: 5px;
}
.layui-form-pane .layui-form-checkbox {
    margin: 4px 0 4px 1px;
}
.layui-form-item .layui-col-xs12{
    margin-top: 10px;
}
.layui-pic {
    margin-top: -20px;
}
.layui-pic li {
    float: left;
    width: 15%;
    height: 250px;
    overflow: hidden;
    margin-left:1%;
    margin-bottom:10px;
    position:relative;
    border: 3px solid #eee;
}
.layui-pic li>img {
    width: 100%;
    cursor: pointer;
}
.p2{
    position:absolute;
    bottom:0;
    right:0;
    padding:2px 10px;
    background: #ff5722;
    cursor: pointer;
}
.p2 i{
    font-size: 16px;
    color:#fff;
}
@media screen and (max-width: 990px){
    .layui-form-item .layui-col-xs12:first-child{
        margin-top: 0px;
    }
    .layui-form-item{
        margin-bottom: 10px; 
    }
}
</style>
</head>
<body class="bsbg">
<div class="layui-fluid">
    <form class="layui-form layui-form-pane" action="<?=links('book','chapter_save',$bid)?>">
        <div class="layui-form-item">
            <label class="layui-form-label">章节标题</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" class="layui-input" value="<?=$name?>" placeholder="请输入章节标题">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">VIP权限</label>
                <div class="layui-input-block">
                    <select name="vip">
                        <option value="0">免费阅读</option>
                        <option value="1"<?php if($vip == 1) echo 'selected';?>>Vip阅读</option>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label"><?=Pay_Cion_Name?>数量</label>
                <div class="layui-input-block">
                    <input type="number" name="cion" class="layui-input" value="<?=$cion?>" placeholder="阅读该章节是否需要<?=Pay_Cion_Name?>">
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">审核状态</label>
                <div class="layui-input-block">
                    <select name="yid" lay-filter="yid">
                        <option value="0">已通过</option>
                        <option value="1"<?php if($yid == 1) echo 'selected';?>>待审核</option>
                        <option value="2"<?php if($yid == 2) echo 'selected';?>>未通过</option>
                    </select>
                </div>
            </div>
            <div class="layui-col-xs12 layui-col-md3">
                <label class="layui-form-label">排序ID</label>
                <div class="layui-input-block">
                    <input type="number" name="xid" class="layui-input" value="<?=$xid?>" placeholder="排序ID，越小越靠前">
                </div>
            </div>
        </div>
        <div id="yid" class="layui-form-item"<?php if($yid < 2) echo ' style="display: none;"';?>>
            <label class="layui-form-label">未通过原因</label>
            <div class="layui-input-block">
                <input type="text" name="msg" class="layui-input" value="<?=$msg?>" placeholder="请输入未通过原因">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">章节内容</label>
            <div class="layui-input-block">
                <textarea name="text" placeholder="章节内容" class="layui-textarea" style="min-height:400px;"><?=$text?></textarea>
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center;">
            <input type="hidden" name="id" value="<?=$id?>">
            <button class="layui-btn" lay-filter="*" lay-submit>保存</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=Web_Base_Path?>admin/js/jquery-migrate-1.1.1.js"></script>
<script type="text/javascript" src="<?=Web_Base_Path?>admin/js/jquery.dragsort-0.5.1.min.js"></script>
<script type="text/javascript">
layui.use(['form'], function(){
    var form = layui.form;
    form.on('select(yid)', function(data){
        if(data.value == 2){
            $('#yid').show();
        }else{
            $('#yid').hide();
        }
    });
});
</script>
</body>
</html>
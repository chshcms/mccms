    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>添加类别</title>
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
    <form class="layui-form" action="<?=links('comic','type_add_save')?>">
        <div class="layui-form-item">
            <label class="layui-form-label">所属大类</label>
            <div class="layui-input-block">
                <select name="fid" lay-filter="fid">
                    <option value="0">一级大类</option>
                <?php
                foreach($type as $row){
                    $sel = $row['id'] == $fid ? ' selected' : '';
                    echo '<option value="'.$row['id'].'"'.$sel.'>'.$row['name'].'</option>';
                }
                ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入分类名称">
            </div>
        </div>
        <div class="layui-form-item" id="zd"<?php if($fid > 0 || $id > 0) echo 'style="display:none;"';?>>
            <label class="layui-form-label">字段名</label>
            <div class="layui-input-block">
                <input type="text" name="zd" autocomplete="off" class="layui-input" value="<?=$zd?>" placeholder="唯一字段名，只能是英文字母，且保存后无法修改">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序编号</label>
            <div class="layui-input-block">
                <input type="number" name="xid" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$xid?>" placeholder="请输入排序编号，越小越靠前">
            </div>
        </div>
        <div id="cid" class="layui-form-item"<?php if($fid > 0 || $id > 0) echo 'style="display:none;"';?>>
            <label class="layui-form-label">模式</label>
            <div class="layui-input-block">
                <select name="cid">
                    <option value="0">多选</option>
                    <option value="1"<?php if($cid==1) echo ' selected';?>>单选</option>
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
layui.use(['form','layer'],function() {
    var layer = layui.layer,
        form = layui.form;
    form.on('select(fid)', function(r){
        if(r.value == 0){
            $('#cid,#zd').show();
        }else{
            $('#cid,#zd').hide();
        }
    });
})
</script>
</body>
</html>
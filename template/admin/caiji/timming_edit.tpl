<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>定时任务新增修改</title>
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
    <form class="layui-form" action="<?=links('caiji','timming_save/'.$op,$type)?>">
        <div class="layui-form-item w120">
            <label class="layui-form-label layui-form-required">任务名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入任务名称">
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label layui-form-required">资源库选择</label>
            <div class="layui-input-block">
                <select name="ly">
                    <?php
                    $n = 1;
                    foreach($zyk as $k=>$v){
                        $ok = $k == $ly ? ' selected' : '';
                        echo '<option value="'.$k.'"'.$ok.'>'.$n.'.'.$v['name'].'</option>';
                        $n++;
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label layui-form-required">采集方式</label>
            <div class="layui-input-block">
                <input type="radio" name="day" value="1" title="采集当天"<?php if($day == 1) echo 'checked';?>>
                <input type="radio" name="day" value="7" title="采集本周"<?php if($day == 7) echo 'checked';?>>
                <input type="radio" name="day" value="30" title="采集本月"<?php if($day == 30) echo 'checked';?>>
                <input type="radio" name="day" value="0" title="采集全部"<?php if($day == 0) echo 'checked';?>>
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label layui-form-required">采集间隔时间</label>
            <div class="layui-input-block">
                <input type="number" name="i" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$i?>" placeholder="请输入采集间隔时间，单位分钟">
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label">采集静态生成</label>
            <div class="layui-input-block">
                <input type="radio" name="html" value="0" title="开启"<?php if($html == 0) echo ' checked';?>>
                <input type="radio" name="html" value="1" title="关闭"<?php if($html == 1) echo ' checked';?>>
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label">任务状态</label>
            <div class="layui-input-block">
                <input type="radio" name="zt" value="0" title="开启"<?php if($zt == 0) echo ' checked';?>>
                <input type="radio" name="zt" value="1" title="关闭"<?php if($zt == 1) echo ' checked';?>>
            </div>
        </div>
        <div class="layui-form-item w120">
            <label class="layui-form-label layui-form-required">任务访问密码</label>
            <div class="layui-input-block">
                <input type="text" name="pass" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$pass?>" placeholder="请输入任务访问密码">
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
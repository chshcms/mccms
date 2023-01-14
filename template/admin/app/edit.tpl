<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>任务修改</title>
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
    <form class="layui-form" action="<?=links('app','save')?>">
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">任务标题</label>
            <div class="layui-input-block">
                <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$name?>" placeholder="请输入任务标题">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">任务介绍</label>
            <div class="layui-input-block">
                <input type="text" name="text" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$text?>" placeholder="请输入任务介绍">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">奖励<?=Pay_Cion_Name?></label>
            <div class="layui-input-block">
                <input type="number" name="cion" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$cion?>" placeholder="请输入单次奖励<?=Pay_Cion_Name?>数量">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">奖励VIP</label>
            <div class="layui-input-block">
                <input type="number" name="vip" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$vip?>" placeholder="请输入单次奖励VIP天数">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">每日上限</label>
            <div class="layui-input-block">
                <input type="number" name="daynum" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$daynum?>" placeholder="请输入任务每日上限奖励次数，0代表不限制">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label layui-form-required">任务状态</label>
            <div class="layui-input-block">
                <select name="yid">
                    <option value="0">开启</option>
                    <option value="1"<?php if($yid == 1) echo 'selected';?>>关闭</option>
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
</body>
</html>
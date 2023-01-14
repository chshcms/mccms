   <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>卡密修改</title>
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
    <form class="layui-form" action="<?=links('card','save')?>">
        <div class="layui-form-item">
            <label class="layui-form-label">卡密</label>
            <div class="layui-input-block">
                <input type="text" name="pass" required lay-verify="required" autocomplete="off" class="layui-input" value="<?=$pass?>" placeholder="请输入卡密">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">卡密类型</label>
            <div class="layui-input-block">
                <select name="sid" lay-filter="sid">
                    <option value="0"><?=Pay_Cion_Name?>卡</option>
                    <option value="1"<?php if($sid == 1) echo 'selected';?>>Vip卡</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="cion"<?php if($sid == 1) echo ' style="display: none;"'?>>
            <label class="layui-form-label"><?=Pay_Cion_Name?>数量</label>
            <div class="layui-input-block">
                <input type="number" name="cion" autocomplete="off" class="layui-input" value="<?=$cion?>" placeholder="请输入<?=Pay_Cion_Name?>数量">
            </div>
        </div>
        <div class="layui-form-item" id="vip"<?php if($sid == 0) echo ' style="display: none;"'?>>
            <label class="layui-form-label">Vip天数</label>
            <div class="layui-input-block">
                <input type="number" name="day" autocomplete="off" class="layui-input" value="<?=$day?>" placeholder="请输入Vip天数">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">会员ID</label>
            <div class="layui-input-block">
                <input type="text" name="uid" autocomplete="off" class="layui-input" value="<?=$uid?>" placeholder="请输入使用的会员ID">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">使用时间</label>
            <div class="layui-input-block">
                <input id="time" type="text" name="usetime" autocomplete="off" class="layui-input" value="<?=$usetime>0 ? date('Y-m-d H:i:s',$usetime) : ''?>" placeholder="请输入使用时间">
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
layui.use(['form','laydate'], function(){
    var form = layui.form,
        laydate = layui.laydate;
    form.on('select(sid)', function(r){
        if(r.value == 1){
            $('#cion').hide();
            $('#vip').show();
        }else{
            $('#cion').show();
            $('#vip').hide();
        }
    });
    laydate.render({
        elem: '#time',
        type: 'datetime'
    });
})
</script>
</body>
</html>
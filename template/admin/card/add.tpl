<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>卡密添加</title>
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
    <form class="layui-form" action="<?=links('card','pladd')?>">
        <div class="layui-form-item">
            <label class="layui-form-label">卡密类型</label>
            <div class="layui-input-block">
                <select name="sid" lay-filter="sid">
                    <option value="0"><?=Pay_Cion_Name?>卡</option>
                    <option value="1">Vip卡</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="cion">
            <label class="layui-form-label"><?=Pay_Cion_Name?>数量</label>
            <div class="layui-input-block">
                <input type="number" name="cion" autocomplete="off" class="layui-input" value="" placeholder="请输入<?=Pay_Cion_Name?>数量">
            </div>
        </div>
        <div class="layui-form-item" id="vip" style='display: none;'>
            <label class="layui-form-label">Vip天数</label>
            <div class="layui-input-block">
                <input type="number" name="day" autocomplete="off" class="layui-input" value="" placeholder="请输入Vip天数">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">卡密数量</label>
            <div class="layui-input-block">
                <input required lay-verify="required" type="number" name="nums" autocomplete="off" class="layui-input" value="" placeholder="请输入卡密数量">
            </div>
        </div>
        <div class="layui-form-item text-right">
            <button class="layui-btn" lay-filter="*" lay-submit>确定提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
</div>
<script>
layui.use('form', function(){
    var form = layui.form;
    form.on('select(sid)', function(r){
        if(r.value == 1){
            $('#cion').hide();
            $('#vip').show();
        }else{
            $('#cion').show();
            $('#vip').hide();
        }
    });
})
</script>
</body>
</html>
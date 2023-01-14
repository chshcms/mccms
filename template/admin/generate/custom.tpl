<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>漫画生成</title>
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
    <script src="<?=Web_Base_Path?>admin/js/md5.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/common.js"></script>
    <style type="text/css">
    .layui-form-select dl dd.layui-this {
        background-color: #fff;
        color: #333;
    }
    </style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>静态生成</a>
        <a><cite>漫画生成</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card" style="padding:30px;">
        <form class="layui-form" action="<?=links('generate','custom_save')?>">
            <div class="layui-form-item w120">
                <label class="layui-form-label">自定义PC页生成:</label>
                <div class="layui-input-inline">
                    <select name="custom" xm-select="select" xm-select-type="2">
                        <?php
                        foreach($tpl as $file){
                            echo '<option value="'.$file.'">'.$file.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" lay-filter="tpl" lay-submit>生成选中PC端</button>
                </div>
            </div>
        </form>
        <form class="layui-form" action="<?=links('generate','custom_save','wap')?>">
            <div class="layui-form-item w120">
                <label class="layui-form-label">自定义手机页生成:</label>
                <div class="layui-input-inline">
                    <select name="custom" xm-select="select2" xm-select-type="2">
                        <?php
                        foreach($waptpl as $file){
                            echo '<option value="'.$file.'">'.$file.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" lay-filter="tpl2" lay-submit>生成选中手机端</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
layui.config({
    base: '<?=Web_Base_Path?>admin/js/'
}).extend({
    formSelects: 'formSelects'
}).use(['form', 'formSelects'], function() {
    var form = layui.form,
        formSelects = layui.formSelects;
    //自定页
    form.on('submit(tpl)', function (data) {
        var value = formSelects.value('select','val');
        $.post('<?=links('generate','custom_save')?>', {tpl:value}, function(res) {
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});       
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
        return false;
    });
    form.on('submit(tpl2)', function (data) {
        var value = formSelects.value('select2','val');
        $.post('<?=links('generate','custom_save','wap')?>', {tpl:value}, function(res) {
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});       
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
        return false;
    });
});
</script>
</body>
</html>
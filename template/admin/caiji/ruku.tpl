<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>资源库</title>
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
    .layui-form{
        border-width: 0!important;
        margin: 0!important;
    }
    .layui-table-view .layui-table td {
        padding: 2px 0!important;
    }
</style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>采集管理</a>
        <a><cite>资源库</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            <b style="padding-left:15px;">资源入库</b>
            <a href="<?=links('caiji','index',$type)?>" style="margin-left: 10px;" class="layui-btn layui-btn-xs layui-btn-danger delbind">返回资源库列表</a>
            <div style="margin-right:20px;float: right;font-weight: bold;">
                共：<?=$nums?>数据 每页采集：<?=$size?>条 正在采集：<?=$page?> 次 需要采集：<?=$pagejs?> 次
            </div>
        </div>
        <div class="layui-card-body" style="padding:0 15px;">
            <table class="layui-hide" id="demo"></table>
            <div style="padding: 5px 15px;color:#000;font-weight: bold;">
                <?php if($finish == 1){ ?>
                恭喜您，全部采集完成
                <?php }else{ ?>
                暂停3秒后继续 >>> <a href="<?=$next_link?>">如果您的浏览器没有自动跳转，请点击这里</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
layui.use('table', function(){
    var table = layui.table;
    //展示已知数据
    table.render({
        elem: '#demo',
        cols: [[{field:'str'}]],
        data: <?=json_encode($msg)?>,
        limit: 100,
        skin: 'nob',
        even: true,
        done: function (res, curr, count) {
            $('th').hide();
            //var h = $(document).height()-$(window).height();
            //$(document).scrollTop(h);
        }
    });
});
setTimeout(function() {
    layer.load();
    window.location.href = '<?=$next_link?>';
}, 3000);
</script>
</body>
</html>
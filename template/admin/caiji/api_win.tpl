<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?=Web_Name?> - 定时采集任务</title>
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
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header">
            <b style="padding-left:15px;color:#000;">定时任务采集</b>
        </div>
        <div class="layui-card-body" style="padding:0 15px;">
            <div style="padding: 5px 15px;color:#000;font-weight: bold;" id="collect">
                定时采集任务等侍中...
            </div>
        </div>
    </div>
</div>
<script>
var cjurl = '<?=$cjurl?>';
var i = <?=$i?>;
var n = 0;
setInterval('collect()',1000*60*i);
function collect(){
    if(n == 0){
        $("#collect").html("<iframe  frameborder='0' width='100%' height='100' src='"+cjurl+"' scrolling='auto'></iframe>");
        n = 1;
    }
}
</script>
</body>
</html>
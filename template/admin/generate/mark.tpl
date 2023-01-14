<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>静态生成</title>
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
.html{
    overflow-y: auto;
    height: 230px;
    width: 100%;
    margin-top: 10px;
}
.html::-webkit-scrollbar {
    /*滚动条整体样式*/
    width : 10px;  /*高宽分别对应横竖滚动条的尺寸*/
    height: 1px;
}
.html::-webkit-scrollbar-thumb {
    /*滚动条里面小方块*/
    border-radius: 10px;
    box-shadow   : inset 0 0 5px rgba(0, 0, 0, 0.2);
    background   : #666;
}
.html::-webkit-scrollbar-track {
    /*滚动条里面轨道*/
    box-shadow   : inset 0 0 5px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    background   : #ededed;
}
.layui-table td{padding: 3px 15px;}
</style>
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row" style="padding:10px;">
        <fieldset class="layui-elem-field layui-field-title" style="margin: 0;">
            <legend>静态页面生成中</legend>
        </fieldset>
        <div class="layui-progress layui-progress-big" lay-showpercent="true" lay-filter="demo" style="margin-top:20px;">
            <div class="layui-progress-bar layui-bg-red" lay-percent="0%"></div>
        </div>
        <div class="html" id="html">
            <table class="layui-table" lay-even lay-skin="row" id="mark"></table>
        </div>
    </div>
</div>
<script type="text/javascript">
var post = <?=$post?>;
layui.use('element', function(){
    var element = layui.element;
    get_mark(element);
})
function get_mark(element){
    var index = layer.load();
    $.post('<?=$link?>',post, function(res) {
        layer.close(index);
        if(res.code == 2){
            post = res.post;
            $('#mark').append(res.msg);
            setTimeout(function() {
                get_mark(element);
            },200);
            element.progress('demo', res.bi+'%');
        } else if(res.code == 1){
            if(res.html) $('#mark').append(res.html);
            element.progress('demo', '100%');
            layer.msg(res.msg,{icon: 1});
        }else{
            layer.msg(res.msg,{icon: 2});
        }
        var divscll = document.getElementById('html');
        divscll.scrollTop = divscll.scrollHeight;
    },'json');
}
</script>
</body>
</html>
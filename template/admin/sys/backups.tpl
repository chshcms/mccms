<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>数据库备份还原</title>
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
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>管理员</a>
        <a><cite>备份还原</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li class="layui-this"><a href="<?=links('backups')?>">数据备份</a></li>
                    <li><a href="<?=links('backups','restore')?>">数据还原</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-row">
                            <button data-url="<?=links('backups','beifen')?>" class="layui-btn icon-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="db-cmd"><i class="layui-icon">&#xe67d;</i>备份数据</button>
                            <button data-url="<?=links('backups','optimize')?>" class="layui-btn layui-btn-sm" lay-submit lay-filter="db-cmd"><i class="layui-icon">&#xe669;</i>优化表</button>
                            <button data-url="<?=links('backups','repair')?>" class="layui-btn layui-btn-sm layui-btn-warm" lay-submit lay-filter="db-cmd"><i class="layui-icon">&#xe631;</i>修复表</button>
                            <button data-url="<?=links('backups','truncate')?>" class="layui-btn layui-btn-sm layui-btn-danger" lay-submit lay-filter="db-truncate"><i class="layui-icon">&#xe640;</i>清空表</button>
                        </div>
                        <table class="layui-hide" id="demo"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
layui.use(['form','layer','table'], function(){
    var table = layui.table,
        layer = layui.layer,
        form = layui.form;
    //展示已知数据
    table.render({
        elem: '#demo',
        cols: [[
        <?php if(defined('MOBILE')): ?>
            {field:'id',type:'checkbox',width:60,align:'center'},
            {field:'name',title:'表名'},
            {field:'cmd',align:'center',title:'操作',width:150}
        <?php else: ?>
            {field:'id',type:'checkbox',width:60,align:'center'},
            {field:'name',title:'表名'},
            {field:'text',align:'center',title:'备注',width:150},
            {field:'type',align:'center',title:'类型',width:100},
            {field:'rows',align:'center',title:'记录数',width:100},
            {field:'size',align:'center',title:'大小',width:100},
            {field:'free',align:'center',title:'碎片',width:100},
            {field:'cmd',align:'center',title:'操作',width:150}
        <?php endif ?>
        ]],
        data: <?=json_encode($table)?>,
        limit: 500,
        skin: 'row',
        even: true
    });
    form.on('submit(db-cmd)', function (r) {
        var _url = $(r.elem).attr('data-url');
        var arr = table.checkStatus('demo');
        var tt = [];
        for (var i = 0; i < arr.data.length; i++) {
            tt.push(arr.data[i].name);
        }
        if(tt.length == 0) {
            layer.msg('请选择要操作的数据表', {icon: 2});
        } else {
            var index = layer.load();
            $.post(_url, {table:tt}, function(res) {
                layer.close(index);
                if(res.code == 2){
                    layer.msg(res.msg,{icon: 1});
                    setTimeout(function() {
                        get_backup(_url,tt,1,1);
                    }, 1000);
                }else if(res.code == 1){
                    layer.msg(res.msg,{icon: 1});
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }else{
                    layer.msg(res.msg,{icon: 2});
                }
            },'json');
        }
    });
    //清空表
    form.on('submit(db-truncate)', function (r) {
        var _url = $(r.elem).attr('data-url');
        var arr = table.checkStatus('demo');
        var tt = [];
        for (var i = 0; i < arr.data.length; i++) {
            tt.push(arr.data[i].name);
        }
        if(tt.length == 0) {
            layer.msg('请选择要操作的数据表', {icon: 2});
        } else {
            layer.confirm('不能还原了，确定要清空吗', {
                title:'清空提示',
                btn: ['确定', '取消'], //按钮
                shade:0.001
            }, function(index) {
                var index2 = layer.load();
                $.post(_url, {table:tt}, function(res) {
                    layer.close(index2);
                    if(res.code == 1){
                        layer.msg('清空成功...',{icon: 1});
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }else{
                        layer.msg(res.msg,{icon: 2});
                    }
                },'json');
            }, function(index) {
                layer.close(index);
            });
        }
    });
});
function get_backup(_url,tt,n,p){
    var index = layer.load();
    $.post(_url,{table:tt,n:n,p:p}, function(res) {
        layer.close(index);
        if(res.code == 2){
            layer.msg(res.msg,{icon: 1});
            setTimeout(function() {
                get_backup(_url,tt,res.n,res.p);
            },1500);
        }else if(res.code == 1){
            layer.msg(res.msg,{icon: 1});
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg,{icon: 2});
        }
    },'json');
}
</script>
</body>
</html>
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
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li class="layui-this"><a href="<?=links('caiji','index',$type)?>">资源中心</a></li>
                    <li><a href="<?=links('caiji','setting',$type)?>">采集配置</a></li>
                    <li><a href="<?=links('caiji','timming',$type)?>">定时任务</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form toolbar">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.open('添加资源库','<?=links('caiji','edit',$type)?>',600,550);"><i class="layui-icon">&#xe654;</i>添加资源</button>
                                    <button id="uptxt" class="layui-btn icon-btn layui-btn-sm layui-btn-normal"><i class="layui-icon">&#xe67c;</i>导入</button>
                                </div>
                            </div>
                        </div>
                        <table class="layui-table" lay-data="{url:'<?=links('caiji','json',$type)?>',page:false}" lay-filter="caiji">
                          <thead>
                            <tr>
                            <?php if(defined('MOBILE')){ ?>
                                <th lay-data="{field:'name'}">资源名称</th>
                                <th lay-data="{field:'zt',width:70,align:'center'}">状态</th>
                                <th lay-data="{field:'cmd',width:150,align:'center'}">操作</th>
                            <?php }else{ ?>
                                <th lay-data="{field:'id',width:80,align:'center',sort: true}">编号</th>
                                <th lay-data="{field:'name'}">资源名称</th>
                                <th lay-data="{field:'text'}">资源简介</th>
                                <th lay-data="{field:'zt',width:70,align:'center'}">状态</th>
                                <th lay-data="{field:'day',width:90,align:'center'}">采集当天</th>
                                <th lay-data="{field:'week',width:90,align:'center'}">采集本周</th>
                                <th lay-data="{field:'all',width:90,align:'center'}">采集所有</th>
                                <th lay-data="{field:'cmd',width:150,align:'center'}">操作</th>
                            <?php } ?>
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function get_zt(zt,ly){
    var index = layer.load();
    $.post('<?=links('caiji','init',$type)?>', {ly:ly,zt:zt}, function(res) {
        layer.close(index);
        if(res.code == 1){
            layer.msg(res.msg,{icon: 1});
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg,{icon: 2});
        }
    },'json');
}
function get_del(ly,_this){
    layer.confirm('确定要删除吗', {
        title:'删除提示',
        btn: ['确定', '取消'], //按钮
        shade:0.001
    }, function(index) {
        $.post('<?=links('caiji','del',$type)?>', {ly:ly}, function(res) {
            if(res.code == 1){
                layer.msg('删除成功...',{icon: 1});
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
    }, function(index) {
        layer.close(index);
    });
}
layui.use('upload', function(){
    var upload = layui.upload;
    upload.render({
        elem: '#uptxt',
        url: '<?=links('caiji','uptxt',$type)?>',
        accept: 'file',
        acceptMime: 'text/plain',
        exts: 'txt',
        done: function(res){
            if(res.code == 0){
                layer.msg(res.msg,{icon: 1});
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
            console.log(res);
        }
    });
})
</script>
</body>
</html>
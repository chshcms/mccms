<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>礼物列表</title>
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
        <a>礼物打赏</a>
        <a><cite>礼物列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('gift','del')?>','gift')"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn layui-btn-sm" onclick="Admin.open('添加礼物','<?=links('gift','edit')?>',500,440)"><i class="layui-icon">&#xe654;</i>添加礼物</button>
                    </div>
                </div>
            </div>
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('gift','ajax')?>',page:false,id:'gift'}" lay-filter="gift">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'name'}">名称</th>
                    <th lay-data="{field:'yid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{align:'center',templet:'#cmd2Tpl'}">操作</th>
                <?php }else{ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'xid',sort: true,width:80,align:'center'}">排序</th>
                    <th lay-data="{field:'pic',width:80,align:'center',templet:'#picTpl'}">图片</th>
                    <th lay-data="{field:'name'}">名称</th>
                    <th lay-data="{field:'text'}">简介</th>
                    <th lay-data="{field:'cion',sort: true,width:100,align:'center'}"><?=Pay_Cion_Name?></th>
                    <th lay-data="{field:'yid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/html" id="picTpl">
    <img src="{{d.pic}}" style="height: 100%;">
</script>
<script type="text/html" id="ztTpl">
    {{#  if(d.yid == 0){ }}
    <span class="layui-btn layui-btn-xs layui-btn-normal">正常</span>
    {{#  } else { }}
    <span class="layui-btn layui-btn-xs layui-btn-danger">停用</span>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('礼物编辑','<?=links('gift','edit')?>/{{d.id}}',500,440)"><i class="layui-icon">&#xe642;</i>编辑</button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('gift','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
</script>
<script type="text/html" id="cmd2Tpl">
    <button title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('礼物编辑','<?=links('gift','edit')?>/{{d.id}}',500,440)"><i class="layui-icon">&#xe642;</i></button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('gift','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i></button>
</script>
</body>
</html>
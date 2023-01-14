<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>链接列表</title>
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
    <a>运营管理</a>
    <a><cite>友情链接列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('links','del')?>','admin')"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn layui-btn-sm" onclick="Admin.open('添加链接','<?=links('links','edit')?>',500,230)"><i class="layui-icon">&#xe654;</i>添加</button>
                    </div>
                </div>
            </div>
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('links','ajax')?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'admin'}" lay-filter="admin">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'id',type:'checkbox',align:'center'}"></th>
                    <th lay-data="{field:'name',align:'center'}">名称</th>
                    <th lay-data="{align:'center',templet:'#cmdTpl'}">操作</th>
                <?php }else{ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'name',align:'center'}">名称</th>
                    <th lay-data="{field:'url',align:'center'}">地址</th>
                    <th lay-data="{align:'center',width:150,templet:'#cmdTpl'}">操作</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
        </div>
    </div>
</div> 
<script type="text/html" id="cmdTpl">
    <button title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('链接编辑','<?=links('links','edit')?>/{{d.id}}',500,230)"><i class="layui-icon">&#xe642;</i></button>
    <button title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('links','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i></button>
</script>
</body>
</html>
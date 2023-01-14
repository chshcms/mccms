 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>管理员登陆日志</title>
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
        <a><cite>登陆日志</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('sys','log_del')?>','adminlog')"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.get_excel('adminlog');"><i class="layui-icon">&#xe67d;</i>导出xls</button>
                    </div>
                    <div class="layui-inline select70 mr0">
                        <div class="layui-input-inline h30">
                            <select name="zd">
                                <option value="name">账号</option>
                                <option value="nichen"<?php if($zd=='nichen') echo ' selected';?>>昵称</option>
                                <option value="uid"<?php if($zd=='uid') echo ' selected';?>>ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <div class="layui-input-inline">
                            <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="<?=$key?>">
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <button class="layui-btn layui-btn-sm" data-id="adminlog" lay-submit lay-filter="table-sreach">
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>
            </div>
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=$ajaxurl?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'adminlog'}" lay-filter="adminlog">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'nichen',align:'center'}">登陆昵称</th>
                    <th lay-data="{field:'machine',width:100,align:'center'}">登陆设备</th>
                    <th lay-data="{field:'logtime',align:'center', sort: true,templet:function(d){return util.toDateString(d.logtime*1000);}}">登录时间</th>
                <?php }else{ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'uid',sort: true,width:100,align:'center'}">登陆ID</th>
                    <th lay-data="{field:'nichen',align:'center'}">登陆昵称</th>
                    <th lay-data="{field:'logip',width:120,align:'center'}">登陆IP</th>
                    <th lay-data="{field:'machine',width:100,align:'center'}">登陆设备</th>
                    <th lay-data="{field:'browser',align:'center'}">浏览器</th>
                    <th lay-data="{field:'logtime',align:'center', sort: true,templet:function(d){return util.toDateString(d.logtime*1000);}}">登录时间</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
        </div>
    </div>
</div>
</body>
</html>
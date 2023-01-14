<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>任务列表</title>
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
    <a>APP管理</a>
    <a><cite>任务列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('app','task_ajax')?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'admin'}" lay-filter="admin">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'name',align:'center'}">任务标题</th>
                    <th lay-data="{field:'daynum',align:'center'}">每日上限</th>
                    <th lay-data="{align:'center',templet:'#cmdTpl'}">操作</th>
                <?php }else{ ?>
                    <th lay-data="{field:'name',align:'center'}">任务标题</th>
                    <th lay-data="{field:'text'}">任务介绍</th>
                    <th lay-data="{field:'cion',align:'center'}">奖励<?=Pay_Cion_Name?>数量</th>
                    <th lay-data="{field:'vip',align:'center'}">奖励VIP天数</th>
                    <th lay-data="{field:'daynum',align:'center'}">每日上限次数</th>
                    <th lay-data="{align:'center',width:150,templet:'#cmdTpl'}">操作</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
        </div>
    </div>
</div> 
<script type="text/html" id="cmdTpl">
    <button title="编辑" class="layui-btn layui-btn-xs layui-btn-normal" onclick="Admin.open('任务编辑','<?=links('app','edit')?>/{{d.id}}',600,400)"><i class="layui-icon">&#xe642;</i>修改</button>
</script>
</body>
</html>
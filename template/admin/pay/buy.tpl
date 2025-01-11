<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>消费记录</title>
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
        <a>财务管理</a>
        <a><cite>消费记录</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li<?php if($type == 'comic') echo ' class="layui-this"';?>><a href="<?=links('pay','buy','comic')?>">漫画记录</a></li>
                    <li<?php if($type == 'book') echo ' class="layui-this"';?>><a href="<?=links('pay','buy','book')?>">小说记录</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form toolbar">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('pay','buy_del',$type)?>','buy')"><i class="layui-icon"></i>批量删除</button>
                                    <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.get_excel('buy');"><i class="layui-icon">&#xe67d;</i>导出xls</button>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline">
                                        <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="zd">
                                            <option value="uid">会员ID</option>
                                            <option value="id">ip地址</option>
                                            <option value="text">备注</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline">
                                        <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="">
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <button class="layui-btn layui-btn-sm" data-id="buy" lay-submit lay-filter="table-sreach">
                                        <i class="layui-icon">&#xe615;</i>搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('pay','buy_ajax',$type)?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'buy'}" lay-filter="buy">
                          <thead>
                            <tr>
                            <?php if(defined('MOBILE')){ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                                <th lay-data="{field:'text'}">备注</th>
                                <th lay-data="{field:'cion',width:70,align:'center',templet:'#cionTpl'}">金额</th>
                                <th lay-data="{align:'center',templet:'#cmdTpl'}">操作</th>
                            <?php }else{ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                                <th lay-data="{field:'id',sort: true,width:80,align:'center'}">ID</th>
                                <th lay-data="{field:'text'}">备注</th>
                                <th lay-data="{field:'cion',width:100,align:'center'}"><?=Pay_Cion_Name?></th>
                                <th lay-data="{field:'uid',width:100,align:'center'}">会员ID</th>
                                <th lay-data="{field:'ip',width:150,align:'center'}">消费ip</th>
                                <th lay-data="{field:'addtime',align:'center',width:160,sort: true,templet:'#dateTpl'}">消费时间</th>
                                <th lay-data="{align:'center',width:80,templet:'#cmdTpl'}">操作</th>
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
<script type="text/html" id="dateTpl">
    {{#  if(util.toDateString(d.addtime*1000,'yyyy-MM-dd') == '<?=date('Y-m-d')?>'){ }}
    <font color=red>{{util.toDateString(d.addtime*1000)}}</font>
    {{#  } else { }}
    {{util.toDateString(d.addtime*1000)}}
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('pay','buy_del',$type)?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
</script>
</body>
</html>
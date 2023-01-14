<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>卡密列表</title>
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
        <a><cite>卡密列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('card','del')?>','pay')"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="get_dc('card');"><i class="layui-icon">&#xe601;</i>导出txt</button>
                        <button class="layui-btn layui-btn-sm" onclick="Admin.open('添加卡密','<?=links('card','add')?>',500,280)"><i class="layui-icon">&#xe654;</i>添加卡密</button>
                    </div>
                    <div class="layui-inline select100 mr0">
                        <div class="layui-input-inline h30">
                            <select name="zt">
                                <option value="">卡密状态</option>
                                <option value="1">未使用</option>
                                <option value="2">已使用</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <div class="layui-input-inline">
                            <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择使用日期范围" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-inline select100 mr0">
                        <div class="layui-input-inline h30">
                            <select name="zd">
                                <option value="pass">卡密</option>
                                <option value="uid">会员ID</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="">
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <button class="layui-btn layui-btn-sm" data-id="pay" lay-submit lay-filter="table-sreach">
                            <i class="layui-icon">&#xe615;</i>搜索
                        </button>
                    </div>
                </div>
            </div>
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('card','ajax')?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'pay'}" lay-filter="pay">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'pass',align:'center'}">卡密</th>
                    <th lay-data="{field:'sid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{align:'center',templet:'#cmd2Tpl'}">操作</th>
                <?php }else{ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'id',sort: true,width:80,align:'center'}">ID</th>
                    <th lay-data="{field:'pass',align:'center'}">卡密</th>
                    <th lay-data="{field:'rmb',width:100,align:'center',templet:'#rmbTpl'}">面额</th>
                    <th lay-data="{field:'uid',width:100,align:'center',templet:'#uidTpl'}">会员ID</th>
                    <th lay-data="{field:'sid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{field:'usetime',align:'center',width:160,sort: true,templet:'#dateTpl'}">使用时间</th>
                    <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/html" id="uidTpl">
    {{#  if(d.uid == 0){ }}
        ---------
    {{#  } else { }}
    {{d.uid}}
    {{#  } }}
</script>
<script type="text/html" id="dateTpl">
    {{#  if(d.usetime == 0){ }}
        ---------
    {{#  } else if(util.toDateString(d.usetime*1000,'yyyy-MM-dd') == '<?=date('Y-m-d')?>'){ }}
    <font color=red>{{util.toDateString(d.usetime*1000)}}</font>
    {{#  } else { }}
    {{util.toDateString(d.usetime*1000)}}
    {{#  } }}
</script>
<script type="text/html" id="ztTpl">
    {{#  if(d.uid == 0){ }}
    <span class="layui-btn layui-btn-xs layui-btn-normal">未使用</span>
    {{#  } else { }}
    <span class="layui-btn layui-btn-xs layui-btn-danger">已使用</span>
    {{#  } }}
</script>
<script type="text/html" id="rmbTpl">
    {{#  if(d.sid == 0){ }}
    <font color=#1E9FFF>{{d.cion}}<?=Pay_Cion_Name?></font>
    {{#  } else { }}
    <font color=red>{{d.day}}天Vip</font>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('卡密编辑','<?=links('card','edit')?>/{{d.id}}',500,390)"><i class="layui-icon">&#xe642;</i>编辑</button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('card','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
</script>
<script type="text/html" id="cmd2Tpl">
    <button title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('卡密编辑','<?=links('card','edit')?>/{{d.id}}',500,390)"><i class="layui-icon">&#xe642;</i></button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('card','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i></button>
</script>
<script>
    function get_dc(){
        var ids = [];
        var checkStatus = table.checkStatus('pay');
        checkStatus.data.forEach(function(n,i){
            ids.push(n.id);
        });
        if(ids.length == 0){
            layer.msg('请选择要导出的卡密',{icon: 2});
        }else{
            window.location.href = '<?=links('card','daochu')?>?id='+ids.join(',');
        }
    }
</script>
</body>
</html>
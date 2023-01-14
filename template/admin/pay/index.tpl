<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>订单记录</title>
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
        <a><cite>订单记录</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('pay','del')?>','pay')"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.get_excel('pay');"><i class="layui-icon">&#xe67d;</i>导出xls</button>
                    </div>
                    <div class="layui-inline select70 mr0">
                        <div class="layui-input-inline h30">
                            <select name="pid">
                                <option value="">状态</option>
                                <option value="1">未完成</option>
                                <option value="2">已完成</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline mr0">
                        <div class="layui-input-inline">
                            <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-inline select100 mr0">
                        <div class="layui-input-inline h30">
                            <select name="zd">
                                <option value="dd">订单号</option>
                                <option value="uid">会员ID</option>
                                <option value="id">订单ID</option>
                                <option value="text">订单备注</option>
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
            <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('pay','ajax')?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'pay'}" lay-filter="pay">
              <thead>
                <tr>
                <?php if(defined('MOBILE')){ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'dd',align:'center'}">订单号</th>
                    <th lay-data="{field:'rmb',width:70,align:'center'}">金额</th>
                    <th lay-data="{field:'pid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{align:'center',templet:'#cmdTpl'}">操作</th>
                <?php }else{ ?>
                    <th lay-data="{field:'id',type:'checkbox',width:60,align:'center'}"></th>
                    <th lay-data="{field:'id',sort: true,width:80,align:'center'}">订单ID</th>
                    <th lay-data="{field:'dd',align:'center'}">订单号</th>
                    <th lay-data="{field:'rmb',width:100,align:'center'}">金额</th>
                    <th lay-data="{field:'uid',width:100,align:'center'}">会员ID</th>
                    <th lay-data="{field:'text',align:'center'}">备注</th>
                    <th lay-data="{field:'pid',width:70,align:'center',templet:'#ztTpl'}">状态</th>
                    <th lay-data="{field:'addtime',align:'center',width:160,sort: true,templet:'#dateTpl'}">订单时间</th>
                    <th lay-data="{align:'center',width:80,templet:'#cmdTpl'}">操作</th>
                <?php } ?>
                </tr>
              </thead>
            </table>
            <div class="layui-form toolbar">
                <div class="layui-form-item">
                    <div class="layui-inline select100 mr0">
                        <div class="layui-input-inline h30">
                            <select name="day" id="day">
                                <option value="">选择天数</option>
                                <option value="1">1天前</option>
                                <option value="2">2天前</option>
                                <option value="3">3天前</option>
                                <option value="5">5天前</option>
                                <option value="7">7天前</option>
                                <option value="10">10天前</option>
                                <option value="20">20天前</option>
                                <option value="30">30天前</option>
                                <option value="90">9天前</option>
                                <option value="180">180天前</option>
                                <option value="365">365天前</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline select100 mr0">
                        <div class="layui-input-inline h30">
                            <select name="zt" id="zt">
                                <option value="">选择状态</option>
                                <option value="2">未完成</option>
                                <option value="3">已完成</option>
                                <option value="1">所有状态</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="get_pldel();"><i class="layui-icon"></i>删除选择的条件</button>
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
<script type="text/html" id="ztTpl">
    {{#  if(d.pid == 1){ }}
    <span class="layui-btn layui-btn-xs">已完成</span>
    {{#  } else { }}
    <span class="layui-btn layui-btn-xs layui-btn-danger">未完成</span>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('pay','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
</script>
<script>
    function get_pldel(){
        var day = $('#day').val();
        var zt = $('#zt').val();
        if(day == ''){
            layer.msg('请选择删除天数',{icon: 2});
        }else if(zt == ''){
            layer.msg('请选择删除状态',{icon: 2});
        }else{
            layer.confirm('不可恢复，确定要删除吗', {
                title:'删除提示',
                btn: ['确定', '取消'], //按钮
                shade:0.001
            }, function(index) {
                var index = layer.load();
                $.post('<?=links('pay','pldel')?>', {day:day,zt:zt}, function(res) {
                    layer.close(index);
                    if(res.code == 1){
                        layer.msg('删除成功...',{icon: 1});
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
    }
</script>
</body>
</html>
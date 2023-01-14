<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>章节章节列表</title>
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
<style>
@media screen and (max-width: 990px){
    .layui-btn-sm{
        margin-bottom: 10px; 
    }
}
</style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>小说管理</a>
        <a><cite>章节列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li<?php if($yid==0) echo ' class="layui-this"';?>><a href="<?=links('book','chapter')?>?id=<?=$bid?>">已审章节</a></li>
                    <li<?php if($yid==1) echo ' class="layui-this"';?>><a href="<?=links('book','chapter',1)?>?id=<?=$bid?>">待审章节</a></li>
                    <li<?php if($yid==2) echo ' class="layui-this"';?>><a href="<?=links('book','chapter',2)?>?id=<?=$bid?>">未通过章节</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form toolbar">
                            <div class="layui-form-item" style="margin-top: 10px;">
                                <div class="layui-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('book','chapter_del',$bid)?>','book')"><i class="layui-icon">&#xe640;</i>删除</button>
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" onclick="get_px('book')">排序</button>
                                    <button class="layui-btn layui-btn-sm" onclick="get_vip('book',1)">设置VIP</button>
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="get_vip('book',2)">取消VIP</button>
                                    <button class="layui-btn layui-btn-sm" onclick="get_cion('book')">设置收费</button>
                                    <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.open('新增章节','<?=links('book','chapter_edit',$bid)?>',0,0,1);"><i class="layui-icon">&#xe624;</i>新增章节</button>
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="get_txt()">一键同步所有章节TXT</button>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline mr0">
                                        <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="zd">
                                            <option value="name">章节名称</option>
                                            <option value="id">章节ID</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline mr0">
                                        <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="">
                                    </div>
                                </div>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="pay">
                                            <option value="">阅读方式</option>
                                            <option value="1">免费阅读</option>
                                            <option value="2">Vip阅读</option>
                                            <option value="3"><?=Pay_Cion_Name?>阅读</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <button class="layui-btn layui-btn-sm" data-id="book" lay-submit lay-filter="table-sreach">
                                        <i class="layui-icon">&#xe615;</i>搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('book','chapter_ajax')?>/<?=$bid?>/<?=$yid?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'book'}" lay-filter="book">
                          <thead>
                            <tr>
                            <?php if(defined('MOBILE')){ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                                <th lay-data="{field:'name',templet:'#nameTpl'}">章节名称</th>
                                <th lay-data="{align:'center',width:130,templet:'#cmdTpl'}">操作</th>
                            <?php }else{ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                                <th lay-data="{field:'id',sort: true,width:90,align:'center'}">章节ID</th>
                                <th lay-data="{field:'xid',templet:'#xidTpl',sort: true,width:90,align:'center'}">排序ID</th>
                                <th lay-data="{field:'name',templet:'#nameTpl'}">章节名称</th>
                                <th lay-data="{field:'text_num',sort: true,width:100,align:'center'}">章节字数</th>
                                <th lay-data="{field:'book_name'}">所属小说</th>
                                <th lay-data="{field:'sid',width:90,align:'center',templet:'#ztTpl'}">收费</th>
                                <th lay-data="{field:'addtime',align:'center',width:100,sort: true,templet:'#dateTpl'}">更新日期</th>
                                <th lay-data="{align:'center',width:130,templet:'#cmdTpl'}">操作</th>
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
<script type="text/html" id="nameTpl">
    <a href="javascript:;" onclick="Admin.open('章节编辑','<?=links('book','chapter_edit')?>/{{d.bid}}?id={{d.id}}',0,0,1)">{{d.name}}</a>
</script>
<script type="text/html" id="picTpl">
    <img src="{{d.pic}}" style="width: 100%;">
</script>
<script type="text/html" id="xidTpl">
    <input type="text" id="xid_{{d.id}}" class="layui-input" style="height: 28px;text-align: center;padding-left:0px;" value="{{d.xid}}" placeholder="排序编号，越小越前" title="排序编号，越小越前">
</script>
<script type="text/html" id="dateTpl">
    {{#  if(util.toDateString(d.addtime*1000,'yyyy-MM-dd') == '<?=date('Y-m-d')?>'){ }}
    <font color=red>{{util.toDateString(d.addtime*1000,'yyyy-MM-dd')}}</font>
    {{#  } else { }}
    {{util.toDateString(d.addtime*1000,'yyyy-MM-dd')}}
    {{#  } }}
</script>
<script type="text/html" id="ztTpl">
    {{#  if(d.vip > 0){ }}
        <span class="layui-btn layui-btn-xs layui-btn-danger">Vip阅读</span>
    {{#  } else if(d.cion > 0){ }}
        <span class="layui-btn layui-btn-xs layui-btn-normal"><?=Pay_Cion_Name?>阅读</span>
    {{#  } else { }}
        <span class="layui-btn layui-btn-xs">免费阅读</span>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button style="margin-left:5px;" title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('章节编辑','<?=links('book','chapter_edit')?>/{{d.bid}}?id={{d.id}}',0,0,1)"><i class="layui-icon">&#xe642;</i>编辑</button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('book','chapter_del/'.$bid)?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
</script>
<script>
function get_vip(_id,_vip){
    var ids = [];
    var checkStatus = table.checkStatus(_id);
    checkStatus.data.forEach(function(n,i){
        ids.push(n.id);
    });
    if(ids.length ==0){
        layer.msg('请选择要操作的数据',{icon: 2});
    }else{
        layer.confirm('确定要操作吗', {
            title:'操作提示',
            btn: ['确定', '取消'], //按钮
            shade:0.001
        }, function(index) {
            $.post('<?=links('book','chapter_init/vip')?>', {'id':ids,vip:_vip,bid:<?=$bid?>}, function(res) {
                if(res.code == 1){
                    layer.msg('设置成功...',{icon: 1});
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
function get_cion(_id){
    var ids = [];
    var checkStatus = table.checkStatus(_id);
    checkStatus.data.forEach(function(n,i){
        ids.push(n.id);
    });
    if(ids.length ==0){
        layer.msg('请选择要操作的数据',{icon: 2});
    }else{
    	layer.prompt({title: '请输入<?=Pay_Cion_Name?>数量',area: ['200px', '150px']},function(value, index, elem){
    		if(isNaN(value)){
    			layer.msg('请输入正确的数量',{icon: 2});
    		}else{
	    		$.post('<?=links('book','chapter_init/cion')?>', {'id':ids,cion:value,bid:<?=$bid?>}, function(res) {
	                if(res.code == 1){
	                    layer.msg('设置成功...',{icon: 1});
	                    setTimeout(function() {
	                        location.reload();
	                    }, 1000);
	                }else{
	                    layer.msg(res.msg,{icon: 2});
	                    layer.close(index);
	                }
	            },'json');
    		}
		});
    }
}
function get_px(_id){
    var ids = [],xids = [];
    var checkStatus = table.checkStatus(_id);
    checkStatus.data.forEach(function(n,i){
        var id = n.id;
        var val = $('#xid_'+id).val();
        xids.push(val);
        ids.push(n.id);
    });
    if(ids.length ==0){
        layer.msg('请选择要操作的数据',{icon: 2});
    }else{
        $.post('<?=links('book','chapter_init/px')?>', {'id':ids,xid:xids,bid:<?=$bid?>}, function(res) {
            if(res.code == 1){
                layer.msg('设置成功...',{icon: 1});
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2});
                layer.close(index);
            }
        },'json');
    }
}
function get_txt(){
    layer.confirm('章节过多时，可能需要同步很久，确定吗？', {
        title:'操作提示',
        btn: ['确定', '取消'], //按钮
        shade:0.001
    }, function(index) {
        layer.close(index);
        var index = layer.load();
        $.post('<?=links('book','chapter_txt')?>', {bid:<?=$bid?>}, function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg('同步完成...',{icon: 1});
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
</script>
</body>
</html>
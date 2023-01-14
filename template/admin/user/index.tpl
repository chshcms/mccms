<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>会员列表</title>
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
        <a>会员管理</a>
        <a><cite>用户列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab-item layui-show">
                <div class="layui-form toolbar">
                    <div class="layui-form-item" style="margin-top: 10px;">
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('user','del')?>','user')"><i class="layui-icon">&#xe640;</i>删除</button>
                            <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.open('新增会员','<?=links('user','edit')?>',0,0,1);"><i class="layui-icon">&#xe624;</i>新增</button>
                        </div>
                        <div class="layui-inline mr0">
                            <div class="layui-input-inline mr0">
                                <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-inline select100 mr0">
                            <div class="layui-input-inline h30">
                                <select name="zd">
                                    <option value="name">会员账号</option>
                                    <option value="nichen">会员昵称</option>
                                    <option value="id">会员ID</option>
                                    <option value="tel">联系手机</option>
                                    <option value="qq">联系QQ</option>
                                    <option value="email">联系邮箱</option>
                                    <option value="city">城市地区</option>
                                    <option value="text">用户介绍</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline mr0">
                            <div class="layui-input-inline mr0">
                                <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="">
                            </div>
                        </div>
                        <div class="layui-inline select70 mr0">
                            <div class="layui-input-inline h30">
                                <select name="vip">
                                    <option value="">VIP</option>
                                    <option value="2">是</option>
                                    <option value="1">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline select70 mr0">
                            <div class="layui-input-inline h30">
                                <select name="sid">
                                    <option value="">锁定</option>
                                    <option value="2">是</option>
                                    <option value="1">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline select100 mr0">
                            <div class="layui-input-inline h30">
                                <select name="cid">
                                    <option value="">认证状态</option>
                                    <option value="1">未认证</option>
                                    <option value="2"<?php if($cid==2) echo ' selected';?>>待审核</option>
                                    <option value="3">审核失败</option>
                                    <option value="4">个人认证</option>
                                    <option value="5">企业认证</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline mr0">
                            <button class="layui-btn layui-btn-sm" data-id="user" lay-submit lay-filter="table-sreach">
                                <i class="layui-icon">&#xe615;</i>搜索
                            </button>
                        </div>
                    </div>
                </div>
                <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('user','ajax')?>?cid=<?=$cid?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'user'}" lay-filter="user">
                  <thead>
                    <tr>
                    <?php if(defined('MOBILE')){ ?>
                        <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                        <th lay-data="{field:'name',templet:'#nameTpl'}">会员账号</th>
                        <th lay-data="{field:'sid',width:80,align:'center',templet:'#ztTpl'}">状态</th>
                        <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
                    <?php }else{ ?>
                        <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                        <th lay-data="{field:'id',sort: true,width:70,align:'center'}">会员ID</th>
                        <th lay-data="{field:'pic',width:80,align:'center',templet:'#picTpl'}">头像</th>
                        <th lay-data="{field:'name',align:'center'}">账号</th>
                        <th lay-data="{field:'nichen',align:'center'}">笔名/昵称</th>
                        <th lay-data="{field:'vip',width:50,align:'center',templet:'#vipTpl'}">Vip</th>
                        <th lay-data="{field:'rmb',width:80,align:'center'}">余额</th>
                        <th lay-data="{field:'cion',width:80,align:'center'}"><?=Pay_Cion_Name?></th>
                        <th lay-data="{field:'ticket',width:80,align:'center'}">月票</th>
                        <th lay-data="{field:'sid',width:80,align:'center',templet:'#ztTpl'}">状态</th>
                        <th lay-data="{field:'addtime',align:'center',width:100,sort: true,templet:'#dateTpl'}">加入日期</th>
                        <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
                    <?php } ?>
                    </tr>
                  </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="picTpl">
    <div onclick="show_img(this)" style="height:100%;width:100%;"><img src="{{d.pic}}" style="height:100%;"></div>
</script>
<script type="text/html" id="vipTpl">
    {{#  if(d.vip == 1){ }}
        <span class="layui-btn layui-btn-danger layui-btn-xs" title="到期时间：{{util.toDateString(d.viptime)}}">是</span>
    {{#  } else { }}
        <span class="layui-btn layui-btn-normal layui-btn-xs">否</span>
    {{#  } }}
</script>
<script type="text/html" id="dateTpl">
    {{#  if(util.toDateString(d.addtime*1000,'yyyy-MM-dd') == '<?=date('Y-m-d')?>'){ }}
    <font color=red>{{util.toDateString(d.addtime*1000,'yyyy-MM-dd')}}</font>
    {{#  } else { }}
    {{util.toDateString(d.addtime*1000,'yyyy-MM-dd')}}
    {{#  } }}
</script>
<script type="text/html" id="ztTpl">
    {{#  if(d.sid == 1){ }}
    <input type="checkbox" data-id="{{d.id}}" data-link="<?=links('user','init')?>" name="switch" lay-text="正常|锁定" lay-skin="switch" lay-filter="*">
    {{#  } else { }}
    <input type="checkbox" data-id="{{d.id}}" data-link="<?=links('user','init')?>" name="switch" lay-text="正常|锁定" lay-skin="switch" lay-filter="*" checked>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button style="margin-left:5px;" title="查看详细" class="layui-btn layui-btn-normal layui-btn-xs" onclick="Admin.open('用户详细信息','<?=links('user','show')?>?id={{d.id}}')"><i class="layui-icon">&#xe615;</i></button>
    <button style="margin-left:5px;" title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('会员编辑','<?=links('user','edit')?>/{{d.id}}',0,0,1)"><i class="layui-icon">&#xe642;</i></button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('user','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i></button>
</script>
<script>
function show_img(t) {
    var t = $(t).find("img");
    //页面层
    layer.open({
        type: 1,
        skin: 'none', //加上边框
        area: ['45%', '65%'], //宽高
        shadeClose: true, //开启遮罩关闭
        end: function (index, layero) {
            return false;
        },
        content: '<div style="text-align:center"><img src="' + $(t).attr('src') + '" /></div>'
    });
}
</script>
</body>
</html>
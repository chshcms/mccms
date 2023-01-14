<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>小说列表</title>
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
        <a>小说管理</a>
        <a><cite>小说列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li<?php if($yid==0) echo ' class="layui-this"';?>><a href="<?=links('book')?>">已审小说</a></li>
                    <li<?php if($yid==1) echo ' class="layui-this"';?>><a href="<?=links('book','index',1)?>">待审小说</a></li>
                    <li<?php if($yid==2) echo ' class="layui-this"';?>><a href="<?=links('book','index',2)?>">未通过小说</a></li>
                    <li<?php if($yid==3) echo ' class="layui-this"';?>><a href="<?=links('book','index',3)?>">检测重复小说</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form toolbar">
                            <div class="layui-form-item" style="margin-top: 10px;">
                                <div class="layui-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.del('<?=links('book','del')?>','book')"><i class="layui-icon">&#xe640;</i>删除</button>
                                    <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.open('新增小说','<?=links('book','edit')?>',0,0,1);"><i class="layui-icon">&#xe624;</i>新增</button>
                                </div>
                                <?php if($yid < 3){ ?>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="cid">
                                        <option value="">小说分类</option>
                                        <?php
                                        foreach($class as $row){
                                            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                            $array = $this->mcdb->get_select('book_class','id,name',array('fid'=>$row['id']),'xid ASC',100);
                                            foreach($array as $row2){
                                            echo '<option value="'.$row2['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;'.$row2['name'].'</option>';
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline mr0">
                                        <input name="times" class="layui-input date-icon h30" type="text" placeholder="请选择日期范围" autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="zd">
                                            <option value="name">小说名称</option>
                                            <option value="author">小说作者</option>
                                            <option value="id">小说ID</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <div class="layui-input-inline mr0 w100">
                                        <input type="text" name="key" placeholder="请输入关键字" autocomplete="off" class="layui-input h30" value="">
                                    </div>
                                </div>
                                <div class="layui-inline select70 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="serialize">
                                            <option value="">状态</option>
                                            <option value="连载">连载</option>
                                            <option value="完结">完结</option>
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
                                <div class="layui-inline select70 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="pay">
                                            <option value="">收费</option>
                                            <option value="1">否</option>
                                            <option value="2">是</option>
                                            <option value="3">VIP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline select70 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="tid">
                                            <option value="">推荐</option>
                                            <option value="2">是</option>
                                            <option value="1">否</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline select100 mr0">
                                    <div class="layui-input-inline h30">
                                        <select name="size">
                                            <option value="">总字数</option>
                                            <option value="1">100万以下</option>
                                            <option value="2">100-300万</option>
                                            <option value="3">300万以上</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline mr0">
                                    <button class="layui-btn layui-btn-sm" data-id="book" lay-submit lay-filter="table-sreach">
                                        <i class="layui-icon">&#xe615;</i>搜索
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <table class="layui-table" lay-even lay-skin="row" lay-data="{url:'<?=links('book','ajax',$yid)?>?name=<?=$name?>',limit:20,limits:[20,30,50,100,500],page:{layout:['count','prev','page','next','refresh','skip','limit']},id:'book'}" lay-filter="book">
                          <thead>
                            <tr>
                            <?php if(defined('MOBILE')){ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                                <th lay-data="{field:'name',templet:'#nameTpl'}">小说名称</th>
                                <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
                            <?php }else{ ?>
                                <th lay-data="{field:'id',type:'checkbox',width:40,align:'center'}"></th>
                                <th lay-data="{field:'id',sort: true,width:70,align:'center'}">小说ID</th>
                                <th lay-data="{field:'pic',width:80,align:'center',templet:'#picTpl'}">缩略图</th>
                                <th lay-data="{field:'name',templet:'#nameTpl'}">小说名称</th>
                                <th lay-data="{field:'text_num',width:100,align:'center'}">总字数</th>
                                <th lay-data="{field:'tid',width:60,align:'center',templet:'#tjTpl'}">推荐</th>
                                <th lay-data="{field:'pic',width:60,align:'center',templet:'#payTpl'}">收费</th>
                                <th lay-data="{field:'author',width:130,align:'center'}">小说作者</th>
                                <th lay-data="{field:'serialize',width:80,align:'center'}">连载</th>
                                <th lay-data="{field:'sid',width:80,align:'center',templet:'#ztTpl'}">状态</th>
                                <th lay-data="{field:'addtime',align:'center',width:100,sort: true,templet:'#dateTpl'}">更新日期</th>
                                <th lay-data="{align:'center',width:120,templet:'#cmdTpl'}">操作</th>
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
    <a href="javascript:;" onclick="parent.Admin.add_tab('章节列表','<?=links('book','chapter')?>?id={{d.id}}')">{{d.name}}<font style="padding-left:8px;color:#f60;">{{d.nums}}话</font></a>
</script>
<script type="text/html" id="picTpl">
    <div onclick="show_img(this)"><img src="{{d.pic}}" style="width: 100%;"></div>
</script>
<script type="text/html" id="payTpl">
    {{#  if(d.pay == 2){ }}
    <font color=#f60>VIP</font>
    {{#  } else if(d.pay == 1){ }}
    <font color=red>收费</font>
    {{#  } else { }}
    免费
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
    <input type="checkbox" data-id="{{d.id}}" data-link="<?=links('book','init')?>" name="switch" lay-text="正常|锁定" lay-skin="switch" lay-filter="*">
    {{#  } else { }}
    <input type="checkbox" data-id="{{d.id}}" data-link="<?=links('book','init')?>" name="switch" lay-text="正常|锁定" lay-skin="switch" lay-filter="*" checked>
    {{#  } }}
</script>
<script type="text/html" id="tjTpl">
    {{#  if(d.tid == 1){ }}
        <div style="color:#080;cursor: pointer;" class="layui-icon reco" data-id="{{d.id}}" data-tid="0" title="已推荐，点击取消推荐">&#xe605;</div>
    {{#  } else { }}
        <div style="color:#f30;cursor: pointer;" class="layui-icon reco" data-id="{{d.id}}" data-tid="1" title="未推荐，点击推荐">&#x1006;</div>
    {{#  } }}
</script>
<script type="text/html" id="cmdTpl">
    <button style="margin-left:5px;" title="查看章节" class="layui-btn layui-btn-normal layui-btn-xs" onclick="parent.Admin.add_tab('章节列表','<?=links('book','chapter')?>?id={{d.id}}')"><i class="layui-icon">&#xe615;</i></button>
    <button style="margin-left:5px;" title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open('小说编辑','<?=links('book','edit')?>/{{d.id}}',0,0,1)"><i class="layui-icon">&#xe642;</i></button>
    <button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del('<?=links('book','del')?>','{{d.id}}',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i></button>
</script>
<script>
$(function(){
    $(document).on('click','.reco',function(){
        var _this = $(this);
        var tid = $(this).attr('data-tid');
        var id = $(this).attr('data-id');
        var index = layer.load();
        $.post('<?=links('ajax','reco/book')?>', {id:id,tid:tid}, function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
                if(tid == 1){
                    _this.html('&#xe605;').attr('data-tid','0').css('color','#080');
                }else{
                    _this.html('&#x1006;').attr('data-tid','1').css('color','#f30');
                }
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
    })
});
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
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>资源库</title>
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
        <a>采集管理</a>
        <a><cite>定时任务</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li><a href="<?=links('caiji','index',$type)?>">资源中心</a></li>
                    <li><a href="<?=links('caiji','setting',$type)?>">采集配置</a></li>
                    <li class="layui-this"><a href="<?=links('caiji','timming',$type)?>">定时任务</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form toolbar">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="Admin.open('添加任务','<?=links('caiji','timming_edit',$type)?>');"><i class="layui-icon">&#xe654;</i>添加任务</button>
                                </div>
                            </div>
                        </div>
                        <table class="layui-table" lay-even lay-skin="row">
                            <colgroup>
                                <col class="hide" width="60">
                                <col>
                                <col class="hide" width="100">
                                <col class="hide" width="70">
                                <col class="hide" width="180">
                                <col width="><?=defined('MOBILE') ? 80 : 220;?>">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="hide" style="text-align:center">序号</th>
                                    <th>任务标题</th>
                                    <th class="hide" style="text-align:center">采集方式</th>
                                    <th class="hide" style="text-align:center">状态</th>
                                    <th class="hide" style="text-align:center">最后执行时间</th>
                                    <th style="text-align:center">操作</th>
                                </tr> 
                            </thead>
                            <tbody>
                            <?php 
                            if(empty($timming)){
                                echo '<tr><td align="center" colspan="6">没有找到相关记录!!!</td></tr>';
                            }
                            $i = 1;
                            foreach($timming as $k=>$row){ 
                                $zt = $row['zt']==0 ? '<span class="layui-btn layui-btn-xs" onclick="get_zt(0,\''.$k.'\');">开启</span>' : '<span class="layui-btn layui-btn-xs layui-btn-danger" onclick="get_zt(1,\''.$k.'\');">关闭</span>';
                                if($row['day'] == 1){
                                    $txt = '采集当天';
                                }elseif($row['day'] == 7){
                                    $txt = '采集本周';
                                }elseif($row['day'] == 30){
                                    $txt = '采集本月';
                                }else{
                                    $txt = '采集全部';
                                }
                                $color = date('Y-m-d') == date('Y-m-d',strtotime($row['time'])) ? ' style="color:red"' : '';
                                echo '<tr><td class="hide" align="center">'.$i.'</td><td>'.$row['name'].'</td><td class="hide" align="center"><span class="layui-btn layui-btn-xs layui-btn-normal">'.$txt.'</span></td><td class="hide" align="center">'.$zt.'</td><td class="hide" align="center"'.$color.'>'.$row['time'].'</td>';
                                if(defined('MOBILE')){
                                    echo '<td align="center"><a style="margin-right: 5px;" href="javascript:;" onclick="Admin.open(\'任务地址\',\''.links('caiji','timming_url',$type.'/'.$k).'\',700,320);"><span class="layui-btn layui-btn-xs layui-btn-normal" title="任务地址">地址</span></a><a style="margin-right: 5px;" href="javascript:;" onclick="Admin.open(\'修改任务\',\''.links('caiji','timming_edit/'.$type,$k).'\');" title="修改"><span class="layui-btn layui-btn-xs"><i class="layui-icon">&#xe642;</i></span></a><a href="javascript:;" onclick="get_del(\''.$k.'\',this);" title="删除"><span class="layui-btn layui-btn-xs layui-btn-danger" title="删除"><i class="layui-icon">&#xe640;</i></span></a></td></tr>';
                                }else{
                                    echo '<td align="center"><a style="margin-right: 5px;" href="javascript:;" onclick="Admin.open(\'任务地址\',\''.links('caiji','timming_url',$type.'/'.$k).'\',700,320);"><span class="layui-btn layui-btn-xs layui-btn-normal" title="任务地址">任务地址</span></a><a style="margin-right: 5px;" href="javascript:;" onclick="Admin.open(\'修改任务\',\''.links('caiji','timming_edit/'.$type,$k).'\');" title="修改"><span class="layui-btn layui-btn-xs"><i class="layui-icon">&#xe642;</i>修改</span></a><a href="javascript:;" onclick="get_del(\''.$k.'\',this);" title="删除"><span class="layui-btn layui-btn-xs layui-btn-danger" title="删除"><i class="layui-icon">&#xe640;</i>删除</span></a></td></tr>';
                                }
                                $i++;
                            } 
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function get_zt(zt,ly){
    var index = layer.load();
    $.post('<?=links('caiji','timming_init',$type)?>', {ly:ly,zt:zt}, function(res) {
        layer.close(index);
        if(res.code == 1){
            layer.msg(res.msg,{icon: 1});
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }else{
            layer.msg(res.msg,{icon: 2});
        }
    },'json');
}
function get_del(ly,_this){
    layer.confirm('确定要删除吗', {
        title:'删除提示',
        btn: ['确定', '取消'], //按钮
        shade:0.001
    }, function(index) {
        $.post('<?=links('caiji','timming_del',$type)?>', {ly:ly}, function(res) {
            if(res.code == 1){
                layer.msg('删除成功...',{icon: 1});
                $(_this).parent().parent().remove();
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
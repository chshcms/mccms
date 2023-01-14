<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>类别列表</title>
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
td input{text-align: center;}
.qzan,.zan{
    height: 23px;
    line-height: 23px;
    padding: 0px 3px;
}
</style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>漫画管理</a>
        <a><cite>类型列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <for class="layui-form toolbar">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <div class="layui-inline">
                        <button lay-filter="edit" lay-submit class="layui-btn layui-btn-sm layui-cmd hide"><i class="layui-icon">&#xe642;</i>修改选中</button>
                        <button lay-filter="del" lay-submit class="layui-btn layui-btn-sm layui-btn-danger layui-cmd hide"><i class="layui-icon">&#xe640;</i>删除选中</button>
                        <button class="layui-btn layui-btn-sm layui-btn-normal" onclick="Admin.open('添加类别','<?=links('comic','type_add')?>',600,400);"><i class="layui-icon">&#xe624;</i>添加类别</button>
                    </div>
                </div>
                <table class="layui-table" lay-skin="line">
                    <colgroup>
                        <col class="hide" width="40">
                        <col width="60">
                        <col width="40">
                        <col>
                        <col class="hide" width="100">
                        <col class="hide" width="160">
                        <col class="hide" width="160">
                        <col class="hide" width="120">
                        <col width="<?=defined('MOBILE')?120:150?>">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="hide" style="text-align:center"><input lay-filter="qxuan" type="checkbox" name="qxuan" lay-skin="primary"></th>
                            <th style="text-align:center">ID</th>
                            <th style="text-align:center"><button title="全部展开" class="layui-btn layui-btn-sm qzan"><i class="layui-icon">&#xe61a;</i></button></th>
                            <th>标题</th>
                            <th class="hide" style="text-align:center">标签字段</th>
                            <th class="hide" style="text-align:center">名称</th>
                            <th class="hide" style="text-align:center">排序</th>
                            <th class="hide" style="text-align:center">模式</th>
                            <th style="text-align:center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $kg = defined('MOBILE') ? '├─' : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;';
                    foreach($type as $row){
                        $sel = $row['cid'] == 1 ? ' selected' : '';
                        //二级类别
                        $array = $this->mcdb->get_select('type','*',array('fid'=>$row['id']),'xid ASC',500);
                        $zan = '';
                        if(!empty($array)) $zan = '<button data-sid="1" title="展开/收起" data-id="'.$row['id'].'" class="layui-btn layui-btn-sm zan"><i class="layui-icon">&#xe610;</i></button>';
                        if(defined('MOBILE')){
                            $cmd = '<button onclick="Admin.open(\'添加类别\',\''.links('comic','type_add',$row['id']).'\',500,280);" title="添加" class="layui-btn layui-btn-xs" href="javascript:;" ><i class="layui-icon">&#xe624;</i></button><button onclick="Admin.open(\'修改类别\',\''.links('comic','type_add',$row['id']).'/'.$row['id'].'\',500,280);" title="修改" class="layui-btn layui-btn-xs layui-btn-normal" href="javascript:;" ><i class="layui-icon">&#xe642;</i></button><button data-sid="1" data-id="'.$row['id'].'" title="删除" class="layui-btn-danger layui-btn layui-btn-xs lay-del" href="javascript:;" data-sid="2"><i class="layui-icon">&#xe640;</i></button>';
                        }else{
                            $cmd = '<button onclick="Admin.open(\'添加类别\',\''.links('comic','type_add',$row['id']).'\',500,280);" title="添加" class="layui-btn layui-btn-xs" href="javascript:;" ><i class="layui-icon">&#xe624;</i>添加</button><button data-sid="1" data-id="'.$row['id'].'" title="删除" class="layui-btn-danger layui-btn layui-btn-xs lay-del" href="javascript:;" data-sid="2"><i class="layui-icon">&#xe640;</i>清空</button>';
                        }
                        echo '<tr id="z_'.$row['id'].'"><td align="center" class="hide"><input lay-filter="zx1" data-sid="1" class="xuan" type="checkbox" name="xuan" lay-skin="primary" value="'.$row['id'].'"></td><td align="center">'.$row['id'].'</td><td>'.$zan.'</td><td>'.$row['name'].'</td><td class="hide" align="center">'.$row['zd'].'</td><td class="hide" align="center"><input type="text" name="name_'.$row['id'].'" class="layui-input h30" value="'.$row['name'].'" placeholder="类别名称"></td><td class="hide" align="center"><input type="text" name="xid_'.$row['id'].'" class="layui-input h30" value="'.$row['xid'].'" placeholder="排序编号，越小越前"></td><td class="hide h30" align="center"><select name="cid_['.$row['id'].']"><option value="0">多选</option><option value="1"'.$sel.'>单选</option></select></td><td align="center">'.$cmd.'</td></tr>';
                        foreach($array as $row2){
                            if(defined('MOBILE')){
                                $cmd2 = '<button onclick="Admin.open(\'修改类别\',\''.links('comic','type_add',$row2['id']).'/'.$row2['id'].'\',500,280);" title="修改" class="layui-btn layui-btn-xs layui-btn-normal" href="javascript:;" ><i class="layui-icon">&#xe642;</i>改</button><button data-sid="2" data-id="'.$row2['id'].'" title="删除" class="layui-btn-danger layui-btn layui-btn-xs lay-del" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删</button>';
                            }else{
                                $cmd2 = '<button data-sid="2" data-id="'.$row2['id'].'" title="删除" class="layui-btn-danger layui-btn layui-btn-xs lay-del" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>';
                            }
                            echo '<tr class="erji z'.$row['id'].'" id="z_'.$row2['id'].'" style="background-color: #f2f2f2;display: none;"><td class="hide" align="center"><input class="xuan zx2_'.$row['id'].'" type="checkbox" name="xuan" lay-skin="primary" value="'.$row2['id'].'"></td><td align="center">'.$row2['id'].'</td><td><button title="展开/收起" class="layui-btn layui-btn-sm zan layui-btn-disabled" disabled><i class="layui-icon">&#xe610;</i></button></td><td>'.$kg.$row2['name'].'</td><td class="hide" align="center">'.$row['zd'].'</td><td class="hide" align="center"><input type="text" name="name_'.$row2['id'].'" class="layui-input h30" value="'.$row2['name'].'" placeholder="类别名称"></td><td class="hide" align="center"><input type="text" name="xid_'.$row2['id'].'" class="layui-input h30" value="'.$row2['xid'].'" placeholder="排序编号，越小越前"></td><td class="hide" align="center"></td><td align="center">'.$cmd2.'</td></tr>';
                        }
                    } 
                    ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
layui.use(['form','layer'],function() {
    var layer = layui.layer,
        form = layui.form;
    //全部展开、关闭
    $('.qzan').click(function(){
        var none = 0;
        $('.erji').each(function(){
            if($(this).css('display') == 'none') none = 1;
        });
        if(none == 1){
            $('.erji').show();
            $(this).children('i').html('&#xe619;');
        }else{
            $('.erji').hide();
            $(this).children('i').html('&#xe61a;');
        }
    });
    //展开、关闭
    $('.zan').click(function(){
        var id = $(this).attr('data-id');
        var sid = $(this).attr('data-sid');
        if(sid == 1){
            $('.z'+id).show();
            $(this).attr('data-sid','2');
        }else{
            $('.z'+id).hide();
            $(this).attr('data-sid','1');
        }
    });
    //监听switch全反选
    form.on('checkbox(qxuan)', function(data){
        var obj = $('.xuan');
        for (var i = 0; i < obj.length; i++) {
            obj[i].checked = (obj[i].checked) ? false : true;
        }
        form.render('checkbox');
    });
    //监听switch全反选
    form.on('checkbox(zx1)', function(data){
        var _id = data.value;
        console.log(_id);
        var obj = $('.zx2_'+_id);
        for (var i = 0; i < obj.length; i++) {
            obj[i].checked = (obj[i].checked) ? false : true;
        }
        form.render('checkbox');
    });
    //批量修改
    form.on('submit(edit)', function(data){
        var obj = $('.xuan');
        var ids = [];
        for (var i = 0; i < obj.length; i++) {
            if(obj[i].checked) ids.push(obj[i].value);
        }
        if(ids.length == 0){
            layer.msg('请选择要操作的数据',{icon: 2});
            return false;
        }
        data.field.ids = ids;
        $.post('<?=links('comic','type_save')?>', data.field, function(res) {
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
    });
    //批量删除
    form.on('submit(del)', function(data){
        var obj = $('.xuan');
        var ids = [];
        for (var i = 0; i < obj.length; i++) {
            if(obj[i].checked) ids.push(obj[i].value);
        }
        if(ids.length == 0){
            layer.msg('请选择要操作的数据',{icon: 2});
            return false;
        }
        layer.confirm('确定要删除吗', {
            title:'删除提示',
            btn: ['确定', '取消'], //按钮
            shade:0.001
        }, function(index) {
            $.post('<?=links('comic','type_del')?>', {id:ids}, function(res) {
                if(res.code == 1){
                    layer.msg(res.msg,{icon: 1});
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }else{
                    layer.msg(res.msg,{icon: 2});
                }
            },'json');
        }, function(index) {
            layer.close(index);
        });
    });
    //删除单个二级
    $('.lay-del').click(function(){
        var _this = $(this);
        var id = $(this).attr('data-id');
        var sid = $(this).attr('data-sid');
        var msg = sid == 2 ? '删除' : '清空';
        layer.confirm('确定要'+msg+'吗', {
            title:msg+'提示',
            btn: ['确定', '取消'], //按钮
            shade:0.001
        }, function(index) {
            $.post('<?=links('comic','type_del')?>', {id:id,sid:sid}, function(res) {
                if(res.code == 1){
                    layer.msg(res.msg,{icon: 1});
                    if(sid == 1){
                        $('.z'+id).remove();
                    }else{
                        _this.parent().parent().remove();
                    }
                }else{
                    layer.msg(res.msg,{icon: 2});
                }
            },'json');
        }, function(index) {
            layer.close(index);
        });
    });
})
</script>
</body>
</html>
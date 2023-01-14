<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>分类列表</title>
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
<style>td input{text-align: center;}</style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>漫画管理</a>
        <a><cite>分类列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <for class="layui-form toolbar">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <div class="layui-inline">
                        <button lay-filter="edit" lay-submit class="layui-btn layui-btn-sm layui-cmd"><i class="layui-icon">&#xe642;</i>修改选中</button>
                        <button lay-filter="del" lay-submit class="layui-btn layui-btn-sm layui-btn-danger layui-cmd"><i class="layui-icon">&#xe640;</i>删除选中</button>
                        <button class="layui-btn icon-btn layui-btn-sm layui-btn-normal" onclick="Admin.open('新增分类','<?=links('comic','lists_edit')?>',500,390);"><i class="layui-icon">&#xe624;</i>添加分类</button>
                    </div>
                </div>
                <table class="layui-table" lay-skin="line">
                    <colgroup>
                        <col width="40">
                        <col class="hide" width="70">
                        <col>
                        <col class="hide" width="160">
                        <col class="hide" width="160">
                        <col class="hide" width="160">
                        <col class="hide" width="100">
                        <col width="150">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="text-align:center"><input lay-filter="qxuan" type="checkbox" name="qxuan" lay-skin="primary"></th>
                            <th class="hide" style="text-align:center">ID</th>
                            <th>标题</th>
                            <th class="hide" style="text-align:center">名称</th>
                            <th class="hide" style="text-align:center">英文别名</th>
                            <th class="hide" style="text-align:center">模版</th>
                            <th class="hide" style="text-align:center">排序</th>
                            <th style="text-align:center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    foreach($class as $row){
                        echo '<tr><td align="center"><input class="xuan" type="checkbox" name="xuan" lay-skin="primary" value="'.$row['id'].'"></td><td class="hide" align="center">'.$row['id'].'</td><td>'.$row['name'].'</td><td class="hide" align="center"><input type="text" name="name_'.$row['id'].'" class="layui-input h30" value="'.$row['name'].'" placeholder="分类名称"></td><td class="hide" align="center"><input type="text" name="yname_'.$row['id'].'" class="layui-input h30" value="'.$row['yname'].'" placeholder="分类英文别名"></td><td class="hide" align="center"><input type="text" name="tpl_'.$row['id'].'" class="layui-input h30" value="'.$row['tpl'].'" placeholder="使用模版"></td><td class="hide" align="center"><input type="text" name="xid_'.$row['id'].'" class="layui-input h30" value="'.$row['xid'].'" placeholder="排序编号，越小越前"></td><td align="center"><div class="layui-table-cell laytable-cell-1-0-7"><button style="margin-left:5px;" title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open(\'分类编辑\',\''.links('comic','lists_edit').'/'.$row['id'].'\',500,390)"><i class="layui-icon">&#xe642;</i>编辑</button><button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del(\''.links('comic','lists_del').'\',\''.$row['id'].'\',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button></div></td></tr>';
                        //二级分类
                        $array = $this->mcdb->get_select('class','*',array('fid'=>$row['id']),'xid ASC',100);
                        foreach($array as $row2){
                            echo '<tr style="background-color: #f2f2f2;"><td align="center"><input class="xuan" type="checkbox" name="xuan" lay-skin="primary" value="'.$row2['id'].'"></td><td class="hide" align="center">'.$row2['id'].'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;'.$row2['name'].'</td><td class="hide" align="center"><input type="text" name="name_'.$row2['id'].'" class="layui-input h30" value="'.$row2['name'].'" placeholder="分类名称"></td><td class="hide" align="center"><input type="text" name="yname_'.$row2['id'].'" class="layui-input h30" value="'.$row2['yname'].'" placeholder="分类英文别名"></td><td class="hide" align="center"><input type="text" name="tpl_'.$row2['id'].'" class="layui-input h30" value="'.$row2['tpl'].'" placeholder="使用模版"></td><td class="hide" align="center"><input type="text" name="xid_'.$row2['id'].'" class="layui-input h30" value="'.$row2['xid'].'" placeholder="排序编号，越小越前"></td><td align="center"><div class="layui-table-cell laytable-cell-1-0-7"><button style="margin-left:5px;" title="编辑" class="layui-btn layui-btn-xs" onclick="Admin.open(\'分类编辑\',\''.links('comic','lists_edit').'/'.$row2['id'].'\',500,390)"><i class="layui-icon">&#xe642;</i>编辑</button><button style="margin-left:5px;" title="删除" class="layui-btn-danger layui-btn layui-btn-xs" onclick="Admin.del(\''.links('comic','lists_del').'\',\''.$row2['id'].'\',this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button></div></td></tr>';
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
    //监听switch全反选
    form.on('checkbox(qxuan)', function(data){
        var val = data.value;
        var obj = $('.xuan');
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
        $.post('<?=links('comic','lists_save')?>', data.field, function(res) {
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
            $.post('<?=links('comic','lists_del')?>', {id:ids}, function(res) {
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
})
</script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>漫画生成</title>
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
    <script src="<?=Web_Base_Path?>admin/js/md5.js"></script>
    <script src="<?=Web_Base_Path?>admin/js/common.js"></script>
    <style type="text/css">
    .layui-form-select dl dd.layui-this {
        background-color: #fff;
        color: #333;
    }
    </style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>静态生成</a>
        <a><cite>漫画生成</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card" style="padding: 20px;">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend style="font-size:15px;">主页生成</legend>
        </fieldset>
        <form class="layui-form" action="<?=links('generate','save')?>">
            <div class="layui-form-item">
                <label class="layui-form-label">主页生成:</label>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="home" lay-submit>一键生成PC端主页</button>
                    <button class="layui-btn" data-type="wap" lay-filter="home" lay-submit>一键生成手机端主页</button>
                </div>
            </div>
        </form>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend style="font-size:15px;">分类页</legend>
        </fieldset>
        <form class="layui-form" action="<?=links('generate','save')?>">
            <div class="layui-form-item">
                <label class="layui-form-label">选择分类:</label>
                <div class="layui-input-inline">
                    <select name="cid" xm-select="select" xm-select-type="2">
                        <option value="0">所有分类</option>
                        <?php
                        foreach($class as $row){
                            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                            $array = $this->mcdb->get_select('class','id,name',array('fid'=>$row['id']),'xid ASC',100);
                            foreach($array as $row2){
                                echo '<option value="'.$row2['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;'.$row2['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tpl" lay-submit>生成选中PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tpl" lay-submit>生成选中手机端</button>
                </div>
            </div>
        </form>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend style="font-size:15px;">详情页</legend>
        </fieldset>
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">按分类:</label>
                <div class="layui-input-inline">
                    <select name="cid" xm-select="select" xm-select-type="2">
                        <option value="0">所有分类</option>
                        <?php
                        foreach($class as $row){
                            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                            $array = $this->mcdb->get_select('class','id,name',array('fid'=>$row['id']),'xid ASC',100);
                            foreach($array as $row2){
                                echo '<option value="'.$row2['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;├─&nbsp;'.$row2['name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="day" id="day">
                        <option value="">按时间</option>
                        <option value="1">1天内</option>
                        <option value="2">2天内</option>
                        <option value="3">3天内</option>
                        <option value="5">5天内</option>
                        <option value="7">7天内</option>
                        <option value="10">10天内</option>
                        <option value="20">20天内</option>
                        <option value="30">30天内</option>
                        <option value="60">60天内</option>
                        <option value="180">180天内</option>
                        <option value="365">365天内</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tplcid" lay-submit>生成选中PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tplcid" lay-submit>生成选中手机端</button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">按漫画ID:</label>
                <div class="layui-input-inline">
                    <input type="text" id="ksid" placeholder="起始ID" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <input type="text" id="jsid" placeholder="结束ID" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tplid" lay-submit>开始生成PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tplid" lay-submit>开始生成手机端</button>
                </div>
            </div>
        </form>
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend style="font-size:15px;">阅读页</legend>
        </fieldset>
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">按时间:</label>
                <div class="layui-input-inline">
                    <select name="day" id="day">
                        <option value="">全部记录</option>
                        <option value="1">1天内</option>
                        <option value="2">2天内</option>
                        <option value="3">3天内</option>
                        <option value="5">5天内</option>
                        <option value="7">7天内</option>
                        <option value="10">10天内</option>
                        <option value="20">20天内</option>
                        <option value="30">30天内</option>
                        <option value="60">60天内</option>
                        <option value="180">180天内</option>
                        <option value="365">365天内</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tplcid2" lay-submit>生成选中PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tplcid2" lay-submit>生成选中手机端</button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">按漫画ID:</label>
                <div class="layui-input-inline">
                    <input type="text" id="mid" placeholder="请输入漫画ID" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tplmid" lay-submit>开始生成PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tplmid" lay-submit>开始生成手机端</button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">按章节ID:</label>
                <div class="layui-input-inline">
                    <input type="text" id="ksid2" placeholder="起始ID" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <input type="text" id="jsid2" placeholder="结束ID" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="pc" lay-filter="tplid2" lay-submit>开始生成PC端</button>
                    <button class="layui-btn" data-type="wap" lay-filter="tplid2" lay-submit>开始生成手机端</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
layui.config({
    base: '<?=Web_Base_Path?>admin/js/'
}).extend({
    formSelects: 'formSelects'
}).use(['form', 'formSelects'], function() {
    var form = layui.form,
        formSelects = layui.formSelects;
    //主页
    form.on('submit(home)', function (data) {
        $.post('<?=links('generate','save')?>/'+data.elem.dataset.type, {op:'index'}, function(res) {
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});       
            }else{
                layer.msg(res.msg,{icon: 2});
            }
        },'json');
        return false;
    });
    //分类页
    form.on('submit(tpl)', function (data) {
        var cid = formSelects.value('select','val');
        Admin.open('分类生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=lists&id='+cid.join(','),600,400);
        return false;
    });
    //详情页
    form.on('submit(tplcid)', function (data) {
        var cid = formSelects.value('select','val');
        var day = $('#day').val();
        Admin.open('漫画生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=comic&do=cid&id='+cid.join(',')+'&day='+day,600,400);
        return false;
    });
    form.on('submit(tplid)', function (data) {
        var ksid = $('#ksid').val();
        var jsid = $('#jsid').val();
        if(ksid == '' && jsid == ''){
            layer.msg('请填写要生成的ID',{icon: 2});
        }else{
            Admin.open('漫画生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=comic&do=id&ksid='+ksid+'&jsid='+jsid,600,400);
        }
        return false;
    });
    //阅读页
    form.on('submit(tplcid2)', function (data) {
        var day = $('#day').val();
        Admin.open('阅读页生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=chapter&do=cid&day='+day,600,400);
        return false;
    });
    form.on('submit(tplid2)', function (data) {
        var ksid = $('#ksid2').val();
        var jsid = $('#jsid2').val();
        if(ksid == '' && jsid == ''){
            layer.msg('请填写要生成的ID',{icon: 2});
        }else{
            Admin.open('阅读页生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=chapter&do=id&ksid='+ksid+'&jsid='+jsid,600,400);
        }
        return false;
    });
    form.on('submit(tplmid)', function (data) {
        var mid = $('#mid').val();
        if(mid == ''){
            layer.msg('请填写要生成的漫画ID',{icon: 2});
        }else{
            Admin.open('阅读页生成','<?=links('generate','mark')?>/'+data.elem.dataset.type+'?op=chapter&do=mid&mid='+mid,600,400);
        }
        return false;
    });
});
</script>
</body>
</html>
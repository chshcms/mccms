<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=Web_Name?> - 管理系统</title>
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
</head>
<body class="index">
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a>漫城CMS管理系统</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="layui-icon">&#xe668;</i></a>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item"><a onclick="Admin.delcache('<?=links('index','caches')?>');" title="刷新缓存"><i class="layui-icon" style="font-size:16px;">&#xe669;</i></a></li>
        <li class="layui-nav-item">
            <a href="javascript:;"><?=$this->cookie->get('admin_nichen')?></a>
            <dl class="layui-nav-child">
                <dd><a onclick="get_pass();">修改密码</a></dd>
                <dd><a href="<?=links('logout')?>">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index"><a href="<?=Web_Path?>" target="_blank">前台首页</a></li>
    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="layui-side left-nav">
    <div class="layui-side-scroll" id="side-nav">
        <ul id="nav">
        <?php 
        foreach($nav as $k=>$v){
            $uarr = explode(',',$v['file']);
            $qx = 1;
            if(!empty($admin['qx'])){
                $qx = 0;
                foreach($uarr as $v2){
                    if(strpos($admin['qx'],$v2) !== false){
                        $qx = 1;
                        break;
                    }
                }
            }
            if($qx == 1){
        ?>
            <li>
                <a href="javascript:;" class="left-nav-li" lay-tips="<?=$v['name']?>">
                    <i class="layui-icon nav-tps"><?=$v['icon']?></i>
                    <cite><?=$v['name']?></cite>
                    <i class="layui-icon nav_right">&#xe603;</i>
                </a>
                <ul class="sub-menu">
                    <?php 
                    foreach($v['list'] as $kk=>$vv){ 
                        if($vv['init'] == 1){
                            $urls = explode(',',$vv['url']);
                            if(empty($admin['qx']) || strstr($admin['qx'],$urls[0])){
                    ?>
                    <li>
                        <a onclick="Admin.add_tab('<?=$vv['name']?>','<?=links($urls[0])?>')">
                            <i class="layui-icon">&#xe602;</i>
                            <cite><?=$vv['name']?></cite>
                        </a>
                    </li>
                    <?php } } } ?>
                </ul>
            </li>
        <?php } } ?>
        </ul>
    </div>
</div>
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="iframe" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>后台首页</li>
        </ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd>
            </dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show" style="padding-bottom:44px;">
                <iframe src='<?=links('index','main')?>' frameborder="0" scrolling="yes" class="iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="layui-footer layui-text">
    <?=base64decode('Y29weXJpZ2h0IMKpIDIwMjAgPGEgaHJlZj0iaHR0cDovL3d3dy5tY2Ntcy5jbi8iIHRhcmdldD0iX2JsYW5rIj7mvKvln45DTVPns7vnu588L2E-IGFsbCByaWdodHMgcmVzZXJ2ZWQu')?>
    <span class="pull-right">Version <?=Ver?></span>
</div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<script type="text/javascript">
function get_pass(){
    layer.prompt({title: '请输入新密码',area: ['200px', '150px']},function(value, index, elem){
        $.post('<?=links('ajax','passedit')?>', {pass:value}, function(res) {
            if(res.code == 0){
                layer.msg('修改成功',{icon: 1});
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2});
                layer.close(index);
            }
        },'json');
    });
}
</script>
</body>
</html>
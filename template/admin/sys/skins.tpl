<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>网站配置</title>
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
    <style type="text/css">
    .layui-text{padding-top:5px;margin-bottom:20px;}
    .comic-skin .pic{height: 155px;background: url(<?=Web_Base_Path?>admin/images/skin_no.png);background-size: 100%;}
    .text{height:20px;overflow:hidden;}
    .cmd{position: relative;height: 50px;border-top: 1px solid #f5f5f5;padding-top: 5px;}
    .left{position: absolute;bottom:0;left:0;}
    .right{position: absolute;bottom:20px;right:0;background-color:#FF5722;height: 24px;line-height: 24px;}
    .b1{background-color:#1E9FFF;}
    .b2{background-color:#2F4056;}
    .upic{display: inline-block;vertical-align: middle;}
    .upic img{width:40px;height:40px;border-radius: 50%;}
    .unichen{display: inline-block;vertical-align: middle;text-align: left;padding-left: 5px;font-size: 14px;}
    </style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>系统配置</a>
        <a><cite>模版配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li><a href="<?=links('setting','skins/pc')?>">电脑模版</a></li>
                    <li><a href="<?=links('setting','skins/wap')?>">手机模版</a></li>
                    <li class="layui-this">模版中心</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-tab layui-tab-card">
                            <ul class="layui-tab-title tpltab">
                            <?php
                            $cls = 0 == $cid ? ' class="layui-this"' : '';
                            echo '<li'.$cls.'><a href="'.links('skins/index').'">全部模板</a></li>';
                            foreach($skins['class'] as $row){
                                $cls = $row['id'] == $cid ? ' class="layui-this"' : '';
                                echo '<li'.$cls.'><a href="'.links('skins/index/'.$row['id']).'">'.$row['name'].'</a></li>';
                            }
                            ?>
                            </ul>
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show">
                                    <div class="layui-text">
                                        <div class="layui-row">
                                            <div style="padding: 10px;">
                                                <?php 
                                                if(empty($skins['tpl'])) echo '<p style="text-align: center;padding: 100px;">没有模板数据</p>';
                                                foreach($skins['tpl'] as $row){ 
                                                ?>
                                                <div class="layui-col-xs6 layui-col-sm4 layui-col-md2">
                                                    <div class="comic-skin center">
                                                        <div class="pic"><a href="<?=$row['url']?>" target="_blank"><img src="<?=$row['pic']?>"></a></div>
                                                        <div class="text"><?=$row['name']?></div>
                                                        <div class="cmd">
                                                            <div class="left">
                                                                <span class="upic"><img src="<?=$row['upic']?>"></span>
                                                                <span class="unichen">
                                                                    <?=$row['unichen']?><br>
                                                                <?php if($row['rmb'] > 0){ ?>
                                                                    ￥<?=$row['rmb']?>元
                                                                <?php }else{ ?>
                                                                    <span class="layui-btn layui-btn-xs layui-btn-warm layui-btn-radius">免费</span>
                                                                <?php } ?>
                                                                <span>
                                                            </div>
                                                            <?php if($row['pay'] < 1){ ?>
                                                                <div class="layui-btn layui-btn-xs layui-btn-radius right tplpay tpl-<?=$row['id']?>" data-id="<?=$row['id']?>" data-url="<?=links('skins/down/'.$row['id'])?>" data-token="<?=$token?>">立即购买</div>
                                                            <?php }elseif(!empty($row['path']) AND file_exists(FCPATH.'template/'.$row['path'].'index.html')){ ?>
                                                                <div class="layui-btn layui-btn-xs layui-btn-radius right b1 tpldown" data-url="<?=links('skins/down/'.$row['id'])?>">重新下载</div>
                                                            <?php }else{ ?>
                                                                <div class="layui-btn layui-btn-xs layui-btn-radius right b2 tpldown" data-url="<?=links('skins/down/'.$row['id'])?>">立即下载</div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($skins['pagejs'] > 1 AND $skins['pagejs'] > $page){ ?>
                                    <div style="text-align: center;"><a class="tpltab" style="padding: 8px 30px;background: #e8e7e7;border-radius: 5px;" href="<?=links('skins/index/'.$cid.'/'.($page+1))?>">点击加载下一页</a></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    if(wap){
        $('.comic-skin .pic').css('height','90px');
        $('.cmd').css('height','65px');
        $('.cmd .left').css('bottom','26px');
        $('.cmd .left .upic img').css('width','30px');
        $('.cmd .left .upic img').css('height','30px');
        $('.cmd .right').css('bottom','-5px');
        $('.cmd .layui-btn').css('width','100%');
    }
    $('.tpltab li a').click(function(){layer.load();});
    $('.tplpay').click(function(){
        var id = $(this).data('id'),token = $(this).data('token');
        if(token == ''){
            w = wap ? '' : '500px';
            h = wap ? '' : '300px';
            layer.open({
                type: 1,
                area: [w, h],
                fix: false, //不固定
                maxmin: true,
                shadeClose: false,
                maxmin: false,
                shade:0.2,
                title: '用户登录',
                content: '<div style="padding:10px 20px;"><fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>登录崇胜账号</legend></fieldset><form class="layui-form" action=""><div class="layui-form-item"><label class="layui-form-label">手机</label><div class="layui-input-block"><input type="text" name="tel" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input"></div></div><div class="layui-form-item"><label class="layui-form-label">密码</label><div class="layui-input-block"><input type="password" name="pass" required  lay-verify="required" placeholder="请输入登录密码" autocomplete="off" class="layui-input"></div></div><div class="layui-form-item"><button type="button" class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="login">立即登录</button></div></div>'
            });
            form.on('submit(login)', function(data){
                var index = layer.load();
                $.getJSON('http:<?=base64decode(Apiurl)?>/skins/log?tel='+data.field.tel+'&pass='+data.field.pass+'&callback=?',function(res) {
                    layer.close(index);
                    if(res.code == 1){
                        layer.msg(res.msg,{icon: 1});
                        Admin.set_cookie('mccms_tpl_token',res.data.token);
                        setTimeout(function() {
                            window.location.reload();
                        },300);
                    }else{
                        layer.msg(res.msg,{shift:6});
                    }
                });
                return false;
            });
        }else{
            var index = layer.load();
            $.getJSON('http:<?=base64decode(Apiurl)?>/skins/pay?id='+id+'&token='+token+'&callback=?',function(res) {
                layer.close(index);
                if(res.code == 1){
                    layer.msg(res.msg,{icon: 1});
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }else{
                    if(res.code == 2){
                        layer.confirm(res.msg+'，要充值购买吗', {
                            title:'友情提示',
                            btn: ['去充值', '在想想'], //按钮
                            shade:0.001
                        }, function(index) {
                            window.open("http://www.chshcms.net/user/pay.html");
                            layer.close(index);
                        }, function(index) {
                            layer.close(index);
                        });
                    }else{
                        layer.msg(res.msg,{shift:6});
                    }
                }
            });
        }
    });
    $('body').on('click','.tpldown',function(){
        var index = layer.load();
        var token = Admin.get_cookie('mccms_tpl_token');
        $.post($(this).data('url'),{token:token},function(res) {
            layer.close(index);
            if(res.code == 1){
                layer.msg(res.msg,{icon: 1});
                setTimeout(function() {
                    window.location.href = res.url;
                }, 1000);
            }else{
                layer.msg(res.msg,{icon: 2,shift:6});
            }
        },'json');
    });
});
</script>
</body>
</html>
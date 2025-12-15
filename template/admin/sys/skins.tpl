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
                                    <?php if($skins['pagejs'] > 1){ ?>
                                    <div style="display:flex;justify-content: center;align-items: center;">
                                        <?php if($page > 1){ ?>
                                        <a class="tpltab" style="padding: 8px 30px;background: #e8e7e7;border-radius: 5px;margin-right:20px;" href="<?=links('skins/index/'.$cid.'/'.($page-1))?>"><i style="vertical-align:middle;" class="layui-icon layui-icon-prev"></i> 加载上一页</a>
                                        <?php } if($skins['pagejs'] > $page){ ?>
                                        <a class="tpltab" style="padding: 8px 30px;background: #e8e7e7;border-radius: 5px;margin-left:20px;" href="<?=links('skins/index/'.$cid.'/'.($page+1))?>">加载下一页 <i style="vertical-align:middle;" class="layui-icon layui-icon-next"></i></a>
                                        <?php } ?>
                                    </div>
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
            layer.open({
                type: 1,
                area: 'auto',
                fix: false, //不固定
                shadeClose: false,
                maxmin: false,
                shade: 0.2,
                title: '用户登录',
                content: '<div style="padding:10px 20px;min-width: 260px;"><fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;"><legend>登录崇胜账号</legend></fieldset><form class="layui-form" action=""><div class="layui-form-item"><input type="text" name="tel" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input"></div><div class="layui-form-item"><input type="password" name="pass" required  lay-verify="required" placeholder="请输入登录密码" autocomplete="off" class="layui-input"></div><div class="layui-form-item"><button type="button" class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="login">立即登录</button></div><div class="layui-form-item" style="text-align: center;"><a href="//www.chshsaas.net/user/reg.html" target="_blank">没有账号？去注册</a></div></div>'
            });
            form.on('submit(login)', function(data){
                var index = layer.load();
                $.getJSON('<?=base64decode(Apiurl)?>/skins/log?tel='+data.field.tel+'&pass='+data.field.pass+'&callback=?',function(res) {
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
            var index = layer.load(),timer = null;
            $.getJSON('<?=base64decode(Apiurl)?>/skins/pay?id='+id+'&token='+token+'&callback=?',function(res) {
                layer.close(index);
                if(res.code == 1){
                    layer.msg(res.msg,{icon: 1});
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }else{
                    if(res.code == 2){
                        layer.open({
                            type: 1,
                            area: 'auto',
                            fix: false, //不固定
                            shadeClose: false,
                            maxmin: false,
                            shade: 0.2,
                            title: '购买模板下单',
                            content: '<div style="padding:20px;min-width: 260px;text-align: center;"><h3 style="font-size: 16px;line-height: 50px;font-weight: 600;color: #111;">'+res.data.row.name+'</h3><p style="font-size: 20px;color: red;margin-top: 5px;">￥'+res.data.row.rmb+'</p><p style="margin: 10px auto;width: 150px;height: 150px;border-radius: 4px;overflow: hidden;"><img width="100%" src="'+res.data.row.pay_qrcode+'"></p><p>请用支付宝或者微信扫一扫支付</p></div>',
                            success: function(layero, index){
                                function get_ispay(){
                                    $.getJSON('<?=base64decode(Apiurl)?>/skins/ispay?id='+id+'&token='+token+'&callback=?',function(res) {
                                        if(res.code == 1){
                                            layer.msg(res.msg,{icon: 1});
                                            setTimeout(function() {
                                                window.location.reload();
                                            }, 1000);
                                        }else{
                                            timer = setTimeout(function() {
                                                get_ispay();
                                            },3000);
                                        }
                                    },'json');
                                }
                                timer = setTimeout(function() {
                                    get_ispay();
                                },3000);
                            },
                            cancel: function(index, layero){ 
                                clearTimeout(timer);
                            }
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
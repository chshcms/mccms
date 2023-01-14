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
                    <li<?php if($tabid == 'pc') echo ' class="layui-this"';?>>电脑模版</li>
                    <li<?php if($tabid == 'wap') echo ' class="layui-this"';?>>手机模版</li>
                    <li><a onclick="layer.load();" href="<?=links('skins','index')?>">模版中心</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item<?php if($tabid == 'pc') echo ' layui-show';?>">
                        <div class="layui-text" style="padding-top:5px;margin-bottom:20px;">
                            <div class="layui-row">
                                <div style="padding: 10px;">
                                    <?php foreach($pc as $row){ ?>
                                    <div class="layui-col-xs6 layui-col-sm4 layui-col-md2">
                                        <div class="comic-skin center">
                                            <div class="pic" style="position: relative;">
                                            	<img src="<?=$row['pic']?>">
                                            <?php if($row['init'] == 0){ ?>
                                            	<div class="layui-btn layui-btn-xs layui-btn-danger" style="position: absolute;top: 3px;right: 3px;padding: 0px 4px;" onclick="Admin.skin_del('<?=links('setting','skins_del','wap')?>','<?=$row['path']?>');"><i class="layui-icon layui-icon-delete" style="font-size: 18px;"></i></div>
                                            <?php } ?>
                                            </div>
                                            <div class="text"><?=$row['name']?></div>
                                            <div class="cmd">
                                            <?php if($row['init'] == 0){ ?>
                                                <div class="layui-btn layui-btn-xs layui-btn-radius" onclick="Admin.skin_init('<?=links('setting','skins_init','pc')?>','<?=$row['path']?>');">设为默认</div>
                                            <?php }else{ ?>
                                                <div class="layui-btn layui-btn-xs layui-btn-radius layui-btn-disabled">默认模版</div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item<?php if($tabid == 'wap') echo ' layui-show';?>">
                        <div class="layui-text" style="padding-top:5px;margin-bottom:20px;">
                            <div class="layui-row">
                                <div style="padding: 10px;">
                                    <?php foreach($wap as $row){ ?>
                                    <div class="layui-col-xs6 layui-col-sm4 layui-col-md2">
                                        <div class="comic-skin center">
                                            <div class="pic" style="position: relative;">
                                            	<img src="<?=$row['pic']?>">
                                            <?php if($row['init'] == 0){ ?>
                                            	<div class="layui-btn layui-btn-xs layui-btn-danger" style="position: absolute;top: 3px;right: 3px;padding: 0px 4px;" onclick="Admin.skin_del('<?=links('setting','skins_del','wap')?>','<?=$row['path']?>');"><i class="layui-icon layui-icon-delete" style="font-size: 18px;"></i></div>
                                            <?php } ?>
                                            </div>
                                            <div class="text"><?=$row['name']?></div>
                                            <div class="cmd">
                                            <?php if($row['init'] == 0){ ?>
                                                <div class="layui-btn layui-btn-xs layui-btn-radius" onclick="Admin.skin_init('<?=links('setting','skins_init','wap')?>','<?=$row['path']?>');">设为默认</div>
                                            <?php }else{ ?>
                                                <div class="layui-btn layui-btn-xs layui-btn-radius layui-btn-disabled">默认模版</div>
                                            <?php } ?>
                                            </div>
                                        </div>
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
</body>
</html>
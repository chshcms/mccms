<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>APP配置</title>
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
        <a>APP管理</a>
        <a><cite>APP配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form" action="<?=links('app','setting')?>">
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief" lay-filter="setting">
                    <ul class="layui-tab-title">
                        <li lay-id="t1" class="layui-this">更新配置</li>
                        <li lay-id="t2">充值配置</li>
                        <li lay-id="t3">热搜关键词</li>
                        <li lay-id="t4">用户协议</li>
                        <li lay-id="t5">隐私政策</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-collapse" lay-accordion="">
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;">全局APP接口秘钥</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label layui-form-required">接口秘钥:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="apikey" placeholder="接口秘钥，留空为关闭" value="<?=$app['apikey']?>" class="layui-input"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;">安卓版本配置</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label layui-form-required">最新版本号:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="update[android][version]" placeholder="最新版本号，如：1.0.5" value="<?=$app['update']['android']['version']?>" class="layui-input" lay-verify="required" required/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label layui-form-required">下载地址:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="update[android][downurl]" placeholder="安卓APK包下载地址" value="<?=$app['update']['android']['downurl']?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">强制更新:</label>
                                                <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                                    <input lay-filter="none" xs="no" type="radio" name="update[android][force]" value="1" title="开启"<?php if($app['update']['android']['force'] == 1) echo ' checked';?>>
                                                    <input lay-filter="none" xs="yes" type="radio" name="update[android][force]" value="0" title="关闭"<?php if($app['update']['android']['force'] == 0) echo ' checked';?>>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">更新文案:</label>
                                                <div class="layui-input-block">
                                                    <textarea style="min-height:120px;" name="update[android][text]" placeholder="新版本更新文案" class="layui-textarea"><?=$app['update']['android']['text']?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;">IOS版本配置</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label layui-form-required">最新版本号:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="update[ios][version]" placeholder="最新版本号，如：1.0.5" value="<?=$app['update']['ios']['version']?>" class="layui-input" lay-verify="required" required/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label layui-form-required">下载地址:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="update[ios][downurl]" placeholder="IOS应用商店下载地址" value="<?=$app['update']['ios']['downurl']?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">强制更新:</label>
                                                <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                                    <input lay-filter="none" xs="no" type="radio" name="update[ios][force]" value="1" title="开启"<?php if($app['update']['ios']['force'] == 1) echo ' checked';?>>
                                                    <input lay-filter="none" xs="yes" type="radio" name="update[ios][force]" value="0" title="关闭"<?php if($app['update']['ios']['force'] == 0) echo ' checked';?>>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">更新文案:</label>
                                                <div class="layui-input-block">
                                                    <textarea style="min-height:120px;" name="update[ios][text]" placeholder="新版本更新文案" class="layui-textarea"><?=$app['update']['ios']['text']?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120" style="margin-top:10px;">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="*" lay-submit>
                                            更新配置信息
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-collapse" lay-accordion="">
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;">VIP充值套餐</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                            <?php foreach($app['pay']['vip'] as $i=>$row){ ?>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label">天数：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[vip][<?=$i?>][day]" placeholder="VIP天数" value="<?=$row['day']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label">价格：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[vip][<?=$i?>][rmb]" placeholder="需要金额" value="<?=$row['rmb']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;"><?=Pay_Cion_Name?>充值套餐</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                            <?php foreach($app['pay']['cion'] as $i=>$row){ ?>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label"><?=Pay_Cion_Name?>：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[cion][<?=$i?>][cion]" placeholder="<?=Pay_Cion_Name?>数量" value="<?=$row['cion']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label">价格：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[cion][<?=$i?>][rmb]" placeholder="需要金额" value="<?=$row['rmb']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title" style="font-size:14px;">月票充值套餐</h2>
                                        <div class="layui-colla-content layui-show">
                                            <div class="layui-form-item w120">
                                            <?php foreach($app['pay']['ticket'] as $i=>$row){ ?>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label">数量：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[ticket][<?=$i?>][nums]" placeholder="月票数量" value="<?=$row['nums']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                                <div class="layui-col-xs6 layui-col-md6" style="margin-bottom:10px;">
                                                    <label class="layui-form-label">价格：</label>
                                                    <div class="layui-input-block">
                                                        <input type="number" name="pay[ticket][<?=$i?>][rmb]" placeholder="需要金额" value="<?=$row['rmb']?>" class="layui-input" lay-verify="required" required/>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120" style="margin-top:10px;">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="*" lay-submit>
                                            更新配置信息
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">漫画热搜词:</label>
                                    <div class="layui-input-block">
                                        <textarea style="min-height:120px;" name="search" placeholder="漫画热搜词，多个用|分割" class="layui-textarea"><?=implode('|', $app['search'])?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">小说热搜词:</label>
                                    <div class="layui-input-block">
                                        <textarea style="min-height:120px;" name="book_search" placeholder="小说热搜词，多个用|分割" class="layui-textarea"><?=implode('|', $app['book_search'])?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="*" lay-submit>
                                            更新配置信息
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-text" style="padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">用户协议:</label>
                                    <div class="layui-input-block">
                                        <textarea lay-verify="editor1" id="editor1" name="html[agreement]" placeholder="用户协议" class="layui-textarea"><?=$app['html']['agreement']?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="*" lay-submit>
                                            更新配置信息
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-tab-item">
                            <div class="layui-form layui-text" style="padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">隐私政策:</label>
                                    <div class="layui-input-block">
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <textarea lay-verify="editor2" id="editor2" name="html[privacy]" placeholder="隐私政策" class="layui-textarea"><?=$app['html']['privacy']?></textarea>
                                    </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-filter="*" lay-submit>
                                            更新配置信息
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
layui.use(['layedit', 'layer', 'form'], function () {
    var layedit = layui.layedit,
        form = layui.form,
        layer = layui.layer;
    layedit.set({
        tool: [
            'html', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'colorpicker', 'face'
            , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'anchors'
            , '|','table', 'fullScreen'
        ]
        ,height: '500px'
    });
    var editor1 = layedit.build('editor1');
    var editor2 = layedit.build('editor2');
    form.verify({
        editor1: function(value) {
            layedit.sync(editor1);
        },
        editor2: function(value) {
            layedit.sync(editor2);
        }
    });
    var tps = '';
    $('.layui-input,.layui-textarea').click(function(){
        if($(this).attr('placeholder') != tps){
            tps = $(this).attr('placeholder');
            layer.tips(tps, $(this),{tips:1});    
        }
    });
})
</script>
</body>
</html>
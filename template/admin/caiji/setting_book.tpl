<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>采集配置</title>
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
        .layui-form-radio{
            margin: 0; 
            padding-right: 0;
        }
        .layui-form-item{
            margin-bottom: 5px;
        }
        .layui-form-pane .layui-form-checkbox {
            margin: 4px 0 4px 1px;
        }
        .layui-form-checkbox[lay-skin=primary] span {
            padding-right: 5px;
        }
        .layui-form-checkbox[lay-skin=primary] i {
            left: 6px;
        }
    </style>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>采集管理</a>
        <a><cite>采集配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="Admin.get_load();" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div class="layui-tab layui-tab-brief">
                <ul class="layui-tab-title">
                    <li><a href="<?=links('caiji','index',$type)?>">资源中心</a></li>
                    <li class="layui-this"><a href="<?=links('caiji','setting',$type)?>">采集配置</a></li>
                    <li><a href="<?=links('caiji','timming',$type)?>">定时任务</a></li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form layui-form-pane" action="<?=links('caiji','book_save')?>">
                            <div class="layui-row layui-col-space10" style="padding-top:5px;">
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">入库状态</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Sh" value="0" title="已审"<?php if(Book_Caiji_Sh == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Sh" value="1" title="待审"<?php if(Book_Caiji_Sh == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">同步缩略图</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Pic" value="0" title="关闭"<?php if(Book_Caiji_Pic == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Pic" value="1" title="开启"<?php if(Book_Caiji_Pic == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">入库时间</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Time" value="0" title="本机"<?php if(Book_Caiji_Time == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Time" value="1" title="来源"<?php if(Book_Caiji_Time == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">过滤空章节</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Chapter" value="0" title="关闭"<?php if(Book_Caiji_Chapter == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Chapter" value="1" title="开启"<?php if(Book_Caiji_Chapter == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">更新规则</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Up" value="0" title="更新所有"<?php if(Book_Caiji_Up == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Up" value="1" title="大于更新"<?php if(Book_Caiji_Up == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">随机人气</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Hits" value="0" title="关闭"<?php if(Book_Caiji_Hits == 0) echo 'checked';?>>
                                            <input type="radio" name="Book_Caiji_Hits" value="1" title="开启"<?php if(Book_Caiji_Hits == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md6">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">同步章节</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Tb_Chapter" value="0" title="浏览网页同步"<?php if(Book_Caiji_Tb_Chapter == 0) echo 'checked';?>>
                                            <input data-txt="章节采集时入库较慢，请谨慎开启" lay-filter="type" type="radio" name="Book_Caiji_Tb_Chapter" value="1" title="采集入库同步"<?php if(Book_Caiji_Tb_Chapter == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md6">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">同步TXT</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Book_Caiji_Tb_Txt" value="0" title="浏览网页同步"<?php if(Book_Caiji_Tb_Txt == 0) echo 'checked';?>>
                                            <input data-txt="章节采集时入库较慢，请谨慎开启" lay-filter="type" type="radio" name="Book_Caiji_Tb_Txt" value="1" title="采集入库同步"<?php if(Book_Caiji_Tb_Txt == 1) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">入库重检测</label>
                                        <div class="layui-input-block">
                                            <?php $carr = explode('|',Book_Caiji_Inspect);?>
                                            <input type="checkbox" name="Book_Caiji_Inspect[name]" lay-skin="primary" title="标题" disabled checked>
                                            <input type="checkbox" name="Book_Caiji_Inspect[cid]" lay-skin="primary" title="分类" <?php if(in_array('cid',$carr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Inspect[serialize]" lay-skin="primary" title="状态" <?php if(in_array('serialize',$carr)) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">解析地址</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Book_Caiji_Tb_Url" class="layui-input" value="<?=Book_Caiji_Tb_Url?>" placeholder="请输入同步解析的地址">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md4">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">解析Token</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Book_Caiji_Tb_Token" class="layui-input" value="<?=Book_Caiji_Tb_Token?>" placeholder="请输入同步解析的Token">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md12">
                                    <div class="layui-form-item" pane>
                                        <label class="layui-form-label">更新内容</label>
                                        <div class="layui-input-block">
                                            <?php $uarr = explode('|',Book_Caiji_Upzd);?>
                                            <input type="checkbox" name="Book_Caiji_Upzd[chapter]" lay-skin="primary" title="章节" disabled checked>
                                            <input type="checkbox" name="Book_Caiji_Upzd[serialize]" lay-skin="primary" title="状态" disabled checked>
                                            <input type="checkbox" name="Book_Caiji_Upzd[addtime]" lay-skin="primary" title="时间" <?php if(in_array('addtime',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[yname]" lay-skin="primary" title="英文别名" <?php if(in_array('yname',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[cid]" lay-skin="primary" title="分类" <?php if(in_array('cid',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[tags]" lay-skin="primary" title="Tags" <?php if(in_array('tags',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[author]" lay-skin="primary" title="作者" <?php if(in_array('author',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[pic]" lay-skin="primary" title="缩略图" <?php if(in_array('pic',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[text_num]" lay-skin="primary" title="总字数" <?php if(in_array('text_num',$uarr)) echo 'checked';?>>
                                            <input type="checkbox" name="Book_Caiji_Upzd[content]" lay-skin="primary" title="介绍" <?php if(in_array('content',$uarr)) echo 'checked';?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md6">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">人气范围</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="Book_Caiji_Hits_Ks" placeholder="随机人气最小值" autocomplete="off" class="layui-input" value="<?=Book_Caiji_Hits_Ks?>">
                                        </div>
                                        <div class="layui-form-mid">-</div>
                                        <div class="layui-input-inline">
                                            <input type="number" name="Book_Caiji_Hits_Js" placeholder="随机人气最大值" autocomplete="off" class="layui-input" value="<?=Book_Caiji_Hits_Js?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md6">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">标题替换</label>
                                        <div class="layui-input-block">
                                            <textarea name="Book_Caiji_Replace_name" placeholder="格式：采集标题->替换后名字,多个使用“|”号分割" class="layui-textarea"><?=Book_Caiji_Replace_name?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md12">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">标题过滤</label>
                                        <div class="layui-input-block">
                                            <textarea name="Book_Caiji_Filter_name" placeholder="多个数据使用“|”号分割, 过滤的数据将不会新增和更新" class="layui-textarea"><?=Book_Caiji_Filter_name?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-xs12 layui-col-md12">
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-filter="*" lay-submit>
                                                更新配置信息
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
layui.use(['layer', 'form'], function () {
    var form = layui.form,
        layer = layui.layer;
    //选择
    form.on('radio(type)', function (r) {
        var tps = $(r.elem).attr('data-txt');
        if(r.elem.checked){
            layer.tips(tps, $(r.elem).parents('div'),{tips:4});
        }
    });
});
</script>
</body>
</html>
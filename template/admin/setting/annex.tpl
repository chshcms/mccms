<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>附件配置</title>
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
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>系统配置</a>
        <a><cite>附件配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form" action="<?=links('setting','annex_save')?>">
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief" lay-filter="setting">
                    <ul class="layui-tab-title">
                        <li class="layui-this">基本配置</li>
                        <li>水印配置</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">附件存储目录:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Annex_Dir" placeholder="附件存储目录：相对于网站根目录" value="<?=Annex_Dir?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">附件路径格式:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Annex_Path" placeholder="附件路径格式，可以使用年、月、日" value="<?=Annex_Path?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">附件支持类型:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Annex_Ext" placeholder="“|”分割（BMP和GIF图片无法增加水印）" value="<?=Annex_Ext?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">附件支持大小:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Annex_Size" placeholder="单张图片支持最大，单位k" value="<?=Annex_Size?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">附件存储方式:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="mode" type="radio" name="Annex_Mode" value="0" title="站内存储"<?php if(Annex_Mode == 0) echo ' checked';?>>
                                        <input lay-filter="mode" type="radio" name="Annex_Mode" value="1" title="FTP存储"<?php if(Annex_Mode == 1) echo ' checked';?>>
                                        <input lay-filter="mode" type="radio" name="Annex_Mode" value="2" title="阿里云oss"<?php if(Annex_Mode == 2) echo ' checked';?>>
                                        <input lay-filter="mode" type="radio" name="Annex_Mode" value="3" title="七牛云"<?php if(Annex_Mode == 3) echo ' checked';?>>
                                        <input lay-filter="mode" type="radio" name="Annex_Mode" value="4" title="又拍云"<?php if(Annex_Mode == 4) echo ' checked';?>>
                                    </div>
                                </div>
                                <div id="mode1"<?php if(Annex_Mode != 1) echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP服务器:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_Host" placeholder="FTP服务器地址" value="<?=Annex_Ftp_Host?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP端口:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_Port" placeholder="FTP端口，一般21" value="<?=Annex_Ftp_Port?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP账号:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_User" placeholder="FTP登陆账号" value="<?=Annex_Ftp_User?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP密码:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_Pass" placeholder="FTP登陆密码" value="<?=get_pass(Annex_Ftp_Pass)?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP保存目录:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_Dir" placeholder="FTP保存目录，留空未FTP根目录" value="<?=Annex_Ftp_Dir?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">FTP访问地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Ftp_Url" placeholder="FTP访问域名地址，http://开头的" value="<?=Annex_Ftp_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="mode2"<?php if(Annex_Mode != 2) echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">Bucket名称:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Oss_Bucket" placeholder="您在阿里云创建的BUCKET" value="<?=Annex_Oss_Bucket?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">ACCESS_ID:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Oss_Aid" placeholder="您在阿里云的ACCESS_ID" value="<?=Annex_Oss_Aid?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">ACCESS_KEY:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Oss_Key" placeholder="您在阿里云的ACCESS_KEY" value="<?=Annex_Oss_Key?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">EndPoint地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Oss_End" placeholder="您的EndPoint地址" value="<?=Annex_Oss_End?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">Oss访问地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Oss_Url" placeholder="您的Bucket域名访问地址" value="<?=Annex_Oss_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="mode3"<?php if(Annex_Mode != 3) echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">七牛空间名:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Qniu_Name" placeholder="您在七牛创建的空间名称" value="<?=Annex_Qniu_Name?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">AccessKey:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Qniu_Ak" placeholder="您在七牛的AccessKey" value="<?=Annex_Qniu_Ak?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">SecretKey:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Qniu_Sk" placeholder="您在七牛的SecretKey" value="<?=Annex_Qniu_Sk?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">七牛云访问地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Qniu_Url" placeholder="七牛云访问域名地址" value="<?=Annex_Qniu_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="mode4"<?php if(Annex_Mode != 4) echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">又拍云空间名:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Up_Name" placeholder="又拍云空间名称" value="<?=Annex_Up_Name?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">又拍云账号:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Up_User" placeholder="又拍云授权账号" value="<?=Annex_Up_User?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">又拍云密码:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Up_Pass" placeholder="又拍云授权密码" value="<?=Annex_Up_Pass?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">又拍云访问地址:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Annex_Up_Url" placeholder="又拍云访问域名地址" value="<?=Annex_Up_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="mode0"<?php if(Annex_Mode == 0) echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">保留站内图片:</label>
                                        <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                            <input type="radio" name="Annex_Pic_Del" value="0" title="删除"<?php if(Annex_Pic_Del == 0) echo ' checked';?>>
                                            <input type="radio" name="Annex_Pic_Del" value="1" title="保留"<?php if(Annex_Pic_Del == 1) echo ' checked';?>>
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
                        <div class="layui-tab-item">
                            <div class="layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">水印类型:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="pic" type="radio" name="Img_Type" value="" title="关闭水印"<?php if(Img_Type == '') echo ' checked';?>>
                                        <input lay-filter="pic" type="radio" name="Img_Type" value="text" title="文字水印"<?php if(Img_Type == 'text') echo ' checked';?>>
                                        <input lay-filter="pic" type="radio" name="Img_Type" value="overlay" title="图片水印"<?php if(Img_Type == 'overlay') echo ' checked';?>>
                                    </div>
                                </div>
                                <div id="pic0"<?php if(Img_Type == '') echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印内边距:</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="Img_Padding" placeholder="内边距，以像素为单位，是水印与图片边缘之间的距离" value="<?=Img_Padding?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印垂直位置:</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Img_Vrt" value="top" title="顶部"<?php if(Img_Vrt == 'top') echo ' checked';?>>
                                            <input type="radio" name="Img_Vrt" value="middle" title="中间"<?php if(Img_Vrt == 'middle') echo ' checked';?>>
                                            <input type="radio" name="Img_Vrt" value="bottom" title="底部"<?php if(Img_Vrt == 'bottom') echo ' checked';?>>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印垂直偏移量:</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="Img_Vrt_Offset" placeholder="垂直偏移量（以像素为单位）" value="<?=Img_Vrt_Offset?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印水平位置:</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="Img_Hor" value="left" title="左边"<?php if(Img_Hor == 'left') echo ' checked';?>>
                                            <input type="radio" name="Img_Hor" value="center" title="中间"<?php if(Img_Hor == 'center') echo ' checked';?>>
                                            <input type="radio" name="Img_Hor" value="right" title="右边"<?php if(Img_Hor == 'right') echo ' checked';?>>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印水平偏移量:</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="Img_Hor_Offset" placeholder="水平偏移量（以像素为单位）" value="<?=Img_Hor_Offset?>" class="layui-input"/>
                                        </div>
                                    </div>
                                </div>
                                <div id="pic1"<?php if(Img_Type != 'text') echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印文字内容:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Img_Text_Txt" placeholder="水印文字内容" value="<?=Img_Text_Txt?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印字体路径:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Img_Text_Ttf" placeholder="TTF格式字体路径，留空系统将使用原生的GD字体" value="<?=Img_Text_Ttf?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印字体大小:</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="Img_Text_Size" placeholder="默认字体1-5,TTF字体可以使用任意有效的字体大小" value="<?=Img_Text_Size?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印字体颜色:</label>
                                        <div class="layui-input-inline">
                                            <input id="color-input1" type="text" name="Img_Text_Color" placeholder="字体颜色,6位数的十六进制值如：993300" value="<?=Img_Text_Color?>" class="layui-input"/>
                                        </div>
                                        <div class="layui-inline" style="left:-11px;">
                                            <div id="color-form1"></div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印阴影颜色:</label>
                                        <div class="layui-input-inline">
                                            <input id="color-input2" type="text" name="Img_Text_Shadow_Color" placeholder="阴影颜色,6位数的十六进制值如：993300，留空则关闭阴影" value="<?=Img_Text_Shadow_Color?>" class="layui-input"/>
                                        </div>
                                        <div class="layui-inline" style="left:-11px;">
                                            <div id="color-form2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="pic2"<?php if(Img_Type != 'overlay') echo ' style="display: none;"';?>>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印图片路径:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Img_Pic_Path" placeholder="服务器图片水印路径，默认在./packs/watermark.png" value="<?=Img_Pic_Path?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">水印图像透明度:</label>
                                        <div class="layui-input-block">
                                            <input type="number" name="Img_Pic_Opacity" placeholder="图像透明度，这将使水印模糊化，从而不会掩盖住底层原始图片，通常设置为 50" value="<?=Img_Pic_Opacity?>" class="layui-input"/>
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
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
layui.use(['form','colorpicker'],function() {
    var form = layui.form,colorpicker = layui.colorpicker;
    //颜色选择
    colorpicker.render({
        elem: '#color-form1',
        color: '#<?=Img_Text_Color?>',
        done: function(color){
            $('#color-input1').val(color.replace('#',''));
        }
    });
    colorpicker.render({
        elem: '#color-form2',
        color: '#<?=Img_Text_Shadow_Color?>',
        done: function(color){
            $('#color-input2').val(color.replace('#',''));
        }
    });
    //监听radio
    form.on('radio(mode)', function (data) {
        for (var i = 1; i < 5; i++) {
            if(data.value == i){
                $('#mode'+i).show();
            }else{
                $('#mode'+i).hide();
            }
        }
        if(data.value == 0){
            $('#mode0').hide();
        }else{
            $('#mode0').show();
        }
    });
    form.on('radio(pic)', function (data) {
        if(data.value == 'overlay'){
            $('#pic0').show();
            $('#pic1').hide();
            $('#pic2').show();
        } else if(data.value == 'text'){
            $('#pic0').show();
            $('#pic1').show();
            $('#pic2').hide();
        } else {
            $('#pic0').hide();
            $('#pic1').hide();
            $('#pic2').hide();
        }
    });
    var tps = '';
    $('.layui-input,.layui-textarea').click(function(){
        if($(this).attr('placeholder') != tps){
            tps = $(this).attr('placeholder');
            layer.tips(tps, $(this),{tips:1});    
        }
    });
});
</script>
</body>
</html>
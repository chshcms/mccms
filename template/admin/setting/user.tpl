<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>用户配置</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <script src="<?=Web_Base_Path?>layui/ace/ace.js"></script>
</head>
<body>
<div class="breadcrumb-nav">
    <span class="layui-breadcrumb">
        <a>系统配置</a>
        <a><cite>用户配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form" action="<?=links('setting','user_save')?>">
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief" lay-filter="setting">
                    <ul class="layui-tab-title">
                        <li lay-id="t1" class="layui-this">读者配置</li>
                        <li lay-id="t2">作者配置</li>
                        <li lay-id="t3">邮件配置</li>
                        <li lay-id="t4">邮件发送</li>
                        <li lay-id="t5">短信配置</li>
                        <li lay-id="t6">第三方登陆</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">注册开关:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="User_Reg" value="0" title="开启"<?php if(User_Reg == 0) echo ' checked';?>>
                                        <input type="radio" name="User_Reg" value="1" title="关闭"<?php if(User_Reg == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">注册验证:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="User_Reg_Tel" value="0" title="开启"<?php if(User_Reg_Tel == 0) echo ' checked';?>>
                                        <input type="radio" name="User_Reg_Tel" value="1" title="关闭"<?php if(User_Reg_Tel == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">默认会员组:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="User_Reg_Vip" value="0" title="普通会员"<?php if(User_Reg_Vip == 0) echo ' checked';?>>
                                        <input type="radio" name="User_Reg_Vip" value="1" title="Vip会员"<?php if(User_Reg_Vip == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">注册赠送<?=Pay_Cion_Name?>:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="User_Reg_Cion" placeholder="新用户注册赠送<?=Pay_Cion_Name?>" value="<?=User_Reg_Cion?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">注册赠送VIP:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="User_Reg_Vip_Day" placeholder="新用户注册赠送多少天VIP" value="<?=User_Reg_Vip_Day?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">推广访问<?=Pay_Cion_Name?>:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="User_Tg_Cion" placeholder="推广成功访问赠送<?=Pay_Cion_Name?>" value="<?=User_Tg_Cion?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">评论赠送<?=Pay_Cion_Name?>:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="User_Pl_Cion" placeholder="评论一次赠送的<?=Pay_Cion_Name?>数量" value="<?=User_Pl_Cion?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">评论赠送上限:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="User_Pl_Num" placeholder="每天评论最多赠送多少<?=Pay_Cion_Name?>" value="<?=User_Pl_Num?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">会员中心公告:</label>
                                    <div class="layui-input-block">
                                        <textarea style="min-height:100px;" name="User_Gg" placeholder="会员中心公告" class="layui-textarea"><?=User_Gg?></textarea>
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
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">作者开关:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="Author_Mode" value="0" title="开启"<?php if(Author_Mode == 0) echo ' checked';?>>
                                        <input type="radio" name="Author_Mode" value="1" title="关闭"<?php if(Author_Mode == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">作者认证:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="Author_Rz" value="0" title="开启"<?php if(Author_Rz == 0) echo ' checked';?>>
                                        <input type="radio" name="Author_Rz" value="1" title="关闭"<?php if(Author_Rz == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">提现最低金额:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Tx_Rmb" placeholder="作者收入提现最小金额" value="<?=Author_Tx_Rmb?>" class="layui-input" lay-verify="number"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">新增漫画赠送<?=Pay_Cion_Name?>:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Comic_Cion" placeholder="新增一部作品赠送<?=Pay_Cion_Name?>数量" value="<?=Author_Comic_Cion?>" class="layui-input" lay-verify="number"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">新增小说赠送<?=Pay_Cion_Name?>:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Book_Cion" placeholder="新增一部小说赠送<?=Pay_Cion_Name?>数量" value="<?=Author_Book_Cion?>" class="layui-input" lay-verify="number"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">打赏分成比例:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Fc_Ds" placeholder="打赏分成比例，百分比，0不分成" value="<?=Author_Fc_Ds?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">月票分成比例:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Fc_Yp" placeholder="月票分成比例，百分比，0不分成" value="<?=Author_Fc_Yp?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">漫画分成比例:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Fc_Comic" placeholder="漫画分成比例，百分比，0不分成" value="<?=Author_Fc_Comic?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">小说分成比例:</label>
                                    <div class="layui-input-block">
                                        <input type="number" name="Author_Fc_Book" placeholder="小说分成比例，百分比，0不分成" value="<?=Author_Fc_Book?>" class="layui-input" lay-verify="required" required/>
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
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">邮件发送方法:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="type" type="radio" name="Mail_Type" value="smtp" title="SMTP"<?php if(Mail_Type == 'smtp') echo ' checked';?>>
                                        <input lay-filter="type" type="radio" name="Mail_Type" value="mail" title="Mail"<?php if(Mail_Type == 'mail') echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">SMTP服务器:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_host" type="text" name="Mail_Host" placeholder="SMTP服务器地址" value="<?=Mail_Host?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">邮件发送端口:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_port" type="text" name="Mail_Port" placeholder="邮件发送端口，一般为25，QQ邮箱465" value="<?=Mail_Port?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">邮件发送名称:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_name" type="text" name="Mail_Name" placeholder="发送者简称，如：Mccms官方" value="<?=Mail_Name?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">发送邮件:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_email" type="text" name="Mail_Email" placeholder="填写完整邮箱" value="<?=Mail_Email?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">邮箱账号:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_user" type="text" name="Mail_User" placeholder="填写完整邮箱,QQ邮箱填写qq号码" value="<?=Mail_User?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">邮箱密码:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_pass" type="text" name="Mail_Pass" placeholder="邮箱密码，如果是qq邮箱填写授权码,163邮箱则填安全密码" value="<?=get_pass(Mail_Pass)?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">SMTP验证方法:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="crypto" type="radio" name="Mail_Crypto" value="" title="无"<?php if(Mail_Crypto == '') echo ' checked';?>>
                                        <input lay-filter="crypto" type="radio" name="Mail_Crypto" value="tls" title="TLS"<?php if(Mail_Crypto == 'tls') echo ' checked';?>>
                                        <input lay-filter="crypto" type="radio" name="Mail_Crypto" value="ssl" title="SSL"<?php if(Mail_Crypto == 'ssl') echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">测试接收邮件:</label>
                                    <div class="layui-input-block">
                                        <input id="mail_tomail" type="text" name="Mail_Demo" placeholder="填写完整邮箱" value="<?=Mail_Demo?>" class="layui-input"/>
                                        <div class="layui-btn layui-btn-normal" onclick="mail_demo();" style="position: absolute;top: 0px;right: 0;">发送测试邮件</div>
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
                                    <label class="layui-form-label">验证码发送标题:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Mail_Code_Title" placeholder="验证码发送邮件标题" value="<?=Mail_Code_Title?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">验证码发送内容:</label>
                                    <div class="layui-input-block">
                                        <textarea lay-verify="editor1" id="editor1" name="Mail_Code_Msg" placeholder="验证码发送内容" class="layui-textarea"><?=Mail_Code_Msg?></textarea>
                                        <div class="layui-form-mid layui-word-aux">
                                            网站名称：<a class="mccms-label" href="javascript:" data-id="1" data-name="{site_name}" title="网站名称">{web_name}</a> 
                                            网站地址：<a class="mccms-label" href="javascript:" data-id="1" data-name="{web_url}" title="网站地址">{web_url}</a> 
                                            操作类型：<a class="mccms-label" href="javascript:" data-id="1" data-name="{type}" title="操作类型">{type}</a> 
                                            验证码：<a class="mccms-label" href="javascript:" data-id="1" data-name="{code}" title="验证码">{code}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">订阅发送邮件:</label>
                                    <div class="layui-input-block">
                                        <select name="Mail_Remind">
                                            <option value="1">开启</option>
                                            <option value="0"<?php if(Mail_Remind==0) echo ' selected';?>>关闭</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">订阅发送标题:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Mail_Remind_Title" placeholder="订阅发送标题" value="<?=Mail_Remind_Title?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">订阅发送内容:</label>
                                    <div class="layui-input-block">
                                        <textarea lay-verify="editor2" id="editor2" name="Mail_Remind_Msg" placeholder="订阅发送内容" class="layui-textarea" style="min-height: 400px;"><?=Mail_Remind_Msg?></textarea>
                                        <div class="layui-form-mid layui-word-aux">
                                            网站名称：<a class="mccms-label" href="javascript:" data-id="2" data-name="{site_name}" title="网站名称">{web_name}</a> 
                                            网站地址：<a class="mccms-label" href="javascript:" data-id="2" data-name="{web_url}" title="网站地址">{web_url}</a> 
                                            用户昵称：<a class="mccms-label" href="javascript:" data-id="2" data-name="{user_nichen}" title="用户昵称">{user_nichen}</a>
                                            用户邮箱：<a class="mccms-label" href="javascript:" data-id="2" data-name="{user_email}" title="用户邮箱">{user_email}</a>
                                            漫画名称：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_name}" title="漫画名称">{comic_name}</a>
                                            章节名称：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_chapter_name}" title="最新章节名称">{comic_chapter_name}</a>
                                            漫画链接：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_url}" title="漫画链接">{comic_url}</a>
                                            漫画分类：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_cname}" title="漫画分类">{comic_cname}</a>
                                            漫画介绍：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_text}" title="漫画介绍">{comic_text}</a>
                                            漫画缩略图：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_pic}" title="漫画缩略图">{comic_pic}</a>
                                            漫画状态：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_serialize}" title="漫画状态">{comic_serialize}</a>
                                            更新时间：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_addtime}" title="漫画更新时间">{comic_addtime}</a>
                                            订阅地址：<a class="mccms-label" href="javascript:" data-id="2" data-name="{comic_remind_url}" title="漫画订阅地址">{comic_remind_url}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">提现发送邮件:</label>
                                    <div class="layui-input-block">
                                        <select name="Mail_Drawing">
                                            <option value="1">开启</option>
                                            <option value="0"<?php if(Mail_Drawing==0) echo ' selected';?>>关闭</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">提现发送标题:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Mail_Drawing_Title" placeholder="提现发送标题" value="<?=Mail_Drawing_Title?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">提现发送内容:</label>
                                    <div class="layui-input-block">
                                        <textarea lay-verify="editor3" id="editor3" name="Mail_Drawing_Msg" placeholder="提现发送内容" class="layui-textarea" style="min-height: 400px;"><?=Mail_Drawing_Msg?></textarea>
                                        <div class="layui-form-mid layui-word-aux">
                                            网站名称：<a class="mccms-label" href="javascript:" data-id="3" data-name="{site_name}" title="网站名称">{web_name}</a> 
                                            网站地址：<a class="mccms-label" href="javascript:" data-id="3" data-name="{web_url}" title="网站名称">{web_url}</a> 
                                            用户昵称：<a class="mccms-label" href="javascript:" data-id="3" data-name="{user_nichen}" title="用户昵称">{user_nichen}</a>
                                            用户邮箱：<a class="mccms-label" href="javascript:" data-id="3" data-name="{user_email}" title="用户邮箱">{user_email}</a>
                                            提现单号：<a class="mccms-label" href="javascript:" data-id="3" data-name="{drawing_dd}" title="提现单号">{drawing_dd}</a>
                                            提现状态：<a class="mccms-label" href="javascript:" data-id="3" data-name="{drawing_zt}" title="提现状态">{drawing_zt}</a>
                                            提现金额：<a class="mccms-label" href="javascript:" data-id="3" data-name="{drawing_rmb}" title="提现金额">{drawing_rmb}</a>
                                            提现时间：<a class="mccms-label" href="javascript:" data-id="3" data-name="{drawing_addtime}" title="提现时间">{drawing_addtime}</a>
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
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">短信服务商:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="sms" type="radio" name="Sms_Mode" value="0" title="关闭短信"<?php if(Sms_Mode == 0) echo ' checked';?>>
                                        <input lay-filter="sms" type="radio" name="Sms_Mode" value="1" title="阿里云短信"<?php if(Sms_Mode == 1) echo ' checked';?>>
                                        <input lay-filter="sms" type="radio" name="Sms_Mode" value="2" title="腾讯云短信"<?php if(Sms_Mode == 2) echo ' checked';?>>
                                        <input lay-filter="sms" type="radio" name="Sms_Mode" value="3" title="聚合数据短信"<?php if(Sms_Mode == 3) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120" id="sms"<?php if(Sms_Mode == 3) echo ' style="display: none;"';?>>
                                    <label class="layui-form-label">AppId:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Appid" placeholder="腾讯云对应AppId，阿里云对应KeyId" value="<?=Sms_Appid?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">AppKey:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Appkey" placeholder="腾讯云对应AppKey，阿里云对应KeySecret，聚合数据对应AppKey" value="<?=Sms_Appkey?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">短信签名:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Name" placeholder="短信签名，如：漫城cms" value="<?=Sms_Name?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">注册登陆模版编号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Tpl_Log" placeholder="模板编号需要在服务商短信控制台中申请" value="<?=Sms_Tpl_Log?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">绑定手机模版编号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Tpl_Bind" placeholder="模板编号需要在服务商短信控制台中申请" value="<?=Sms_Tpl_Bind?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">找回密码模版编号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Sms_Tpl_Pass" placeholder="模板编号需要在服务商短信控制台中申请" value="<?=Sms_Tpl_Pass?>" class="layui-input"/>
                                    </div>
                                </div>
                                <blockquote class="layui-elem-quote layui-quote-nm">
                                    提示信息：<br>
                                    请务必按照短信接口服务商的要求做好短信签名和短信内容的设置。<br>
                                    腾讯云短信：https://cloud.tencent.com/product/sms<br>
                                    腾讯云短信模板例子：<br>
                                    尊敬的用户，您的验证码为：{1}，请勿泄漏于他人！<br>
                                    验证码为：{1}，您正在绑定手机，若非本人操作，请勿泄露。<br>
                                    验证码为：{1}，您正在进行密码重置操作，如非本人操作，请忽略本短信！<br>
                                    阿里云短信：https://www.aliyun.com/product/sms<br>
                                    阿里云短信模板例子：<br>
                                    尊敬的用户，您的验证码为：${code}，请勿泄漏于他人！<br>
                                    验证码为：${code}，您正在绑定手机，若非本人操作，请勿泄露。<br>
                                    验证码为：${code}，您正在进行密码重置操作，如非本人操作，请忽略本短信！
                                </blockquote>
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
                            <div class="layui-form layui-text" style="max-width: 700px;padding-top:5px;">
                                <div class="layui-tab">
                                    <ul class="layui-tab-title">
                                        <li class="layui-this">腾讯QQ</li>
                                        <li>微信扫码</li>
                                        <li>新浪微博</li>
                                    </ul>
                                    <div class="layui-tab-content">
                                        <div class="layui-tab-item layui-show">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppId:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_QQ_Appid" placeholder="对应腾讯QQ AppId" value="<?=Land_QQ_Appid?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppKey:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_QQ_Appkey" placeholder="对应腾讯QQ AppKey" value="<?=Land_QQ_Appkey?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">QQ回调地址:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_QQ_Url" placeholder="一般只需要修改域名" value="<?=Land_QQ_Url==''?(is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/qq':Land_QQ_Url?>" class="layui-input"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-tab-item">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppId:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wx_Appid" placeholder="对应微信 AppId" value="<?=Land_Wx_Appid?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppKey:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wx_Appkey" placeholder="对应微信 AppKey" value="<?=Land_Wx_Appkey?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">微信回调地址:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wx_Url" placeholder="一般只需要修改域名" value="<?=Land_Wx_Url==''?(is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/weixin':Land_Wx_Url?>" class="layui-input"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-tab-item">
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppId:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wb_Appid" placeholder="对应新浪微博 AppId" value="<?=Land_Wb_Appid?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">AppKey:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wb_Appkey" placeholder="对应新浪微博 AppKey" value="<?=Land_Wb_Appkey?>" class="layui-input"/>
                                                </div>
                                            </div>
                                            <div class="layui-form-item w120">
                                                <label class="layui-form-label">微博回调地址:</label>
                                                <div class="layui-input-block">
                                                    <input type="text" name="Land_Wb_Url" placeholder="一般只需要修改域名" value="<?=Land_Wb_Url==''?(is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/weibo':Land_Wb_Url?>" class="layui-input"/>
                                                </div>
                                            </div>
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
var mail_type = '<?=Mail_Type?>';
var mail_crypto = '<?=Mail_Crypto?>';
layui.use(['layedit', 'layer', 'form'], function () {
    var layedit = layui.layedit,
        form = layui.form,
        layer = layui.layer;
    layedit.set({
        uploadImage: {
            url: '<?=links('ajax','upload')?>?dir=<?=sys_auth('editor')?>',
            accept: 'image',
            acceptMime: 'image/*',
            exts: '<?=Annex_Ext?>',
            size: '10240'
        }
        //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
        //传递参数：imgpath --图片路径
        ,calldel: {
            url: '<?=links('ajax','editor_delpic')?>'
        }
        ,tool: [
            'html', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'colorpicker', 'face'
            , '|', 'left', 'center', 'right', '|', 'link', 'unlink','images', 'image_alt', 'anchors'
            , '|','table', 'fullScreen'
        ]
        ,height: '90%'
    });
    var editor1 = layedit.build('editor1');
    var editor2 = layedit.build('editor2');
    var editor3 = layedit.build('editor3');
    //插入内容到编辑器
    $('.mccms-label').click(function(){
        var id= $(this).attr('data-id');
        var tpl= $(this).attr('data-name');
        layedit.setContent(eval('editor'+id), tpl, true);
    });
    form.on('radio(sms)', function (d) {
        if(d.value == '3'){
            $('#sms').hide();
        }else{
            $('#sms').show(); 
        }
    });
    //监听
    form.on('radio(crypto)', function (data) {
        mail_crypto = data.vaule;
    });
    form.on('radio(type)', function (data) {
        mail_type = data.vaule;
    });
    form.verify({
        editor1: function(value) {
            layedit.sync(editor1);
        },
        editor2: function(value) {
            layedit.sync(editor2);
        },
        editor3: function(value) {
            layedit.sync(editor3);
        }
    });
})
//发送测试邮件
function mail_demo(){
    var post = {type:mail_type,crypto:mail_crypto,host:$('#mail_host').val(),port:$('#mail_port').val(),user:$('#mail_user').val(),pass:$('#mail_pass').val(),form_mail:$('#mail_email').val(),form_name:$('#mail_name').val(),to_mail:$('#mail_tomail').val()};
    var index = layer.load();
    $.post('<?=links('ajax','mailadd')?>', post, function(res) {
        layer.close(index);
        if(res.code == 1){
            layer.msg(res.msg,{icon: 1});
        }else{
            layer.msg(res.msg,{icon: 2});
        }
    },'json');
}
</script>
</body>
</html>
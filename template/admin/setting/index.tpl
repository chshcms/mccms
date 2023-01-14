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
        <a><cite>网站配置</cite></a>
    </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新"><i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-card">
        <form class="layui-form" action="<?=links('setting','save')?>">
            <div class="layui-card-body">
                <div class="layui-tab layui-tab-brief" lay-filter="setting">
                    <ul class="layui-tab-title">
                        <li lay-id="t1" class="layui-this">基本配置</li>
                        <li lay-id="t2">安全配置</li>
                        <li lay-id="t3">留言评论</li>
                        <li lay-id="t4">URL配置</li>
                        <li lay-id="t5">SEO配置</li>
                        <li lay-id="t6">公众号配置</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="layui-text" style="max-width: 700px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">网站名称:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Name" placeholder="网站名称" value="<?=Web_Name?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">网站域名:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Url" placeholder="网站域名" value="<?=Web_Url?>" class="layui-input pdl60" lay-verify="required" required/>
                                        <div class="select-ssl">
                                            <select name="Web_Ssl_Mode">
                                                <option value="0">http://</option>
                                                <option value="1">https://</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">安装目录:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Path" placeholder="网站安装目录" value="<?=Web_Path?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">小说域名:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Book_Url" placeholder="小说版块域名独立域名，留空为漫画同一域名" value="<?=Web_Book_Url?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">网站开关:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="none" xs="no" type="radio" name="Web_Mode" value="0" title="开启"<?php if(Web_Mode == 0) echo ' checked';?>>
                                        <input lay-filter="none" xs="yes" type="radio" name="Web_Mode" value="1" title="关闭"<?php if(Web_Mode == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120" id="Web_Mode"<?php if(Web_Mode == 0) echo ' style="display: none;"';?>>
                                    <label class="layui-form-label">关闭提示:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Web_Close_Txt" placeholder="网站关闭提示，支持HTML" class="layui-textarea"><?=Web_Close_Txt?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">网站备案号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Icp" placeholder="网站备案号" value="<?=Web_Icp?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">统计代码:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Web_Stat" placeholder="网站统计代码" class="layui-textarea"><?=Web_Stat?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">小说主题标签:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Web_Book_Tags" placeholder="小说主题标签，多个用“|”隔开" class="layui-textarea"><?=Web_Book_Tags?></textarea>
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
                                    <label class="layui-form-label layui-form-required">后台认证码:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Admin_Code" placeholder="后台登陆认证码" value="<?=Admin_Code?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label layui-form-required">日志保存天数:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Admin_Log_Day" placeholder="管理员登陆日志保存天数" value="<?=Admin_Log_Day?>" class="layui-input" lay-verify="number"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">后台IP白名单:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Admin_Ip" placeholder="后台IP白名单，多个用“|”隔开，留空为不限制" class="layui-textarea"><?=Admin_Ip?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">外站入库密码:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Rkpass" placeholder="外站入库密码" value="<?=Web_Rkpass?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">客服QQ:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_QQ" placeholder="客服联系QQ" value="<?=Web_QQ?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">客服电话:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Tel" placeholder="客服联系电话" value="<?=Web_Tel?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">客服邮箱:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Mail" placeholder="客服联系邮箱" value="<?=Web_Mail?>" class="layui-input"/>
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
                                    <label class="layui-form-label">评论开关:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input type="radio" name="Pl_Mode" value="0" title="开启"<?php if(Pl_Mode == 0) echo ' checked';?>>
                                        <input type="radio" name="Pl_Mode" value="1" title="关闭"<?php if(Pl_Mode == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">评论间隔秒数:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Pl_Time" placeholder="评论间隔时间，小于则视为灌水" value="<?=Pl_Time?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">每人每天条数:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Pl_Add_Num" placeholder="每人每天最多评论多少条，0为不限制" value="<?=Pl_Add_Num?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">过滤关键字:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Pl_Str" placeholder="评论时过滤关键字多个用“|”隔开" class="layui-textarea" style="min-height: 100px;"><?=Pl_Str?></textarea>
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
                            <div class="layui-text" style="max-width: 900px;padding-top: 25px;">
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">运行模式:</label>
                                    <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                        <input lay-filter="none" nid="1" type="radio" name="Url_Mode" value="0" title="动态"<?php if(Url_Mode == 0) echo ' checked';?>>
                                        <input lay-filter="none" nid="2" type="radio" name="Url_Mode" value="1" title="静态"<?php if(Url_Mode == 1) echo ' checked';?>>
                                    </div>
                                </div>
                                <div id="Url_Mode1"<?php if(Url_Mode == 1) echo ' style="display: none;"';?>>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">漫画路由</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">分类页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Web_List" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>lists/[id]/[page]">1.lists/id/page</option>
                                                <option value="<?=Web_Path?>lists/[en]/[page]">2.lists/en/page</option>
                                                <option value="<?=Web_Path?>lists_[id]_[page].html">3.lists_id_page.html</option>
                                                <option value="<?=Web_Path?>lists_[en]_[page].html">4.lists_en_page.html</option>
                                                <option value="<?=Web_Path?>lists-[id]-[page].html">5.lists-id-page.html</option>
                                                <option value="<?=Web_Path?>lists-[en]-[page].html">6.lists-en-page.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Web_List" placeholder="分类路由URL，可用标签：[id]、[en]、[page]" value="<?=Url_Web_List?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">内容页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Web_Show" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>comic/[id]">1.comic/id</option>
                                                <option value="<?=Web_Path?>comic/[en]">2.comic/en</option>
                                                <option value="<?=Web_Path?>comic_[id].html">3.comic_id.html</option>
                                                <option value="<?=Web_Path?>comic_[en].html">4.comic_en.html</option>
                                                <option value="<?=Web_Path?>comic-[id].html">5.comic-id.html</option>
                                                <option value="<?=Web_Path?>comic-[en].html">6.comic-en.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Web_Show" placeholder="内容路由URL，可用标签：[id]、[en]" value="<?=Url_Web_Show?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">阅读页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Web_Pic" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>chapter/[mid]/[id]">1.chapter/mid/id</option>
                                                <option value="<?=Web_Path?>chapter_[mid]_[id].html">2.chapter_mid_id.html</option>
                                                <option value="<?=Web_Path?>chapter-[mid]-[id].html">3.chapter-mid-id.html</option>
                                                <option value="<?=Web_Path?>chapter/[id]">4.chapter/id</option>
                                                <option value="<?=Web_Path?>chapter_[id].html">5.chapter_id.html</option>
                                                <option value="<?=Web_Path?>chapter-[id].html">6.chapter-id.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Web_Pic" placeholder="阅读路由URL，可用标签：[mid]，[id]" value="<?=Url_Web_Pic?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">小说路由</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">分类页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Web_List" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/lists/[id]/[page]">1.book/lists/id/page</option>
                                                <option value="<?=Web_Path?>book/lists/[en]/[page]">2.book/lists/en/page</option>
                                                <option value="<?=Web_Path?>book_lists_[id]_[page].html">3.book_lists_id_page.html</option>
                                                <option value="<?=Web_Path?>book_lists_[en]_[page].html">4.book_lists_en_page.html</option>
                                                <option value="<?=Web_Path?>book-lists-[id]-[page].html">5.book-lists-id-page.html</option>
                                                <option value="<?=Web_Path?>book-lists-[en]-[page].html">6.book-lists-en-page.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Web_List" placeholder="分类路由URL，可用标签：[id]、[en]、[page]" value="<?=Url_Book_Web_List?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">内容页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Web_Info" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/info/[id]">1.book/info/id</option>
                                                <option value="<?=Web_Path?>book/info/[en]">2.book/info/en</option>
                                                <option value="<?=Web_Path?>book_info_[id].html">3.book_info_id.html</option>
                                                <option value="<?=Web_Path?>book_info_[en].html">4.book_info_en.html</option>
                                                <option value="<?=Web_Path?>book-info-[id].html">5.book-info-id.html</option>
                                                <option value="<?=Web_Path?>book-info-[en].html">6.book-info-en.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Web_Info" placeholder="内容路由URL，可用标签：[id]、[en]" value="<?=Url_Book_Web_Info?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">阅读页路由:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Web_Read" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/read/[bid]/[id]">1.book/read/bid/id</option>
                                                <option value="<?=Web_Path?>book_read_[bid]_[id].html">2.book_read_bid_id.html</option>
                                                <option value="<?=Web_Path?>book-read-[bid]-[id].html">3.book-read-bid-id.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Web_Read" placeholder="阅读路由URL，可用标签：[bid]，[id]" value="<?=Url_Book_Web_Read?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">去除index.php:</label>
                                        <div class="layui-input-inline" style="display: block;width: auto;float: none;">
                                            <input lay-filter="index-mode" type="radio" name="Url_Index_Mode" value="0" title="保留"<?php if(Url_Index_Mode == 0) echo ' checked';?>>
                                            <input lay-filter="index-mode" type="radio" name="Url_Index_Mode" value="1" title="去除"<?php if(Url_Index_Mode == 1) echo ' checked';?>>
                                        </div>
                                    </div>
                                    <blockquote class="layui-elem-quote layui-quote-nm l40"<?php if(Url_Index_Mode == 0) echo ' style="display: none;"';?>>
                                        提示信息：<br>
                                        如果开启去除index.php，需要使用伪静态规则。<br>
                                        每个WEB环境的伪静态规则都有所不同<br>
                                        系统本身已经写好每个环境的规则，文件在 <font color=red>./rewrite/</font> 目录中<br>
                                        1..htaccess格式--->apache或者IIS6.0+Rewrite3的格式，一般将规则放到网站更目录<br>
                                        2..conf格式--->一般情况为nginx的伪静态格式，将文件内容复制到配置文件中<br>
                                        3.httpd.ini格式--->一般情况为IIS6.0+Rewrite2的伪静态格式，一般放在网站根目录<br>
                                        4.web.config格式--->一般情况为IIS7以上支持，请使用.htaccess格式然后导入
                                    </blockquote>
                                </div>
                                <div id="Url_Mode2"<?php if(Url_Mode == 0) echo ' style="display: none;"';?>>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">漫画手机版静态配置</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">手机版目录:</label>
                                        <div class="layui-input-block w400">
                                            <input type="text" name="Wap_Html_Dir" placeholder="漫画静态手机版目录" value="<?=Wap_Html_Dir?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">手机版域名:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Wap_Html_Url" placeholder="漫画静态手机端域名：如：m.mccms.com，域名绑定到<?=FCPATH.Wap_Html_Dir?>目录，留空为主域名" value="<?=Wap_Html_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">漫画静态规则</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">主页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Html_Index" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>index.html">1.index.html</option>
                                                <option value="<?=Web_Path?>index.shtml">2.index.shtml</option>
                                                <option value="<?=Web_Path?>index.htm">3.index.htm</option>
                                                <option value="<?=Web_Path?>index.shtm">4.index.shtm</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Html_Index" placeholder="静态首页文件" value="<?=Url_Html_Index?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">分类页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Html_List" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>list/[id]/[page]/">1.list/id/page/</option>
                                                <option value="<?=Web_Path?>list/[en]/[page]/">2.list/en/page/</option>
                                                <option value="<?=Web_Path?>list_[id]_[page].html">3.list_id_page.html</option>
                                                <option value="<?=Web_Path?>list_[en]_[page].html">4.list_en_page.html</option>
                                                <option value="<?=Web_Path?>list-[id]-[page].html">5.list-id-page.html</option>
                                                <option value="<?=Web_Path?>list-[en]-[page].html">6.list-en-page.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Html_List" placeholder="静态分类页URL，可用标签：[id]、[en]、[page]" value="<?=Url_Html_List?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">内容页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Html_Show" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>comic/[id]/">1.comic/id/</option>
                                                <option value="<?=Web_Path?>comic/[en]/">2.comic/en/</option>
                                                <option value="<?=Web_Path?>comic_[id].html">3.comic_id.html</option>
                                                <option value="<?=Web_Path?>comic_[en].html">4.comic_en.html</option>
                                                <option value="<?=Web_Path?>comic-[id].html">5.comic-id.html</option>
                                                <option value="<?=Web_Path?>comic-[en].html">6.comic-en.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Html_Show" placeholder="静态内容页URL，可用标签：[id]、[en]" value="<?=Url_Html_Show?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">阅读页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Html_Pic" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>chapter/[mid]/[id]/">1.chapter/mid/id/</option>
                                                <option value="<?=Web_Path?>chapter_[mid]_[id].html">2.chapter_mid_id.html</option>
                                                <option value="<?=Web_Path?>chapter-[mid]-[id].html">3.chapter-mid-id.html</option>
                                                <option value="<?=Web_Path?>chapter/[id]/">4.chapter/id/</option>
                                                <option value="<?=Web_Path?>chapter_[id].html">5.chapter_id.html</option>
                                                <option value="<?=Web_Path?>chapter-[id].html">6.chapter-id.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Html_Pic" placeholder="静态阅读页URL，可用标签：[mid]，[id]" value="<?=Url_Html_Pic?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">小说手机版静态配置</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">手机版目录:</label>
                                        <div class="layui-input-block w400">
                                            <input type="text" name="Wap_Book_Html_Dir" placeholder="漫画静态手机版目录" value="<?=Wap_Book_Html_Dir?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">手机版域名:</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="Wap_Book_Html_Url" placeholder="漫画静态手机端域名：如：m.mccms.com，域名绑定到<?=FCPATH.Wap_Book_Html_Dir?>目录，留空为小说主域名" value="<?=Wap_Book_Html_Url?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                                        <legend style="font-size:15px;">小说静态规则</legend>
                                    </fieldset>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">主页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Html_Index" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/">1.book/</option>
                                                <option value="<?=Web_Path?>book/index.shtml">2.book/index.shtml</option>
                                                <option value="<?=Web_Path?>book/index.htm">3.book/index.htm</option>
                                                <option value="<?=Web_Path?>book/index.shtm">4.book/index.shtm</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Html_Index" placeholder="静态首页文件" value="<?=Url_Book_Html_Index?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">分类页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Html_List" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/list/[id]/[page]/">1.book/list/id/page/</option>
                                                <option value="<?=Web_Path?>book/list/[en]/[page]/">2.book/list/en/page/</option>
                                                <option value="<?=Web_Path?>book/list/[id]_[page].html">3.book/list/id_page.html</option>
                                                <option value="<?=Web_Path?>book/list/[en]_[page].html">4.book/list/en_page.html</option>
                                                <option value="<?=Web_Path?>book/list/[id]-[page].html">5.book/list/id-page.html</option>
                                                <option value="<?=Web_Path?>book/list/[en]-[page].html">6.book/list/en-page.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Html_List" placeholder="静态分类页URL，可用标签：[id]、[en]、[page]" value="<?=Url_Book_Html_List?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">内容页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Html_Info" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/info/[id]/">1.book/info/id/</option>
                                                <option value="<?=Web_Path?>book/info/[en]/">2.book/info/en/</option>
                                                <option value="<?=Web_Path?>book/info_[id].html">3.book/info_id.html</option>
                                                <option value="<?=Web_Path?>book/info_[en].html">4.book/info_en.html</option>
                                                <option value="<?=Web_Path?>book/info-[id].html">5.book/info-id.html</option>
                                                <option value="<?=Web_Path?>book/info-[en].html">6.book/info-en.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Html_Info" placeholder="静态内容页URL，可用标签：[id]、[en]" value="<?=Url_Book_Html_Info?>" class="layui-input"/>
                                        </div>
                                    </div>
                                    <div class="layui-form-item w120">
                                        <label class="layui-form-label">阅读页URL:</label>
                                        <div class="layui-inline select120">
                                            <select name="" data-id="Url_Book_Html_Read" lay-filter="url">
                                                <option value="">常用结构</option>
                                                <option value="<?=Web_Path?>book/read/[bid]/[id]/">1.book/read/bid/id/</option>
                                                <option value="<?=Web_Path?>book/read/[bid]_[id].html">2.book/read/bid_id.html</option>
                                                <option value="<?=Web_Path?>book/read/[bid]-[id].html">3.book/read/bid-id.html</option>
                                            </select>
                                        </div>
                                        <div class="layui-inline w400">
                                            <input type="text" name="Url_Book_Html_Read" placeholder="静态阅读页URL，可用标签：[bid]，[id]" value="<?=Url_Book_Html_Read?>" class="layui-input"/>
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
                                    <label class="layui-form-label layui-form-required">附件路径:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Web_Base_Path" placeholder="默认在网站根目录下的 pccks目录，如：http://cdn.abc.com/" value="<?=Web_Base_Path?>" class="layui-input" lay-verify="required" required/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">网站标题:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Seo_Title" placeholder="网站标题" value="<?=Seo_Title?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">网站关键词:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Seo_Keywords" placeholder="网站关键词" class="layui-textarea"><?=Seo_Keywords?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">网站描述:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Seo_Description" placeholder="网站描述,控制在80个汉字，160个字符以内" class="layui-textarea"><?=Seo_Description?></textarea>
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
                                    <label class="layui-form-label">令牌(Token):</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="Wx_Token" placeholder="公众号与网站通信的令牌(Token)，在公众平台查看" value="<?=Wx_Token?>" class="layui-input"/>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">关注回复内容:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Wx_Gz_Msg" placeholder="用户关注后的回复内容" class="layui-textarea" style="min-height: 100px;"><?=Wx_Gz_Msg?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item w120">
                                    <label class="layui-form-label">关键字回复内容:</label>
                                    <div class="layui-input-block">
                                        <textarea name="Wx_Key_Msg" placeholder="格式：关键字|回复内容，一行一条" class="layui-textarea" style="min-height: 100px;"><?=Wx_Key_Msg?></textarea>
                                    </div>
                                </div>
                                <blockquote class="layui-elem-quote layui-quote-nm">
                                    提示信息：<br>
                                    1.登录微信公众号-开发-基本配置-修改服务器配置<br>
                                    2.服务器地址(URL)：<?=(is_ssl()?'https://':'http://').Web_Url.Web_Path.(Url_Index_Mode==0?'index.php/':'')?>api/wxapp<br>
                                    3.令牌(Token)要跟后台填写一直，然后启用即可
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
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
layui.use(['layer', 'form'], function () {
    var form = layui.form,
        layer = layui.layer;
    //监听伪静态
    form.on('radio(index-mode)', function (r) {
        if(r.value == '1'){
            $('blockquote').show();
        }else{
            $('blockquote').hide();
        }
    });
    form.on('select(url)', function (r) {
        var _id = $(r.elem).attr('data-id');
        console.log(_id);
        $("input[name='"+_id+"']").val(r.value);
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
<?php
/*
'软件名称：漫城CMS（Mccms）
'官方网站：http://www.mccms.cn/
'软件作者：桂林崇胜网络科技有限公司（By:烟雨江南）
'--------------------------------------------------------
'Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
'遵循Apache2开源协议发布，并提供免费使用。
'--------------------------------------------------------
*/
define('IS_ADMIN', TRUE); // 后台标识
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); // 后台文件名
define('FCPATH', str_replace("\\", "/", dirname(__FILE__).'/')); // 网站根目录
require('index.php'); // 引入主文件
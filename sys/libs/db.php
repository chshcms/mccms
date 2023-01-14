<?php
/**
 * @Mccms open source management system
 * @copyright 2016-2020 mccms.cn. All rights reserved.
 * @Author:cheng kai jie
 * @Dtime:2020-01-11
 */
//服务器IP 一般为localhost或者127.0.0.1
define('Mc_Sqlserver','127.0.0.1');

//服务器端口
define('Mc_Sqlport','');

//数据库名称
define('Mc_Sqlname','mccms');

//数据库表前缀
define('Mc_SqlPrefix','mc_');

//数据库用户名
define('Mc_Sqluid','root');

//数据库密码
define('Mc_Sqlpwd','root');

//数据库方式
define('Mc_Dbdriver','mysqli');

//Mysql数据库编码
define('Mc_Sqlcharset','utf8');

//encryption_key密钥
define('Mc_Encryption_Key','9LneByFjWQUYozg');

//Cookie 前缀，同一域名下安装多套系统时，请修改Cookie前缀
define('Mc_Cookie_Prefix','mc_');

//Cookie_Domain 作用域,使用多个二级域名时可以启用，格式如 .mccms.cn
define('Mc_Cookie_Domain','');

//Cookie 生命周期，0 表示随浏览器进程
define('Mc_Cookie_Ttl',10800);

//小说TXTMD5秘钥
define('Mc_Book_Key','');
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
defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-Type: text/html; charset=utf-8');
//装载全局配置文件
require_once 'db.php';
require_once 'config.php';
require_once 'user.php';
require_once 'cache.php';
require_once 'annex.php';
require_once 'pay.php';
require_once 'push.php';
require_once 'caiji.php';
require_once 'ver.php';
//判断开启SSL
if(Web_Ssl_Mode == 1 && isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'http' && $_SERVER['REQUEST_SCHEME'] == 'http'){
	header("location:https://".Web_Url.Web_Path);exit;
}
//手机客户端访问标示
if(preg_match("/(iPhone|iPad|iPod|Android|Linux)/i", strtoupper($_SERVER['HTTP_USER_AGENT']))){
    define('MOBILE', true);	
}
//判断网站运行状态
if(!defined('IS_ADMIN') && Web_Mode == 1){
    exit(html_entity_decode(Web_Close_Txt));
}
//判断是否安装
if(strpos($_SERVER['REQUEST_URI'],'/install') === false){
  	if(!file_exists(FCPATH.'caches/install.lock')){
        if(strpos($_SERVER['REQUEST_URI'],'index.php') === false){
            $install_path = $_SERVER['REQUEST_URI'].'index.php';
        }else{
            $install_path = $_SERVER['REQUEST_URI'];
        }
        header("location:".$install_path.'/install');exit;
    }
}else{
    define('IS_INSTALL',true);
}
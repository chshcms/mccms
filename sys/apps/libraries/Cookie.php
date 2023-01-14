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
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Cookie类
 */
class Cookie {
	function __construct(){
		log_message('debug', "Native Cookie Class Initialized");
	}

	//设置 cookie
	public static function set($var, $value = '', $time = 0) {
		$time = $time > 0 ? $time : ($value == '' ? time() - 3600 : 0);
		$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
		$var = Mc_Cookie_Prefix.$var;
        $ips = explode(':',$_SERVER['HTTP_HOST']);
		$Domain = Mc_Cookie_Domain;
		setcookie($var,sys_auth($value,0,$var.Mc_Encryption_Key),$time, Web_Path, $Domain, $s);
	}

    //获取cookie
    public static function get($var, $default = '') {
		$var = Mc_Cookie_Prefix.$var;
		$value = isset($_COOKIE[$var]) ? sys_auth($_COOKIE[$var],1,$var.Mc_Encryption_Key) : $default;
		$value = safe_replace($value);
		return $value;
	}
}
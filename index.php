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
//默认时区
date_default_timezone_set("Asia/Shanghai");
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
switch (ENVIRONMENT){
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;
	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')){
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}else{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;
	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1);
}
$application_folder = 'sys/apps';
$system_path = 'sys/system';
$view_folder = 'template';
if(defined('STDIN')) chdir(dirname(__FILE__));
if(($_temp = realpath($system_path)) !== FALSE){
	$system_path = $_temp.'/';
}else{
	$system_path = rtrim($system_path, '/').'/';
}
if(!is_dir($system_path)){
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3);
}
if(!defined('SELF')) {
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
}
define('BASEPATH', str_replace('\\', '/', $system_path));
if(!defined('FCPATH')) define('FCPATH', str_replace('\\', '/', dirname(__FILE__).'/'));
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
define('MCCMSPATH', str_replace('\\', '/', dirname(BASEPATH).DIRECTORY_SEPARATOR));
if(is_dir($application_folder)){
	if(($_temp = realpath($application_folder)) !== FALSE){
		$application_folder = $_temp;
	}
	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
}else{
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
	exit(3);
}
if(($_temp = realpath($view_folder)) !== FALSE){
	$view_folder = $_temp.DIRECTORY_SEPARATOR;
}else{
	$view_folder = rtrim($view_folder, '/\\').DIRECTORY_SEPARATOR;
}
if(!is_dir($view_folder)){
	header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
	echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
	exit(3);
}
define('VIEWPATH', $view_folder);
require_once MCCMSPATH.'libs/sys.php';
require_once BASEPATH.'core/CodeIgniter.php';
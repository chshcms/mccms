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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Mccms_Controller {
	
	function __construct() {
	    parent::__construct();
	}

	public function index()
	{
		$this->cookie->set('admin_id', '');
		$this->cookie->set('admin_nichen', '');
		$this->cookie->set('admin_login', '');
		header("location:".links('login'));exit;
	}
}
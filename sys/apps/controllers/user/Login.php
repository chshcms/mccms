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

class Login extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//会员登陆
    public function index() {
        $str = load_file('user/login.html');
        $str = $this->parser->parse_string($str,array(),true);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

	//会员注册
    public function reg() {
        $str = load_file('user/reg.html');
        $str = $this->parser->parse_string($str,array(),true);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

	//找回密码
    public function pass() {
        $str = load_file('user/pass.html');
        $str = $this->parser->parse_string($str,array(),true);
        //IF判断解析
        echo $this->parser->labelif($str);
	}
}
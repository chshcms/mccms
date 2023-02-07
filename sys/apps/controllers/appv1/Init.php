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

class Init extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//判断签名
		get_app_sign();
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
	}
	
	//检测版本更新
    public function update() {
        $facility = $this->input->get_post('facility',true);
        $version = $this->input->get_post('version',true);
        if($facility != 'ios') $facility = 'android';
        if(empty($version)) $version = '1.0.0';
        $app = require FCPATH.'sys/libs/app.php';
        if($version < $app['update'][$facility]['version']){
            get_json($app['update'][$facility],1);
        }else{
            get_json('已经是最新版本',0);
        }
    }
    
	//联系方式
    public function kefu() {
        $d['code'] = 1;
        $d['qq'] = Web_QQ;
        $d['email'] = Web_Mail;
        $d['weburl'] = Web_Url;
		get_json($d);
    }
	
	//用户协议、隐私政策
    public function txt() {
        $type = $this->input->get_post('type',true);
        $config = require FCPATH.'sys/libs/app.php';
        $d['code'] = 1;
        $d['txt'] = isset($config['html'][$type]) ? str_decode($config['html'][$type]) : '';
		get_json($d);
    }
}
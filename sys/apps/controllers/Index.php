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
class Index extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
    }

	//主页
    public function index() {
    	//判断纯静态
    	if(Url_Mode > 0){
    		header("location:".get_url('index'));
    		exit;
    	}
    	if(!$this->caches->start('index',Cache_Time_Index)){
    		echo $this->tpl->index();
    		$this->caches->end();
    	}
	}
}
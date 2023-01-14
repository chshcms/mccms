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
class Custom extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index($op='') {
    	if($op == '') get_show_404();
    	//判断纯静态
    	if(Url_Mode > 0){
            $op = str_replace('.html','',$op);
    		header("location:".get_url('custom',array('file'=>$op)));
    		exit;
    	}
        if(!$this->caches->start('custom_'.$op)){
    	   echo $this->tpl->custom($op);
           $this->caches->end();
        }
	}
}
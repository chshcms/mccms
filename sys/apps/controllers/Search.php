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
class Search extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    //按关键字检索
    public function index($key='',$page=1) {
        $page = (int)$page;
        if($page == 0) $page =1;
        if(empty($key)) $key = $this->input->get_post('key',true);
        $key = safe_replace(urldecode($key));
        echo $this->tpl->search($key,$page);
    }
}
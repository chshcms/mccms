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
class Category extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    //智能检索
    public function index() {
        $uri = $this->uri->uri_string();
        $n = strpos($uri,'/index') !== false ? 3 : 2;
        $arr = safe_replace($this->uri->uri_to_assoc($n));
        echo $this->tpl->category($arr);
	}
}
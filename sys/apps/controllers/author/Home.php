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

class Home extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//作者主页
    public function index($uid=0,$page=1) {
    	$uid = (int)$uid;
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        if(!$row) get_err();
        //网站标题
        $data['mccms_title'] = $row['nichen'].'的个人主页 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val) $data['author_'.$key] = $val;
        $str = load_file('author/home.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}
}
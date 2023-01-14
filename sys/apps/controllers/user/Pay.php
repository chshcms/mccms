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

class Pay extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//购买
    public function index($op='') {
        $this->users->login();//判断登陆
        $data = array();
        $data['mccms_title'] = '在线购买充值 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $data['user_id'] = $uid;
        //获取模版
        $tpl = !empty($op) ? 'pay_'.$op.'.html' : 'pay.html';
        $str = load_file('user/'.$tpl);
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}
}
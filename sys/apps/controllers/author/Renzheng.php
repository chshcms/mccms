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

class Renzheng extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//认证
    public function index() {
        //判断作者
        $this->users->login();
        $data = array();
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //判断认证
        if($row['cid'] == 1 || $row['cid'] > 2){
            $row['realname'] = sub_str($row['realname'],1,0,'').'**';
            $row['idcard'] = substr($row['idcard'],0,4).'************'.substr($row['idcard'],-2);
            $row['card'] = substr($row['card'],0,4).'*************'.substr($row['card'],-2);
        }
        //网站标题
        $data['mccms_title'] = '作者认证 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val) $data['author_'.$key] = $val;
        $str = load_file('author/renzheng.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //认证提交
    public function save() {
        if(!$this->users->login(1)) get_json('请先登录!!!');
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','cid',array('id'=>$uid));
        if($row['cid'] == 1) get_json('您的认证已提交，请耐心等待审核!!!');
        if($row['cid'] > 2) get_json('您已通过认证了，不用提交了!!!');

        $edit['rz_type'] = (int)$this->input->post('type');
        if($edit['rz_type'] > 2) $edit['rz_type'] = 1;
        $edit['realname'] = $this->input->post('realname',true);
        $edit['idcard'] = $this->input->post('idcard',true);
        $edit['qq'] = $this->input->post('qq',true);
        $edit['bank'] = $this->input->post('bank',true);
        $edit['bankcity'] = $this->input->post('bankcity',true);
        $edit['card'] = $this->input->post('card',true);
        $edit['cid'] = 1;
        if(empty($edit['realname']) || 
            empty($edit['idcard']) || 
            empty($edit['qq']) || 
            empty($edit['bank']) || 
            empty($edit['bankcity']) || 
            empty($edit['card'])){
            get_json('数据不完整');
        }
        $this->mcdb->get_update('user',$uid,$edit);
        get_json('提交成功',1);
    }
}
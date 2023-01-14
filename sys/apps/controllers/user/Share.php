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

class Share extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
        //判断登陆
		$this->users->login();
        //加载函数
        $this->load->helper('app_helper');
	}

    public function index() {
        $data = array();
        $data['mccms_title'] = '邀请好友 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //当前数据
        foreach ($row as $key => $val) $data['user_'.$key] = $val;
        //邀请码
        $data['inviteid'] = $uid+10000;
        //分享地址、分享文本
        $share_url = 'http://'.Web_Url.links('share/app/'.$data['inviteid']);
        $data['share_txt'] = "我发现一个非常不错的漫画网站，点击下面地址可以免费看~\n".$share_url;
        //邀请人数
        $data['znums'] = $this->mcdb->get_nums('user_invite',array('inviteid'=>$uid));
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        $data['daynums'] = $this->mcdb->get_nums('user_invite',array('inviteid'=>$uid,'addtime>'=>$jtime));
        //二维码
        $data['qrcode'] = 'https://wenhairu.com/static/api/qr/?size=200&text='.urlencode($share_url);
        //邀请记录
        $sql = 'select * from '.Mc_SqlPrefix.'user_invite where inviteid='.$uid.' order by id desc';
        $list = $this->mcdb->get_sql($sql.' limit 15',1);
        foreach ($list as $k => $v) {
            $tel = getzd('user','tel',$v['uid']);
            $list[$k]['tel'] = substr($tel,0,3).'****'.substr($tel,-4);
        }
        $data['list'] = $list;
        $this->load->get_templates(Skin_Wap_Path);
        $str = $this->load->view('user/share.html',$data,true);
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    public function ajax() {
        $uid = (int)$this->cookie->get('user_id');
        $page = (int)$this->input->post('page');
        if($page == 0) $page = 1;
        //总数量
        $size = 15;
        $nums = $this->mcdb->get_nums('user_invite',array('inviteid'=>$uid));
        //总页数
        $pagejs = ceil($nums / $size);
        if($pagejs == 0) $pagejs = 1;
        //偏移量
        $limit = $size*($page-1).','.$size;
        //邀请记录
        $sql = 'select * from '.Mc_SqlPrefix.'user_invite where inviteid='.$uid.' order by id desc';
        $list = $this->mcdb->get_sql($sql.' limit '.$limit,1);
        $html = '';
        foreach ($list as $k => $v) {
            $tel = getzd('user','tel',$v['uid']);
            $tel = substr($tel,0,3).'****'.substr($tel,-4);
            $html .= '<li><span>'.$tel.'</span><span class="right">'.date('Y-m-d H:i:s',$v['addtime']).'</span></li>';
        }
        //输出
        $d['code'] = 1;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['html'] = $html;
        get_json($d);
    }
}
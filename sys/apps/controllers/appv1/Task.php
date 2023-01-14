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

class Task extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//判断签名
		get_app_sign();
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
		//判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$this->user) get_json('未登录',-1);
	}
	
	//任务列表
    public function index() {
        //任务列表
        $list = $this->mcdb->get_select('task','*',array('yid'=>0),'id ASC',50);
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        foreach ($list as $k=>$row){
            if($row['id'] == 2){
                $list[$k]['nums'] = $this->mcdb->get_nums('task_list',array('tid'=>$row['id'],'uid'=>$this->uid));
            }else{
                $list[$k]['nums'] = $this->mcdb->get_nums('task_list',array('tid'=>$row['id'],'uid'=>$this->uid,'addtime>'=>$jtime));
            }
            $list[$k]['init'] = $this->init($list[$k]['nums'],$row['daynum']);
            unset($list[$k]['yid']);
        }
        //输出
        $d['code'] = 1;
        $d['list'] = $list;
        get_json($d);
    }
    
	//任务奖励列表
    public function record() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;

        //总数量
		$nums = $this->mcdb->get_nums('task_list',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select * from '.Mc_SqlPrefix.'task_list where uid='.$this->uid.' order by id desc';
		$list = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		foreach ($list as $k=>$row){
		    $list[$k]['name'] = getzd('task','name',$row['tid']);
		    $list[$k]['cion'] = $list[$k]['cion'] > 0 ? $list[$k]['cion'].Pay_Cion_Name : '0';
		    unset($list[$k]['id'],$list[$k]['tid'],$list[$k]['uid']);
		}
        //输出
        $d['code'] = 1;
        $d['nums'] = $nums;
        $d['size'] = $size;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['list'] = get_app_data($list);
        get_json($d);
    }
    
    //领取任务奖励
    public function add() {
        $tid = (int)$this->input->get_post('tid');//类型：1签到，2邀请用户，3每日评论，4收藏漫画
        if($tid == 0) get_json('任务ID错误',0);
        $res = app_task_reward($this->mcdb,$tid,$this->user);
        if($res) get_json('领取成功',1);
        get_json('领取失败',0);
    }
    
    //分享接口
    public function share() {
        //邀请码
        $d['data']['inviteid'] = $this->uid+10000;
        //分享地址、分享文本
        $d['data']['share_txt'] = '我发现一个非常不错的漫画APP，点击下面地址下载可以免费看~';
        $d['data']['share_url'] = 'http://'.Web_Url.links('share/app/'.$d['data']['inviteid']);
        //邀请人数
        $d['data']['znums'] = $this->mcdb->get_nums('user_invite',array('inviteid'=>$this->uid));
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        $d['data']['daynums'] = $this->mcdb->get_nums('user_invite',array('inviteid'=>$this->uid,'addtime>'=>$jtime));
        //二维码
        $d['data']['qrcode'] = 'https://wenhairu.com/static/api/qr/?size=200&text='.urlencode($d['data']['share_url']);
        get_json($d,1);
    }
    
    //判断任务完成状态
    private function init($nums,$daynum) {
        if($daynum == 1){
            return $nums == 0 ? 0 : 1;
        }else{
            return $daynum ==0 || $nums < $daynum ? 0 : 1;
        }
    }
}
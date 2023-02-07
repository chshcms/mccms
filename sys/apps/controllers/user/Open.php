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

class Open extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('denglu');
	}

	//登陆
    public function index($type='qq') {
    	//记住来路地址
    	$this->cookie->set('referer_url',$_SERVER["HTTP_REFERER"]);
    	if($type != 'weixin' && $type != 'weibo') $type = 'qq';
    	$state = sys_auth(time());
    	$this->denglu->$type($state);
	}

	//返回
    public function callback($type='qq') {
    	$url = $this->cookie->get('referer_url');
    	if(empty($url)) $url = links('user');
        $state = sys_auth($this->input->get_post('state'));
        if(empty($state)) exit('非法访问!!!');
    	$arr = $this->denglu->callback($type);
    	//是否登陆过
    	if($arr['uid'] > 0){
    		$row = $this->mcdb->get_row_arr('user','*',array('id'=>(int)$arr['uid']));
    		if($row){ //存在则登陆

		        //记住COOKIE
		        $time = 86400+time();
		        $log = md5($row['id'].$row['sid'].$row['pass'].Mc_Encryption_Key);
		        $this->cookie->set('user_id',$row['id'],$time);
		        $this->cookie->set('user_login',$log,$time);
		        $this->cookie->set('referer_url','');

		        header("Location:$url");
		        exit;
    		}
    	}
        //判断是否登陆，已登陆则直接绑定
        if($this->users->login(1)){
            //修改第三方会员ID
            $uid = (int)$this->cookie->get('user_id');
            $this->mcdb->get_update('user_oauth',$arr['id'],array('uid'=>$uid));
        }else{
            //否则新增会员
            $add['addtime'] = time();
            $add['name'] = 'T-'.substr(md5($add['addtime']),8,-8);
            $add['nichen'] = $arr['nichen'];
            $add['pic'] = $arr['pic'];
            $add['sid'] = 0;
            $add['pass'] = time();
            $add['cion'] = User_Reg_Cion;
            $add['vip'] = User_Reg_Vip;
            $add['viptime'] = 0;
            if(User_Reg_Vip_Day > 0) $add['viptime'] = time()+86400*User_Reg_Vip_Day;
            //注册
            $res = $this->mcdb->get_insert('user',$add);
            //修改第三方会员ID
            $this->mcdb->get_update('user_oauth',$arr['id'],array('uid'=>$res));
            //记住COOKIE
            $time = 86400+time();
            $log = md5($res.$add['sid'].$add['pass'].Mc_Encryption_Key);
            $this->cookie->set('user_id',$res,$time);
            $this->cookie->set('user_login',$log,$time);
        }

        $this->cookie->set('referer_url','');
        header("Location:$url");
        exit;
	}
}
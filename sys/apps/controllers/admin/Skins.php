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

class Skins extends Mccms_Controller {
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//模板中心
	public function index($cid=0,$page = 1)
	{
		$page = (int)$page;
		$data['page'] = $page == 0 ? 1 : $page;
		$data['cid'] = (int)$cid;
		$token = isset($_COOKIE['mccms_tpl_token']) ? $_COOKIE['mccms_tpl_token'] : '';
		$arr = json_decode(getcurl('http:'.base64decode(Apiurl).'/skins/index/'.$cid.'/'.$page,array('token'=>$token)),1);
		$data['skins'] = $arr['code'] == 1 ? $arr['data'] : array('tpl'=>array(),'class'=>array(),'pagejs'=>1);
		$data['token'] = $token;
		$this->load->view('sys/skins.tpl',$data);
	}

	public function down($id=0)
	{
		set_time_limit(0);
		$id = (int)$id;
		$token = isset($_COOKIE['mccms_tpl_token']) ? $_COOKIE['mccms_tpl_token'] : '';
		if(empty($token)) $token = $this->input->get_post('token');
		$arr = json_decode(getcurl('http:'.base64decode(Apiurl).'/skins/down',array('token'=>$token,'id'=>$id)),1);
		if(!isset($arr['code'])) get_json('未知错误，请联系Mccms官方客服');
		if($arr['code'] != 1) get_json($arr['msg']);
		if(empty($arr['data']['type'])) get_json('该模板未定义类型');
		if(empty($arr['data']['zipurl'])) get_json('没有获取到模板包地址');
		$type = $arr['data']['type'];
		//下载文件
		$zipurl = sys_auth($arr['data']['zipurl'],1,'mccms_tpl_zip');
		if(empty($zipurl)) get_json('模板包地址错误');
		//获取文件头信息
		$arr2 = get_headers($zipurl,true);
		if(!in_array('application/zip',$arr2['Content-Type']) && 
			$arr2['Content-Type'] !== 'application/zip' && 
			$arr2['Content-Type'] !== 'zip'){
			get_json('压缩包不是zip类型文件');
		}
		$data = getcurl($zipurl);
		if(empty($data)) get_json('获取压缩包失败');
		$file_zip = FCPATH."caches/upzip/".end(explode('/',$zipurl));
		if(!file_put_contents($file_zip, $data)) get_json('压缩包下载失败');
		//解压
		$this->load->library('mczip');
		$this->mczip->PclZip($file_zip);
		if ($this->mczip->extract(PCLZIP_OPT_PATH, FCPATH.'template/'.$type.'/', PCLZIP_OPT_REPLACE_NEWER) == 0) {
            unlink($file_zip);
			get_json('文件解压失败，或者没有权限覆盖文件~！');
		}else{
			unlink($file_zip);
			$arr['url'] = links('setting/skins/'.$type);
			$arr['msg'] = '模板下载成功~！';
			get_json($arr,1);
		}
	}
}
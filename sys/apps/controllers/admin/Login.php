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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Mccms_Controller {

	function __construct() {
	    parent::__construct();
	}

	public function index()
	{
		if($this->admin->login(1)){
			header("location:".links('index'));
			exit;
		}
		$this->load->view('index/login.tpl');
	}
	
	public function save()
	{
	    $adminname = $this->input->post('name', TRUE);
	    $adminpass = $this->input->post('pass', TRUE);
	    $admincode = $this->input->post('code', TRUE);
	    $code=0;
		if(empty($adminname)){
			$error='账号不能为空!';  
		}elseif(empty($adminpass)){
			$error='密码不能为空!'; 
		}elseif(empty($admincode)){
			$error='认证码不能为空!'; 
        }elseif($admincode!=Admin_Code){
			$error='认证码错误!'; 
		}else{
            $where = array('name'=>$adminname,'pass'=>md5($adminpass));
		    $row=$this->mcdb->get_row('admin','*',$where);
		    if($row){

		    	//账号被禁用
		    	if($row->sid == 1){
		    		get_json('该账号已经被禁用!!!');
		    	}

                //保存cookie
				$login = md5($row->id.$row->name.$row->pass.Admin_Code);
				$time = time()+86400;
				if(empty($row->nichen)) $row->nichen = $row->name;
				$this->cookie->set('admin_id', $row->id, $time);
				$this->cookie->set('admin_nichen', $row->nichen, $time);
				$this->cookie->set('admin_login', $login, $time);

                //修改登陆次数
                $updata['logip'] = getip();
                $updata['lognum'] = $row->lognum+1;
                $updata['logtime'] = time();
                $this->mcdb->get_update('admin',$row->id,$updata);

                //写入登陆记录
                if(Admin_Log_Day > 0){
	                $agent = getClientBrowser();
	                $add['uid'] = $row->id;
	                $add['logip'] = $updata['logip'];
	                $add['logtime'] = time();
	                $add['browser'] = $agent['browser'].$agent['ver'];
	                if(!defined('MOBILE')){
	                	$add['machine'] = '电脑';
	                }elseif($agent['browser'] == '微信浏览器'){
	                	$add['machine'] = '微信';
	                }elseif($agent['browser'] == 'QQ浏览器'){
	                	$add['machine'] = 'QQ';
	                }else{
	                	$add['machine'] = '手机';
	                }
	                $this->mcdb->get_insert('admin_log',$add);
	                //删除日期以前的记录
	                $deltime = time()-86400*Admin_Log_Day;
	                $this->db->query('delete from '.Mc_SqlPrefix.'admin_log where logtime < '.$deltime);
	            }

		        $error='登陆成功';
		        $code=1;
		        $data['url'] = links('index');		
		    }else{
				$error='账号密码不匹配';
			}
		}
		$data['msg'] = $error;
		get_json($data,$code);
	}
}
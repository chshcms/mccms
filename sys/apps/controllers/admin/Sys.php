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

class Sys extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//管理员列表
	public function index()
	{
		$this->load->view('sys/index.tpl');
	}

	//管理员列表
	public function ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $name = safe_replace($this->input->get_post('name',true));
        if($page==0) $page=1;

	    $like = array();
	    if(!empty($name)) $like['name'] = $name;

        //总数量
	    $total = $this->mcdb->get_nums('admin','',$like);
		//每页数量
	    if($per_page == 0) $per_page = 20;
	    if($per_page > 100) $per_page = 100;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($page > $pagejs) $page = $pagejs;
	    if($total < $per_page) $per_page = $total;
	    $limit = array($per_page,$per_page*($page-1));
        //记录数组
        $data['count'] = $total;
	    $data['data'] = $this->mcdb->get_select('admin','id,name,nichen,lognum,logip,logtime,sid','','id DESC',$limit,$like);
		get_json($data,0);
	}

	//管理员增加编辑
	public function edit($id=0)
	{
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data['id'] = 0;
            $data['name'] = '';
            $data['nichen'] = '';
		}else{
            $data = $this->mcdb->get_row_arr("admin","*",array('id'=>$id)); 
		}
        $this->load->view('sys/edit.tpl',$data);
	}

	//管理员修改
	public function save()
	{
 	    $id = (int)$this->input->post('id',true);
		$data['name'] = $this->input->post('name',true);
		$data['nichen'] = $this->input->post('nichen',true);
		$pass = $this->input->post('pass',true);
		if(!empty($pass)){
		    $data['pass'] = md5($pass);
		}
		if(empty($data['name'])){
            get_json('账号不能为空~！');
		}
		if(empty($pass) && $id==0){
            get_json('密码不能为空~！');
		}
		if($id==0){
            $this->mcdb->get_insert('admin',$data);
		}else{
            $this->mcdb->get_update('admin',$id,$data);
		}
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('sys');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

	//管理员禁用开启
	public function init()
	{
 	    $id = (int)$this->input->post('id');
 	    $zt = $this->input->post('zt',true);
 	    if($id == 0) get_json('ID不能为空');
 	    if($id == $this->cookie->get('admin_id')) get_json('不能操作自己');

 	    $edit['sid'] = $zt == 'yes' ? 0 : 1;
 	    $this->mcdb->get_update('admin',$id,$edit);
		get_json('恭喜您，操作成功~!',1);
	}

    //删除管理员
	public function del($id=0)
	{
 	    $id = (int)$id;
 	    if($id == 0){
 	    	$ids = $this->input->get_post('id',true);
 	    	$ids = implode(',',$ids);
 	    	if(is_numeric($ids) || preg_match('/^([0-9]+[,]?)+$/', $ids)){
				$id = $ids;
			}
 	    }
 	    if(empty($id)) get_json('ID不能为空~!');
 	    $arr = explode(',', $id);
 	    foreach ($arr as $_id) {
 	    	//跳过自己的号
 	    	if($_id != $this->cookie->get('admin_id')){
 	    		$this->mcdb->get_del('admin',$_id);
 	    		//删除管理员日志
 	    		$this->mcdb->get_del('admin_log',$_id,'uid');
 	    	}else{
 	    		if(count($arr) == 1){
 	    			get_json('不能删除自己!!!');
 	    		}
 	    	}
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('sys');
		get_json($arr,1);
	}

	//管理员登陆日志
	public function log()
	{
 	    $id = (int)$this->input->get_post('id');
 	    $data['zd'] = '';
 	    $data['key'] = '';
 	    if($id == 0){
 	    	$data['ajaxurl'] = links('sys','logajax');
 	    }else{
 	    	$data['ajaxurl'] = links('sys','logajax').'?zd=uid&key='.$id;
 	    	$data['zd'] = 'uid';
 	    	$data['key'] = $id;
 	    }
		$this->load->view('sys/log.tpl',$data);
	}

	//管理员登陆日志
	public function logajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = $this->input->get_post('zd');
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
 
	    $wh = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'nichen'){
	    		$uid = (int)getzd('admin','id',$key,'nichen');
	    	}elseif($zd == 'name'){
	    		$uid = (int)getzd('admin','id',$key,'name');
	    	}else{
	    		$uid = (int)$key;
	    	}
		    $wh['uid'] = $uid;
	    }
        if(!empty($kstime)){
        	$wh['logtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['logtime<'] = strtotime($jstime)+86401;
        }
        //总数量
	    $total = $this->mcdb->get_nums('admin_log',$wh);
		//每页数量
	    if($per_page == 0) $per_page = 20;
	    if($per_page > 100) $per_page = 100;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($page > $pagejs) $page = $pagejs;
	    if($total < $per_page) $per_page = $total;
	    $limit = array($per_page,$per_page*($page-1));
        //记录数组
        $data['count'] = $total;
	    $log = $this->mcdb->get_select('admin_log','*',$wh,'id DESC',$limit);
	    foreach ($log as $k => $v) {
	    	$log[$k]['nichen'] = getzd('admin','nichen',$v['uid']);
	    }
	    $data['data'] = $log;
		get_json($data,0);
	}

    //删除管理员日志
	public function log_del()
	{
	    $ids = $this->input->get_post('id',true);
	    $ids = implode(',',$ids);
	    if(!is_numeric($ids) && !preg_match('/^([0-9]+[,]?)+$/', $ids)) $ids = '';
 	    if(empty($ids)) get_json('ID不能为空~!');
 	    $arr = explode(',', $ids);
 	    foreach ($arr as $_id) {
 	    	$this->mcdb->get_del('admin_log',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('sys','log');
		get_json($arr,1);
	}
}
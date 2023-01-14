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

class App extends Mccms_Controller {

	function __construct(){
	    parent::__construct();
	    //加载函数
		$this->load->helper('app_helper');
		//判断是否登陆
		$this->admin->login();
	}

	public function index()
	{
		$data['app'] = require FCPATH.'sys/libs/app.php';
		$this->load->view('app/index.tpl',$data);
	}

	//保存APP入库
	public function setting()
	{
		$post = $this->input->post();
		$post['search'] = explode('|', $post['search']);
		$post['book_search'] = explode('|', $post['book_search']);
		$res = arr_file_edit($post,FCPATH.'sys/libs/app.php');
		if(!$res) get_json('抱歉，修改失败，请检查文件写入权限~!');
		$arr['msg'] = '恭喜您，配置修改成功~！';
		$arr['url'] =  links('app','index');
        get_json($arr,1);
	}

	//任务列表
	public function task()
	{
		$this->load->view('app/task.tpl');
	}

	//任务列表
	public function task_ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
        if($page==0) $page=1;

        //总数量
	    $total = $this->mcdb->get_nums('task');
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
	    $data['data'] = $this->mcdb->get_select('task','*','','id ASC',$limit);
		get_json($data,0);
	}

	//任务编辑
	public function edit($id=0)
	{
 	    $id = (int)$id;
	    $data = $this->mcdb->get_row_arr("task","*",array('id'=>$id)); 
        $this->load->view('app/edit.tpl',$data);
	}

	//任务修改
	public function save()
	{
 	    $id = (int)$this->input->post('id',true);
		$data['name'] = $this->input->post('name',true);
		$data['text'] = $this->input->post('text',true);
		$data['cion'] = (int)$this->input->post('cion');
		$data['vip'] = (int)$this->input->post('vip');
		$data['yid'] = (int)$this->input->post('yid');
		$data['daynum'] = (int)$this->input->post('daynum');
		if(empty($data['name'])) get_json('任务标题不能为空~！');
		if(empty($data['text'])) get_json('任务介绍不能为空~！');
        $this->mcdb->get_update('task',$id,$data);
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('app','task');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

	//任务记录
	public function task_list()
	{
		$this->load->view('app/task_list.tpl');
	}

	//任务记录ajax
	public function task_list_ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $uid = (int)$this->input->get_post('uid');
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

        $wh = array();
        if(!empty($kstime)){
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if($uid > 0) $wh['uid'] = $uid;

        //总数量
	    $total = $this->mcdb->get_nums('task_list',$wh);
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
	    $task = $this->mcdb->get_select('task_list','*',$wh,'id DESC',$limit);
	    foreach ($task as $k => $v) {
	    	$task[$k]['tname'] = getzd('task','name',$v['tid']);
	    }
	    $data['data'] = $task;
		get_json($data,0);
	}

    //删除任务记录
	public function task_list_del($id=0)
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
 	    	$this->mcdb->get_del('task_list',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('app/task_list');
		get_json($arr,1);
	}

	//APP统计
	public function user()
	{
		$this->load->view('app/user.tpl');
	}

	//APP统计ajax
	public function user_ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

        $wh = array();
        if(!empty($kstime)){
        	$wh['date>'] = date('Ymd',strtotime($kstime)-1);
        }
        if(!empty($jstime)){
        	$wh['date<'] = date('Ymd',strtotime($jstime)+86401);
        }

        //总数量
	    $total = $this->mcdb->get_nums('user_app_nums',$wh);
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
	    $data['data'] = $this->mcdb->get_select('user_app_nums','*',$wh,'date DESC',$limit);
		get_json($data,0);
	}

	//邀请记录
	public function invite()
	{
		$this->load->view('app/invite.tpl');
	}

	//邀请记录ajax
	public function invite_ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = $this->input->get_post('zd',true);
 	    $key = $this->input->get_post('key',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

        $wh = array();
        if(!empty($kstime)){
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if(!empty($zd) && !empty($key)) $wh[$zd] = (int)$key;

        //总数量
	    $total = $this->mcdb->get_nums('user_invite',$wh);
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
	    $data['data'] = $this->mcdb->get_select('user_invite','*',$wh,'id DESC',$limit);
		get_json($data,0);
	}

    //删除邀请记录
	public function invite_del($id=0)
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
 	    	$this->mcdb->get_del('user_invite',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('app/invite');
		get_json($arr,1);
	}
}
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

class Ads extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//广告列表
	public function index()
	{
		$this->load->view('ads/index.tpl');
	}

	//广告列表
	public function ajax()
	{
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
        if($page==0) $page=1;

        //总数量
	    $total = $this->mcdb->get_nums('ads');
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
	    $ads = $this->mcdb->get_select('ads','*','','id DESC',$limit);
	    foreach ($ads as $k => $v) {
	    	$ads[$k]['label'] = '[mccms_js_'.$v['bs'].']';
	    	$ads[$k]['jspath'] = Web_Path.'advert/'.$v['bs'].'.js';
	    }
	    $data['data'] = $ads;
		get_json($data,0);
	}

	//广告增加编辑
	public function edit($id=0)
	{
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data['id'] = 0;
            $data['name'] = '';
            $data['bs'] = '';
            $data['html'] = '';
		}else{
            $data = $this->mcdb->get_row_arr("ads","*",array('id'=>$id));
		}
        $this->load->view('ads/edit.tpl',$data);
	}

	//广告修改
	public function save()
	{
 	    $id = (int)$this->input->post('id',true);
		$data['name'] = $this->input->post('name',true);
		$data['bs'] = $this->input->post('bs',true);
		$data['html'] = str_encode($this->input->post('html'));
		if(empty($data['name'])){
            get_json('广告名称不能为空~！');
		}
		if(empty($data['bs'])){
            get_json('唯一标示不能为空~！');
		}
		if(!preg_match("/^[A-Za-z0-9_]/",$data['bs'])){ 
            get_json('唯一标示只能是英文字母和数字和下划线~！');
		}
		//判断唯一标示
		$row = $this->mcdb->get_row_arr("ads","*",array('bs'=>$data['bs']));
		if($row && $row['id'] != $id){
			get_json('标示已经存在，请更换~！');
		}
		//写入内容到JS文件
		mkdirss(FCPATH.'advert/');
		$jsstr = htmltojs(str_decode($data['html']));
		if(!write_file(FCPATH.'advert/'.$data['bs'].'.js',$jsstr)){
			get_json('无法生成JS，请检查：./advert/目录是否有权限');
		}
		if($id==0){
            $this->mcdb->get_insert('ads',$data);
		}else{
            $this->mcdb->get_update('ads',$id,$data);
		}
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('ads');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

    //删除广告
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
 	    	$this->mcdb->get_del('ads',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('ads');
		get_json($arr,1);
	}
}
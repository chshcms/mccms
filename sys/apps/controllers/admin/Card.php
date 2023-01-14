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

class Card extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//卡密列表
	public function index(){
		$this->load->view('card/index.tpl');
	}

	//卡密数据
	public function ajax(){
 	    $page = (int)$this->input->get_post('page');
 	    $sid = (int)$this->input->get_post('sid');
 	    $zt = (int)$this->input->get_post('zt');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

        $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'uid'){
	    		$wh[$zd] = (int)$key;
	    	}else{
	    		$like[$zd] = $key;
	    	}
	    }
        if(!empty($kstime)){
        	$wh['usetime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['usetime<'] = strtotime($jstime)+86401;
        }
        if($zt == 2){
        	$wh['uid>'] = 0;
        }elseif($zt == 1){
        	$wh['uid'] = 0;
        }
        if($sid > 0) $wh['sid'] = $sid-1;

        //总数量
	    $total = $this->mcdb->get_nums('card',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('card','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}

	//批量加卡
	public function add(){
		$this->load->view('card/add.tpl');
	}

	//卡密编辑
	public function edit($id=0){
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $this->load->view('card/add.tpl');
		}else{
            $data = $this->mcdb->get_row_arr("card","*",array('id'=>$id)); 
            $this->load->view('card/edit.tpl',$data);
		}
	}

	//卡密修改
	public function save(){
 	    $id = (int)$this->input->post('id',true);
		$data['pass'] = $this->input->post('pass',true);
		$data['sid'] = (int)$this->input->post('sid');
		$data['day'] = (int)$this->input->post('day');
		$data['cion'] = (int)$this->input->post('cion');
		$data['uid'] = (int)$this->input->post('uid');
		$data['usetime'] = $this->input->post('usetime',true);
		if(!empty($data['usetime'])) $data['usetime'] = strtotime($data['usetime']);
		if($data['sid'] == 0) $data['day'] = 0;
		if($data['sid'] == 1) $data['cion'] = 0;
		if(empty($data['pass'])){
            get_json('卡密不能为空~！');
		}
		if($data['sid'] == 0 && $data['cion'] == 0){
            get_json('<?=Pay_Cion_Name?>不能为空~！');
		}
		if($data['sid'] == 1 && $data['day'] == 0){
            get_json('天数不能为空~！');
		}
		if($id==0){
            $this->mcdb->get_insert('card',$data);
		}else{
            $this->mcdb->get_update('card',$id,$data);
		}
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('card');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

	//批量加卡
	public function pladd(){
		$this->load->helper('string');
		$sid = (int)$this->input->post('sid',true);
		$day = (int)$this->input->post('day',true);
		$cion = (int)$this->input->post('cion',true);
		$nums = (int)$this->input->post('nums',true);
		if($nums > 5000) get_json('单次数量不能超过5000');
		if($sid == 0 && $cion == 0){
            get_json('<?=Pay_Cion_Name?>不能为空~！');
		}
		if($sid == 1 && $day == 0){
            get_json('天数不能为空~！');
		}
		if($nums == 0){
			get_json('请输入卡密数量~！');
		}
		for($i=0; $i < $nums; $i++) { 
			$add['sid'] = $sid;
			$add['day'] = $sid == 0 ? 0 : $day;
			$add['cion'] = $sid == 1 ? 0 : $cion;
			$add['pass'] = random_string('alnum',30);
			$this->mcdb->get_insert('card',$add);
		}
		$arr['msg'] = '恭喜您，成功添加'.$nums.'张卡密~!';
		$arr['url'] = links('card');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

    //删除卡密
	public function del($id=0){
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
 	    	$this->mcdb->get_del('card',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('card');
		get_json($arr,1);
	}

	//导出卡密
	public function daochu(){
		$id = $this->input->get_post('id',true);
		if(empty($id)) exit('请选择要导出的卡密');
		$ids = explode(',',$id);
		$text = '';
		foreach ($ids as $k=>$_id) {
			$_id = (int)$_id;
			if($_id > 0){
				if($k == 0){
					$text .= getzd('card','pass',$_id);
				}else{
					$text .= "\r\n".getzd('card','pass',$_id);
				}
			}
		}
		$this->load->helper('download');
		$name = 'Mccms-card-'.time().'.txt';
		force_download($name, $text);
	}
}
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

class Gift extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//礼物列表
	public function index(){
		$this->load->view('gift/index.tpl');
	}

	//礼物数据
	public function ajax(){
        //记录数组
	    $data['data'] = $this->mcdb->get_select('gift','*','','xid ASC',1000);
		get_json($data,0);
	}

	//礼物编辑
	public function edit($id=0){
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data = array(
            	'id'=>0,
            	'name'=>'',
            	'pic'=>'',
            	'cion'=>10,
            	'text'=>'',
            	'xid'=>0,
            	'yid'=>0
            );
		}else{
            $data = $this->mcdb->get_row_arr("gift","*",array('id'=>$id)); 
		}
		$this->load->view('gift/edit.tpl',$data);
	}

	//礼物修改
	public function save(){
 	    $id = (int)$this->input->post('id',true);
        $data = array(
        	'name'=>$this->input->post('name',true),
        	'pic'=>$this->input->post('pic',true),
        	'cion'=>(int)$this->input->post('cion'),
        	'text'=>$this->input->post('text',true),
        	'xid'=>(int)$this->input->post('xid'),
        	'yid'=>(int)$this->input->post('yid')
        );
		if(empty($data['name'])) get_json('礼物名称不能为空~！');
		if(empty($data['cion'])) get_json('礼物价格不能为空~！');
		if(empty($data['pic'])) get_json('礼物图片不能为空~！');

		if($id==0){
            $this->mcdb->get_insert('gift',$data);
		}else{
            $this->mcdb->get_update('gift',$id,$data);
		}
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('gift');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

    //删除礼物
	public function del($id=0){
		$this->load->model('tongbu');
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
 	    	//删除图片
 	    	$picpath = getzd('gift','pic',$_id);
 	    	$this->tongbu->del($picpath);
 	    	$this->mcdb->get_del('gift',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('gift');
		get_json($arr,1);
	}

	//打赏列表
	public function reward(){
		$data['gift'] = $this->mcdb->get_select('gift','*','','id DESC',100);
		$this->load->view('gift/reward.tpl',$data);
	}

	//打赏数据
	public function reward_ajax(){
 	    $gid = (int)$this->input->get_post('gid');
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
 	    $type = $this->input->get_post('type',true);
        if($page==0) $page=1;

        $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'uid' || $zd == 'mid'){
	    		$wh[$zd] = (int)$key;
	    	}else{
	    		$like[$zd] = $key;
	    	}
	    }
        if(!empty($kstime)){
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if($gid > 0) $wh['gid'] = $gid;
        if($type == 'comic') $wh['mid>'] = 0;
        if($type == 'book') $wh['bid>'] = 0;

        //总数量
	    $total = $this->mcdb->get_nums('gift_reward',$wh,$like);
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
        //记录数组
	    $reward = $this->mcdb->get_select('gift_reward','*',$wh,'id DESC',$limit,$like);
	    foreach ($reward as $k => $v) {
	    	if($v['mid'] > 0){
	    		$rowm = $this->mcdb->get_row_arr('comic','id,name,cid,yname',$v['mid']);
	    	}else{
	    		$rowm = $this->mcdb->get_row_arr('book','id,name,cid,yname',$v['bid']);
	    	}
	    	$reward[$k]['mname'] = $rowm['name'];
	    	$reward[$k]['mlink'] = $v['mid'] > 0 ? get_url('show',$rowm) : get_url('book_info',$rowm);
	    	$reward[$k]['gname'] = getzd('gift','name',$v['gid']);
	    }
	    $data['data'] = $reward;
		get_json($data,0);
	}

    //删除打赏记录
	public function reward_del($id=0){
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
 	    	$this->mcdb->get_del('gift_reward',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('gift','reward');
		get_json($arr,1);
	}

	//月票列表
	public function ticket(){
		$this->load->view('gift/ticket.tpl');
	}

	//月票数据
	public function ticket_ajax(){
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
 	    $type = $this->input->get_post('type',true);
        if($page==0) $page=1;

        $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'uid' || $zd == 'mid'){
	    		$wh[$zd] = (int)$key;
	    	}else{
	    		$like[$zd] = $key;
	    	}
	    }
        if(!empty($kstime)){
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if($type == 'comic') $wh['mid>'] = 0;
        if($type == 'book') $wh['bid>'] = 0;

        //总数量
	    $total = $this->mcdb->get_nums('ticket',$wh,$like);
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
        //记录数组
	    $ticket = $this->mcdb->get_select('ticket','*',$wh,'id DESC',$limit,$like);
	    foreach ($ticket as $k => $v) {
	    	if($v['mid'] > 0){
	    		$rowm = $this->mcdb->get_row_arr('comic','id,name,cid,yname',$v['mid']);
	    	}else{
	    		$rowm = $this->mcdb->get_row_arr('book','id,name,cid,yname',$v['bid']);
	    	}
	    	$ticket[$k]['mname'] = $rowm['name'];
	    	$ticket[$k]['mlink'] = $v['mid'] > 0 ? get_url('show',$rowm) : get_url('book_info',$rowm);
	    }
	    $data['data'] = $ticket;
		get_json($data,0);
	}

    //删除月票记录
	public function ticket_del($id=0){
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
 	    	$this->mcdb->get_del('ticket',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('gift','ticket');
		get_json($arr,1);
	}
}
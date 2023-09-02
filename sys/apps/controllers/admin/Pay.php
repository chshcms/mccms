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

class Pay extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//订单列表
	public function index(){
		$this->load->view('pay/index.tpl');
	}

	//订单数据
	public function ajax(){
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $pid = (int)$this->input->get_post('pid');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
 
        $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'uid' || $zd == 'id'){
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
        if($pid > 0) $wh['pid'] = $pid-1;

        //总数量
	    $total = $this->mcdb->get_nums('order',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('order','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}

    //删除订单
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
 	    	$this->mcdb->get_del('order',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('pay');
		get_json($arr,1);
	}

    //按条件删除订单
	public function pldel(){
		$day = (int)$this->input->get_post('day');
		$zt = (int)$this->input->get_post('zt');

		$wh = array();
		if($zt > 1) $wh['pid'] = $zt-2;
		if($day > 0){
			$time = strtotime(date('Y-m-d 0:0:0'))-86401*$day;
			$wh['addtime<'] = $time;
		}
		$res = $this->db->where($wh)->delete('order');
		if(!$res) get_json('删除失败！！！');
		get_json('恭喜您，删除成功!',1);
	}

	//消费列表
	public function buy($type='comic'){
		$data['type'] = $type;
		$this->load->view('pay/buy.tpl',$data);
	}

	//消费数据
	public function buy_ajax($type='comic'){
 	    $page = (int)$this->input->get_post('page');
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
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        //表
        $table = $type == 'book' ? 'book_buy' : 'buy';

        //总数量
	    $total = $this->mcdb->get_nums($table,$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select($table,'*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}

    //删除消费
	public function buy_del($id=0,$type='comic'){
 	    $id = (int)$id;
 	    if($id == 0){
 	    	$ids = $this->input->get_post('id',true);
 	    	$ids = implode(',',$ids);
 	    	if(is_numeric($ids) || preg_match('/^([0-9]+[,]?)+$/', $ids)){
				$id = $ids;
			}
 	    }
 	    if(empty($id)) get_json('ID不能为空~!');
 	    //表
        $table = $type == 'book' ? 'book_buy' : 'buy';
 	    $arr = explode(',', $id);
 	    foreach ($arr as $_id) {
 	    	$this->mcdb->get_del($table,$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('pay','buy',$type);
		get_json($arr,1);
	}

	//消费列表
	public function income(){
		$this->load->view('pay/income.tpl');
	}

	//消费数据
	public function income_ajax(){
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
	    	if($zd == 'uid'){
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
        if($type == 'book') $wh['bid>'] = 0; 
        if($type == 'comic') $wh['mid>'] = 0;

        //总数量
	    $total = $this->mcdb->get_nums('income',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('income','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}

    //删除消费
	public function income_del($id=0){
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
 	    	$this->mcdb->get_del('income',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('pay','income');
		get_json($arr,1);
	}

	//提现列表
	public function drawing(){
		$this->load->view('pay/drawing.tpl');
	}

	//提现数据
	public function drawing_ajax(){
 	    $pid = (int)$this->input->get_post('pid');
 	    $page = (int)$this->input->get_post('page');
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
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if($pid > 0) $wh['pid'] = $pid-1;

        //总数量
	    $total = $this->mcdb->get_nums('drawing',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('drawing','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}

	//设置提现状态
	public function drawing_show(){
		$id = (int)$this->input->get('id');
		$data = $this->mcdb->get_row_arr('drawing','*',array('id'=>$id));
		if(!$data) exit('记录不存在!');
		//会员银行信息
		$data['user'] = $this->mcdb->get_row_arr('user','*',array('id'=>$data['uid']));
		$this->load->view('pay/drawing_show.tpl',$data);
	}

	//设置提现状态
	public function drawing_save($id=0){
		$id = (int)$id;
		if($id == 0) get_json('数据ID错误');
		$pid = (int)$this->input->post('pid');
		$msg = $this->input->post('msg',true);
		if($pid == 2 && empty($msg)) get_json('请填写为什么失败');
		//入库
		$this->mcdb->get_update('drawing',$id,array('pid'=>$pid,'msg'=>$msg));

		//数据信息
		$row = $this->mcdb->get_row_arr('drawing','*',array('id'=>$id));
		//返还会员金额
		if($pid == 2){
			$this->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$row['rmb'].' where id='.$row['uid']);
			$title = '您申请的提现审核未通过';
			$text = '亲爱的作者，您申请的提现于'.date('Y-m-d H:i:s').'审核未通过，原因：'.$msg;
		}else{
			$title = '您申请的提现审核已通过';
			$text = '亲爱的作者，您申请的提现于'.date('Y-m-d H:i:s').'审核通过，已完成打款，请注意查收您的银行账号';
		}
		//发送消息
		$add['uid'] = $row['uid'];
		$add['name'] = $title;
		$add['text'] = $text;
		$add['addtime'] = time();
		$this->mcdb->get_insert('message',$add);

		//发送邮件提醒
		if(Mail_Drawing == 1){
			$user = $this->mcdb->get_row_arr('user','name,nichen,email',array('id'=>$row['uid']));
			if(!empty($user['email'])){

				$e['user']['name'] = $user['name'];
				$e['user']['nichen'] = $user['nichen'];
				$e['drawing']['dd'] = $row['dd'];
				$e['drawing']['zt'] = $row['pid'] == 1 ? '提现成功' : '提现失败（提示：'.$row['msg'].'）';
				$e['drawing']['rmb'] = $row['rmb'];
				$e['drawing']['addtime'] = $row['addtime'];

				$this->load->model('mail');
				$add['to_mail'] = $user['email'];
				$add['title'] = email_replace(Mail_Drawing_Title,$e);
				$add['html'] = email_replace(Mail_Drawing_Msg,$e);
				$this->mail->send($add);
			}
		}

		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('pay','drawing');
		$arr['parent'] = 1;
		get_json($arr,1);
	}

    //删除提现
	public function drawing_del($id=0){
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
 	    	$this->mcdb->get_del('drawing',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('pay','drawing');
		get_json($arr,1);
	}
}
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

class User extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}
	//用户列表
	public function index($cid=0){
		$data['cid'] = $cid;
		$this->load->view('user/index.tpl',$data);
	}
	//用户列表JSON
	public function ajax(){
 	    $page = (int)$this->input->get_post('page');
 	    $sid = (int)$this->input->get_post('sid');
 	    $cid = (int)$this->input->get_post('cid');
 	    $vip = (int)$this->input->get_post('vip');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $serialize = safe_replace($this->input->get_post('serialize',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
 
	    $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'id'){
	    		$wh['id'] = (int)$key;
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
        if($sid > 0) $wh['sid'] = $sid-1;
        if($vip > 0) $wh['vip'] = $vip-1;
        if($cid > 0) $wh['cid'] = $cid-1;

        //总数量
	    $total = $this->mcdb->get_nums('user',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('user','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}
	//会员增加编辑
	public function edit($id=0){
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data = array('id' => 0,'signing'=>0,'cid' => 0,'rz_type' => 1,'sid' => 0,'name' => '','nichen' => '','pic' => '','tel' => '','qq' => '','sex' => '保密','email' => '','city' => '','text' => '','vip' => 0,'cion' => 0,'rmb' => 0,'ticket' => 0,'viptime'=>'','rz_msg'=>'','realname'=>'','idcard'=>'','bank'=>'','card'=>'','bankcity'=>'');
		}else{
            $data = $this->mcdb->get_row_arr("user","*",array('id'=>$id)); 
		}
		//class
		$data['class'] = $this->mcdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		//type
		$data['type'] = $this->mcdb->get_select('type','id,name,zd,cid',array('fid'=>0),'xid ASC',20);
        $this->load->view('user/edit.tpl',$data);
	}
	//会员修改
	public function save(){
 	    $id = (int)$this->input->post('id');
		$pass = $this->input->post('pass',true);
		$viptime = $this->input->post('viptime',true);
		$data = array(
			'cid' => (int)$this->input->post('cid'),
			'sid' => (int)$this->input->post('sid'),
			'signing' => (int)$this->input->post('signing'),
			'name' => safe_replace($this->input->post('name',true)),
			'nichen' => $this->input->post('nichen',true),
			'pic' => $this->input->post('pic',true),
			'tel' => safe_replace($this->input->post('tel',true)),
			'qq' => $this->input->post('qq',true),
			'email' => $this->input->post('email',true),
			'city' => $this->input->post('city',true),
			'sex' => $this->input->post('sex',true),
			'text' => $this->input->post('text',true),
			'rz_msg' => $this->input->post('rz_msg',true),
			'vip' => (int)$this->input->post('vip'),
			'cion' => (int)$this->input->post('cion'),
			'rmb' => (int)$this->input->post('rmb'),
			'ticket' => (int)$this->input->post('ticket'),
			'realname'=>$this->input->post('realname',true),
			'idcard'=>$this->input->post('idcard',true),
			'bank'=>$this->input->post('bank',true),
			'card'=>$this->input->post('card',true),
			'bankcity'=>$this->input->post('bankcity',true)
        );
		if(empty($data['name'])) get_json('登陆账号不能为空~！');
		if($id ==0 && empty($pass)) get_json('登陆密码不能为空~！');
		if(empty($data['tel'])) get_json('联系手机不能为空~！');
		if(!empty($pass)) $data['pass'] = md5($pass);
		if($data['vip'] > 0 && empty($viptime)) get_json('Vip到期时间不能为空~！');
		if($data['vip'] > 0) $data['viptime'] = strtotime($viptime);
		if($data['cid'] == 2 && empty($data['rz_msg'])) get_json('请填写认证失败原因');

		if($id == 0){
			$row = $this->mcdb->get_row('user','id',array('name'=>$data['name']));
			if($row) get_json('改账号已经存在，请更换~！');
			$row = $this->mcdb->get_row('user','id',array('tel'=>$data['tel']));
			if($row) get_json('改手机号码已经存在，请更换~！');
			$data['addtime'] = time();
            $id = $this->mcdb->get_insert('user',$data);
		}else{
			$ycid = getzd('user','cid',$id);
			//判断头像和原头像是否是一直
			$pic = getzd('user','pic',$id);
			if(!empty($data['pic']) && !empty($pic) && $pic != $data['pic']){ //不一样删除原来头像
				$this->load->model('tongbu');
				$this->tongbu->del($pic);
			}
            $this->mcdb->get_update('user',$id,$data);
            //发送认证消息
            if($ycid != $data['cid']){
	            if($data['cid'] == 2){
					//发送消息
					$add['uid'] = $id;
					$add['name'] = '您申请的作者认证审核未通过';
					$add['text'] = '亲爱的作者，您申请的作者认证于'.date('Y-m-d H:i:s').'时间审核不通过，不通过原因：'.$data['rz_msg'];
					$add['addtime'] = time();
					$this->mcdb->get_insert('message',$add);
	            }elseif($data['cid'] > 2){
	            	$type = $data['cid'] == 3 ? '个人' : '企业';
					//发送消息
					$add['uid'] = $id;
					$add['name'] = '您申请的作者认证审核已通过';
					$add['text'] = '亲爱的作者，您申请的作者认证于'.date('Y-m-d H:i:s').'时间审核已通过《'.$type.'认证》，感谢您对我们的支持';
					$add['addtime'] = time();
					$this->mcdb->get_insert('message',$add);
	            }
	        }
		}

		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('user');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
	//会员锁定开启
	public function init(){
 	    $id = (int)$this->input->post('id');
 	    $zt = $this->input->post('zt',true);
 	    if($id == 0) get_json('ID不能为空');

 	    $edit['sid'] = $zt == 'yes' ? 0 : 1;
 	    $this->mcdb->get_update('user',$id,$edit);
		get_json('恭喜您，操作成功~!',1);
	}
    //删除会员
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
 	    	//删除头像地址
			$pic = getzd('user','pic',$id);
			$this->load->model('tongbu');
			$this->tongbu->del($pic);
			//删除记录
    		$this->mcdb->get_del('user',$_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('user');
		get_json($arr,1);
	}
	//会员详细
	public function show(){
 	    $id = (int)$this->input->get_post('id');
 	    if($id == 0) exit('会员ID不能为空！！！');
 	    $row = $this->mcdb->get_row_arr('user','*',array('id'=>$id));
 	    if(!$row) exit('会员不存在');
        $this->load->view('user/show.tpl',$row);
 	}
	//签约用户列表
	public function signing(){
		$this->load->view('user/signing.tpl');
	}
	//签约用户列表JSON
	public function signing_ajax(){
 	    $page = (int)$this->input->get_post('page');
 	    $sid = (int)$this->input->get_post('sid');
 	    $vip = (int)$this->input->get_post('vip');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $serialize = safe_replace($this->input->get_post('serialize',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
 
	    $like = array();
	    $wh['signing'] = 1;
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'id'){
	    		$wh['id'] = (int)$key;
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
        if($sid > 0) $wh['sid'] = $sid-1;
        if($vip > 0) $wh['vip'] = $vip-1;

        //总数量
	    $total = $this->mcdb->get_nums('user',$wh,$like);
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
	    $data['data'] = $this->mcdb->get_select('user','*',$wh,'id DESC',$limit,$like);
		get_json($data,0);
	}
    //删除签约
	public function signing_del($id=0){
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
			//删除记录
    		$this->mcdb->get_update('user',$_id,array('signing'=>0));
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('user','signing');
		get_json($arr,1);
	}
}
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

class Comment extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}
	//用户列表
	public function index($ac=''){
		$tpl = $ac == 'reply' ? 'reply' : 'index';
		$this->load->view('comment/'.$tpl.'.tpl');
	}
	//用户列表JSON
	public function ajax($ac=''){
        $table = $ac == 'reply' ? 'comment_reply' : 'comment';
 	    $page = (int)$this->input->get_post('page');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $serialize = safe_replace($this->input->get_post('serialize',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
 	    $type = $this->input->get_post('type',true);
        if($page==0) $page=1;
 
	    $wh = $like = array();
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'text' || $zd == 'ip'){
	    		$like[$zd] = $key;
	    	}else{
	    		if(!($table == 'comment' && ($zd == 'fid' || $zd == 'cid'))){
	    			$wh[$zd] = (int)$key;
	    		}
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
    //删除评论
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
			//删除记录
    		$this->mcdb->get_del('comment',$_id);
    		//删除下级评论
    		$this->mcdb->get_del('comment_reply',$_id,'cid');
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comment');
		get_json($arr,1);
	}
    //删除评论回复
	public function reply_del($id=0){
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
    		$this->mcdb->get_del('comment_reply',$_id);
    		//删除下级评论
    		$this->mcdb->get_del('comment_reply',$_id,'fid');
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comment','index','reply');
		get_json($arr,1);
	}
	//评论详细
	public function show($ac=''){
		$table = $ac == 'reply' ? 'comment_reply' : 'comment';
 	    $id = (int)$this->input->get_post('id');
 	    if($id == 0) exit('评论ID不能为空！！！');
 	    $row = $this->mcdb->get_row_arr($table,'*',array('id'=>$id));
 	    if(!$row) exit('评论不存在');
        $this->load->view('comment/show.tpl',$row);
 	}
}
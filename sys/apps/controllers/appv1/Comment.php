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

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
		//判断签名
		get_app_sign();
	}
	
    //评论列表
    public function index() {
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;
        if($mid == 0 && $bid == 0) get_json('ID不能为空',0);
        //查询条件
        $wh = $mid > 0 ? array('mid'=>$mid) : array('bid'=>$bid);
        //总数量
		$nums = $this->mcdb->get_nums('comment',$wh);
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = array($size,$size*($page-1));
        //评论列表
		$comment = $this->mcdb->get_select('comment','id,text,uid,reply_num,zan,addtime',$wh,'id DESC',$limit);
		foreach($comment as $k=>$v){
		    $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$v['uid']));
		    $comment[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
            $comment[$k]['upic'] = getpic($rowu['pic'],'user');
            $comment[$k]['addtime'] = date('m-d H:i',$v['addtime']);
            //是否赞过
            $comment[$k]['is_zan'] = 0;
            if($this->uid > 0){
                $rowz = $this->mcdb->get_row_arr('comment_zan','id',array('fid'=>0,'cid'=>$v['id'],'uid'=>$this->uid));
                if($rowz) $comment[$k]['is_zan'] = 1;
            }
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($comment);
		get_json($data,1);
    }
    
    //回复列表
    public function reply() {
        $id = (int)$this->input->get_post('id');
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;
        if($id == 0) get_json('评论ID不能为空',0);
        //获取评论
        $row = $this->mcdb->get_row_arr('comment','id,text,uid,reply_num,zan,addtime',array('id'=>$id));
        if(!$row) get_json('评论不存在',0);
        //评论详情
        $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$row['uid']));
	    $row['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
        $row['upic'] = getpic($rowu['pic'],'user');
        $row['addtime'] = date('m-d H:i',$v['addtime']);
        //是否赞过
        $row['is_zan'] = 0;
        if($this->uid > 0){
            $rowz = $this->mcdb->get_row_arr('comment_zan','id',array('fid'=>0,'cid'=>$row['id'],'uid'=>$this->uid));
            if($rowz) $row['is_zan'] = 1;
        }
        
        //总数量
		$nums = $this->mcdb->get_nums('comment_reply',array('cid'=>$id));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = array($size,$size*($page-1));
        //评论列表
		$comment = $this->mcdb->get_select('comment_reply','id,text,uid,zan,addtime',array('cid'=>$id),'id DESC',$limit);
		foreach($comment as $k=>$v){
		    $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$v['uid']));
		    $comment[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
            $comment[$k]['upic'] = getpic($rowu['pic'],'user');
            $comment[$k]['addtime'] = date('m-d H:i',$v['addtime']);
            //是否赞过
            $comment[$k]['is_zan'] = 0;
            if($this->uid > 0){
                $rowz = $this->mcdb->get_row_arr('comment_zan','id',array('fid'=>1,'cid'=>$v['id'],'uid'=>$this->uid));
                if($rowz) $comment[$k]['is_zan'] = 1;
            }
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($comment);
		$data['row'] = get_app_data($row);
		get_json($data,1);
    }
	
    //新增评论
    public function add() {
        if(Pl_Mode == 1) get_json('评论已关闭',0);
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $cid = (int)$this->input->get_post('cid');
        $fid = (int)$this->input->get_post('fid');
        $text = $this->input->get_post('text',true);
        if(($mid == 0 && $bid == 0) || empty($text)) get_json('参数错误',0);
        //判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$this->user) get_json('未登录',-1);
        //判断上次评论时间
        $table = $cid > 0 ? 'comment_reply' : 'comment';
        $row = $this->mcdb->get_row_arr($table,'addtime',array('uid'=>$this->uid),'addtime desc');
        if(($row['addtime']+Pl_Time) > time()) get_json('请先休息一会，再来评论吧',0);
        //判断每天评论数量
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        $num1 = $this->mcdb->get_nums('comment',array('uid'=>$this->uid,'addtime>'=>$jtime));
        $num2 = $this->mcdb->get_nums('comment_reply',array('uid'=>$this->uid,'addtime>'=>$jtime));
        if(($num1+$num2) >= Pl_Add_Num) get_json('您今天评论数已达上限，明天再来吧',0);
        //过滤评论内容
        $add['text'] = $text;
        $add['mid'] = $mid;
        $add['bid'] = $bid;
        $add['uid'] = $this->uid;
        $add['machine'] = 'app';
        $add['ip'] = getip();
        $add['addtime'] = time();
        if($cid > 0){
            $add['cid'] = $cid;
            $add['fid'] = $fid;
        }
        $res = $this->mcdb->get_insert($table,$add);
        if($res){
            //增加回复次数
            if($cid > 0){
                $this->db->query('update '.Mc_SqlPrefix.'comment set reply_num=reply_num+1 where id='.$cid);
            }
            //任务奖励
            $tid = $mid > 0 ? 3 : 5;
            app_task_reward($this->mcdb,$tid,$this->user);
            //输出
            $d['code'] = 1;
            $d['msg'] = '评论成功';
            $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$this->uid));
		    $d['comment']['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
            $d['comment']['upic'] = getpic($rowu['pic'],'user');
            $d['comment']['addtime'] = date('m-d H:i');
            $d['comment']['id'] = $res;
            $d['comment']['text'] = $text;
            $d['comment']['uid'] = $this->uid;
            $d['comment']['text'] = $text;
            $d['comment']['reply_num'] = 0;
            $d['comment']['zan'] = 0;
            $d['comment']['is_zan'] = 0;
            get_json($d);
        }else{
            get_json('评论失败',0);
        }
    }
	
	//赞评论
	public function zan(){
	    //判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$this->user) get_json('未登录',-1);
	    $cid = (int)$this->input->get_post('id');
	    $fid = (int)$this->input->get_post('fid');
	    if($cid == 0) get_json('ID错误',0);
        $table = $fid == 1 ? 'comment_reply' : 'comment';
	    $row = $this->mcdb->get_row_arr($table,'zan',array('id'=>$cid));
	    if(!$row) get_json('评论不存在',0);
	    //判断是否赞过
	    $rowz = $this->mcdb->get_row_arr('comment_zan','id',array('cid'=>$cid,'fid'=>$fid,'uid'=>$this->uid));
	    if($rowz){
	        $this->mcdb->get_del('comment_zan',$rowz['id']);
	        $this->mcdb->get_update($table,$cid,array('zan'=>$row['zan']-1));
	        $type= 0;
	    }else{
	        $res = $this->mcdb->get_insert('comment_zan',array('cid'=>$cid,'uid'=>$this->uid,'fid'=>$fid));
	        $this->mcdb->get_update($table,$cid,array('zan'=>$row['zan']+1));
	        $type= 1;
	    }
	    get_json(array('type'=>$type),1);
	}
}
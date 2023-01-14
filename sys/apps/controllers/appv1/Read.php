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

class Read extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//判断签名
		get_app_sign();
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
		//判断登录
		if(!get_app_log($this->uid,$this->token,$this->mcdb)){
		    $code = strpos($_SERVER['REQUEST_URI'],'/read/add') === false ? -1 : 1;
		    get_json('未登录',$code);
		}
	}

	//漫画阅读记录
    public function index() {
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size == 0 || $size > 100) $size = 15;
		if($page == 0) $page = 1;
		
        //总数量
		$nums = $this->mcdb->get_nums('read',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select mid,cid zid,pid,addtime from '.Mc_SqlPrefix.'read where uid='.$this->uid.' order by addtime desc';
		$read = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		$i = 0;
		foreach ($read as $k => $v) {
			//详情
			$row = get_app_data($this->mcdb->get_row_arr('comic','name,pic,picx,author,score,serialize state,hits,text,content,nums,addtime',array('id'=>$v['mid'])));
			if($row){
    			$v['name'] = $row['name'];
    			$v['pic'] = $row['pic'];
    			$v['author'] = $row['author'];
    			$v['state'] = $row['state'];
    			$v['nums'] = $row['nums'];
    			$v['read_name'] = getzd('comic_chapter','name',$v['zid']);
    			$v['news_name'] = getzd('comic_chapter','name',$v['mid'],'mid');
    			//是否收藏
    			$rowf = $this->mcdb->get_row_arr('fav','id',array('mid'=>$v['mid'],'uid'=>$this->uid));
    			$v['fav'] = $rowf ? 1 : 0;
			    $read[$i] = $v;
			    $i++;
			}
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($read);
		get_json($data,1);
	}

	//小说阅读记录
    public function book() {
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size == 0 || $size > 100) $size = 15;
		if($page == 0) $page = 1;
		
        //总数量
		$nums = $this->mcdb->get_nums('book_read',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select bid,cid zid,addtime from '.Mc_SqlPrefix.'book_read where uid='.$this->uid.' order by addtime desc';
		$read = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		$i = 0;
		foreach ($read as $k => $v) {
			//详情
			$row = get_app_data($this->mcdb->get_row_arr('book','name,pic,picx,author,score,serialize state,hits,text,content,nums,addtime',array('id'=>$v['bid'])));
			if($row){
				$table = get_chapter_table($v['bid']);
    			$v['name'] = $row['name'];
    			$v['pic'] = $row['pic'];
    			$v['author'] = $row['author'];
    			$v['state'] = $row['state'];
    			$v['nums'] = $row['nums'];
    			$v['read_name'] = getzd($table,'name',$v['zid']);
    			$v['news_name'] = getzd($table,'name',$v['bid'],'bid');
    			//是否收藏
    			$rowf = $this->mcdb->get_row_arr('book_fav','id',array('bid'=>$v['bid'],'uid'=>$this->uid));
    			$v['fav'] = $rowf ? 1 : 0;
			    $read[$i] = $v;
			    $i++;
			}
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($read);
		get_json($data,1);
	}

	//加入记录
	public function add($type='comic') {
		if($type != 'book') $type = 'comic';
		$id = (int)$this->input->get_post('id');
		$zid = (int)$this->input->get_post('zid');
		$pid = (int)$this->input->get_post('pid');
		if($id == 0) get_json('ID为空',0);
		if($zid == 0) get_json('章节ID为空',0);
		$table = $type == 'comic' ? 'read' : 'book_read';
		$zd = $type == 'comic' ? 'mid' : 'bid';
		$add = array('cid'=>$zid,'addtime'=>time());
		if($type == 'comic') $add['pid'] = $pid;
		//判断是否在书架
		$row = $this->mcdb->get_row_arr($table,'id',array('uid'=>$this->uid,$zd=>$id));
		if($row){
		    $this->mcdb->get_update($table,$row['id'],$add);
		}else{
    		//加入
    		$add['uid'] = $this->uid;
    		$add[$zd] = $id;
    		$res = $this->mcdb->get_insert($table,$add);
    		if(!$res) get_json('记录失败，稍后再试',0);
		}
		get_json('记录成功',1);
	}

	//记录清空
	public function del($type='comic') {
		if($type != 'book') $type = 'comic';
		$table = $type == 'comic' ? 'read' : 'book_read';
		//删除开始
		$this->db->query('DELETE FROM '.Mc_SqlPrefix.$table.' WHERE uid='.$this->uid);
		get_json('清空完成',1);
	}
}
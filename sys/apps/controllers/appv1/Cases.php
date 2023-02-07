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

class Cases extends Mccms_Controller {

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
	}

	//漫画书架
    public function index() {
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size == 0 || $size > 100) $size = 15;
		if($page == 0) $page = 1;
		
        //总数量
		$nums = $this->mcdb->get_nums('fav',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select max(a.id) id,a.mid,max(b.cid) zid,max(b.pid) pid from '.Mc_SqlPrefix.'fav a left join '.Mc_SqlPrefix.'read b on a.mid=b.mid and b.uid='.$this->uid.' where a.uid='.$this->uid.' GROUP BY a.mid order by id desc';
		$fav = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		$i = 0;
		foreach ($fav as $k => $v) {
			//详情
			$row = get_app_data($this->mcdb->get_row_arr('comic','name,pic,picx,author,score,serialize state,hits,text,content,nums,addtime',array('id'=>$v['mid'])));
			if($row){
    		    $v['zid'] = (int)$v['zid'];
    		    $v['pid'] = (int)$v['pid'];
    			$v['name'] = $row['name'];
    			$v['pic'] = $row['pic'];
    			$v['author'] = $row['author'];
    			$v['state'] = $row['state'];
    			$v['nums'] = $row['nums'];
    			$v['read_name'] = $v['zid'] > 0 ? getzd('comic_chapter','name',$v['zid']) : '未阅读';
    			$v['news_name'] = getzd('comic_chapter','name',$v['mid'],'mid');
			    $fav[$i] = $v;
			    $i++;
			}
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($fav);
		get_json($data,1);
	}

	//小说书架
    public function book() {
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size == 0 || $size > 100) $size = 15;
		if($page == 0) $page = 1;
		
        //总数量
		$nums = $this->mcdb->get_nums('book_fav',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select max(a.id) id,a.bid,max(b.cid) zid from '.Mc_SqlPrefix.'book_fav a left join '.Mc_SqlPrefix.'book_read b on a.bid=b.bid and b.uid='.$this->uid.' where a.uid='.$this->uid.' GROUP BY a.bid order by id desc';
		$fav = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		$i = 0;
		foreach ($fav as $k => $v) {
			//详情
			$row = get_app_data($this->mcdb->get_row_arr('book','name,pic,picx,author,score,serialize state,hits,text,content,nums,addtime',array('id'=>$v['bid'])));
			if($row){
				$table = get_chapter_table($v['bid']);
    		    $v['zid'] = (int)$v['zid'];
    			$v['name'] = $row['name'];
    			$v['pic'] = $row['pic'];
    			$v['author'] = $row['author'];
    			$v['state'] = $row['state'];
    			$v['nums'] = $row['nums'];
    			$v['read_name'] = $v['zid'] > 0 ? getzd($table,'name',$v['zid']) : '未阅读';
    			$v['news_name'] = getzd($table,'name',$v['bid'],'bid');
			    $fav[$i] = $v;
			    $i++;
			}
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($fav);
		get_json($data,1);
	}

	//加入书架
	public function add($type='comic') {
		if($type != 'book') $type = 'comic';
		if($type == 'book'){
			$table = 'book_fav';
			$zd = 'bid';
			$tid = 6;
		}else{
			$table = 'fav';
			$zd = 'mid';
			$tid = 4;
		}
		$id = (int)$this->input->get_post('id');
		if($id == 0) get_json('ID为空',0);
		//判断登录
		$log = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$log) get_json('未登录',-1);
		//判断是否在书架
		$row = $this->mcdb->get_row_arr($table,'id',array('uid'=>$this->uid,$zd=>$id));
		if($row) get_json('已在书架',0);
		//加入书架
		$res = $this->mcdb->get_insert($table,array($zd=>$id,'uid'=>$this->uid,'addtime'=>time()));
		if(!$res) get_json('加入失败，稍后再试',0);
		//增加收藏人气
		$this->db->query("UPDATE `".Mc_SqlPrefix.$type."` SET shits=shits+1 WHERE id=".$id);
		//任务奖励
        app_task_reward($this->mcdb,$tid,$log);
		get_json('加入书架成功',1);
	}

	//书架删除
	public function del($type='comic') {
		if($type != 'book') $type = 'comic';
		$ids = $this->input->get_post('ids');
		if(empty($ids)) get_json('ID为空',0);
        //判断登录
		$log = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$log) get_json('未登录',-1);
		//删除开始
		if(!is_array($ids)) $ids = explode(',',$ids);
		foreach ($ids as $_id) {
			$id = (int)$_id;
			if($id > 0){
				if($type == 'book'){
				    $this->db->query('DELETE FROM '.Mc_SqlPrefix.'book_fav WHERE uid='.$this->uid.' and bid='.$id);
				    //减去收藏人气
			        $this->db->query("UPDATE `".Mc_SqlPrefix."book` SET shits=shits-1 WHERE id=".$id);
				}else{
				    $this->db->query('DELETE FROM '.Mc_SqlPrefix.'fav WHERE uid='.$this->uid.' and mid='.$id);
				    //减去收藏人气
			        $this->db->query("UPDATE `".Mc_SqlPrefix."comic` SET shits=shits-1 WHERE id=".$id);
				}
			}
		}
		get_json('删除完成',1);
	}
}
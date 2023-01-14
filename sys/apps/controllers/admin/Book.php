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

class Book extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		$this->load->model('novel');
		$this->load->library('pinyin');
	}
	//小说列表
	public function index($yid=0){
		$data['yid'] = $yid;
 	    $data['name'] = safe_replace($this->input->get_post('name',true));
		$data['class'] = $this->mcdb->get_select('book_class','id,name',array('fid'=>0),'xid ASC',100);
		$this->load->view('book/index.tpl',$data);
	}
	//小说列表JSON
	public function ajax($yid=0){
 	    $page = (int)$this->input->get_post('page');
 	    $cid = (int)$this->input->get_post('cid');
 	    $tid = (int)$this->input->get_post('tid');
 	    $sid = (int)$this->input->get_post('sid');
 	    $pay = (int)$this->input->get_post('pay');
 	    $size = (int)$this->input->get_post('size');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $serialize = safe_replace($this->input->get_post('serialize',true));
 	    $name = safe_replace($this->input->get_post('name',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
 	    $yid = (int)$yid;
        if($page==0) $page=1;
 
         //检测重复名数据
        if($yid == 3){
        	$sql = "Select * From ".Mc_SqlPrefix."book Where name in (Select name From ".Mc_SqlPrefix."book Group By name Having Count(*)>1)";
	        //总数量
		    $total = $this->db->query($sql)->num_rows();
			//每页数量
		    if($per_page == 0) $per_page = 20;
		    if($per_page > 500) $per_page = 100;
			//总页数
		    $pagejs = ceil($total / $per_page);
		    if($page > $pagejs) $page = $pagejs;
		    $limit = ($per_page*($page-1)).','.$per_page;
		    if($total == 0) $limit = $per_page;
		    $sql .= " limit ".$limit;
        	$book = $this->mcdb->get_sql($sql,1);
        }else{
		    $like = array();
		    $wh['yid'] = $yid;
		    if(!empty($zd) && !empty($key)){
		    	if($zd == 'name' || $zd == 'author'){
		    		$like[$zd] = $key;
		    	}else{
		    		$wh['id'] = (int)$key;
		    	}
		    }
	        if(!empty($kstime)){
	        	$wh['addtime>'] = strtotime($kstime)-1;
	        }
	        if(!empty($jstime)){
	        	$wh['addtime<'] = strtotime($jstime)+86401;
	        }
	        if(!empty($name)) $like['name'] = $name;
	        if(!empty($serialize)) $like['serialize'] = $serialize;
	        if($sid > 0) $wh['sid'] = $sid-1;
	        if($cid > 0) $wh['cid'] = $cid;
	        if($tid > 0) $wh['tid'] = $tid-1;
	        if($pay > 0) $wh['pay'] = $pay-1;
	        if($size == 3) $wh['text_num>'] = 2999999;
	        if($size == 2){
	        	$wh['text_num>'] = 999999;
	        	$wh['text_num<'] = 3000000;
	        }
	        if($size == 1) $wh['text_num<'] = 1000000;

	        //总数量
		    $total = $this->mcdb->get_nums('book',$wh,$like);
			//每页数量
		    if($per_page == 0) $per_page = 20;
		    if($per_page > 500) $per_page = 100;
			//总页数
		    $pagejs = ceil($total / $per_page);
		    if($page > $pagejs) $page = $pagejs;
		    if($total < $per_page) $per_page = $total;
		    $limit = array($per_page,$per_page*($page-1));
		    $book = $this->mcdb->get_select('book','id,name,pic,serialize,author,uid,text_num,hits,sid,tid,nums,pay,addtime',$wh,'addtime DESC',$limit,$like);
		}
	    foreach ($book as $k => $v) {
	    	$book[$k]['text_num'] = format_wan($book[$k]['text_num']);
	    	$book[$k]['pic'] = getpic($book[$k]['pic']);
	    }
        //记录数组
        $data['count'] = $total;
        $data['data'] = $book;
		get_json($data,0);
	}
	//小说增加编辑
	public function edit($id=0){
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data = array('id' => 0,'cid' => 0,'sid' => 0,'yid' => 0,'tid' => 0,'name' => '','yname' => '','pic' => '','picx' => '','notice' => '','text' => '','serialize' => '连载中','author' => '','tags' => '','text_num' => 0,'notice' => '','content' => '','hits' => 0,'yhits' => 0,'zhits' => 0,'rhits' => 0,'score'=>9.8);
		}else{
            $data = $this->mcdb->get_row_arr("book","*",array('id'=>$id)); 
		}
		//class
		$data['class'] = $this->mcdb->get_select('book_class','id,name',array('fid'=>0),'xid ASC',100);
        $this->load->view('book/edit.tpl',$data);
	}
	//小说修改
	public function save(){
 	    $id = (int)$this->input->post('id');
		$type = $this->input->post('type',true);
		$push = $this->input->post('push',true);
		$addtime = $this->input->post('addtime',true);
		$data = array(
			'cid' => (int)$this->input->post('cid'),
			'sid' => (int)$this->input->post('sid'),
			'yid' => (int)$this->input->post('yid'),
			'tid' => (int)$this->input->post('tid'),
			'score' => (float)$this->input->post('score'),
			'name' => $this->input->post('name',true),
			'yname' => $this->input->post('yname',true),
			'notice' => $this->input->post('notice',true),
			'text' => $this->input->post('text',true),
			'pic' => $this->input->post('pic',true),
			'picx' => $this->input->post('picx',true),
			'msg' => $this->input->post('msg',true),
			'serialize' => $this->input->post('serialize',true),
			'author' => $this->input->post('author',true),
			'text_num' => (int)$this->input->post('text_num'),
			'tags' => $this->input->post('tags',true),
			'content' => $this->input->post('content',true),
			'hits' => (int)$this->input->post('hits'),
			'yhits' => (int)$this->input->post('yhits'),
			'zhits' => (int)$this->input->post('zhits'),
			'rhits' => (int)$this->input->post('rhits')
        );
		if(empty($data['name'])) get_json('标题不能为空~！');
		if($data['yid'] == 2 && empty($data['msg'])) get_json('未通过原因不能为空~！');
		if(empty($data['yname'])) $data['yname'] = $this->pinyin->send($data['name']);

		if($id == 0){
			$data['addtime'] = time();
            $id = $this->mcdb->get_insert('book',$data);
		}else{
			//判断小说审核
			$uid = getzd('book','uid',$id);
			if($uid > 0){
				$yid = getzd('book','yid',$id);
				if($yid == 1){
					if($data['yid'] == 0){ //通过
						$data['msg'] = '';
						$title = '您的新小说《'.$data['name'].'》审核已通过';
						$text = '亲爱的作者，您的新小说《'.$data['name'].'》于'.date('Y-m-d H:i:s').'时间审核已通过';
						//增加金币奖励
						if(Author_book_Cion > 0){
							$text.='，同时奖励您['.Author_book_Cion.']个'.Pay_Cion_Name;
							$this->db->query("update ".Mc_SqlPrefix."user set cion=cion+".Author_book_Cion." where id=".$uid);
						}
					}elseif($data['yid'] == 2){ //未通过
						$title = '您的新小说《'.$data['name'].'》审核不通过';
						$text = '亲爱的作者，您的新小说《'.$data['name'].'》于'.date('Y-m-d H:i:s').'时间审核不通过，不通过原因：'.$data['msg'];
					}
					//发送消息
					$add['uid'] = $uid;
					$add['name'] = $title;
					$add['text'] = $text;
					$add['addtime'] = time();
					$this->mcdb->get_insert('message',$add);
				}
			}
			if(!empty($addtime)) $data['addtime'] = time();
            $this->mcdb->get_update('book',$id,$data);
		}
		//判断推送URL
		if(!empty($push)){
			$this->load->library('push');
			$data['id'] = $id;
			$url = get_push_host(get_url('book_info',$data));
			$this->push->add($url);
		}
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('book');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
	//小说锁定开启
	public function init(){
 	    $id = (int)$this->input->post('id');
 	    $zt = $this->input->post('zt',true);
 	    if($id == 0) get_json('ID不能为空');

 	    $edit['sid'] = $zt == 'yes' ? 0 : 1;
 	    $this->mcdb->get_update('book',$id,$edit);
		get_json('恭喜您，操作成功~!',1);
	}
    //删除小说
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
    		$this->novel->del($_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('book');
		get_json($arr,1);
	}
	//章节列表
	public function chapter($yid=0){
		$data['yid'] = (int)$yid;
		$data['bid'] = (int)$this->input->get_post('id');
		$this->load->view('book/chapter.tpl',$data);
	}
	//章节列表JSON
	public function chapter_ajax($bid = 0,$yid=0){
		$bid = (int)$bid;
		$yid = (int)$yid;
 	    $page = (int)$this->input->get_post('page');
 	    $pay = (int)$this->input->get_post('pay');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
        if($bid == 0) $bid = 1;
	    $like = array();
	    $wh['yid'] = $yid;
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'name'){
	    		$like[$zd] = $key;
	    	}elseif($zd == 'id'){
	    		$bid = (int)$key;
	    		$wh['id'] = $bid;
	    	}else{
	    		$bid = (int)$key;
	    	}
	    }
	    if($bid > 0) $wh['bid'] = $bid;
        if(!empty($kstime)){
        	$wh['addtime>'] = strtotime($kstime)-1;
        }
        if(!empty($jstime)){
        	$wh['addtime<'] = strtotime($jstime)+86401;
        }
        if($pay > 0){
        	if($pay == 3){
        		$wh['cion>'] = 0;
        	}elseif($pay == 2){
        		$wh['vip'] = 1;
        	}elseif($pay == 1){
        		$wh['vip'] = 0;
        		$wh['cion'] = 0;
        	}

        }
        //章节表
        $chapter_table = get_chapter_table($bid);
        //先判断章节是否需要远程解析
        if($page < 2 || $bid > 0){
        	$znum = $this->mcdb->get_nums($chapter_table,array('bid'=>$bid));
        	$rowm = $this->mcdb->get_row_arr('book','nums,did,ly',array('id'=>$bid));
        	if($znum < $rowm['nums']){
        		$arr = require MCCMSPATH.'libs/collect.php';
        		$this->load->model('collect');
        		if(!empty($rowm['ly'])){
        			if(isset($arr['book_zyk'][$rowm['ly']])){
        				$this->collect->get_update_chapter($arr['book_zyk'][$rowm['ly']]['jxurl'].'/chapter/'.$rowm['did'],$bid,'book',$chapter_table,$arr['book_zyk'][$rowm['ly']]['token']);
        			}
        		}else{
        			$this->collect->get_update_chapter(Book_Caiji_Tb_Url.'/chapter/'.$rowm['did'],$bid,'book',$chapter_table,Book_Caiji_Tb_Token);
        		}
        	}
        }
        //总数量
	    $total = $this->mcdb->get_nums($chapter_table,$wh,$like);
		//每页数量
	    if($per_page == 0) $per_page = 20;
	    if($per_page > 500) $per_page = 100;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($page > $pagejs) $page = $pagejs;
	    if($total < $per_page) $per_page = $total;
	    $limit = array($per_page,$per_page*($page-1));
        //记录数组
        $data['count'] = $total;
	    $chapter = $this->mcdb->get_select($chapter_table,'*',$wh,'id DESC',$limit,$like);
	    foreach ($chapter as $k => $v) {
	    	$chapter[$k]['book_name'] = getzd('book','name',$v['bid']);
	    }
	    $data['data'] = $chapter;
		get_json($data,0);
	}
	//章节增加编辑
	public function chapter_edit($bid=0){
 	    $id = (int)$this->input->get_post('id');
 	    if($bid == 0) exit('ID不能为空');
        //章节表
        $chapter_table = get_chapter_table($bid);
 	    if($id == 0){
			$data = array(
				'id' => 0,
				'yid' => 0,
				'xid' => 0,
				'text_num' => 0,
				'text' => '',
				'name' => '',
				'vip' => 0,
				'cion' => 0,
				'msg' => ''
	        );
 	    }else{
			$data = $this->mcdb->get_row_arr($chapter_table,"*",array('id'=>$id));
			$data['text'] = get_book_txt($bid,$id);
			if(empty($data['text'])){
				$rowm = $this->mcdb->get_row_arr('book','did,ly',array('id'=>$bid));
				if(!empty($rowm['did'])){
					$arr = require MCCMSPATH.'libs/collect.php';
					$this->load->model('collect');
					if(!empty($rowm['ly'])){
	        			if(isset($arr['book_zyk'][$rowm['ly']])){
	        				$data['text'] = $this->collect->get_update_txt($arr['book_zyk'][$rowm['ly']]['jxurl'].'/txt/'.$rowm['did'].'/'.$data['xid'],$id,$bid,$chapter_table,$arr['book_zyk'][$rowm['ly']]['token']);
	        			}
	        		}else{
	        			$data['text'] = $this->collect->get_update_txt(Book_Caiji_Tb_Url.'/txt/'.$rowm['did'].'/'.$data['xid'],$id,$bid,$chapter_table,Book_Caiji_Tb_Token);
	        		}
				}
			}
 	    }
		$data['bid'] = $bid;
        $this->load->view('book/chapter_edit.tpl',$data);
	}
	//章节修改
	public function chapter_save($bid=0){
		$bid = (int)$bid;
		if($bid == 0) get_json('小说ID不能为空~！');
        //章节表
        $chapter_table = get_chapter_table($bid);
 	    $zid = $id = (int)$this->input->post('id');
		$text = $this->input->post('text');
		$data = array(
			'name' => $this->input->post('name',true),
			'vip' => (int)$this->input->post('vip'),
			'yid' => (int)$this->input->post('yid'),
			'xid' => (int)$this->input->post('xid'),
			'msg' => $this->input->post('msg',true),
			'cion' => (int)$this->input->post('cion'),
			'text_num' => (int)$this->input->post('text_num')
        );
		if(empty($data['name'])) get_json('标题不能为空~！');
		if($data['yid'] == 2 && empty($data['msg'])) get_json('未通过原因不能为空~！');
		if($data['text_num'] == 0) $data['text_num'] = mb_strlen($text,"UTF-8");
		if($id == 0){
			if($data['xid'] == 0){
				$xid = (int)getzd($chapter_table,'xid',$bid,'bid','xid desc');
				$data['xid'] = $xid +1;
			}else{
				$row = $this->mcdb->get_row_arr($chapter_table,'id',array('xid'=>$data['xid'],'bid'=>$bid));
				if($row) get_json('排序ID已存在~！');
			}
			$data['bid'] = $bid;
			$data['addtime'] = time();
            $id = $this->mcdb->get_insert($chapter_table,$data);
		}else{
			$yxid = getzd($chapter_table,'xid',$id);
			if($yxid != $data['xid']){
				$row = $this->mcdb->get_row_arr($chapter_table,'id',array('xid'=>$data['xid'],'bid'=>$bid));
				if($row) get_json('排序ID已存在~！');
			}
			//判断小说审核
			$uid = getzd('book','uid',$bid);
			if($uid > 0){
				$yid = getzd($chapter_table,'yid',$id);
				if($yid == 1){
					$mname = getzd('book','name',$bid);
					if($data['yid'] == 0){ //通过
						$data['msg'] = '';
						$title = '您发布的新章节审核已通过-小说：'.$mname;
						$mtext = '亲爱的作者，您的新小说《'.$mname.'》章节['.$data['name'].']于'.date('Y-m-d H:i:s').'时间审核已通过';
					}elseif($data['yid'] == 2){ //未通过
						$title = '您发布的新章节审核不通过-小说：'.getzd('book','name',$bid);
						$mtext = '亲爱的作者，您的新小说《'.$mname.'》章节['.$data['name'].']于'.date('Y-m-d H:i:s').'时间审核不通过，不通过原因：'.$data['msg'];
					}
					//发送消息
					$add['uid'] = $uid;
					$add['name'] = $title;
					$add['text'] = $mtext;
					$add['addtime'] = time();
					$this->mcdb->get_insert('message',$add);
				}
			}
			$data['addtime'] = time();
            $this->mcdb->get_update($chapter_table,$id,$data);
		}
        //写入txt文本
        get_book_txt($bid,$id,$text);
		//更新小说
		$pay = $data['vip'] == 1 ? 2 : ($data['cion'] > 0 ? 1 : 0);
		$this->novel->get_update_nums($bid,$chapter_table,array('pay'=>$pay,'addtime'=>time()));
		
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('book_chapter');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
    //删除章节
	public function chapter_del($bid=0,$id=0){
 	    $bid = (int)$bid;
 	    $id = (int)$id;
 	    if($bid == 0) get_json('小说ID不能为空~!');
 	    //章节表
        $chapter_table = get_chapter_table($bid);
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
    		$this->novel->chapter_del($_id,$chapter_table);
 	    }

		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('book','chapter');
		get_json($arr,1);
	}
    //批量设置章节VIP
	public function chapter_init($op='vip'){
    	$ids = $this->input->get_post('id',true);
    	$vip = (int)$this->input->get_post('vip');
    	$cion = (int)$this->input->get_post('cion');
    	$bid = (int)$this->input->get_post('bid');
    	$ids = implode(',',$ids);
    	if(is_numeric($ids) || preg_match('/^([0-9]+[,]?)+$/', $ids)){
			$id = $ids;
		}
 	    if($bid == 0) get_json('小说ID不能为空~!');
 	    if(empty($id)) get_json('ID不能为空~!');
 	    //章节表
        $chapter_table = get_chapter_table($bid);
 	    $arr = explode(',', $id);
 	    //排序
 	    if($op == 'px'){
    		$xids = $this->input->get_post('xid',true);
 	    	foreach ($arr as $k=>$_id) {
 	    		$_xid = $xids[$k];
    			$this->mcdb->get_update($chapter_table,$_id,array('xid'=>$_xid));
 	    	}
 	    	$arr['msg'] = '恭喜您，设置成功~!';
			$arr['url'] = links('book','chapter');
			get_json($arr,1);
 	    }
 	    if($vip != 1) $vip = 0;
 	    $edit = $op == 'vip' ? array('vip'=>$vip,'cion'=>0) : array('vip'=>$vip,'cion'=>$cion);
 	    foreach ($arr as $_id) {
    		$this->mcdb->get_update($chapter_table,$_id,$edit);
 	    }
		//修改小说收费状态
		if($op == 'vip'){
			$row = $this->mcdb->get_row($chapter_table,'id',array('vip'=>1,'bid'=>$bid));
			if($row){
				$pay = 2;
			}else{
				$row = $this->mcdb->get_row($chapter_table,'id',array('cion>'=>0,'bid'=>$bid));
				$pay = $row ? 1 : 0;
			}
		}else{
			$row = $this->mcdb->get_row($chapter_table,'id',array('cion>'=>0,'bid'=>$bid));
			if($row){
				$pay = 1;
			}else{
				$row = $this->mcdb->get_row($chapter_table,'id',array('vip'=>1,'bid'=>$bid));
				$pay = $row ? 2 : 0;
			}
		}
		$this->mcdb->get_update('book',$bid,array('pay'=>$pay));

		$arr['msg'] = '恭喜您，设置成功~!';
		$arr['url'] = links('book','chapter');
		get_json($arr,1);
	}
	//同步所有txt内容
	public function chapter_txt(){
		set_time_limit(0); //不超时
		$bid = (int)$this->input->get_post('bid');
		if($bid == 0) get_json('小说ID不能为空');
		$rowm = $this->mcdb->get_row_arr('book','did,ly',array('id'=>$bid));
		//章节表
        $chapter_table = get_chapter_table($bid);
		$chapter = $this->mcdb->get_select($chapter_table,'id,xid',array('bid'=>$bid),'id DESC',10000);
		$arr = require MCCMSPATH.'libs/collect.php';
		$this->load->model('collect');
	    foreach ($chapter as $k => $v) {
	    	$txt_file = FCPATH.'caches/txt/'.$bid.'/'.md5($v['id'].Mc_Book_Key).'.txt';
			if(!file_exists($txt_file)){
				if(!empty($rowm['ly'])){
        			if(isset($arr['book_zyk'][$rowm['ly']])){
        				$this->collect->get_update_txt($arr['book_zyk'][$rowm['ly']]['jxurl'].'/txt/'.$rowm['did'].'/'.$v['xid'],$v['id'],$bid,$chapter_table,$arr['book_zyk'][$rowm['ly']]['token']);
        			}
        		}else{
        			$this->collect->get_update_txt(Book_Caiji_Tb_Url.'/txt/'.$rowm['did'].'/'.$v['xid'],$v['id'],$bid,$chapter_table,Book_Caiji_Tb_Token);
        		}
			}
	    }
	    get_json('同步完成',1);
	}
	//分类列表
	public function lists(){
		$data['class'] = $this->mcdb->get_select('book_class','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('book/lists.tpl',$data);
	}
	//分类新增修改
	public function lists_edit($id = 0){
		$id = (int)$id;
		if($id == 0){
			$data = array('id'=>0,'name'=>'','tpl'=>'lists.html','yname'=>'','xid'=>0,'fid'=>0);
		}else{
			$data = $this->mcdb->get_row_arr('book_class','*',array('id'=>$id));
		}
		$data['class'] = $this->mcdb->get_select('book_class','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('book/lists_edit.tpl',$data);
	}
	//分类批量更新
	public function lists_save(){
		$ids = $this->input->post('ids',true);
		if(empty($ids)) get_json('请选择要修改的数据');
		foreach ($ids as $_id) {
			$_id = (int)$_id;
			if($_id > 0){
				$edit = array();
				$edit['name'] = $this->input->post('name_'.$_id,true);
				$edit['yname'] = $this->input->post('yname_'.$_id,true);
				$edit['tpl'] = $this->input->post('tpl_'.$_id,true);
				$edit['xid'] = (int)$this->input->post('xid_'.$_id);
				$this->mcdb->get_update('book_class',$_id,$edit);
			}
		}
		get_json('全部更新成功',1);
	}
	//分类更新
	public function lists_edit_save(){
		$edit['name'] = $this->input->post('name',true);
		$edit['yname'] = $this->input->post('yname',true);
		$edit['tpl'] = $this->input->post('tpl',true);
		$edit['xid'] = (int)$this->input->post('xid',true);
		$edit['fid'] = (int)$this->input->post('fid',true);
		$id = (int)$this->input->post('id',true);

		if(empty($edit['name'])) get_json('分类名称不能为空');
		if(empty($edit['yname'])) $edit['yname'] = $this->pinyin->send($edit['name']);

		if($id == 0){
			$res = $this->mcdb->get_insert('book_class',$edit);
		}else{
			$res = $this->mcdb->get_update('book_class',$id,$edit);
		}
		if(!$res) get_json('数据操作失败');

		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('book','lists');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
    //分类删除
	public function lists_del(){
 	    $id = $this->input->post('id',true);
 	    if(empty($id)) get_json('ID不能为空~!');
    	foreach ($id as $_id){
    		$_id = (int)$_id;
    		if($_id > 0) $this->mcdb->get_del('book_class',$_id);
    	}
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('book','lists');
		get_json($arr,1);
	}
}
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

class Comic extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		$this->load->model('manhua');
		$this->load->library('pinyin');
	}
	//漫画列表
	public function index($yid=0){
		$data['yid'] = $yid;
 	    $data['name'] = safe_replace($this->input->get_post('name',true));
		$data['class'] = $this->mcdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		$this->load->view('comic/index.tpl',$data);
	}
	//漫画列表JSON
	public function ajax($yid=0){
 	    $page = (int)$this->input->get_post('page');
 	    $cid = (int)$this->input->get_post('cid');
 	    $tid = (int)$this->input->get_post('tid');
 	    $sid = (int)$this->input->get_post('sid');
 	    $pay = (int)$this->input->get_post('pay');
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
        	$sql = "Select * From ".Mc_SqlPrefix."comic Where name in (Select name From ".Mc_SqlPrefix."comic Group By name Having Count(*)>1)";
	        //总数量
		    $total = $this->db->query($sql)->num_rows();
			//每页数量
		    if($per_page == 0) $per_page = 20;
		    if($per_page > 500) $per_page = 100;
			//总页数
		    $pagejs = ceil($total / $per_page);
		    if($page > $pagejs) $page = $pagejs;
		    $limit = $per_page*($page-1).','.$per_page;
		    if($total == 0) $limit = $per_page;
		    $sql .= " limit ".$limit;
        	$comic = $this->mcdb->get_sql($sql,1);
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

	        //总数量
		    $total = $this->mcdb->get_nums('comic',$wh,$like);
			//每页数量
		    if($per_page == 0) $per_page = 20;
		    if($per_page > 500) $per_page = 100;
			//总页数
		    $pagejs = ceil($total / $per_page);
		    if($page > $pagejs) $page = $pagejs;
		    if($total < $per_page) $per_page = $total;
		    $limit = array($per_page,$per_page*($page-1));
		    $comic = $this->mcdb->get_select('comic','id,name,pic,serialize,author,uid,hits,sid,tid,nums,pay,addtime',$wh,'addtime DESC',$limit,$like);
		}
	    foreach ($comic as $k => $v) {
	    	$comic[$k]['pic'] = getpic($comic[$k]['pic']);
	    }
        //记录数组
        $data['count'] = $total;
        $data['data'] = $comic;
		get_json($data,0);
	}
	//漫画增加编辑
	public function edit($id=0){
 	    $id = (int)$id;
	    $data = array();
		if($id==0){
            $data = array('id' => 0,'cid' => 0,'sid' => 0,'yid' => 0,'tid' => 0,'name' => '','yname' => '','pic' => '','picx' => '','notice' => '','text' => '','serialize' => '连载中','author' => '','pic_author' => '','txt_author' => '','notice' => '','content' => '','hits' => 0,'yhits' => 0,'zhits' => 0,'rhits' => 0,'score'=>9.8);
		}else{
            $data = $this->mcdb->get_row_arr("comic","*",array('id'=>$id)); 
		}
		//class
		$data['class'] = $this->mcdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		//type
		$data['type'] = $this->mcdb->get_select('type','id,name,zd,cid',array('fid'=>0),'xid ASC',20);
        $this->load->view('comic/edit.tpl',$data);
	}
	//漫画修改
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
			'pic_author' => $this->input->post('pic_author',true),
			'txt_author' => $this->input->post('txt_author',true),
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
            $id = $this->mcdb->get_insert('comic',$data);
		}else{
			//判断漫画审核
			$uid = getzd('comic','uid',$id);
			if($uid > 0){
				$yid = getzd('comic','yid',$id);
				if($yid == 1){
					if($data['yid'] == 0){ //通过
						$data['msg'] = '';
						$title = '您的新漫画《'.$data['name'].'》审核已通过';
						$text = '亲爱的作者，您的新漫画《'.$data['name'].'》于'.date('Y-m-d H:i:s').'时间审核已通过';
						//增加金币奖励
						if(Author_Comic_Cion > 0){
							$text.='，同时奖励您['.Author_Comic_Cion.']个'.Pay_Cion_Name;
							$this->db->query("update ".Mc_SqlPrefix."user set cion=cion+".Author_Comic_Cion." where id=".$uid);
						}
					}elseif($data['yid'] == 2){ //未通过
						$title = '您的新漫画《'.$data['name'].'》审核不通过';
						$text = '亲爱的作者，您的新漫画《'.$data['name'].'》于'.date('Y-m-d H:i:s').'时间审核不通过，不通过原因：'.$data['msg'];
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
            $this->mcdb->get_update('comic',$id,$data);
		}
		//更新附表内容
		$this->manhua->get_set_type($type,$id);
		//判断推送URL
		if(!empty($push)){
			$this->load->library('push');
			$data['id'] = $id;
			$url = get_push_host(get_url('show',$data));
			$this->push->add($url);
		}
		
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('comic');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
	//漫画锁定开启
	public function init(){
 	    $id = (int)$this->input->post('id');
 	    $zt = $this->input->post('zt',true);
 	    if($id == 0) get_json('ID不能为空');

 	    $edit['sid'] = $zt == 'yes' ? 0 : 1;
 	    $this->mcdb->get_update('comic',$id,$edit);
		get_json('恭喜您，操作成功~!',1);
	}
    //删除漫画
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
    		$this->manhua->del($_id);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comic');
		get_json($arr,1);
	}
	//章节列表
	public function chapter($yid=0){
		$data['yid'] = (int)$yid;
		$data['mid'] = (int)$this->input->get_post('id');
		$this->load->view('comic/chapter.tpl',$data);
	}
	//章节列表JSON
	public function chapter_ajax($mid = 0,$yid=0){
		$mid = (int)$mid;
		$yid = (int)$yid;
 	    $page = (int)$this->input->get_post('page');
 	    $pay = (int)$this->input->get_post('pay');
 	    $per_page = (int)$this->input->get_post('limit');
 	    $zd = safe_replace($this->input->get_post('zd',true));
 	    $key = safe_replace($this->input->get_post('key',true));
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

	    $like = array();
	    $wh['yid'] = $yid;
	    if(!empty($zd) && !empty($key)){
	    	if($zd == 'name'){
	    		$like[$zd] = $key;
	    	}elseif($zd == 'id'){
	    		$mid = (int)$key;
	    		$wh['id'] = $mid;
	    	}else{
	    		$mid = (int)$key;
	    	}
	    }
	    if($mid > 0) $wh['mid'] = $mid;
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
        //先判断章节是否需要远程解析
        if($page < 2 || $mid > 0){
        	$znum = $this->mcdb->get_nums('comic_chapter',array('mid'=>$mid));
        	$rowm = $this->mcdb->get_row_arr('comic','ly,nums,did',array('id'=>$mid));
        	if($znum < $rowm['nums'] && $rowm['did'] > 0){
                $this->load->model('collect');
                $arr = require MCCMSPATH.'libs/collect.php';
                if(!empty($rowm['ly'])){
                    if(isset($arr['zyk'][$rowm['ly']])){
                        $chapter = $this->collect->get_update_chapter($arr['zyk'][$rowm['ly']]['jxurl'].'/index/'.$rowm['did'],$mid,'comic','comic_chapter',$arr['zyk'][$rowm['ly']]['token']);
                    }
                }else{
                    $chapter = $this->collect->get_update_chapter(Caiji_Tb_Url.'/index/'.$rowm['did'],$mid,'comic','comic_chapter',Caiji_Tb_Token);
                }
                if(count($chapter) <= $row['nums']){
                    $this->mcdb->get_update('comic',$mid,array('nums'=>count($chapter)));
                }
        	}
        }
        //总数量
	    $total = $this->mcdb->get_nums('comic_chapter',$wh,$like);
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
	    $chapter = $this->mcdb->get_select('comic_chapter','*',$wh,'id DESC',$limit,$like);
	    foreach ($chapter as $k => $v) {
	    	$chapter[$k]['comic_name'] = getzd('comic','name',$v['mid']);
	    }
	    $data['data'] = $chapter;
		get_json($data,0);
	}
	//章节增加编辑
	public function chapter_edit($mid=0){
 	    $id = (int)$this->input->get_post('id');
 	    if($mid == 0) exit('ID不能为空');
 	    if($id == 0){
			$data = array(
				'id' => 0,
				'yid' => 0,
				'xid' => 0,
				'name' => '',
				'jxurl' => '',
				'vip' => 0,
				'cion' => 0,
				'msg' => '',
				'pic' => array()
	        );
 	    }else{
			$data = $this->mcdb->get_row_arr("comic_chapter","*",array('id'=>$id));
			$data['pic'] = $this->mcdb->get_select('comic_pic','*',array('cid'=>$id),'xid ASC',10000);
 	    }
		$data['mid'] = $mid;
        $this->load->view('comic/chapter_edit.tpl',$data);
	}
	//章节修改
	public function chapter_save($mid=0){
		$mid = (int)$mid;
		if($mid == 0) get_json('漫画ID不能为空~！');
 	    $zid = $id = (int)$this->input->post('id');
		$pic = $this->input->post('pic',true);
		$data = array(
			'name' => $this->input->post('name',true),
			'jxurl' => $this->input->post('jxurl',true),
			'vip' => (int)$this->input->post('vip'),
			'yid' => (int)$this->input->post('yid'),
			'xid' => (int)$this->input->post('xid'),
			'msg' => $this->input->post('msg',true),
			'cion' => (int)$this->input->post('cion'),
			'pnum' => count($pic)
        );
		if(empty($data['name'])) get_json('标题不能为空~！');
		if($data['yid'] == 2 && empty($data['msg'])) get_json('未通过原因不能为空~！');

		if($id == 0){
			if($data['xid'] == 0){
				$xid = (int)getzd('comic_chapter','xid',$mid,'mid','xid desc');
				$data['xid'] = $xid +1;
			}else{
				$row = $this->mcdb->get_row_arr('comic_chapter','id',array('xid'=>$data['xid'],'mid'=>$mid));
				if($row) get_json('排序ID已存在~！');
			}
			$data['mid'] = $mid;
			$data['addtime'] = time();
            $id = $this->mcdb->get_insert('comic_chapter',$data);
            //更新漫画总章节数
            $this->db->query('update '.Mc_SqlPrefix.'comic set nums=nums+1,addtime='.time().' where id='.$mid);
		}else{
			$yxid = getzd('comic_chapter','xid',$id);
			if($yxid != $data['xid']){
				$row = $this->mcdb->get_row_arr('comic_chapter','id',array('xid'=>$data['xid'],'mid'=>$mid));
				if($row) get_json('排序ID已存在~！');
			}
			//判断漫画审核
			$uid = getzd('comic','uid',$mid);
			if($uid > 0){
				$yid = getzd('comic_chapter','yid',$id);
				if($yid == 1){
					$mname = getzd('comic','name',$mid);
					if($data['yid'] == 0){ //通过
						$data['msg'] = '';
						$title = '您发布的新章节审核已通过-漫画：'.$mname;
						$text = '亲爱的作者，您的新漫画《'.$mname.'》章节['.$data['name'].']于'.date('Y-m-d H:i:s').'时间审核已通过';
					}elseif($data['yid'] == 2){ //未通过
						$title = '您发布的新章节审核不通过-漫画：'.getzd('comic','name',$mid);
						$text = '亲爱的作者，您的新漫画《'.$mname.'》章节['.$data['name'].']于'.date('Y-m-d H:i:s').'时间审核不通过，不通过原因：'.$data['msg'];
					}
					//发送消息
					$add['uid'] = $uid;
					$add['name'] = $title;
					$add['text'] = $text;
					$add['addtime'] = time();
					$this->mcdb->get_insert('message',$add);
				}
			}
			$data['addtime'] = time();
            $this->mcdb->get_update('comic_chapter',$id,$data);
            $this->mcdb->get_update('comic',$mid,array('addtime'=>time()));
		}

		//修改图片排序
		if(!empty($pic)){
			foreach ($pic as $xid => $pid) {
				$this->mcdb->get_update('comic_pic',$pid,array('xid'=>$xid,'cid'=>$id));
			}
		}
		//修改漫画收费状态
		if($data['vip'] == 1){
			$pay = 2;
		}else{
			$pay = $data['cion'] > 0 ? 1 : 0;
		}
		$this->mcdb->get_update('comic',$mid,array('pay'=>$pay));
		
		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('comic_chapter');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
    //删除章节
	public function chapter_del($id=0){
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
 	    $min = 0;
 	    foreach ($arr as $_id) {
 	    	if($mid == 0) $mid = (int)getzd('comic_chapter','mid',$_id);
    		$this->manhua->chapter_del($_id);
 	    }
 	    //更新漫画章节数
 	    if($mid > 0) $this->mcdb->get_update('comic',$mid,array('nums'=>(int)$this->mcdb->get_nums('comic_chapter',array('mid'=>$mid,'yid'=>0)),'id'=>$mid));
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comic','chapter');
		get_json($arr,1);
	}
    //批量设置章节VIP
	public function chapter_init($op='vip'){
    	$ids = $this->input->get_post('id',true);
    	$vip = (int)$this->input->get_post('vip');
    	$cion = (int)$this->input->get_post('cion');
    	$mid = (int)$this->input->get_post('mid');
    	$ids = implode(',',$ids);
    	if(is_numeric($ids) || preg_match('/^([0-9]+[,]?)+$/', $ids)){
			$id = $ids;
		}
 	    if(empty($id)) get_json('ID不能为空~!');
 	    $arr = explode(',', $id);
 	    //排序
 	    if($op == 'px'){
    		$xids = $this->input->get_post('xid',true);
 	    	foreach ($arr as $k=>$_id) {
 	    		$_xid = $xids[$k];
    			$this->mcdb->get_update('comic_chapter',$_id,array('xid'=>$_xid));
 	    	}
 	    	$arr['msg'] = '恭喜您，设置成功~!';
			$arr['url'] = links('comic','chapter');
			get_json($arr,1);
 	    }
 	    if($vip != 1) $vip = 0;
 	    $edit = $op == 'vip' ? array('vip'=>$vip,'cion'=>0) : array('vip'=>$vip,'cion'=>$cion);
 	    foreach ($arr as $_id) {
    		$this->mcdb->get_update('comic_chapter',$_id,$edit);
 	    }
		//修改漫画收费状态
		if($op == 'vip'){
			$row = $this->mcdb->get_row('comic_chapter','id',array('vip'=>1,'mid'=>$mid));
			if($row){
				$pay = 2;
			}else{
				$row = $this->mcdb->get_row('comic_chapter','id',array('cion>'=>0,'mid'=>$mid));
				$pay = $row ? 1 : 0;
			}
		}else{
			$row = $this->mcdb->get_row('comic_chapter','id',array('cion>'=>0,'mid'=>$mid));
			if($row){
				$pay = 1;
			}else{
				$row = $this->mcdb->get_row('comic_chapter','id',array('vip'=>1,'mid'=>$mid));
				$pay = $row ? 2 : 0;
			}
		}
		$this->mcdb->get_update('comic',$mid,array('pay'=>$pay));

		$arr['msg'] = '恭喜您，设置成功~!';
		$arr['url'] = links('comic','chapter');
		get_json($arr,1);
	}
    //删除章节图片
	public function pic_del(){
 	    $ac = $this->input->post('ac',true);
	 	$id = (int)$this->input->post('id');
	 	if(empty($id)) get_json('ID不能为空~!');
	 	$cid = getzd('comic_pic','cid',$id);
 	    if($ac == 'all'){
	 	    $this->manhua->pic_del_all($id);
	 	    //更新图片数
        	$this->db->query('update '.Mc_SqlPrefix.'comic_chapter set pnum=0 where id='.$cid);
 	    }else{
	 	    $this->manhua->pic_del($id,'pic');
	 	    //更新图片数
	 	    $this->db->query('update '.Mc_SqlPrefix.'comic_chapter set pnum=pnum-1 where id='.$cid);
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		get_json($arr,1);
	}
	//一键同步章节图片
	public function tbpic($mid=0,$tb=0){
		$row = $this->mcdb->get_row_arr('comic_chapter','id',array('mid'=>$mid),'xid asc');
		$data['mid'] = (int)$mid;
		$data['cid'] = $row ? $row['id'] : 0;
		$data['tb'] = (int)$tb;
        $this->load->view('comic/pic_tb.tpl',$data);
	}
	//同步开始
	public function tbpic_save(){
		$mid = (int)$this->input->get_post('mid');
		$cid = (int)$this->input->get_post('cid');
		$tb = (int)$this->input->get_post('tb');
		if($mid == 0) get_json('Mid不能为空',0);
		if($cid == 0){
			$html = '<tr><td><b style="color:#080;">所有章节图片全部同步完成...</b></td></tr>';
	        get_json(array('html'=>$html),2);
		}
		$row = $this->mcdb->get_row_arr('comic_chapter','*',array('id'=>$cid));
		//解析采集图片
		if(!empty($row['jxurl'])){
			$res = $this->pic_api($row['jxurl'],$mid,$cid,$tb,1);
		}else{ //获取车头或者其他入库资源
			$sql = "SELECT id,img FROM ".Mc_SqlPrefix."comic_pic where (Lower(Left(img,7))='http://' or  Lower(Left(img,2))='//' or  Lower(Left(img,8))='https://') and cid=".$cid." order by xid ASC";
			$pic_arr = $this->db->query($sql)->result_array();
			if(empty($pic_arr)){
	            $html = '<tr><td style="color:red;">第'.$row['xid'].'章《'.$row['name'].'》，没有需要同步的图片，跳过...</td></tr>';
	            $rowx = $this->mcdb->get_row_arr('comic_chapter','id',array('xid>'=>$row['xid'],'mid'=>$mid),'xid asc');
				$xid = $rowx ? $rowx['id'] : 0;
	            get_json(array('html'=>$html,'cid'=>$xid),1);
			}
			$res = array();
			foreach ($pic_arr as $row2) {
				$edit['img'] = get_downpic($row2['img']);
				$res[] = $this->mcdb->get_update('comic_pic',$row2['id'],$edit);
			}
		}
		if($res){
			$html = '<tr><td>第'.$row['xid'].'章《'.$row['name'].'》，同步完成，共同步'.count($res).'张图片</td></tr>';
		}else{
			$html = '<tr><td style="color:#f90;">第'.$row['xid'].'章《'.$row['name'].'》，同步失败...</td></tr>';
		}
		$rowx = $this->mcdb->get_row_arr('comic_chapter','id',array('xid>'=>$row['xid'],'mid'=>$mid),'xid asc');
		$xid = $rowx ? $rowx['id'] : 0;
		get_json(array('html'=>$html,'cid'=>$xid),1);
	}
	//解析图片
	public function pic_api($url='',$mid=0,$cid=0,$tb=0,$return=0){
		if(empty($url)) $url = $this->input->get_post('url',true);
		if(empty($cid)) $cid = (int)$this->input->get_post('cid');
		if(empty($mid)) $mid = (int)$this->input->get_post('mid');
		if(empty($url)) get_json('解析地址不能为空');
		if($mid == 0) get_json('Mid不能为空');
		if($cid == 0) get_json('章节ID不能为空');
		$row = $this->mcdb->get_row_arr('comic','*',array('id'=>$mid));
		if(!$row) get_json('漫画不存在');
		//解析
		$this->load->model('collect');
        $collect = require MCCMSPATH.'libs/collect.php';
        $ly = $row['ly'];
        $token = isset($collect['zyk'][$ly]) ? $collect['zyk'][$ly]['token'] : Caiji_Tb_Token;
        $picarr = $this->collect->get_update_pic($url,$cid,$mid,$token,$tb);
		if(empty($picarr)){
			if($return == 1){
				return false;
			}else{
				get_json('解析失败!!!');
			}
		}
		if($return == 1) return $picarr;
		$pic_arr = $this->mcdb->get_select('comic_pic','*',array('cid'=>$cid),'xid ASC',10000);
		$data['msg'] = '解析成功';
		$data['pic'] = $pic_arr;
		get_json($data,1);
	}
	//手动添加图片
	public function pic_save(){
		$pic = str_replace("\n\n", "\n", str_replace("\r", "\n", $this->input->post('pic')));
		$cid = (int)$this->input->post('cid');
		$xid = (int)$this->input->post('xid');
		$mid = (int)$this->input->post('mid');
		$tb = (int)$this->input->post('tb');
		if(empty($pic)) get_json('您至少填写一组图片地址');
		if($mid == 0) get_json('Mid不能为空');
        $n = 0;
        $pic_arr = array();
        $pic = explode("\n",$pic);
        foreach ($pic as $k2 => $_pic) {
        	if(!empty($_pic)){
	            $md5 = md5($_pic);
	            //判断图片是否存在
	            $row2 = $this->mcdb->get_row_arr('comic_pic','id',array('md5'=>$md5));
	            if(!$row2){
	                //下载图片到本地
	                if($tb == 1) $_pic = get_downpic($_pic);
	                if($_pic){
		                $add['img'] = $_pic;
		                $add['cid'] = $cid;
		                $add['mid'] = $mid;
		                $add['md5'] = $md5;
		                $add['xid'] = $xid++;
		                $pic_arr[$n]['id'] = $this->mcdb->get_insert('comic_pic',$add);
		                $pic_arr[$n]['img'] = getpic($add['img']);
		                $n++;
	                }
	            }
        	}
        }
        //更新章节图片总数
        $this->mcdb->get_update('comic_chapter',$cid,array('pnum'=>$xid));
        //输出
		$data['msg'] = '添加成功';
		$data['pic'] = $pic_arr;
		get_json($data,1);
	}
	//分类列表
	public function lists(){
		$data['class'] = $this->mcdb->get_select('class','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('comic/lists.tpl',$data);
	}
	//分类新增修改
	public function lists_edit($id = 0){
		$id = (int)$id;
		if($id == 0){
			$data = array('id'=>0,'name'=>'','tpl'=>'lists.html','yname'=>'','xid'=>0,'fid'=>0);
		}else{
			$data = $this->mcdb->get_row_arr('class','*',array('id'=>$id));
		}
		$data['class'] = $this->mcdb->get_select('class','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('comic/lists_edit.tpl',$data);
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
				$this->mcdb->get_update('class',$_id,$edit);
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
			$res = $this->mcdb->get_insert('class',$edit);
		}else{
			$res = $this->mcdb->get_update('class',$id,$edit);
		}
		if(!$res) get_json('数据操作失败');

		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('comic','lists');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
    //分类章节
	public function lists_del(){
 	    $id = $this->input->post('id',true);
 	    if(empty($id)) get_json('ID不能为空~!');
    	foreach ($id as $_id){
    		$_id = (int)$_id;
    		if($_id > 0) $this->mcdb->get_del('class',$_id);
    	}
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comic','lists');
		get_json($arr,1);
	}
	//类型列表
	public function type(){
		$data['type'] = $this->mcdb->get_select('type','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('comic/type.tpl',$data);
	}
	//类型新增修改
	public function type_add($fid=0,$id=0){
		if($id == 0){
			$data = array(
				'id'=>0,
				'fid'=>$fid,
				'name'=>'',
				'xid'=>'',
				'cid'=>'',
				'zd'=>''
			);
		}else{
			$data = $this->mcdb->get_row_arr('type','*',array('id'=>$id));
		}
		$data['type'] = $this->mcdb->get_select('type','*',array('fid'=>0),'xid ASC',100);
		$this->load->view('comic/type_edit.tpl',$data);
	}
	//类型批量更新
	public function type_save(){
		$ids = $this->input->post('ids',true);
		if(empty($ids)) get_json('请选择要修改的数据');
		foreach ($ids as $_id) {
			$_id = (int)$_id;
			if($_id > 0){
				$edit = array();
				$edit['name'] = $this->input->post('name_'.$_id,'true');
				$edit['cid'] = (int)$this->input->post('cid_'.$_id);
				$edit['xid'] = (int)$this->input->post('xid_'.$_id);
				$this->mcdb->get_update('type',$_id,$edit);
			}
		}
		get_json('全部更新成功',1);
	}
	//类型增加
	public function type_add_save(){
		$id = (int)$this->input->post('id');
		$fid = (int)$this->input->post('fid');
		$edit['name'] = $this->input->post('name',true);
		$edit['xid'] = (int)$this->input->post('xid',true);
		$edit['fid'] = $fid;

		if(empty($edit['name'])) get_json('名称不能为空');
		if($fid > 0){
			$row = $this->mcdb->get_row_arr('type','zd,cid',array('id'=>$fid));
			$edit['cid'] = $row['cid'];
			$edit['zd'] = $row['zd'];
		}else{
			$edit['cid'] = (int)$this->input->post('cid',true);
			$edit['zd'] = $this->input->post('zd',true);
			if(!preg_match("/^[A-Za-z]/",$edit['zd'])) get_json('字段只能是2-15位英文字母');
		}
		if($id == 0){
			$res = $this->mcdb->get_insert('type',$edit);
		}else{
			$res = $this->mcdb->get_update('type',$id,$edit);
		}
		if(!$res) get_json('数据操作失败');

		$arr['msg'] = '恭喜您，操作成功~!';
		$arr['url'] = links('comic','type');
		$arr['parent'] = 1;
		get_json($arr,1);
	}
    //类型章节
	public function type_del(){
 	    $sid = (int)$this->input->post('sid',true);
 	    $id = $this->input->post('id',true);
 	    if(empty($id)) get_json('ID不能为空~!');
 	    if(is_array($id)){
	    	foreach ($id as $_id){
	    		$_id = (int)$_id;
	    		if($_id > 1) $this->mcdb->get_del('type',$_id);
	    	}	
 	    }else{
 	    	$id = (int)$id;
 	    	if($id > 1){ //默认TAGS标签不给删除
	 	    	if($sid == 2){
	 	    		$this->mcdb->get_del('type',$id);
	 	    	}else{
	 	    		$this->mcdb->get_del('type',$id,'fid');
	 	    	}
 	    	}
 	    }
		$arr['msg'] = '恭喜您，删除成功~!';
		$arr['url'] = links('comic','type');
		get_json($arr,1);
	}
}
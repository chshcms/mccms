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

class Receive extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('pinyin');
		//判断密码
		$pass = $this->input->get_post('pass');
		if(Web_Rkpass == '123456') get_json('初始密码未修改');
		if($pass != Web_Rkpass) get_json('密码错误');
	}

	//漫画入库
    public function comic() {
    	//类型tags标签
        $type = $this->input->get_post('type');
		$data = array(
			'cid' => $this->input->get_post('cid',true),
			'tid' => (int)$this->input->get_post('tid'),
			'uid' => (int)$this->input->get_post('uid'),
			'score' => (float)$this->input->get_post('score'),
			'name' => $this->input->get_post('name',true),
			'notice' => $this->input->get_post('notice',true),
			'text' => $this->input->get_post('text',true),
			'pic' => $this->input->get_post('pic',true),
			'picx' => $this->input->get_post('picx',true),
			'serialize' => $this->input->get_post('serialize',true),
			'author' => $this->input->get_post('author',true),
			'pic_author' => $this->input->get_post('pic_author',true),
			'txt_author' => $this->input->get_post('txt_author',true),
			'content' => $this->input->get_post('content',true),
			'hits' => (int)$this->input->get_post('hits'),
			'yhits' => (int)$this->input->get_post('yhits'),
			'zhits' => (int)$this->input->get_post('zhits'),
			'rhits' => (int)$this->input->get_post('rhits')
        );
        $data = safe_replace($data);
        if(empty($data['name'])) get_json('漫画名称不能为空');
        if(empty($data['cid'])) get_json('漫画分类不能为空');
        if(!is_numeric($data['cid'])){
	        //判断分类是否存在
	        $rowc = $this->mcdb->get_row_arr('class','id',array('name'=>$data['cid']));
	        if($rowc){
	        	$data['cid'] = $rowc['id'];
	        }else{
	        	$data['cid'] = $this->mcdb->get_insert('class',array(
	        		'name'=>$data['cid'],
	        		'yname'=>$this->pinyin->send($data['cid']),
	        		'tpl'=>'lists.html'
	        	));
	        }
	    }
        if($data['score'] == 0) $data['score'] = 9.9;
		if(empty($data['yname'])) $data['yname'] = $this->pinyin->send($data['name']);
		$data['addtime'] = time();

		//判断漫画是否存在
		$row = $this->mcdb->get_row_arr('comic','id,author',array('name'=>$data['name'],'cid'=>$data['cid']));
		if(!$row){
            $id = $this->mcdb->get_insert('comic',$data);
		}else{
			//判断作者是否相同
			if($row['author'] == $data['author']){
            	$this->mcdb->get_update('comic',$row['id'],$data);
            	$id = $row['id'];
			}else{
				$data['name'].=' - '.$data['author'];
				$data['yname'] = $this->pinyin->send($data['name']);
            	$id = $this->mcdb->get_insert('comic',$data);
			}
		}
		//更新附表内容
		if(!empty($type)){
			if(!is_array($type)) $type = explode(',', $type);
			$this->get_set_tags($type,$id);
		}
		//判断推送URL
		if(!empty($push)){
			$this->load->library('push');
			$data['id'] = $id;
			$url = get_push_host(get_url('show',$data));
			$this->push->add($url);
		}
		get_json('恭喜您，入库成功~!',1);
	}

	//漫画章节入库
    public function chapter() {
        $mid = (int)$this->input->get_post('mid');
        $mname = safe_replace($this->input->get_post('mname'));
        $mauthor = safe_replace($this->input->get_post('mauthor'));
		if($mid == 0 && empty($mname)) get_json('漫画ID和漫画标题不能都为空~！');
		//判断漫画是否存在
		if($mid == 0){
			$where = !empty($mauthor) ? array('name'=>$mname,'author'=>$mauthor) : array('name'=>$mname);
			$rowm = $this->mcdb->get_row_arr('comic','id',$where);
			if(!$rowm) get_json('漫画不存在~！');
			$mid = $rowm['id'];
		}else{
			$rowm = $this->mcdb->get_row_arr('comic','name',array('id'=>$mid));
			if(!$rowm) get_json('漫画ID不存在~！');
		}
		$pic = $this->input->get_post('pic');
		if(!is_array($pic)) $pic = explode('###',$pic);
		$data = array(
			'name' => $this->input->get_post('name',true),
			'jxurl' => $this->input->get_post('jxurl',true),
			'vip' => (int)$this->input->get_post('vip'),
			'yid' => (int)$this->input->get_post('yid'),
			'xid' => (int)$this->input->get_post('xid'),
			'msg' => $this->input->get_post('msg',true),
			'cion' => (int)$this->input->get_post('cion'),
			'pnum' => count($pic)
        );
		if(empty($data['name'])) get_json('章节名称不能为空~！');
		$data['addtime'] = time();

		//判断章节是否存在
		$row = $this->mcdb->get_row_arr('comic_chapter','id',array('name'=>$data['name'],'mid'=>$mid,'xid'=>$data['xid']));
		if(!$row){
			if($data['xid'] == 0){
				$data['xid'] = (int)getzd('comic_chapter','xid',$mid,'mid','xid desc');
				$data['xid']++;
			}
			$data['mid'] = $mid;
            $id = $this->mcdb->get_insert('comic_chapter',$data);
            //更新漫画总章节数
            $this->db->query('update '.Mc_SqlPrefix.'comic set nums=nums+1,addtime='.time().' where id='.$mid);
		}else{
			unset($data['xid']);
            $this->mcdb->get_update('comic_chapter',$row['id'],$data);
            $id = $row['id'];
		}

		//图片入库
		if(!empty($pic)){
			foreach ($pic as $xid => $v) {
				$md5 = md5($v);
				$row = $this->mcdb->get_row_arr('comic_pic','id',array('md5'=>$md5,'cid'=>$id));
				if(!$row){
					$this->mcdb->get_insert('comic_pic',array('cid'=>$id,'mid'=>$mid,'img'=>$v,'md5'=>$md5,'xid'=>$xid));
				}
			}
		}
		//修改漫画收费状态
		if($data['vip'] > 0 || $data['cion'] > 0){
			$pay = $data['vip'] > 0 ? 2 : ($data['cion'] > 0 ? 1 : 0);
			$this->mcdb->get_update('comic',$mid,array('pay'=>$pay));
		}
		get_json('入库完成',1);
    }

	//小说入库
    public function book() {
		$data = array(
			'cid' => $this->input->get_post('cid',true),
			'tid' => (int)$this->input->get_post('tid'),
			'uid' => (int)$this->input->get_post('uid'),
			'score' => (float)$this->input->get_post('score'),
			'name' => $this->input->get_post('name',true),
			'tags' => $this->input->get_post('tags',true),
			'notice' => $this->input->get_post('notice',true),
			'text' => $this->input->get_post('text',true),
			'pic' => $this->input->get_post('pic',true),
			'picx' => $this->input->get_post('picx',true),
			'serialize' => $this->input->get_post('serialize',true),
			'author' => $this->input->get_post('author',true),
			'text_num' => (int)$this->input->get_post('text_num',true),
			'content' => $this->input->get_post('content',true),
			'hits' => (int)$this->input->get_post('hits'),
			'yhits' => (int)$this->input->get_post('yhits'),
			'zhits' => (int)$this->input->get_post('zhits'),
			'rhits' => (int)$this->input->get_post('rhits')
        );
        $data = safe_replace($data);
        if(empty($data['name'])) get_json('小说名称不能为空');
        if(empty($data['cid'])) get_json('小说分类不能为空');
        if(!is_numeric($data['cid'])){
	        //判断分类是否存在
	        $rowc = $this->mcdb->get_row_arr('book_class','id',array('name'=>$data['cid']));
	        if($rowc){
	        	$data['cid'] = $rowc['id'];
	        }else{
	        	$data['cid'] = $this->mcdb->get_insert('book_class',array(
	        		'name'=>$data['cid'],
	        		'yname'=>$this->pinyin->send($data['cid']),
	        		'tpl'=>'lists.html'
	        	));
	        }
        }
        if($data['score'] == 0) $data['score'] = 9.9;
		if(empty($data['yname'])) $data['yname'] = $this->pinyin->send($data['name']);
		$data['addtime'] = time();

		//判断小说是否存在
		$row = $this->mcdb->get_row_arr('book','id,author',array('name'=>$data['name'],'cid'=>$data['cid']));
		if(!$row){
            $id = $this->mcdb->get_insert('book',$data);
		}else{
			//判断作者是否相同
			if($row['author'] == $data['author']){
            	$this->mcdb->get_update('book',$row['id'],$data);
            	$id = $row['id'];
			}else{
				$data['name'].=' - '.$data['author'];
				$data['yname'] = $this->pinyin->send($data['name']);
            	$id = $this->mcdb->get_insert('book',$data);
			}
		}
		//判断推送URL
		if(!empty($push)){
			$this->load->library('push');
			$data['id'] = $id;
			$url = get_push_host(get_url('book_info',$data));
			$this->push->add($url);
		}
		get_json('恭喜您，入库成功~!',1);
	}

	//小说章节入库
    public function book_chapter() {
        $bid = (int)$this->input->get_post('bid');
        $bname = safe_replace($this->input->get_post('bname'));
        $bauthor = safe_replace($this->input->get_post('bauthor'));
		if($bid == 0 && empty($bname)) get_json('小说ID和小说标题不能都为空~！');
		//判断漫画是否存在
		if($bid == 0){
			$where = !empty($bauthor) ? array('name'=>$bname,'author'=>$bauthor) : array('name'=>$bname);
			$rowb = $this->mcdb->get_row_arr('book','id',$where);
			if(!$rowb) get_json('小说不存在~！');
			$bid = $rowb['id'];
		}else{
			$rowb = $this->mcdb->get_row_arr('book','name',array('id'=>$bid));
			if(!$rowb) get_json('小说ID不存在~！');
		}
		$text = $this->get_txt_replace($this->input->get_post('text'));
		if(empty($text)) get_json('章节TXT文本内容不能为空~！');
		$data = array(
			'name' => $this->input->get_post('name',true),
			'vip' => (int)$this->input->get_post('vip'),
			'yid' => (int)$this->input->get_post('yid'),
			'xid' => (int)$this->input->get_post('xid'),
			'msg' => $this->input->get_post('msg',true),
			'cion' => (int)$this->input->get_post('cion'),
			'text_num' => (int)$this->input->get_post('text_num')
        );
		if(empty($data['name'])) get_json('章节名称不能为空~！');
		$data['addtime'] = time();
		if($data['text_num'] == 0) $data['text_num'] = mb_strlen($text,"UTF-8");
		//判断章节是否存在
		$chapter_table = get_chapter_table($bid);//章节表
		$wh = $data['xid'] == 0 ? array('bid'=>$bid,'name'=>$data['name']) : array('bid'=>$bid,'xid'=>$data['xid']);
		$row = $this->mcdb->get_row_arr($chapter_table,'id',$wh);
		if(!$row){
			//最大xid
			if($data['xid'] == 0){
				$xid = (int)getzd($chapter_table,'xid',$bid,'bid','xid desc');
				$data['xid'] = $xid +1;
			}
			$data['bid'] = $bid;
            $id = $this->mcdb->get_insert($chapter_table,$data);
            //更新小说总章节数
            $this->db->query('update '.Mc_SqlPrefix.'book set nums=nums+1,text_num=text_num+'.$data['text_num'].',addtime='.time().' where id='.$bid);
		}else{
			unset($data['xid']);
            $this->mcdb->get_update($chapter_table,$row['id'],$data);
            $id = $row['id'];
		}
        //写入txt文本
        get_book_txt($bid,$id,$text);
		//修改收费状态
		if($data['vip'] > 0 || $data['cion'] > 0){
			$pay = $data['vip'] > 0 ? 2 : ($data['cion'] > 0 ? 1 : 0);
			$this->mcdb->get_update('book',$bid,array('pay'=>$pay));
		}
		get_json('入库完成',1);
    }

    //更新TAGS主题
    private function get_set_tags($tarr,$mid=0){
        foreach ($tarr as $v) {
            $row = $this->mcdb->get_row('type','id',array('name'=>$v,'zd'=>'tags'));
            if(!$row){
                //获取最大ID
                $row = $this->mcdb->get_row('type','id',array('zd'=>'tags'),'xid DESC');
                $tid = $this->mcdb->get_insert('type',array('name'=>$v,'zd'=>'tags','fid'=>1,'cid'=>1,'xid'=>($row->id+1)));
            }else{
                $tid = $row->id;
            }
            $this->mcdb->get_insert('comic_type',array('tid'=>$tid,'mid'=>$mid));
        }
        return true;
    }

    //替换章节TXT文本内容
    private function get_txt_replace($txt){
        $arr1 = array('<p>','</p>','<br>','<br>','<br />','<br/>',"\r");
        $arr2 = array("","\n","\n","\n","\n","\n","");
        $txt = str_replace($arr1, $arr2, $txt);
        $txt = str_replace("\n\n", "\n", $txt);
        return $txt;
    }
}
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

class Generate extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		$this->load->model('statics');
	}

	//自定义模板
	public function custom(){
		$data = array();
		//获取自定义模版
		$this->load->helper('file');
		//获取所有PC模板
		$tplpath = FCPATH.'template/'.Skin_Pc_Path.'/custom';
		$file_arr = get_filenames($tplpath);
		$farr = array();
		if(!empty($file_arr)){
	        foreach ($file_arr as $file) {
				$farr[] = $file;
			}
		}
        $data['tpl'] = $farr;
		//获取所有WAP模板
		$tplpath = FCPATH.'template/'.Skin_Wap_Path.'/custom';
		$file_arr = get_filenames($tplpath);
		$farr = array();
		if(!empty($file_arr)){
	        foreach ($file_arr as $file) {
				$farr[] = $file;
			}
		}
        $data['waptpl'] = $farr;
		$this->load->view('generate/custom.tpl',$data);
	}

	//漫画
	public function comic(){
		$data['class'] = $this->mcdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		$this->load->view('generate/comic.tpl',$data);
	}

	//小说
	public function book(){
		$data['class'] = $this->mcdb->get_select('book_class','id,name',array('fid'=>0),'xid ASC',100);
		$this->load->view('generate/book.tpl',$data);
	}

	//生成窗口
	public function mark($type=''){
		$op = $this->input->get_post('op',true);
		$data['link'] = links('generate',$op.'_save/'.$type);
		if($op == 'lists'){
			$data['post'] = json_encode(array('id'=>$this->input->get_post('id',true)));
		}else{
			$post['do'] = $this->input->get_post('do',true);
			$post['cid'] = $this->input->get_post('id',true);
			$post['day'] = (int)$this->input->get_post('day');
			$post['ksid'] = (int)$this->input->get_post('ksid');
			$post['jsid'] = (int)$this->input->get_post('jsid');
			$post['mid'] = (int)$this->input->get_post('mid');
			$data['post'] = json_encode($post);
		}
		$this->load->view('generate/mark.tpl',$data);
	}

	//自定义模板生成
	public function custom_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->custom();
	}

	//漫画主页
	public function save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->comic_index();
	}

	//分类页生成
	public function lists_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->comic_list();
	}

	//漫画页生成
	public function comic_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->comic_show();
	}

	//章节页生成
	public function chapter_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->comic_pic();
	}

	//小说主页
	public function book_index($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->book_index();
	}

	//小说分类页生成
	public function blist_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->book_list();
	}

	//小说详情页生成
	public function info_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->book_info();
	}

	//小说阅读页生成
	public function read_save($type='pc'){
		if(Url_Mode == 0) get_json('网站未开启静态生成!!!');
		define('HTML_DIR',$type);
		$this->statics->book_read();
	}
}
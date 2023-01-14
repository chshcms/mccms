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

class Ajax extends Mccms_Controller {
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$log = $this->admin->login(1);
		if(!$log) get_json('登陆超时!!!');
	}

	//漫画、小说推荐、取消
	public function reco($type='comic')
	{
		if($type != 'book') $type = 'comic';
		$id = (int)$this->input->post('id');
		$tid = (int)$this->input->post('tid');
		if($id==0) get_json('缺少参数');
		$res = $this->mcdb->get_update($type,$id,array('tid'=>$tid));
		if(!$res) get_json('操作失败');
		get_json('操作成功',1);
	}

	//缓存链接测试
	public function caches()
	{
		$id = (int)$this->input->post('id');
		$ip = $this->input->post('ip',true);
		$port = (int)$this->input->post('port');
		$pass = $this->input->post('pass',true);
		if(empty($ip) || $port==0) get_json('缺少参数');
		if($id == 2){
			if(!class_exists('Memcache')) get_json('发生错误，请检查是否开启相应扩展库!');
			//创建对象
        	$conn = new Memcache;
        	$res = $conn->pconnect($ip, $port);
        	if(!$res) get_json('链接失败，请检查主机地址或者端口是否有误!');
		}else{
			if(!class_exists('Redis')) get_json('发生错误，请检查是否开启相应扩展库!');
			//创建对象
			$redis = new Redis();
			$res = $redis->connect($ip,$port);
			if(!$res) get_json('链接失败，请检查主机地址或者端口是否有误!');
		}
		get_json('链接成功...',1);
	}

	//测试邮件
	public function mailadd()
	{
		$arr = array(
			'type' => $this->input->post('type',true),
			'host' => $this->input->post('host',true),
			'port' => (int)$this->input->post('port'),
			'user' => $this->input->post('user',true),
			'pass' => $this->input->post('pass',true),
			'crypto' => $this->input->post('crypto',true),
			'form_mail' => $this->input->post('form_mail',true),
			'form_name' => $this->input->post('form_name',true),
			'to_mail' => $this->input->post('to_mail',true),
			'title' => '这是一封测试邮件',
			'html' => '这是一封测试邮件，收到就说明我来过了，无需回复，谢谢!!!',
		);
		if($arr['pass'] == get_pass(Mail_Pass)) $arr['pass'] = Mail_Pass;
		foreach ($arr as $k => $v) {
			if(empty($v) && $k != 'crypto') get_json($k.'-->参数内容不完整!');
		}
		$this->load->model('mail');
		$res = $this->mail->send($arr);
		if($res){
			get_json('邮件发送失败，请检查信息是否有误!');
		}else{
			get_json('哇，恭喜，邮件发送成功...',1);
		}
	}

	//URL推送
	public function push(){
		//判断推送信息是否填写完整
		if(Push_Type == '') get_json('请先配置URL推送信息!!!');

		$urls = array();
		$wh = array();
		$ac = $this->input->post('ac',true);
		$url = $this->input->post('url',true);
		$cid = (int)$this->input->post('cid');
		$mid = (int)$this->input->post('mid');
		$bid = (int)$this->input->post('bid');
		$day = (int)$this->input->post('day');
		if($day == 1){
			$wh['addtime>'] = time()-3600;
		}elseif($day == 2){
			$wh['addtime>'] = time()-3600*2;
		}elseif($day == 3){
			$wh['addtime>'] = strtotime(date('Y-m-d 0:0:0'))-1;
		}elseif($day == 4){
			$wh['addtime<'] = strtotime(date('Y-m-d 0:0:0'));
			$wh['addtime>'] = strtotime(date('Y-m-d 0:0:0'))-86400;
		}elseif($day == 5){
			$wh['addtime>'] = strtotime(date('Y-m-d 0:0:0'))-86400*7-1;
		}elseif($day == 6){
			$wh['addtime>'] = strtotime(date('Y-m-01 0:0:0'))-1;
		}elseif($day == 7){
			$wh['addtime>'] = strtotime(date('Y-01-01 0:0:0'))-1;
		}

		//列表页
		if($ac == 'lists'){
			//指定分类页
			if($cid > 0){
				$row = $this->mcdb->get_row_arr('class','*',array('id'=>$cid));
				$urls[] = get_push_host(get_url('lists',$row));
			}else{ //全部分类页
				$array = $this->mcdb->get_select('class','id,yname','','xid ASC',10000);
				foreach ($array as $row) {
					$urls[] = get_push_host(get_url('lists',$row));
				}
			}
		}
		//内容页
		if($ac == 'show'){
			if($cid > 0) $wh['cid'] = $cid; 
			$array = $this->mcdb->get_select('comic','id,cid,yname',$wh,'addtime DESC',10000);
			foreach ($array as $row) {
				$urls[] = get_push_host(get_url('show',$row));
			}
		}
		//章节页
		if($ac == 'pic'){
			$wh['mid'] = $mid;
			$array = $this->mcdb->get_select('comic_chapter','id,mid',$wh,'addtime DESC',10000);
			foreach ($array as $row) {
				$urls[] = get_push_host(get_url('pic',$row));
			}
		}
		//小说列表页
		if($ac == 'blists'){
			//指定分类页
			if($cid > 0){
				$row = $this->mcdb->get_row_arr('book_class','*',array('id'=>$cid));
				$urls[] = get_push_host(get_url('book_lists',$row));
			}else{ //全部分类页
				$array = $this->mcdb->get_select('book_class','id,yname','','xid ASC',10000);
				foreach ($array as $row) {
					$urls[] = get_push_host(get_url('book_lists',$row));
				}
			}
		}
		//小说内容页
		if($ac == 'info'){
			if($cid > 0) $wh['cid'] = $cid; 
			$array = $this->mcdb->get_select('book','id,cid,yname',$wh,'addtime DESC',10000);
			foreach ($array as $row) {
				$urls[] = get_push_host(get_url('book_info',$row));
			}
		}
		//小说章节页
		if($ac == 'read'){
			if($bid == 0) get_json('请输入小说ID');
			$wh['bid'] = $bid;
			//章节表
        	$chapter_table = get_chapter_table($bid);
			$array = $this->mcdb->get_select($chapter_table,'id,bid',$wh,'addtime DESC',10000);
			foreach ($array as $row) {
				$urls[] = get_push_host(get_url('book_read',$row));
			}
		}
		//自定义URL
		if($ac == 'zdy'){
			if(empty($url) || (substr($url,0,7) !== 'http://' && substr($url,0,8) !== 'https://')){
				get_url('推送的URL地址格式不正确');
			}
			$urls[] = $url;
		}
		//加载推送类
		$this->load->library('push');
		$tarr = explode('|',Push_Type);
		foreach ($tarr as $type) {
			$this->push->send($urls,$type);
		}
		get_json('推送完成!!!',1);
	}

	//上传图片
	public function upload($id = 0){
		$id = (int)$id;
		$dir = sys_auth($this->input->get_post('dir',true),1);
		$sy = $this->input->get_post('sy',true);
		if(empty($dir)) $dir = 'comic';
		if($sy!='no') $sy = 'yes';
		$cof['upload_path'] = FCPATH.Annex_Dir.'/'.$dir.'/'.get_str_date(Annex_Path).'/';
		mkdirss($cof['upload_path']); //创建文件夹
		$cof['allowed_types'] = Annex_Ext;
		$cof['file_name'] = date('YmdHis').rand(1111,9999);
		$cof['max_size'] = Annex_Size;
		$this->load->library('upload',$cof);

		if(!$this->upload->do_upload('file')){
			$msg = $this->upload->display_errors();
			get_json($msg);
		}else{
    		$pid = 0;
			$arr = $this->upload->data();
			$img_path_file = $arr['full_path'];
            $res = checkPicHex($img_path_file);
            if($res == 1) get_json('非法图片');
			//水印
    		if($sy == 'yes') get_watermark($img_path_file);
    		//同步
    		$img_path_file = get_tongbu($img_path_file);
            if(!$img_path_file) get_json('图片上传失败');
    		//替换绝对路径
			$img_file = str_replace(FCPATH,Web_Path,$img_path_file);
			//入库
			if($id > 0){
				$row = $this->mcdb->get_row('comic_pic','xid',array('cid'=>$id),'xid DESC');
				$xid = $row ? $row->xid+1 : 0;
				$add['cid'] = $id;
				$add['mid'] = getzd('comic_chapter','mid',$id);
				$add['img'] = $img_file;
				$add['width'] = $arr['image_width'];
				$add['height'] = $arr['image_height'];
				$add['xid'] = $xid;
				$add['md5'] = md5($img_path_file);
				$pid = $this->mcdb->get_insert('comic_pic',$add);
			}
			get_json(array('url'=>$img_file,'img'=>getpic($img_file),'pid'=>$pid,'msg'=>'图片上传完成'),0);
		}
	}
}
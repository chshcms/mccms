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
	public function __construct(){
		parent::__construct();
	}

	//我的漫画
    public function index($page=1) {
        $this->users->author();
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '我的漫画 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val){
            $data['author_'.$key] = $val;
        }
        $str = load_file('author/comic.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sqlstr = 'select * from '.Mc_SqlPrefix.'comic where uid='.$uid.' order by addtime desc';
            //总数量
            $total = $this->mcdb->get_sql_nums($sqlstr);
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/comic/index/[page]',$row); 
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //新增漫画
    public function add() {
        $this->users->author();
        $uid = (int)$this->cookie->get('user_id');
        $data = array();
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '新增漫画 - '.Web_Name;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        //模版
        $str = load_file('author/comic_add.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$author);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //漫画修改
    public function info($id=0) {
        $this->users->author();
        $uid = (int)$this->cookie->get('user_id');
        $id = (int)$id;
        if($id == 0) get_err();
        $comic = $this->mcdb->get_row_arr('comic','*',array('id'=>$id,'uid'=>$uid));
        if(!$comic) get_err();
        $data = array();
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '漫画修改 - '.Web_Name;
        //漫画数据
        foreach ($comic as $key => $val) $data['comic_'.$key] = $val;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        //模版
        $str = load_file('author/comic_info.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //漫画数据
        $str = $this->parser->mccms_tpl('comic',$str,$str,$comic);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$author);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //漫画入库修改
    public function save() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        $id = (int)$this->input->post('id');
        $type = $this->input->post('type',true);
        $data['cid'] = (int)$this->input->post('cid');
        $data['name'] = safe_replace($this->input->post('name',true));
        $data['pic_author'] = $this->input->post('pic_author',true);
        $data['txt_author'] = $this->input->post('txt_author',true);
        $data['serialize'] = $this->input->post('serialize',true);
        $data['text'] = $this->input->post('text',true);
        $data['content'] = $this->input->post('content',true);
        $data['notice'] = $this->input->post('notice',true);
        if(empty($data['name'])) get_json('标题不能为空~！');
        if(empty($data['text'])) get_json('一句话介绍不能为空~！');
        //新增
        if($id == 0){
            //判断五分钟内更新数量
            $time = time()-300;
            $mnum = $this->mcdb->get_nums('comic',array('uid'=>$uid,'addtime>'=>$time));
            if($mnum > 3) get_json('系统检测到你有非法暴库行为~！');
            //判断漫画标题相同
            $row = $this->mcdb->get_row_arr('comic','id',array('name'=>$data['name']));
            if($row) get_json('存在相同名字的漫画，请更改漫画名字');
            //英文别名
            $this->load->library('pinyin');
            $data['yname'] = $this->pinyin->send($data['name']);
            $data['uid'] = $uid;
            $data['author'] = getzd('user','nichen',$uid);
            $data['pic'] = $this->input->post('pic',true);
            $data['picx'] = $this->input->post('picx',true);
            //判断签约，签约用户自动审核
            $signing = getzd('user','signing',$uid);
            $data['yid'] = $signing == 1 ? 0 : 1;
            $data['addtime'] = time();
            $id = $this->mcdb->get_insert('comic',$data);
            $url = links('author','chapter/add',$id);
        }else{
            $data['msg'] = '';
            $data['yid'] = 1;
            $this->mcdb->get_update('comic',$id,$data);
            $url = links('author','comic');
        }
        //更新附表内容
        $this->load->model('manhua');
        $this->manhua->get_set_type($type,$id);
        
        $arr['msg'] = '恭喜您，操作成功~!';
        $arr['url'] = $url;
        get_json($arr,1);
    }

    //漫画图片上传
    public function uppic() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $mid = (int)$this->input->post('mid');

        $x = (int)$this->input->post('x');
        $y = (int)$this->input->post('y');
        $w = (int)$this->input->post('w');
        $h = (int)$this->input->post('h');
        $type = $this->input->post('type',true);
        $uid = (int)$this->cookie->get('user_id');
        $zd = $type == 'x' ? 'picx' : 'pic';
        //判断权限
        if($mid > 0){
            $muid = (int)getzd('comic','uid',$mid);
            if($muid != $uid) get_json('没有操作权限!!!');
        }

        $cof['upload_path'] = FCPATH.Annex_Dir.'/comic/'.get_str_date(Annex_Path).'/';
        mkdirss($cof['upload_path']); //创建文件夹
        $cof['allowed_types'] = Annex_Ext;
        $cof['file_name'] = date('YmdHis').rand(1111,9999);
        $cof['max_size'] = Annex_Size;
        $this->load->library('upload',$cof);

        if(!$this->upload->do_upload('image')){
            $msg = $this->upload->display_errors();
            get_json($msg);
        }else{
            $arr = $this->upload->data();
            $img_path_file = $arr['full_path'];
            $res = checkPicHex($img_path_file);
            if($res == 1) get_json('非法图片');
            //裁剪
            $config['image_library'] = 'gd2';
            $config['source_image'] = $img_path_file;
            $config['maintain_ratio'] = false;
            $config['width']  = $w;
            $config['height'] = $h;
            $config['x_axis'] = $x;
            $config['y_axis'] = $y;
            $this->load->library('image_lib', $config);
            $this->image_lib->crop();
            //缩放
            $config2['maintain_ratio'] = TRUE;
            if($zd == 'picx'){
                $config2['width']  = 640;
                $config2['height'] = 360;
            }else{
                $config2['width']  = 600;
                $config2['height'] = 800;
            }
            $this->image_lib->initialize($config2);
            $this->image_lib->resize();
            //同步
            $img_path_file = get_tongbu($img_path_file);
            if(!$img_path_file) get_json('图片上传失败');
            //替换绝对路径
            $img_file = str_replace(FCPATH,Web_Path,$img_path_file);
            //更新数据库，进入待审核
            if($mid > 0){
                $this->mcdb->get_update('comic',$mid,array($zd=>$img_file,'yid'=>1));
            }
            //返回
            get_json(array('url'=>$img_file,'img'=>getpic($img_file),'msg'=>'图片上传完成'),1);
        }
    }

    //删除漫画
    public function del() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $id = (int)$this->input->post('id');
        if($id == 0) get_json('漫画ID为空');
        $uid = (int)$this->cookie->get('user_id');
        $this->load->model('manhua');
        $muid = (int)getzd('comic','uid',$id);
        if($muid != $uid) get_json('没有权限');
        $this->manhua->del($id);
        get_json('删除完成',1);
    }
}
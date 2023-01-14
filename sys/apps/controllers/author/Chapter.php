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

class Chapter extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//我的章节
    public function index($mid=0,$page=1) {
        $this->users->author();
        $mid = (int)$mid;
        if($mid == 0) get_err();
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
    	$uid = (int)$this->cookie->get('user_id');
        //漫画
        $comic = $this->mcdb->get_row_arr('comic','*',array('id'=>$mid));
        if(!$comic || $comic['uid'] != $uid) get_err();
        //会员
    	$user = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '章节管理 - '.Web_Name;
        //当前数据
        foreach ($user as $key => $val) $data['author_'.$key] = $val;
        foreach ($comic as $key => $val) $data['comic_'.$key] = $val;

        $str = load_file('author/chapter.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sqlstr = 'select * from '.Mc_SqlPrefix.'comic_chapter where mid='.$mid.' order by xid desc';
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/chapter/index/'.$mid.'/[page]',$user); 
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$user);
        //漫画数据
        $str = $this->parser->mccms_tpl('comic',$str,$str,$comic);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //新增章节
    public function add($mid=0) {
        $this->users->author();
        $data = array();
        $mid = (int)$mid;
        if($mid == 0) get_err();
        $uid = (int)$this->cookie->get('user_id');
        //漫画
        $comic = $this->mcdb->get_row_arr('comic','*',array('id'=>$mid));
        if(!$comic || $comic['uid'] != $uid) get_err();
        //作者
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '新增章节 - '.Web_Name;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        foreach ($comic as $key => $val) $data['comic_'.$key] = $val;
        //模版
        $str = load_file('author/chapter_add.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$author);
        //漫画数据
        $str = $this->parser->mccms_tpl('comic',$str,$str,$comic);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //漫画修改
    public function edit($id=0) {
        $this->users->author();
        $uid = (int)$this->cookie->get('user_id');
        $id = (int)$id;
        if($id == 0) get_err();
        $chapter = $this->mcdb->get_row_arr('comic_chapter','*',array('id'=>$id));
        if(!$chapter) get_err();
        //判断权限
        $muid = (int)getzd('comic','uid',$chapter['mid']);
        if($muid != $uid) get_err();
        $data = array();
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '漫画修改 - '.Web_Name;
        //章节数据
        foreach ($chapter as $key => $val) $data['chapter_'.$key] = $val;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        //模版
        $str = load_file('author/chapter_edit.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //漫画数据
        $str = $this->parser->mccms_tpl('chapter',$str,$str,$chapter);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$author);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //章节入库修改
    public function save() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        $id = (int)$this->input->post('id');
        $mid = (int)$this->input->post('mid');
        $images = $this->input->post('images',true);
        $pid = $this->input->post('pid',true);

        $data['name'] = $this->input->post('name',true);
        if(empty($data['name'])) get_json('章节名称不能为空~！');

        if($id == 0){
            //判断权限
            $muid = (int)getzd('comic','uid',$mid);
            if($muid != $uid) get_json('没有权限操作~！');

            //判断五分钟内更新数量
            $time = time()-300;
            $mnum = $this->mcdb->get_nums('comic_chapter',array('mid'=>$mid,'addtime>'=>$time));
            if($mnum > 5) get_json('系统检测到你有非法暴库行为~！');
            //判断签约，签约用户自动审核
            $signing = getzd('user','signing',$uid);
            $data['yid'] = $signing == 1 ? 0 : 1;
            $data['mid'] = $mid;
            $data['addtime'] = time();
            $data['pnum'] = count($images);
            //获取最大排序ID
            $xid = (int)getzd('comic_chapter','xid',$mid,'mid','xid desc');
            $data['xid'] = $xid+1;

            $id = $this->mcdb->get_insert('comic_chapter',$data);
            //更新图片
            if(!empty($images)){
                foreach ($images as $k=>$_pic) {
                    $add['cid'] = $id;
                    $add['mid'] = $mid;
                    $add['xid'] = $k;
                    $add['img'] = $_pic;
                    $add['md5'] = md5($_pic);
                    $this->mcdb->get_insert('comic_pic',$add); 
                }
            }
            $this->load->library('session');
            session_destroy();
        }else{
            //判断权限
            $mid = (int)getzd('comic_chapter','mid',$id);
            $muid = (int)getzd('comic','uid',$mid);
            if($muid != $uid) get_json('没有权限操作~！');

            $data['msg'] = '';
            $data['yid'] = 1;
            $data['pnum'] = count($pid);
            $this->mcdb->get_update('comic_chapter',$id,$data);
            //更新图片
            if(!empty($pid)){
                foreach ($pid as $xid=>$_id) {
                    $this->mcdb->get_update('comic_pic',$_id,array('xid'=>$xid)); 
                }
            }
        }
        $arr['msg'] = '恭喜您，操作成功~!';
        $arr['url'] = get_url('author/chapter/index/'.$mid);
        get_json($arr,1);
    }

    //章节图片上传
    public function uppic($mid=0,$cid=0) {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $mid = (int)$mid;
        $cid = (int)$cid;
        if($mid == 0) get_json('漫画ID为空');
        $uid = (int)$this->cookie->get('user_id');
        //判断权限
        if($cid > 0){
            $mid = (int)getzd('comic_chapter','mid',$cid);
            $muid = (int)getzd('comic','uid',$mid);
        }else{
            $muid = (int)getzd('comic','uid',$mid);
        }
        if($muid !== $uid) get_json('没有操作权限!!!');

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
            //水印
            get_watermark($img_path_file);
            //同步
            $img_path_file = get_tongbu($img_path_file);
            //替换绝对路径
            $img_file = str_replace(FCPATH,Web_Path,$img_path_file);
            if($cid > 0){
                $this->mcdb->get_insert('comic_pic',array('img'=>$img_file,'mid'=>$mid,'cid'=>$cid,'md5'=>md5($img_file)));
            }else{
                //存储到session
                $pics = array();
                $this->load->library('session');
                if(isset($_SESSION['chapter_pic'])){
                    $pics = $_SESSION['chapter_pic'];
                }
                $pics[] = $img_file;
                $_SESSION['chapter_pic'] = $pics;
            }
            //返回
            get_json(array('url'=>$img_file,'msg'=>'图片上传完成'),1);
        }
    }

    //删除章节
    public function del() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $id = (int)$this->input->post('id');
        if($id == 0) get_json('章节ID为空');
        $uid = (int)$this->cookie->get('user_id');
        //判断权限
        $muid = (int)getzd('comic','uid',getzd('comic_chapter','mid',$id));
        if($muid != $uid) get_json('没有权限');
        $this->load->model('manhua');
        $this->manhua->chapter_del($id);
        get_json('删除完成',1);
    }

    //删除图片
    public function picdel() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $url = $this->input->post('url',true);
        $id = (int)$this->input->post('id',true);
        $uid = (int)$this->cookie->get('user_id');
        if($id > 0){
            //判断权限
            $muid = (int)getzd('comic','uid',getzd('comic_pic','mid',$id));
            if($muid != $uid) get_json('图片不存在');
            $this->load->model('manhua');
            $this->manhua->pic_del($id,'pic');
            get_json('操作成功',1);
        }else{
            if(empty($url)) get_json('图片地址错误');
            $this->load->library('session');
            if(isset($_SESSION['chapter_pic'])){
                if(in_array($url,$_SESSION['chapter_pic'])){
                    $this->load->model('tongbu');
                    $this->tongbu->del($url);
                    $xarr = array();
                    foreach ($_SESSION['chapter_pic'] as $_pic) {
                        if($_pic != $url) $xarr[] = $_pic;
                    }
                    if(!empty($xarr)){
                        $_SESSION['chapter_pic'] = $xarr;
                    }else{
                        session_destroy();
                    }
                    get_json('操作成功',1);
                }
            }
            get_json('非法地址');
        }
    }
}
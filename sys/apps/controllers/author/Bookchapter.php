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

class Bookchapter extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//我的章节
    public function index($bid=0,$page=1) {
        $this->users->author();
        $bid = (int)$bid;
        if($bid == 0) get_err();
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
    	$uid = (int)$this->cookie->get('user_id');
        //小说
        $book = $this->mcdb->get_row_arr('book','*',array('id'=>$bid));
        if(!$book || $book['uid'] != $uid) get_err();
        //会员
    	$user = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '章节管理 - '.Web_Name;
        //当前数据
        foreach ($user as $key => $val) $data['author_'.$key] = $val;
        foreach ($book as $key => $val) $data['book_'.$key] = $val;

        $str = load_file('author/book_chapter.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $table = get_chapter_table($bid);
            $sqlstr = 'select * from '.Mc_SqlPrefix.$table.' where bid='.$bid.' order by xid desc';
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/bookchapter/index/'.$bid.'/[page]',$user); 
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$user);
        //小说数据
        $str = $this->parser->mccms_tpl('book',$str,$str,$book);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //新增章节
    public function add($bid=0) {
        $this->users->author();
        $data = array();
        $bid = (int)$bid;
        if($bid == 0) get_err();
        $uid = (int)$this->cookie->get('user_id');
        //小说
        $book = $this->mcdb->get_row_arr('book','*',array('id'=>$bid));
        if(!$book || $book['uid'] != $uid) get_err();
        //作者
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '新增章节 - '.Web_Name;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        foreach ($book as $key => $val) $data['book_'.$key] = $val;
        //模版
        $str = load_file('author/book_chapter_add.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('author',$str,$str,$author);
        //小说数据
        $str = $this->parser->mccms_tpl('book',$str,$str,$book);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //小说修改
    public function edit($id=0,$bid=0) {
        $this->users->author();
        $uid = (int)$this->cookie->get('user_id');
        $id = (int)$id;
        $bid = (int)$bid;
        if($id == 0 || $bid == 0) get_err();
        $table = get_chapter_table($bid);
        $chapter = $this->mcdb->get_row_arr($table,'*',array('id'=>$id));
        if(!$chapter) get_err();
        $chapter['text'] = get_book_txt($bid,$id);
        //判断权限
        $muid = (int)getzd('book','uid',$chapter['bid']);
        if($muid != $uid) get_err();
        $data = array();
        $author = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '小说修改 - '.Web_Name;
        //章节数据
        foreach ($chapter as $key => $val) $data['chapter_'.$key] = $val;
        //作者数据
        foreach ($author as $key => $val) $data['author_'.$key] = $val;
        //模版
        $str = load_file('author/book_chapter_edit.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //小说数据
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
        $bid = (int)$this->input->post('bid');
        $text = $this->input->post('text');

        $data['name'] = $this->input->post('name',true);
        if(empty($data['name'])) get_json('章节名称不能为空~！');
        if(empty($text)) get_json('章节txt内容不能为空~！');
        //章节表
        $table = get_chapter_table($bid);
        //字数
        $data['text_num'] = mb_strlen($text,"UTF-8");
        if($id == 0){
            //判断权限
            $muid = (int)getzd('book','uid',$bid);
            if($muid != $uid) get_json('没有权限操作~！');

            //判断五分钟内更新数量
            $time = time()-300;
            $mnum = $this->mcdb->get_nums($table,array('bid'=>$bid,'addtime>'=>$time));
            if($mnum > 5) get_json('系统检测到你有非法暴库行为~！');
            //判断签约，签约用户自动审核
            $signing = getzd('user','signing',$uid);
            $data['yid'] = $signing == 1 ? 0 : 1;
            $data['bid'] = $bid;
            $data['addtime'] = time();
            //获取最大排序ID
            $xid = (int)getzd($table,'xid',$bid,'bid','xid desc');
            $data['xid'] = $xid+1;

            $id = $this->mcdb->get_insert($table,$data);
        }else{
            //判断权限
            $bid = (int)getzd($table,'bid',$id);
            $muid = (int)getzd('book','uid',$bid);
            if($muid != $uid) get_json('没有权限操作~！');

            $data['msg'] = '';
            $data['yid'] = 1;
            $this->mcdb->get_update($table,$id,$data);
        }
        //写入小说到TXT文本
        get_book_txt($bid,$id,$text);

        $arr['msg'] = '恭喜您，操作成功~!';
        $arr['url'] = get_url('author/bookchapter/index/'.$bid);
        get_json($arr,1);
    }

    //删除章节
    public function del() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $bid = (int)$this->input->post('bid');
        $id = (int)$this->input->post('id');
        if($bid == 0) get_json('小说ID为空');
        if($id == 0) get_json('章节ID为空');
        $uid = (int)$this->cookie->get('user_id');
        //章节表
        $table = get_chapter_table($bid);
        //判断权限
        $muid = (int)getzd('book','uid',getzd($table,'bid',$id));
        if($muid != $uid) get_json('没有权限');
        $this->load->model('novel');
        $this->novel->chapter_del($id,$table);
        get_json('删除完成',1);
    }
}
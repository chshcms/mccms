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

class Fav extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//漫画收藏列表
    public function index($page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
        $data['mccms_title'] = '漫画收藏记录 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //获取模版
        $str = load_file('user/fav.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'fav where uid='.$uid;
            $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/fav/index/[page]',$row); 
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //小说收藏列表
    public function book($page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
        $data['mccms_title'] = '小说收藏记录 - '.Web_Name;
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //获取模版
        $str = load_file('user/book_fav.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'book_fav where uid='.$uid;
            $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/fav/book/[page]',$row); 
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //删除收藏
    public function del($type='comic') {
        if($type != 'book') $type = 'comic';
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $ids = $this->input->get_post('ids',true);
        if(empty($ids)) get_json('请选择要删除的数据!!!');
        $uid = (int)$this->cookie->get('user_id');
        $table = $type == 'book' ? 'book_fav' : 'fav';
        if(!is_array($ids)) $ids = explode(',', $ids);
        foreach ($ids as $_id) {
            $_id = (int)$_id;
            $this->db->where(array('id'=>$_id,'uid'=>$uid))->delete($table);
        }
        get_json('删除完成',1);
    }
}
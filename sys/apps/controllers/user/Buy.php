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

class Buy extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//金币消费列表
    public function index($day=0,$page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        $day = (int)$day;
        if($page == 0) $page = 1;
        $data = array();
        $data['day'] = $day;
        $data['mccms_title'] = '消费列表 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $row['day'] = $day;
        //获取模版
        $str = load_file('user/buy.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'buy where uid='.$uid;
            if($day > 0) $sql.=' and addtime > '.(strtotime(date('Y-m-d 0:0:0'))-86400*($day-1));
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/buy/index/[day]/[page]',$row);
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

    //月卡消费列表
    public function ticket($day=0,$page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        $day = (int)$day;
        if($page == 0) $page = 1;
        $data = array();
        $data['day'] = $day;
        $data['mccms_title'] = '消费列表 - '.Web_Name;
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $row['day'] = $day;
        //获取模版
        $str = load_file('user/ticket.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'ticket where uid='.$uid;
            if($day > 0) $sql.=' and addtime > '.(strtotime(date('Y-m-d 0:0:0'))-86400*($day-1));
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/buy/ticket/[day]/[page]',$row);
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

    //漫画购买列表
    public function comic($page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
        $data['mccms_title'] = '购买漫画列表 - '.Web_Name;
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //获取模版
        $str = load_file('user/comic.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sqlstr = 'select a.*,b.auto,count(b.id) as count from '.Mc_SqlPrefix.'comic a inner join '.Mc_SqlPrefix.'comic_buy b on a.id = b.mid where a.yid=0 and b.uid='.$uid.' GROUP BY a.id,b.auto order by a.id desc';
            //总数量
            $total = $this->db->query($sqlstr)->num_rows();
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/buy/comic/[page]',$row);
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

    //小说购买列表
    public function book($page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = array();
        $data['mccms_title'] = '购买小说列表 - '.Web_Name;
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //获取模版
        $str = load_file('user/book.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sqlstr = 'select a.*,b.auto,count(b.id) as count from '.Mc_SqlPrefix.'book a inner join '.Mc_SqlPrefix.'book_buy b on a.id = b.bid where a.yid=0 and b.uid='.$uid.' GROUP BY a.id,b.auto order by a.id desc';
            //总数量
            $total = $this->db->query($sqlstr)->num_rows();
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/buy/book/[page]',$row);
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

    //自动购买开关
    public function auto_init($type='comic'){
        $did = (int)$this->input->get_post('did');
        $auto = (int)$this->input->get_post('auto');
        if($did == 0) get_json('参数错误!!!');
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        if($type == 'book'){
            $res = $this->db->where(array('uid'=>$uid,'bid'=>$did))->update('book_buy',array('auto'=>$auto));
        }else{
            $res = $this->db->where(array('uid'=>$uid,'mid'=>$did))->update('comic_buy',array('auto'=>$auto));
        }
        if($res){
            if($auto == 1){
                get_json('已开启自动购买',1);
            }else{
                get_json('已关闭自动够买',1);
            }
        }else{
            get_json('修改失败');
        }
    }
}
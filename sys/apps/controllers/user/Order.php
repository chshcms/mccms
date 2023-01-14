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

class Order extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
        $this->users->login();//判断登陆
	}

	//充值列表
    public function index($zt=0,$day=0,$page=1) {
        $page = (int)$page;
        $day = (int)$day;
        $zt = (int)$zt;
        if($page == 0) $page = 1;
        $data = array();
        $data['day'] = $day;
        $data['zt'] = $zt;
        $data['mccms_title'] = '充值列表 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $row['day'] = $day;$row['zt'] = $zt;
        //获取模版
        $str = load_file('user/order.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'order where uid='.$uid;
            if($day > 0) $sql.=' and addtime > '.(strtotime(date('Y-m-d 0:0:0'))-86400*$day);
            if($zt > 0) $sql.=' and pid='.($zt-1);
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/order/index/[zt]/[day]/[page]',$row);
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
}
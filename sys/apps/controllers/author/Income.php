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

class Income extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
        //判断作者
        $this->users->author();
	}

	//收入分成
    public function index($day=0,$page=1) {
        $page = (int)$page;
        $day = (int)$day;
        if($page == 0) $page = 1;
        $data = array();
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $data['day'] = $day;
        //网站标题
        $data['mccms_title'] = '收入明细 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val){
            $data['author_'.$key] = $val;
        }
        $str = load_file('author/income.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select * from '.Mc_SqlPrefix.'income where uid='.$uid;
            if($day > 0) $sql.=' and addtime>'.(strtotime(date('Y-m-d 0:0:0'))-86400*($day-1));
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/income/index/'.$day.'/[page]',$row); 
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
}
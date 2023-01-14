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

class Message extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//消息列表
    public function index($op='all',$day=0,$page=1) {
        $this->users->login();//判断登陆
        $page = (int)$page;
        $day = (int)$day;
        if($op != 'wd') $op = 'all';
        if($page == 0) $page = 1;
        $data = array();
        $data['day'] = $day;
        $data['op'] = $op;
        $data['mccms_title'] = '消费列表 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        $row['day'] = $day;
        //获取模版
        $str = load_file('user/message.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select {field} from '.Mc_SqlPrefix.'message where uid='.$uid;
            if($day > 0) $sql.=' and addtime > '.(strtotime(date('Y-m-d 0:0:0'))-86400*$day);
            if($op == 'wd') $sql.=' and did=0';
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'user/message/index/'.$op.'/[day]/[page]',$row);
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

    //读消息
    public function init(){
        $id = (int)$this->input->post('id');
        if($id == 0) get_json('参数错误!!!');
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('message','did,text',array('id'=>$id,'uid'=>$uid));
        if($row){
            if($row['did'] == 0){
                $this->mcdb->get_update('message',$id,array('did'=>1));
            }
            get_json(array('msg'=>'消息信息','text'=>$row['text']),1);
        }else{
            get_json('记录不存在');
        }
    }

    //删消息
    public function del(){
        $ids = $this->input->post('ids',true);
        if(empty($ids)) get_json('参数错误!!!');
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        foreach ($ids as $_id) {
            $_id = (int)$_id;
            if($_id > 0) $this->db->where(array('uid'=>$uid,'id'=>$_id))->delete('message');
        }
        get_json('删除完成',1);
    }
}
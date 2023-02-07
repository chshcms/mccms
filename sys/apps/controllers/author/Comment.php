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

class Comment extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//读者漫画评论
    public function index($page=1) {
        $this->users->author();
        $name = safe_replace($this->input->get_post('name',true));
        $time = safe_replace($this->input->get_post('time',true));
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = $parame = $wh = array();
        if(!empty($name)){
            $wh[] = "b.name like '%".$name."%'";
            $parame[] = 'name='.urlencode($name);
        }
        if(!empty($time)){
            $tarr = explode(' - ',$time);
            if(!empty($tarr[0])) $wh[] = 'a.addtime>'.strtotime($tarr[0]);
            if(!empty($tarr[1])) $wh[] = 'a.addtime<'.strtotime($tarr[1]);
            $parame[] = 'time='.urlencode($time);
        }
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '读者评论 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val) $data['author_'.$key] = $val;
        $data['name'] = $name;
        $data['time'] = $time;
        $str = load_file('author/comment.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select * from '.Mc_SqlPrefix.'comment where uid='.$uid.' and mid>0';
            $sqlstr = 'select a.* from '.Mc_SqlPrefix.'comment a inner join '.Mc_SqlPrefix.'comic b on a.mid = b.id where a.mid>0 and b.uid='.$uid;
            if(!empty($wh)){
                $sqlstr .= ' and '.implode(' and ', $wh);
            }
            $sqlstr .= ' order by a.id desc';
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/comment/index/[page]',$row,implode('&',$parame)); 
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

    //读者小说评论
    public function book($page=1) {
        $this->users->author();
        $name = safe_replace($this->input->get_post('name',true));
        $time = safe_replace($this->input->get_post('time',true));
        $page = (int)$page;
        if($page == 0) $page = 1;
        $data = $parame = $wh = array();
        if(!empty($name)){
            $wh[] = "b.name like '%".$name."%'";
            $parame[] = 'name='.urlencode($name);
        }
        if(!empty($time)){
            $tarr = explode(' - ',$time);
            if(!empty($tarr[0])) $wh[] = 'a.addtime>'.strtotime($tarr[0]);
            if(!empty($tarr[1])) $wh[] = 'a.addtime<'.strtotime($tarr[1]);
            $parame[] = 'time='.urlencode($time);
        }
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //网站标题
        $data['mccms_title'] = '读者评论 - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val) $data['author_'.$key] = $val;
        $data['name'] = $name;
        $data['time'] = $time;
        $str = load_file('author/book_comment.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            $sql = 'select * from '.Mc_SqlPrefix.'comment where uid='.$uid.' and bid>0';
            $sqlstr = 'select a.* from '.Mc_SqlPrefix.'comment a inner join '.Mc_SqlPrefix.'book b on a.bid = b.id where a.bid>0 and b.uid='.$uid;
            if(!empty($wh)){
                $sqlstr .= ' and '.implode(' and ', $wh);
            }
            $sqlstr .= ' order by a.id desc';
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
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'author/comment/book/[page]',$row,implode('&',$parame)); 
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

    //评论回复详情
    public function reply() {
        $id = (int)$this->input->post('id');
        $data = array();
        if($id > 0){
            $array = $this->mcdb->get_select('comment_reply','*',array('cid'=>$id),'id DESC',100);
            foreach ($array as $row) {
                $reply = array();
                $reply['id'] = $row['id'];
                $reply['cid'] = $row['cid'];
                $reply['mid'] = $row['mid'];
                $reply['bid'] = $row['bid'];
                $reply['unichen'] = getzd('user','nichen',$row['uid']);
                $reply['text'] = get_face($row['text']);
                $reply['addtime'] = date('Y-m-d H:i:s',$row['addtime']);
                $data[] = $reply;
            }
        }
        $arr['msg'] = '评论回复列表';
        $arr['data'] = $data;
        get_json($arr,1); 
    }

    //评论回复入库
    public function reply_save() {
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        $cid = (int)$this->input->post('cid');
        $mid = (int)$this->input->post('mid');
        $bid = (int)$this->input->post('bid');
        $text = $this->input->post('text',true);
        if($cid == 0 || ($mid == 0 && $bid == 0)) get_json('参数错误~！');
        if(empty($text)) get_json('回复内容不能为空~！');

        //判断权限
        if($mid > 0){
            $muid = (int)getzd('comic','uid',$mid);
        }else{
            $muid = (int)getzd('book','uid',$bid);
        }
        if($muid != $uid) get_json('非法操作~！');

        //判断上次评论时间
        $row = $this->mcdb->get_row_arr('comment_reply','addtime',array('uid'=>$uid),'addtime desc');
        if(($row['addtime']+Pl_Time) > time()) get_json('请先休息一会，再来回复吧');

        $add['cid'] = $cid;
        $add['mid'] = $mid;
        $add['bid'] = $bid;
        $add['text'] = get_comment_text($text);
        $add['uid'] = $uid;
        $add['machine'] = defined('MOBILE') ? 'wap' : 'pc';
        $add['ip'] = getip();
        $add['addtime'] = time();
        $id = $this->mcdb->get_insert('comment_reply',$add);
        //增加回复次数
        $this->db->query('update '.Mc_SqlPrefix.'comment set reply_num=reply_num+1 where id='.$cid);

        $arr['msg'] = '恭喜您，回复成功~!';
        get_json($arr,1);
    }

    //删除评论
    public function del($table = 'comic') {
        if($table != 'book') $table = 'comic';
        $zd = $table == 'book' ? 'bid' : 'mid';
        if(!$this->users->author(1)) get_json('登陆超时!!!');
        $type = $this->input->post('type',true);
        $id = (int)$this->input->post('id');
        if($id == 0) get_json('ID为空');
        $uid = (int)$this->cookie->get('user_id');
        if($type == 'reply'){
            //权限
            $muid = (int)getzd($table,'uid',getzd('comment_reply',$zd,$id));
            if($muid != $uid) get_json('没有权限');
            $cid = getzd('comment_reply','cid',$id);
            //删除记录
            $this->mcdb->get_del('comment_reply',$id);
            //删除下级评论
            $this->mcdb->get_del('comment_reply',$id,'fid');
            //减去回复数量
            $this->db->query('update '.Mc_SqlPrefix.'comment set reply_num=reply_num-1 where id='.$cid);
        }else{
            //权限
            $muid = (int)getzd($table,'uid',getzd('comment',$zd,$id));
            if($muid != $uid) get_json('没有权限');
            //删除记录
            $this->mcdb->get_del('comment',$id);
            //删除回复
            $this->mcdb->get_del('comment_reply',$id,'cid');
        }
        get_json('删除完成',1);
    }
}
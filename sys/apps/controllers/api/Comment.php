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

	//评论列表
    public function lists() {
        if(Pl_Mode == 1) get_json(array('html'=>'站内评论已关闭','msg'=>'评论已关闭'),1);
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $wh = $bid > 0 ? 'bid='.$bid : 'mid='.$mid;

        $data = array();
        //获取模板
        $str = load_file('comment.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
              //每页数量
              $per_page = (int)$arr[3][0];
              $sql = 'select {field} from '.Mc_SqlPrefix.'comment where '.$wh;
              //组装SQL数据
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
              $pagearr = get_comment_page($total,$pagejs,$page,$pagenum,$mid,$bid);
              $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
              $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //IF判断解析
        $str = $this->parser->labelif($str);
        //输出
        $d['code'] = 1;
        $d['msg'] = '评论列表';
        $d['html'] = $str;
        get_json($d);
	}

    //新增评论
    public function add() {
        if(Pl_Mode == 1) get_json('评论已关闭');
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $cid = (int)$this->input->get_post('cid');
        $fid = (int)$this->input->get_post('fid');
        $text = $this->input->get_post('text',true);
        if(($mid == 0 && $bid == 0) || empty($text)) get_json('参数错误');
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = $this->cookie->get('user_id');
        //判断上次评论时间
        $table = $cid > 0 ? 'comment_reply' : 'comment';
        $row = $this->mcdb->get_row_arr($table,'addtime',array('uid'=>$uid),'addtime desc');
        if(($row['addtime']+Pl_Time) > time()) get_json('请先休息一会，再来评论吧');
        //判断每天评论数量
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        $num1 = $this->mcdb->get_nums('comment',array('uid'=>$uid,'addtime>'=>$jtime));
        $num2 = $this->mcdb->get_nums('comment_reply',array('uid'=>$uid,'addtime>'=>$jtime));
        if(($num1+$num2) >= Pl_Add_Num) get_json('您今天评论数已达上限，明天再来吧');
        //过滤评论内容
        $add['text'] = get_comment_text($text);
        $add['mid'] = $mid;
        $add['bid'] = $bid;
        $add['uid'] = $uid;
        $add['machine'] = defined('MOBILE') ? 'wap' : 'pc';
        $add['ip'] = getip();
        $add['addtime'] = time();
        if($cid > 0){
            $add['cid'] = $cid;
            $add['fid'] = $fid;
        }
        $res = $this->mcdb->get_insert($table,$add);
        if($res){
            $d['code'] = 1;
            $d['msg'] = $cid > 0 ? '回复评论成功' : '评论成功';
            //增加回复次数
            if($cid > 0){
                $this->db->query('update '.Mc_SqlPrefix.'comment set reply_num=reply_num+1 where id='.$cid);
            }
            //判断赠送金币
            if(User_Pl_Cion > 0 && User_Pl_Num > 0){
                //获取今日评论数
                $jnum = $this->mcdb->get_nums('comment',array('uid'=>$uid,'addtime>'=>strtotime(date('Y-m-d 0:0:0'))));
                $jcion = $jnum*User_Pl_Cion;
                if($jcion < User_Pl_Num){
                    //赠送金币
                    $this->db->query('update '.Mc_SqlPrefix.'user set cion=cion+'.User_Pl_Cion.' where id='.$uid);
                }
            }
            if($cid > 0){
                $d['cid'] = $res;
                $d['pid'] = $cid;
            }else{
                $d['pid'] = $res;  
            }
            get_json($d);
        }else{
            get_json('评论失败');
        }
    }

    //赞评论
    public function zan() {
        $fid = (int)$this->input->get_post('fid');
        $id = (int)$this->input->get_post('id');
        if($id == 0) get_json('参数错误');
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = $this->cookie->get('user_id');

        $table = $fid == 1 ? 'comment_reply' : 'comment';
        //判断是否赞过
        $row = $this->mcdb->get_row_arr('comment_zan','id',array('uid'=>$uid,'cid'=>$id));
        if($row){
            $arr['zt'] = 0;
            $this->mcdb->get_del('comment_zan',$row['id']);
            //评论赞数量
            $this->db->query('update '.Mc_SqlPrefix.$table.' set zan=zan-1 where id='.$id);
        }else{
            $arr['zt'] = 1;
            $this->mcdb->get_insert('comment_zan',array('cid'=>$id,'fid'=>$fid,'uid'=>$uid));
            //评论赞数量
            $this->db->query('update '.Mc_SqlPrefix.$table.' set zan=zan+1 where id='.$id);
        }
        $arr['code'] = 1;
        $arr['zan'] = getzd($table,'zan',$id);
        $arr['msg'] = '操作成功';
        get_json($arr);
    }
}
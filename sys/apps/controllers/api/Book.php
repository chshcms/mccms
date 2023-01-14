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

class Book extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//搜索栏热门10条
    public function hot() {
        $data = $this->mcdb->get_select('book','id,name,pic,yname,author,hits,tags',array('yid'=>0),'rhits DESC',10);
        $xarr = array();
        foreach ($data as $k => $v) {
            $xarr[$k]['id'] = $v['id'];
            $xarr[$k]['pic'] = getpic($v['pic']);
            $xarr[$k]['name'] = $v['name'];
            $xarr[$k]['author'] = $v['author'];
            $xarr[$k]['tags'] = $v['tags'];
            $xarr[$k]['hits'] = format_wan($v['hits']);
            $xarr[$k]['url'] = get_url('book_info',$v);
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $xarr;
        get_json($d);
	}

    //根据ID获取小说所有章节
    public function chapter() {
        $bid = (int)$this->input->get_post('bid');
        $cid = (int)$this->input->get_post('cid');
        $xarr = array();
        if($bid > 0){
            $table = get_chapter_table($bid);
            $data = $this->mcdb->get_select($table,'*',array('bid'=>$bid),'xid ASC',10000);
            foreach ($data as $k => $v) {
                $xarr[$k]['id'] = $v['id'];
                $xarr[$k]['name'] = $v['name'];
                $xarr[$k]['link'] = get_url('book_read',$v);
                $xarr[$k]['on'] = $v['id'] == $cid ? 1 : 0;
                $xarr[$k]['pay'] = $v['vip'] > 0 || $v['cion'] > 0 ? 1 : 0;
            }
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $xarr;
        get_json($d);
    }

    //小说章节是否购买
    public function isbuy() {
        $bid = (int)$this->input->get_post('bid');
        $cid = (int)$this->input->get_post('cid');
        if($bid == 0 || $cid == 0) get_json('参数错误');
        if(!$this->users->login(1)) get_json('登录超时',2);
        $table = get_chapter_table($bid);
        $row = $this->mcdb->get_row_arr($table,'vip,cion',array('id'=>$cid));
        if(!$row) get_json('章节不存在');
        //会员ID
    	$uid = (int)$this->cookie->get('user_id');
    	//VIP章节
    	if($row['vip'] > 0){
    		$vip = (int)getzd('user','vip',$uid);
    		if($vip == 0) get_json(array('msg'=>'VIP专属，级别不够','type'=>'vip'),3);
    	}else{
            //判断是否购买过
            $row = $this->mcdb->get_row_arr('book_buy','id',array('cid'=>$cid,'uid'=>$uid));
            if(!$row){
                $row2 = $this->mcdb->get_row_arr('book_buy','auto',array('bid'=>$bid,'uid'=>$uid));
                if($row2 && $row2['auto'] == 1){
                    $this->buy($bid,$cid,1);
                }
                get_json(array('msg'=>'未购买','type'=>'cion'),3);
            }
    	}
    	//获取章节text
        $text = get_book_txt($bid,$cid);
        $text = '<p>'.str_replace("\n",'</p><p>',$text).'</p>';
    	get_json(array('msg'=>'已购买','text'=>$text),1);
    }

    //购买小说章节
    public function buy($bid=0,$cid=0,$auto=0) {
        $bid = (int)$bid;
        $cid = (int)$cid;
        $auto = (int)$auto;
        $uid = $this->cookie->get('user_id');
        if($bid == 0 || $cid == 0) get_json('参数错误');
        //判断登陆
        if(!$this->users->login(1)) get_json('登陆超时!!!',2);
        $table = get_chapter_table($bid);
        $row = $this->mcdb->get_row_arr($table,'*',array('id'=>$cid));
        if(!$row) get_json('章节不存在');
        //获取章节text
        $text = get_book_txt($bid,$cid);
        $text = '<p>'.str_replace("\n",'</p><p>',$text).'</p>';
        //判断是否购买过
        $row2 = $this->mcdb->get_row_arr('book_buy','id',array('cid'=>$cid,'uid'=>$uid));
        if($row2) get_json(array('msg'=>'已经购买过了','text'=>$text),1);
        //会员信息
        $user = $this->mcdb->get_row_arr('user','id,cion',array('id'=>$uid));
        //作者ID
        $zzid = getzd('book','uid',$row['bid']);
        //判断条件
        if($row['cion'] > 0){
            //金币不足
            if($row['cion'] > $user['cion']) get_json(Pay_Cion_Name.'不足，请先充值',3);
            //小说标题
            $mname = getzd('book','name',$row['bid']);
            //扣币
            $xcion = $user['cion']-$row['cion'];
            $this->mcdb->get_update('user',$uid,array('cion'=>$xcion));
            //写入消费记录
            $add['uid'] = $uid;
            $add['text'] = '购买小说《'.$mname.'》章节《'.$row['name'].'》';
            $add['cion'] = $row['cion'];
            $add['bid'] = $row['bid'];
            $add['cid'] = $cid;
            $add['ip'] = getip();
            $add['addtime'] = time();
            $this->mcdb->get_insert('buy',$add);
            //写入购买记录
            $add1['uid'] = $uid;
            $add1['bid'] = $row['bid'];
            $add1['cid'] = $cid;
            $add1['auto'] = $auto;
            $this->mcdb->get_insert('book_buy',$add1);
			//改变所有购买模式
			$this->mcdb->get_update('book_buy',$row['bid'],array('auto'=>$auto),'bid');
            //分成记录
            if($zzid != $uid){
                $add2['uid'] = $zzid;
                $add2['text'] = '收到小说《'.$mname.'》章节购买分成';
                $add2['bid'] = $row['bid'];
                $add2['cion'] = round($row['cion']*Author_Fc_Comic/100);
                $add2['zcion'] = $row['cion'];
                $add2['addtime'] = time();
                $this->mcdb->get_insert('income',$add2);
                //增加收入
                $xrmb = round($row['cion']/Pay_Rmb_Cion*Author_Fc_Comic/100,2);
                $this->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$zzid);
            }
        }
        get_json(array('msg'=>'购买成功','text'=>$text,'cion'=>$row['cion'].Pay_Cion_Name),1);
    }

    //根据小说ID获取购买的章节，跟已阅读的
    public function buyread() {
        $bid = (int)$this->input->get_post('bid');
        $read = $buy = $pay = array();
        if($bid > 0){
            //判断登陆
            if($this->users->login(1)){
                $uid = $this->cookie->get('user_id');
                $buy = $this->mcdb->get_select('book_buy','cid',array('bid'=>$bid,'uid'=>$uid),'id DESC',1000);
                $read = $this->mcdb->get_select('book_read','cid',array('bid'=>$bid,'uid'=>$uid),'id DESC',1000);
            }
        }
        $pay = $this->db->or_where('vip>',0)->or_where('cion>',0)->where('bid',$bid)->select('id')->limit(10000)->
                get(get_chapter_table($bid))->result_array();
        $arr['code'] = 1;
        $arr['msg'] = '获取成功';
        $arr['read'] = $read;
        $arr['buy'] = $buy;
        $arr['pay'] = $pay;
        get_json($arr);
    }
}
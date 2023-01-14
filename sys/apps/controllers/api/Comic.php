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

class Comic extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//搜索栏热门10条
    public function hot() {
        $data = $this->mcdb->get_select('comic','id,name,pic,yname,author,text,content',array('yid'=>0),'rhits DESC',10);
        $xarr = array();
        foreach ($data as $k => $v) {
            $xarr[$k]['id'] = $v['id'];
            $xarr[$k]['pic'] = getpic($v['pic']);
            $xarr[$k]['name'] = $v['name'];
            $xarr[$k]['author'] = $v['author'];
            $xarr[$k]['text'] = empty($v['text']) ? sub_str($v['content'],10) : $v['text'];
            $xarr[$k]['url'] = get_url('show',$v);
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $xarr;
        get_json($d);
	}

    //根据ID获取漫画所有章节
    public function chapter() {
        $mid = (int)$this->input->get_post('mid');
        $xarr = array();
        if($mid > 0){
            $data = $this->mcdb->get_select('comic_chapter','*',array('mid'=>$mid),'xid ASC,id DESC',5000);
            foreach ($data as $k => $v) {
                $xarr[$k]['id'] = $v['id'];
                $xarr[$k]['name'] = $v['name'];
                $xarr[$k]['link'] = get_url('pic',$v);
                $xarr[$k]['pnum'] = $v['pnum'];
                $xarr[$k]['price'] = date('Y-m-d',$v['addtime']) == date('Y-m-d') ? 1 : 0;
                $xarr[$k]['vip'] = $v['vip'] > 0 ? 1 : 0;
                $xarr[$k]['cion'] = $v['cion'] > 0 ? $v['cion'] : 0;
            }
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $xarr;
        get_json($d);
    }

    //漫画章节是否购买
    public function isbuy($op='cion') {
        $id = (int)$this->input->get_post('id');
        if($id == 0) get_json('参数错误');
        $row = $this->mcdb->get_row_arr('comic_chapter','vip,cion',array('id'=>$id));
        if(!$row) get_json('章节不存在');
        if(!$this->users->login(1)) get_json('登录超时',2);
        //会员ID
    	$uid = $this->cookie->get('user_id');
    	//VIP章节
    	if($row['vip'] > 0){
    		$vip = (int)getzd('user','vip',$uid);
    		if($vip == 0) get_json(array('msg'=>'VIP专属，级别不够','type'=>'vip'),3);
    	}else{
            //判断是否购买过
            $row = $this->mcdb->get_row_arr('comic_buy','id',array('cid'=>$id,'uid'=>$uid));
            if(!$row) get_json(array('msg'=>'未购买','type'=>'cion'),3);
    	}
    	//获取章节图片
    	$data = $this->mcdb->get_select('comic_pic','*',array('cid'=>$id),'xid ASC',1000);
    	$parr = array();
    	foreach ($data as $k => $v) {
    		$parr[$k]['id'] = $v['id'];
    		$parr[$k]['img'] = getpic($v['img']);
    		$parr[$k]['width'] = $v['width'];
    		$parr[$k]['height'] = $v['height'];
    	}
    	get_json(array('msg'=>'已购买','pic'=>$parr),1);
    }

    //购买漫画章节
    public function buy() {
        $id = (int)$this->input->get_post('id');
        $auto = (int)$this->input->get_post('auto');
        if($id == 0) get_json('参数错误!!!');
        //判断登陆
        if(!$this->users->login(1)) get_json('登陆超时!!!',2);
        $uid = $this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('comic_chapter','*',array('id'=>$id));
        if(!$row) get_json('章节不存在');
        //判断是否购买过
        $row2 = $this->mcdb->get_row_arr('comic_buy','id',array('cid'=>$id,'uid'=>$uid));
        if($row2) get_json('已经购买过了',1);
        //会员信息
        $user = $this->mcdb->get_row_arr('user','id,cion',array('id'=>$uid));
        //作者ID
        $zzid = getzd('comic','uid',$row['mid']);
        //判断条件
        if($row['cion'] > 0){
            //金币不足
            if($row['cion'] > $user['cion']) get_json(Pay_Cion_Name.'不足，请先充值',3);
            //漫画标题
            $mname = getzd('comic','name',$row['mid']);
            //扣币
            $xcion = $user['cion']-$row['cion'];
            $this->mcdb->get_update('user',$uid,array('cion'=>$xcion));
            //写入消费记录
            $add['uid'] = $uid;
            $add['text'] = '购买漫画《'.$mname.'》章节《'.$row['name'].'》';
            $add['cion'] = $row['cion'];
            $add['mid'] = $row['mid'];
            $add['cid'] = $id;
            $add['ip'] = getip();
            $add['addtime'] = time();
            $this->mcdb->get_insert('buy',$add);
            //写入购买记录
            $add1['uid'] = $uid;
            $add1['mid'] = $row['mid'];
            $add1['cid'] = $id;
            $add1['auto'] = $auto;
            $this->mcdb->get_insert('comic_buy',$add1);
			//改变所有购买模式
			$this->mcdb->get_update('comic_buy',$row['mid'],array('auto'=>$auto),'mid');
            //分成记录
            if($zzid != $uid){
                $add2['uid'] = $zzid;
                $add2['text'] = '收到漫画《'.$mname.'》章节购买分成';
                $add2['mid'] = $row['mid'];
                $add2['cion'] = round($row['cion']*Author_Fc_Comic/100);
                $add2['zcion'] = $row['cion'];
                $add2['addtime'] = time();
                $this->mcdb->get_insert('income',$add2);
                //增加收入
                $xrmb = round($row['cion']/Pay_Rmb_Cion*Author_Fc_Comic/100,2);
                $this->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$zzid);
            }
        }
        get_json('购买成功，三秒后刷新!',1);
    }
    
    //根据漫画ID获取阅读记录
    public function myread() {
        $mid = (int)$this->input->get_post('mid');
        if($mid > 0){
            //判断登陆
            if($this->users->login(1)){
                $uid = $this->cookie->get('user_id');
                $row = $this->mcdb->get_row_arr('read','cid',array('mid'=>$mid,'uid'=>$uid),'addtime DESC');
                if($row){
                    $rowz = $this->mcdb->get_row_arr('comic_chapter','*',array('id'=>$row['cid']));
                    get_json(array('url'=>get_url('pic',$rowz)),1);
                }
            }
        }
        get_json('没有记录',0);
    }

    //根据漫画ID获取购买的章节，跟已阅读的
    public function buyread() {
        $mid = (int)$this->input->get_post('mid');
        $read = $buy = array();
        if($mid > 0){
            //判断登陆
            if($this->users->login(1)){
                $uid = $this->cookie->get('user_id');
                $buy = $this->mcdb->get_select('comic_buy','cid',array('mid'=>$mid,'uid'=>$uid),'id DESC',1000);
                $read = $this->mcdb->get_select('read','cid',array('mid'=>$mid,'uid'=>$uid),'id DESC',1000);
            }
        }
        $arr['code'] = 1;
        $arr['msg'] = '获取成功';
        $arr['read'] = $read;
        $arr['buy'] = $buy;
        get_json($arr);
    }
}
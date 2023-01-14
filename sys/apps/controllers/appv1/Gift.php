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

class Gift extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//判断签名
		get_app_sign();
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
	}
	
	//礼物列表
    public function index() {
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        //任务列表
        $list = $this->mcdb->get_select('gift','id,name,pic,cion',array('yid'=>0),'xid ASC',8);
        //打赏榜单
        if($mid > 0){
            $sql = 'select uid,sum(cion) as cion from '.Mc_SqlPrefix.'gift_reward where mid='.$mid.' GROUP BY uid order by cion desc limit 20';
        }else{
            $sql = 'select uid,sum(cion) as cion from '.Mc_SqlPrefix.'gift_reward where bid='.$bid.' GROUP BY uid order by cion desc limit 20';
        }
		$gift_hot = $this->mcdb->get_sql($sql,1);
		foreach($gift_hot as $k=>$v){
		    $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$v['uid']));
		    $gift_hot[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
		    $gift_hot[$k]['upic'] = getpic($rowu['pic'],'user');
		}
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        //输出
        $d['code'] = 1;
        $d['user'] = $this->user;
        $d['list'] = get_app_data($list);
        $d['hot'] = get_app_data($gift_hot);
        get_json($d);
    }
    
    //打赏礼物
    public function send(){
        $num = (int)$this->input->get_post('num');
        $gid = (int)$this->input->get_post('gid');
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        if($num == 0) $num = 1;
        if($gid == 0 || ($mid == 0 && $bid == 0)) get_json('参数错误',0);
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        if(!$this->user) get_json('登陆超时',-1);
        if($mid > 0){
            $rowm = $this->mcdb->get_row_arr('comic','id,name,uid,cion',array('id'=>$mid,'yid'=>0));
            if(!$rowm) get_json('漫画不存在');
        }else{
            $rowm = $this->mcdb->get_row_arr('book','id,name,uid,cion',array('id'=>$bid,'yid'=>0));
            if(!$rowm) get_json('小说不存在');
        }
        $row = $this->mcdb->get_row_arr('gift','*',array('id'=>$gid));
        if(!$row) get_json('礼物不存在');
        //需要总金币
        $cion = $row['cion']*$num;
        if($cion > $this->user['cion']) get_json('余额不足，请充值',0);
        //减去用户余额
        $xcion = $this->user['cion']-$cion;
        $this->mcdb->get_update('user',$this->uid,array('cion'=>$xcion));
        //增加漫画打赏数量
        if($mid > 0){
            $this->db->query('update '.Mc_SqlPrefix.'comic set cion=cion+'.$cion.' where id='.$mid);
            $title = '漫画';
        }else{
            $this->db->query('update '.Mc_SqlPrefix.'book set cion=cion+'.$cion.' where id='.$bid);
            $title = '小说';
        }
        //写入记录
        $text = '给你送了'.$num.'个'.$row['name'];
        $this->mcdb->get_insert('gift_reward',array('uid'=>$this->uid,'gid'=>$gid,'num'=>$num,'mid'=>$mid,'bid'=>$bid,'cion'=>$cion,'text'=>$text,'addtime'=>time()));
        //写入消费记录
        $add['uid'] = $this->uid;
        $add['text'] = '打赏了'.$num.'个['.$row['name'].']礼物给'.$title.'《'.$rowm['name'].'》';
        $add['mid'] = $mid;
        $add['bid'] = $bid;
        $add['cion'] = $cion;
        $add['ip'] = getip();
        $add['addtime'] = time();
        $this->mcdb->get_insert('buy',$add);
        //分成记录
        $fccion = round($cion*Author_Fc_Ds/100);
       	if($rowm['uid'] > 0 && $fccion > 0 && $rowm['uid'] != $this->uid){
            $add2['uid'] = $rowm['uid'];
            $add2['text'] = '收到'.$title.'《'.$rowm['name'].'》'.$num.'个['.$row['name'].']礼物打赏';
            $add2['mid'] = $mid;
            $add2['bid'] = $bid;
            $add2['cion'] = $fccion;
            $add2['zcion'] = $cion;
            $add2['addtime'] = time();
            $this->mcdb->get_insert('income',$add2);
            //增加收入
            $xrmb = round($cion*Author_Fc_Ds/100/Pay_Rmb_Cion,2);
            $this->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$rowm['uid']);
        }
        get_json(array('xcion'=>$rowm['cion']+$cion),1);
    }
    
	//月票贡献排行
    public function ticket() {
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        //打赏榜单
        if($mid > 0){
            $sql = 'select uid,sum(num) as ticket from '.Mc_SqlPrefix.'ticket where mid='.$mid.' GROUP BY uid order by ticket desc limit 20';
        }else{
            $sql = 'select uid,sum(num) as ticket from '.Mc_SqlPrefix.'ticket where bid='.$bid.' GROUP BY uid order by ticket desc limit 20';
        }
		$tickethot = $this->mcdb->get_sql($sql,1);
		foreach($tickethot as $k=>$v){
		    $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$v['uid']));
		    $tickethot[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
		    $tickethot[$k]['upic'] = getpic($rowu['pic'],'user');
		}
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        //输出
        $d['code'] = 1;
        $d['user'] = $this->user;
        $d['hot'] = get_app_data($tickethot);
        get_json($d);
    }

    //赠送月票
    public function ticket_send(){
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $ticket = (int)$this->input->get_post('ticket');
        if(($mid == 0 && $bid == 0) || $ticket == 0) get_json('参数错误',0);
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        if(!$this->user) get_json('登陆超时',-1);
        if($mid > 0){
            $rowm = $this->mcdb->get_row_arr('comic','id,name,uid,ticket',array('id'=>$mid,'yid'=>0));
            if(!$rowm) get_json('漫画不存在',0);
        }else{
            $rowm = $this->mcdb->get_row_arr('book','id,name,uid,ticket',array('id'=>$bid,'yid'=>0));
            if(!$rowm) get_json('小说不存在',0);
        }
        //用户余额
        $uticket = getzd('user','ticket',$this->uid);
        if($ticket > $uticket) get_json('月票不足，请充值',0);
        //减去用户余额
        $xticket = $uticket-$ticket;
        $this->mcdb->get_update('user',$this->uid,array('ticket'=>$xticket));
        //增加漫画月票数量
        if($mid > 0){
            $this->db->query('update '.Mc_SqlPrefix.'comic set ticket=ticket+'.$ticket.' where id='.$mid);
        }else{
            $this->db->query('update '.Mc_SqlPrefix.'book set ticket=ticket+'.$ticket.' where id='.$bid);
        }
        //写入月票消费记录
        $add['uid'] = $this->uid;
        $add['text'] = '打赏了'.$ticket.'张月票给《'.$rowm['name'].'》';
        $add['mid'] = $mid;
        $add['bid'] = $bid;
        $add['num'] = $ticket;
        $add['addtime'] = time();
        $this->mcdb->get_insert('ticket',$add);
        //分成记录
        $fccion = round($ticket*Pay_Rmb_Cion*Author_Fc_Yp/100);
        if($rowm['uid'] > 0 && $fccion > 0 && $rowm['uid'] != $uid){
            $add2['uid'] = $rowm['uid'];
            $add2['text'] = '收到'.$ticket.'张月票打赏';
            $add2['mid'] = $mid;
            $add2['bid'] = $bid;
            $add2['cion'] = $fccion;
            $add2['zcion'] = $ticket;
            $add2['addtime'] = time();
            $this->mcdb->get_insert('income',$add2);
            //增加收入
            $xrmb = round($ticket*Author_Fc_Yp/100,2);
            $this->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$rowm['uid']);
        }
        get_json(array('msg'=>'感谢亲宝贵的月票','xticket'=>($rowm['ticket']+$ticket)),1);
    }
}
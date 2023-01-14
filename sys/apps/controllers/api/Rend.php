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

class Rend extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//获取会员浏览记录
    public function history($type='comic') {
        if($type != 'book') $type = 'comic';
        $arr = array();
        $k = 0;
        //先获取登陆后数据库记录
        $log = $this->users->login(1);
        if($log){
            $uid = $this->cookie->get('user_id');
            $table = $type == 'book' ? 'book_read' : 'read';
            $array = $this->mcdb->get_select($table,'*',array('uid'=>$uid),'addtime desc',10);
            foreach ($array as $k=>$v) {
                if($type == 'book'){
                    $row = $this->mcdb->get_row_arr('book','id,cid,name,pic,yname,text,content',array('id'=>$v['bid']));
                    $row['pic'] = getpic($row['pic']);
                    $arr[$k] = $row;
                    $chapter_table = get_chapter_table($v['bid']);
                    if(empty($arr[$k]['text'])) $arr[$k]['text'] = sub_str($arr[$k]['content'],10);
                    $arr[$k]['url'] = get_url('book_info',array('id'=>$row['id'],'yname'=>$row['yname']));
                    $arr[$k]['chapter_url'] = get_url('book_read',array('id'=>$v['cid'],'bid'=>$v['bid']));
                    $arr[$k]['chapter_name'] = getzd($chapter_table,'name',$v['cid']);
                    $row = $this->mcdb->get_row_arr($chapter_table,'id,name',array('bid'=>$v['bid']),'id desc');
                    $arr[$k]['chapter_xurl'] = get_url('book_read',array('id'=>$row['id'],'bid'=>$v['bid']));
                    $arr[$k]['chapter_xname'] = $row['name'];
                }else{
                    $row = $this->mcdb->get_row_arr('comic','id,cid,name,pic,yname,text,content',array('id'=>$v['mid']));
                    $row['pic'] = getpic($row['pic']);
                    $arr[$k] = $row;
                    if(empty($arr[$k]['text'])) $arr[$k]['text'] = sub_str($arr[$k]['content'],10);
                    $arr[$k]['url'] = get_url('show',array('id'=>$row['id'],'yname'=>$row['yname']));
                    $arr[$k]['chapter_url'] = get_url('pic',array('id'=>$v['cid'],'mid'=>$v['mid']));
                    $arr[$k]['chapter_name'] = getzd('comic_chapter','name',$v['cid']);
                    $row = $this->mcdb->get_row_arr('comic_chapter','id,name',array('mid'=>$v['mid']),'id desc');
                    $arr[$k]['chapter_xurl'] = get_url('pic',array('id'=>$row['id'],'mid'=>$v['mid']));
                    $arr[$k]['chapter_xname'] = $row['name'];
                }
            }
        }else{
            //再次获取浏览器缓存记录
            $read = $this->cookie->get($type.'_read');
            if($read){
                $k = 0;
                $ids = explode(',', $read);
                foreach ($ids as $i=>$v) {
                    $aa = explode('-', $v);
                    $did = (int)$aa[0];
                    $cid = (int)$aa[1];
                    if($did > 0){
                        if($type == 'book'){
                            $row = $this->mcdb->get_row_arr('book','id,cid,name,pic,yname,text,content',array('id'=>$did));
                            if($row){
                                $chapter_table = get_chapter_table($did);
                                $row['pic'] = getpic($row['pic']);
                                $row2 = $this->mcdb->get_row_arr($chapter_table,'id,name',array('bid'=>$did),'id desc');
                                if(empty($row['text'])) $row['text'] = sub_str($row['content'],10);
                                $row['url'] = get_url('book_info',array('id'=>$row['id'],'yname'=>$row['yname']));
                                $row['chapter_url'] = get_url('book_read',array('id'=>$cid,'bid'=>$did));
                                $row['chapter_name'] = getzd($chapter_table,'name',$cid);
                                $row['chapter_xurl'] = get_url('book_read',array('id'=>$row2['id'],'bid'=>$did));
                                $row['chapter_xname'] = $row2['name'];
                            }
                        }else{
                            $row = $this->mcdb->get_row_arr('comic','id,cid,name,pic,yname,text,content',array('id'=>$did));
                            if($row){
                                $row['pic'] = getpic($row['pic']);
                                $row2 = $this->mcdb->get_row_arr('comic_chapter','id,name',array('mid'=>$did),'id desc');
                                if(empty($row['text'])) $row['text'] = sub_str($row['content'],10);
                                $row['url'] = get_url('show',array('id'=>$row['id'],'yname'=>$row['yname']));
                                $row['chapter_url'] = get_url('pic',array('id'=>$cid,'mid'=>$did));
                                $row['chapter_name'] = getzd('comic_chapter','name',$cid);
                                $row['chapter_xurl'] = get_url('pic',array('id'=>$row2['id'],'mid'=>$did));
                                $row['chapter_xname'] = $row2['name'];
                            }
                        }
                        $arr[] = $row;
                    }
                }
            }
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $arr;
        get_json($d);
	}

    //获取会员收藏记录
    public function fav($type='comic') {
        if($type != 'book') $type = 'comic';
        $arr = array();
        $log = $this->users->login(1);
        if($log){
            $uid = $this->cookie->get('user_id');
            $table = $type == 'book' ? 'book_fav' : 'fav';
            $array = $this->mcdb->get_select($table,'*',array('uid'=>$uid),'addtime desc',10);
            foreach ($array as $k=>$v) {
                if($type == 'book'){
                    $row = $this->mcdb->get_row_arr('book','id,cid,name,pic,yname,text,content',array('id'=>$v['bid']));
                    $row['pic'] = getpic($row['pic']);
                    $row2 = $this->mcdb->get_row_arr('book_read','id,cid',array('bid'=>$v['bid'],'uid'=>$uid));
                    $chapter_table = get_chapter_table($v['bid']);
                    $row3 = $this->mcdb->get_row_arr($chapter_table,'id,name',array('bid'=>$v['bid']),'xid asc');
                    $row4 = $this->mcdb->get_row_arr($chapter_table,'id,name',array('bid'=>$v['bid']),'xid desc');
                    $arr[$k] = $row;
                    if(empty($arr[$k]['text'])) $arr[$k]['text'] = sub_str($arr[$k]['content'],10);
                    $arr[$k]['url'] = get_url('book_info',array('id'=>$row['id'],'yname'=>$row['yname']));
                    $v['cid'] = $row2 ? $row2['cid'] : $row3['id'];
                    $arr[$k]['chapter_url'] = get_url('book_read',array('id'=>$v['cid'],'bid'=>$v['bid']));
                    $arr[$k]['chapter_name'] = getzd($chapter_table,'name',$v['cid']);
                    $arr[$k]['chapter_xurl'] = get_url('book_read',array('id'=>$row4['id'],'bid'=>$v['bid']));
                    $arr[$k]['chapter_xname'] = $row4['name'];
                }else{
                    $row = $this->mcdb->get_row_arr('comic','id,cid,name,pic,yname,text,content',array('id'=>$v['mid']));
                    $row['pic'] = getpic($row['pic']);

                    $row2 = $this->mcdb->get_row_arr('read','id,cid',array('mid'=>$v['mid'],'uid'=>$uid));
                    $row3 = $this->mcdb->get_row_arr('comic_chapter','id,name',array('mid'=>$v['mid']),'xid asc');
                    $row4 = $this->mcdb->get_row_arr('comic_chapter','id,name',array('mid'=>$v['mid']),'xid desc');
                    $arr[$k] = $row;
                    if(empty($arr[$k]['text'])) $arr[$k]['text'] = sub_str($arr[$k]['content'],10);
                    $arr[$k]['url'] = get_url('show',array('id'=>$row['id'],'yname'=>$row['yname']));
                    $v['cid'] = $row2 ? $row2['cid'] : $row3['id'];
                    $arr[$k]['chapter_url'] = get_url('pic',array('id'=>$v['cid'],'mid'=>$v['mid']));
                    $arr[$k]['chapter_name'] = getzd('comic_chapter','name',$v['cid']);
                    $arr[$k]['chapter_xurl'] = get_url('pic',array('id'=>$row4['id'],'mid'=>$v['mid']));
                    $arr[$k]['chapter_xname'] = $row4['name'];
                }
            }
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $arr;
        get_json($d);
    }

    //未读消息
    public function message(){
        $log = $this->users->login(1);
        $count = 0;
        if($log){
            $uid = $this->cookie->get('user_id');
            $count = $this->mcdb->get_nums('message',array('uid'=>$uid,'did'=>0));
        }
        get_json(array('count'=>$count,'msg'=>'用户未读消息'),1);
    }

    //礼物记录
    public function gift(){
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        if($mid == 0 && $bid == 0) get_json('参数错误');
        if($mid > 0){
            $gift = $this->mcdb->get_select('gift_reward','*',array('mid'=>$mid),'id DESC',500);
        }else{
            $gift = $this->mcdb->get_select('gift_reward','*',array('bid'=>$bid),'id DESC',500);
        }
        $data = array();
        foreach ($gift as $k => $row) {
            $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$row['uid']));
            $data[$k]['num'] = $row['num'];
            $data[$k]['cion'] = $row['cion'];
            $data[$k]['name'] = getzd('gift','name',$row['gid']);
            $data[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
            $data[$k]['upic'] = getpic($rowu['pic']);
            $data[$k]['addtime'] = date('Y-m-d H:i:s',$row['addtime']);
        }
        get_json(array('msg'=>'礼物记录','list'=>$data),1);
    }

    //赠送月票
    public function ticket_send(){
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        $ticket = (int)$this->input->get_post('ticket');
        if(($mid == 0 && $bid == 0) || $ticket == 0) get_json('参数错误');
        $log = $this->users->login(1);
        if(!$log) get_json('登陆超时');
        $uid = $this->cookie->get('user_id');
        if($mid > 0){
            $table = 'comic';$did = $mid;
            $rowm = $this->mcdb->get_row_arr('comic','id,name,uid,ticket',array('id'=>$mid,'yid'=>0));
            if(!$rowm) get_json('漫画不存在');
        }else{
            $table = 'book';$did = $bid;
            $rowm = $this->mcdb->get_row_arr('book','id,name,uid,ticket',array('id'=>$bid,'yid'=>0));
            if(!$rowm) get_json('小说不存在');
        }
        //用户余额
        $uticket = getzd('user','ticket',$uid);
        if($ticket > $uticket) get_json('月票不足，请充值');
        //减去用户余额
        $xticket = $uticket-$ticket;
        $this->mcdb->get_update('user',$uid,array('ticket'=>$xticket));
        //增加漫画月票数量
        $this->db->query('update '.Mc_SqlPrefix.$table.' set ticket=ticket+'.$ticket.' where id='.$did);
        //写入月票消费记录
        $add['uid'] = $uid;
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
        get_json(array('msg'=>'感谢亲宝贵的月票,作者大大会继续努力创作','uticket'=>$xticket,'ticket'=>($rowm['ticket']+$ticket)),1);
    }

    //打赏礼物
    public function gift_send(){
        $num = (int)$this->input->get_post('num');
        $gid = (int)$this->input->get_post('gid');
        $mid = (int)$this->input->get_post('mid');
        $bid = (int)$this->input->get_post('bid');
        if($num == 0) $num = 1;
        if($gid == 0 || ($mid == 0 && $bid == 0)) get_json('参数错误');
        $log = $this->users->login(1);
        if(!$log) get_json('登陆超时');
        $uid = $this->cookie->get('user_id');
        if($mid > 0){
            $table = 'comic';$did = $mid;$mode = '漫画';
            $rowm = $this->mcdb->get_row_arr('comic','id,name,uid,cion',array('id'=>$mid,'yid'=>0));
            if(!$rowm) get_json('漫画不存在');
        }else{
            $table = 'book';$did = $bid;$mode = '小说';
            $rowm = $this->mcdb->get_row_arr('book','id,name,uid,cion',array('id'=>$bid,'yid'=>0));
            if(!$rowm) get_json('小说不存在');
        }
        $row = $this->mcdb->get_row_arr('gift','*',array('id'=>$gid));
        if(!$row) get_json('礼物不存在');
        //用户余额
        $ucion = getzd('user','cion',$uid);
        //需要总金币
        $cion = $row['cion']*$num;
        if($cion > $ucion) get_json('余额不足，请充值');
        //减去用户余额
        $xcion = $ucion-$cion;
        $this->mcdb->get_update('user',$uid,array('cion'=>$xcion));
        //增加漫画打赏数量
        $this->db->query('update '.Mc_SqlPrefix.$table.' set cion=cion+'.$cion.' where id='.$did);
        //写入记录
        $text = '给你送了'.$num.'个'.$row['name'];
        $this->mcdb->get_insert('gift_reward',array('uid'=>$uid,'gid'=>$gid,'num'=>$num,'mid'=>$mid,'bid'=>$bid,'cion'=>$cion,'text'=>$text,'addtime'=>time()));
        //写入消费记录
        $add['uid'] = $uid;
        $add['text'] = '打赏了'.$num.'个['.$row['name'].']礼物给'.$mode.'《'.$rowm['name'].'》';
        if($mid > 0) $add['mid'] = $mid;
        if($bid > 0) $add['bid'] = $bid;
        $add['cion'] = $cion;
        $add['ip'] = getip();
        $add['addtime'] = time();
        $this->mcdb->get_insert('buy',$add);
        //分成记录
        $fccion = round($cion*Author_Fc_Ds/100);
       	if($rowm['uid'] > 0 && $fccion > 0 && $rowm['uid'] != $uid){
            $add2['uid'] = $rowm['uid'];
            $add2['text'] = '收到'.$mode.'《'.$rowm['name'].'》'.$num.'个['.$row['name'].']礼物打赏';
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
        get_json(array('msg'=>'感谢支持~','mcion'=>($rowm['cion']+$cion),'cion'=>$xcion),1);
    }
    //评分
    public function score_send(){
        $type = $this->input->get_post('type',true);
        $fen = (int)$this->input->get_post('fen');
        $did = (int)$this->input->get_post('did');
        if($fen == 0 || $did == 0) get_json('参数错误');
        if($type == 'book'){
            $table = 'book_score';
            $zd = 'bid';
        }else{
            $type = 'comic';
            $table = 'comic_score';
            $zd = 'mid';
        }
        $fen = $fen*2;
        $log = $this->users->login(1);
        if(!$log) get_json('登陆超时');
        $uid = $this->cookie->get('user_id');
        $rowm = $this->mcdb->get_row_arr($type,'id',array('id'=>$did,'yid'=>0));
        if(!$rowm) get_json('漫画不存在');
        $row = $this->mcdb->get_row_arr($table,'*',array($zd=>$did,'uid'=>$uid));
        if($row) get_json('不能重复评分');
        $this->mcdb->get_insert($table,array('uid'=>$uid,$zd=>$did,'pf'=>$fen,'addtime'=>time()));
        //计算评分
        $score = get_score($mid);
        //更新到漫画
        $this->db->query('update '.Mc_SqlPrefix.$type.' set score='.$score.' where id='.$did);
        //评分总数
        $nums = $this->mcdb->get_nums($table,array($zd=>$did));
        get_json(array('msg'=>'评分成功~','score'=>$score,'nums'=>$nums),1);
    }

    //判断是否收藏
    public function isfav() {
        $type = $this->input->get_post('type',true);
        $did = (int)$this->input->get_post('did');
        if($did > 0){
            //判断登陆
            if($this->users->login(1)){
                $uid = $this->cookie->get('user_id');
                if($type == 'book'){
                    $row = $this->mcdb->get_row_arr('book_fav','id',array('bid'=>$did,'uid'=>$uid));
                }else{
                    $row = $this->mcdb->get_row_arr('fav','id',array('mid'=>$did,'uid'=>$uid));
                }
                if($row) get_json('已收藏',1);
            }
        }
        get_json('未收藏');
    }

    //收藏
    public function favadd() {
        $type = $this->input->get_post('type',true);
        $did = (int)$this->input->get_post('did');
        if($did == 0) get_json('ID不能为空');
        if($type != 'book') $type = 'comic';
        $favtable = $type == 'book' ? 'book_fav' : 'fav';
        $zd = $type == 'book' ? 'bid' : 'mid';
        //判断登陆
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = $this->cookie->get('user_id');
        $rowm = $this->mcdb->get_row_arr($type,'id,shits',array('id'=>$did,'yid'=>0));
        if(!$rowm) get_json('数据不存在或者未审核');
        //判断是否已经收藏
        $row = $this->mcdb->get_row_arr($favtable,'id',array($zd=>$did,'uid'=>$uid));
        if($row){
            $res = $this->mcdb->get_del($favtable,$row['id']);
            $txt = '取消收藏';
            $cid = 0;
            //减少收藏人气
            $xshits = $rowm['shits'] > 0 ? $rowm['shits']-1 : 0;
        }else{
            $res = $this->mcdb->get_insert($favtable,array($zd=>$did,'uid'=>$uid,'addtime'=>time()));
            $txt = '收藏成功';
            $cid = 1;
            //增加收藏人气
            $xshits = $rowm['shits']+1;
        }
        if($res){
            //修改收藏人气
            $this->mcdb->get_update($type,$did,array('shits'=>$xshits));
            get_json(array('msg'=>$txt,'cid'=>$cid,'shits'=>$xshits),1);
        }else{
            get_json('操作失败!');
        }
    }

    //写入阅读记录
    public function read() {
        $type = $this->input->get_post('type',true);
        $did = (int)$this->input->get_post('did');
        $cid = (int)$this->input->get_post('cid');
        $pid = (int)$this->input->get_post('pid');
        if($type != 'book') $type = 'comic';
        if($did == 0 || $cid == 0) get_json('参数错误!!!');
        $uid = $this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr($type,'id',array('id'=>$did,'yid'=>0));
        if(!$row) get_json('数据不存在或者未审核');
        //判断登陆
        if(!$this->users->login(1)){
            $k = 0;
            $xarr = array();
            $xarr[$k] = $did.'-'.$cid;
            $ids = $this->cookie->get($type.'_read');
            if($ids){
                $ids = explode(',',$ids);
                foreach ($ids as $i=>$v) {
                    $k++;
                    $a = explode('-', $v);
                    if($k < 10 && $a[0] != $did) $xarr[$k] = $v;
                }
            }
            //记录
            $this->cookie->set($type.'_read',implode(',',$xarr),time()+86400*30);
        }else{
            $table = $type == 'book' ? 'book_read' : 'read';
            $zd = $type == 'book' ? 'bid' : 'mid';
            //获取最新一条记录时间来判断暴库
            $row = $this->mcdb->get_row_arr($table,'addtime',array('uid'=>$uid),'addtime desc');
            if($row['addtime']+5 > time()) get_json('频繁操作!!!');
            $add = array('uid'=>$uid,$zd=>$did,'cid'=>$cid,'addtime'=>time());
            if($type == 'comic') $add['pid'] = $pid;
            //判断漫画记录是否存在
            $row = $this->mcdb->get_row_arr($table,'id,addtime',array('uid'=>$uid,$zd=>$did));
            if($row){
                unset($add['uid']);
                $this->mcdb->get_update($table,$row['id'],$add);
            }else{
                $this->mcdb->get_insert($table,$add);
            }
        }
        get_json('记录完成!!!',1);
    }

    //判断数组是否包含相同ID
    private function get_is_arr($arr,$id=0){
        foreach ($arr as $k=>$v) {
            if($arr[$k]['id'] == $id){
                return $k;
            }
        }
        return -1;
    }
}
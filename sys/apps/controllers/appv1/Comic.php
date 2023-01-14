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
        header("Access-Control-Allow-Origin: *");
        //加载函数
        $this->load->helper('app_helper');
        //用户ID
        $this->uid = (int)$this->input->get_post('user_id');
        //用户token
        $this->token = $this->input->get_post('user_token');
        //判断签名
        get_app_sign();
    }

    //主页
    public function index() {
        $reco_size = (int)$this->input->get_post('reco_size');
        $news_size = (int)$this->input->get_post('news_size');
        $type_size = (int)$this->input->get_post('type_size');
        if($reco_size == 0) $reco_size = 5;
        if($news_size == 0) $news_size = 4;
        if($type_size == 0) $type_size = 4;
        //banner
        $banner = $this->mcdb->get_select('comic','id,name,pic,picx',array('yid'=>0,'tid'=>1),'rhits DESC',8);
        $list['banner'] = get_app_data($banner);
        //热门推荐
        $reco = $this->mcdb->get_select('comic','*',array('yid'=>0),'zhits DESC',$reco_size);
        $list['reco'] = get_app_data($reco);
        //近期更新
        $news = $this->mcdb->get_select('comic','*',array('yid'=>0),'addtime DESC',$news_size);
        $list['news'] = get_app_data($news);
        //分类
        $list['class'] = $this->mcdb->get_select('class','id,name',array(),'xid ASC',6);
        foreach ($list['class'] as $k=>$v){
            $list['class'][$k]['comic'] = get_app_data($this->mcdb->get_select('comic','*',array('yid'=>0,'cid'=>$v['id']),'rhits DESC',$type_size));
        }
        //输出
        $d['code'] = 1;
        $d['data'] = $list;
        get_json($d);
    }
    
    //自定义获取数据
    public function data() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;
        $cid = (int)$this->input->get_post('cid');
        $uid = (int)$this->input->get_post('uid');
        $tid = (int)$this->input->get_post('tid');
        $pay = (int)$this->input->get_post('pay');
        $state = (int)$this->input->get_post('state');
        $type = $this->input->get_post('type',true);
        $key = safe_replace($this->input->get_post('key',true));
        $order = $this->input->get_post('sort',true);
        $oarr = array('id','addtime','hits','yhits','zhits','rhits','shits','shits','ticket','cion','score');
        if(!in_array($order, $oarr)) $order = 'id';
        $serialize = '';
        if($state == 1) $serialize = '连载';
        if($state == 2) $serialize = '完结';

        $wh = $like = array();
        $wh['yid'] = 0;
        $wh['sid'] = 0;
        if($cid > 0) $wh['cid'] = $cid;
        if($tid > 0) $wh['tid'] = $tid;
        if($uid > 0) $wh['uid'] = $uid;
        if($pay > 0) $wh['pay'] = $pay-1;
        if(!empty($serialize)) $wh['serialize'] = $serialize;
        if(!empty($key)) $like['name'] = $key;

        $data = array();
        if(!empty($type)){
            $arr = safe_replace($type);
            if(!empty($arr)){
                $tquery = 0;
                $sql="select a.id,a.name,a.pic,a.picx,a.author,a.serialize state,a.score,a.hits,a.text,a.content,a.addtime from ".Mc_SqlPrefix."comic a inner join ".Mc_SqlPrefix."comic_type b on a.id = b.mid where a.yid=0";
                $order = 'a.hits desc';
                $wh = array();
                foreach ($arr as $k => $v) {
                    //首字母
                    if($k == 'mark'){
                        $zimu_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                        $zimu_arr1 = array(-20319,-20283,-19775,-19218,-18710,-18526,-18239,-17922,-1,-17417,-16474,-16212,-15640,-15165,-14922,-14914,-14630,-14149,-14090,-13318,-1,-1,-12838,-12556,-11847,-11055);
                        $zimu_arr2 = array(-20284,-19776,-19219,-18711  ,-18527,-18240,-17923,-17418,-1,-16475,-16213,-15641,-15166,-14923,-14915,-14631,-14150,-14091,-13319,-12839,-1,-1,-12557,-11848,-11056,-2050);
                        if(!in_array(strtoupper($v),$zimu_arr)){
                            $sql .= $wh[]=" and substring(a.name,1,1) NOT REGEXP '^[a-zA-Z]' and substring(a.name,1,1) REGEXP '^[u4e00-u9fa5]'";
                        }else{
                            $posarr = array_keys($zimu_arr,strtoupper($v));
                            $pos=$posarr[0];
                            $sql .= $wh[]=" and (((ord(substring(convert(a.name USING gbk),1,1)) -65536>=".($zimu_arr1[$pos])." and  ord(substring(convert(a.name USING gbk),1,1)) -65536<=".($zimu_arr2[$pos]).")) or UPPER(substring(convert(a.name USING gbk),1,1))='".$zimu_arr[$pos]."')";
                        }
                    }elseif($k == 'pay'){ //是否收费
                        if($v == 3){ //VIP
                            $sql .= $wh[]=" and a.pay=2";
                        }elseif($v == 2){ //收费
                            $sql .= $wh[]=" and a.pay=1";
                        }elseif($v == 1){ //免费
                            $sql .= $wh[]=" and a.pay=0";
                        }
                    }elseif($k == 'list'){ //分类
                        if((int)$v > 0){
                            $cids = getcid($v);
                            if(!is_numeric($cids)){
                                $sql .= $wh[]=" and a.cid in(".$cids.")";
                            }else{
                                $sql .= $wh[]=" and a.cid=".$cids;
                            }
                        }
                    }elseif($k == 'finish'){ //连载
                        if($v == 2){
                            $sql .= $wh[]=" and a.serialize='完结'";
                        }elseif($v == 1){
                            $sql .= $wh[]=" and a.serialize='连载'";
                        }
                    }elseif($k == 'order'){ //排序
                        $oarr = array('id','addtime','hits','yhits','rhits','zhits','shits');
                        if(in_array($v,$oarr)) $order = 'a.'.$v.' desc';
                    }else{
                        //判断type是否存在
                        $rt = $this->mcdb->get_row('type','id',array('zd'=>$k,'fid'=>0));
                        if($rt){
                            $tquery = 1;
                            $sql .= " and b.tid=".(int)$v;
                        }
                    }
                }
                $sql .= $wh[]=' order by '.$order;
                if($tquery == 0){
                    $sql = "select id,name,pic,picx,author,score,serialize state,hits,text,content,addtime from ".Mc_SqlPrefix."comic where yid = 0".str_replace('a.','',implode('',$wh));
                }
                $nums = $this->mcdb->get_sql_nums($sql);
                $sql .= " limit ".$size*($page-1).",".$size;
                $data = $this->db->query($sql)->result_array();
            }
        }else{
            $limit = array($size,$size*($page-1));
            $nums = $this->mcdb->get_nums('comic',$wh,$like);
            $data = $this->mcdb->get_select('comic','id,name,pic,picx,author,serialize state,score,hits,text,content,addtime',$wh,$order.' DESC',$limit,$like);
        }
        //输出
        $d['code'] = 1;
        $d['msg'] = '漫画列表';
        $d['nums'] = $nums;
        $d['size'] = $size;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['list'] = get_app_data($data);
        get_json($d);
    }
    
    //漫画详情
    public function info() {
        $id = (int)$this->input->get_post('id');
        if($id == 0) get_json('漫画ID为空',0);
        //判断漫画是否存在
        $row = $this->mcdb->get_row_arr('comic','id,cid,name,pic,picx,author,serialize state,score,cion,ticket,sid,hits,shits,text,content,nums,ly,did,addtime',array('id'=>$id,'yid'=>0));
        if(!$row) get_json('漫画不存在',0);
        $row['addtime'] = datetime($row['addtime']);
        $row['cion_name'] = Pay_Cion_Name;
        $row['cion_rank'] = get_rank($id);
        $row['ticket_rank'] = get_rank($id,'ticket');
        //是否收藏
        $rowf = $this->mcdb->get_row_arr('fav','id',array('mid'=>$id,'uid'=>$this->uid));
        $row['fav'] = $rowf ? 1 : 0;
        //判断是否阅读
        $rowr = $this->mcdb->get_row_arr('read','cid,pid',array('mid'=>$id,'uid'=>$this->uid));
        $row['zid'] = $rowr ? $rowr['cid'] : 0;
        $row['pid'] = $rowr ? $rowr['pid'] : 0;
        //判断章节入库
        $zjnum = $this->mcdb->get_nums('comic_chapter',array('mid'=>$row['id']));
        if(($zjnum == 0 || $zjnum < $row['nums']) && $row['did'] > 0){
            $this->load->model('collect');
            $arr = require MCCMSPATH.'libs/collect.php';
            if(!empty($row['ly'])){
                if(isset($arr['zyk'][$row['ly']])){
                    $this->collect->get_update_chapter($arr['zyk'][$row['ly']]['jxurl'].'/index/'.$row['did'],$row['id'],'comic','comic_chapter',$arr['zyk'][$row['ly']]['token']);
                }
            }else{
                $this->collect->get_update_chapter(Caiji_Tb_Url.'/index/'.$row['did'],$row['id'],'comic','comic_chapter',Caiji_Tb_Token);
            }
            $row['nums'] = $this->mcdb->get_nums('comic_chapter',array('mid'=>$row['id']));
        }
        //章节列表
        $chapter = $this->mcdb->get_select('comic_chapter','id,name,cion,vip,pnum,addtime',array('mid'=>$id,'yid'=>0),'id ASC,xid ASC',15);
        foreach ($chapter as $k=>$v){
            $chapter[$k]['pic'] = getzd('comic_pic','img',$v['id'],'id','id','asc');
            if(empty($chapter[$k]['pic'])) $chapter[$k]['pic'] = $row['pic'];
        }
        $row['chapter_list'] = $chapter;
        //猜你喜欢
        $row['love_list'] = $this->mcdb->get_select('comic','id,name,pic,picx,author,score',array('cid'=>$row['cid'],'yid'=>0),'rhits DESC',6);
        //评论列表
        $comment = $this->mcdb->get_select('comment','id,text,uid,reply_num,zan,addtime',array('mid'=>$row['id']),'id DESC',5);
        foreach($comment as $k=>$v){
            $rowu = $this->mcdb->get_row_arr('user','name,nichen,pic',array('id'=>$v['uid']));
            $comment[$k]['unichen'] = empty($rowu['nichen']) ? $rowu['name'] : $rowu['nichen'];
            $comment[$k]['upic'] = getpic($rowu['pic']);
            $comment[$k]['addtime'] = date('m-d H:i',$v['addtime']);
            //是否赞过
            $comment[$k]['is_zan'] = 0;
            if($this->uid > 0){
                $rowz = $this->mcdb->get_row_arr('comment_zan','id',array('fid'=>0,'cid'=>$v['id'],'uid'=>$this->uid));
                if($rowz) $comment[$k]['is_zan'] = 1;
            }
        }
        $row['comment_list'] = $comment;
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        $row['user'] = array('log'=>0,'cion'=>0,'vip'=>0,'ticket'=>0);
        if($this->user) $row['user'] = $this->user;
        //分享地址、分享文本
        $row['share_txt'] = '我发现一个非常不错的漫画APP，点击下面地址下载可以免费看~';
        $row['share_url'] = 'http://'.Web_Url.links('share/comic/'.$id.'/'.($this->uid+10000));
        unset($row['did'],$row['ly']);
        //输出
        $d['code'] = 1;
        $d['comic'] = get_app_data($row);
        get_json($d);   
    }
    
    //漫画目录
    public function chapter() {
        $id = (int)$this->input->get_post('id');
        $size = (int)$this->input->get_post('size');
        $limit = $size > 0 ? array(5000,$size) : 5000;
        $list = $this->mcdb->get_select('comic_chapter','id,name,cion,vip,pnum,addtime',array('mid'=>$id),'id ASC,xid ASC',$limit);
        $pic = getzd('comic','pic',$id);
        foreach ($list as $k=>$v){
            $list[$k]['pic'] = getzd('comic_pic','img',$v['id'],'id','id','asc');
            if(empty($list[$k]['pic'])) $list[$k]['pic'] = $pic;
        }
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        $user = array('log'=>0,'cion'=>0,'vip'=>0,'ticket'=>0);
        if($this->user) $user = $this->user;
        //输出
        $d['code'] = 1;
        $d['list'] = get_app_data($list);
        $d['user'] = $user;
        get_json($d);
    }
    
    //章节图片
    public function read(){
        $mid = (int)$this->input->get_post('mid');
        $zid = (int)$this->input->get_post('zid');
        if($mid == 0) get_json('漫画ID为空',0);
        //判断漫画是否存在
        $row = $this->mcdb->get_row_arr('comic','id,name,cion,ticket,sid,nums,ly,addtime',array('id'=>$mid,'yid'=>0));
        if(!$row) get_json('漫画不存在',0);
        $row['addtime'] = datetime($row['addtime']);
        $row['cion_name'] = Pay_Cion_Name;
        $row['cion_rank'] = get_rank($mid);
        $row['ticket_rank'] = get_rank($mid,'ticket');
        //是否收藏
        $rowf = $this->mcdb->get_row_arr('fav','id',array('mid'=>$mid,'uid'=>$this->uid));
        $row['fav'] = $rowf ? 1 : 0;
        //章节信息
        if($zid == 0){
            $rowz = $this->mcdb->get_row_arr('comic_chapter','id,name,pnum,cion,vip,xid,addtime,jxurl',array('mid'=>$mid,'yid'=>0),'xid ASC');
        }else{
            $rowz = $this->mcdb->get_row_arr('comic_chapter','id,name,pnum,cion,vip,xid,addtime,jxurl',array('id'=>$zid,'yid'=>0));
        }
        if(!$rowz) get_json('章节不存在',0);
        $zid = $rowz['id'];
        //排序ID
        $rowt = $this->db->query("SELECT * FROM (SELECT id,xid,mid,(@rowNum:=@rowNum+1) AS rowNo FROM ".Mc_SqlPrefix."comic_chapter,(SELECT (@rowNum :=0) ) b WHERE mid=".$mid." ORDER BY xid DESC,id DESC) c WHERE mid=".$mid)->row_array();
        $rowz['hid'] = $rowt ? $rowt['rowNo'] : 1;
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        $user = array('log'=>0,'cion'=>0,'vip'=>0,'ticket'=>0);
        if($this->user) $user = $this->user;
        //判断章节收费
        $rowz['pay'] = app_comic_pay($this->mcdb,$mid,$zid,$rowz['cion'],$rowz['vip'],$user);
        //获取图片列表
        $picarr = $this->mcdb->get_select('comic_pic','id,mid,cid zid,img pic',array('cid'=>$zid),'xid ASC',10000);
        foreach($picarr as $k=>$v){
            $picarr[$k]['hid'] = $rowz['hid'];
            $picarr[$k]['xid'] = $k+1;
        }
        if(($rowz['pnum'] == 0 || count($picarr) < $rowz['pnum']) && !empty($rowz['jxurl'])){
            $this->load->model('collect');
            $collect = require MCCMSPATH.'libs/collect.php';
            $ly = $row['ly'];
            $token = isset($collect['zyk'][$ly]) ? $collect['zyk'][$ly]['token'] : Caiji_Tb_Token;
            $picarr = $this->collect->get_update_pic($rowz['jxurl'],$rowz['id'],$mid,$token);
        }
        $rowz['ispay'] = 0;
        if($rowz['pay'] == 3){
            $rowz['ispay'] = 1;
            $rowz['pay'] = 0;
        }
        if($rowz['pay'] !== 0) $picarr = [];
        //分享地址、分享文本
        $row['share_txt'] = '我发现一个非常不错的漫画APP，点击下面地址下载可以免费看~';
        $row['share_url'] = 'http://'.Web_Url.links('share/comic/'.$mid.'/'.($this->uid+10000));
        //上下章ID
        $rows = $this->mcdb->get_row_arr('comic_chapter','id',array('mid'=>$mid,'xid<'=>$rowz['xid'],'yid'=>0),'xid DESC');
        $rowz['szid'] = $rows ? $rows['id'] : 0;
        $rowx = $this->mcdb->get_row_arr('comic_chapter','id',array('mid'=>$mid,'xid>'=>$rowz['xid'],'yid'=>0),'xid ASC');
        $rowz['xzid'] = $rowx ? $rowx['id'] : 0;
        unset($rowz['jxurl'],$row['ly']);
        //输出
        $d['code'] = 1;
        $d['data'] = get_app_data($rowz);
        $d['data']['piclist'] = get_app_data($picarr);
        $d['data']['comic'] = get_app_data($row);
        $d['data']['user'] = get_app_data($user);
        get_json($d);   
    }
    
    //购买章节
    public function buy() {
        $mid = (int)$this->input->get_post('mid');
        $zid = (int)$this->input->get_post('zid');
        $auto = (int)$this->input->get_post('auto');
        if($mid == 0 || $zid == 0) get_json('参数错误',0);
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        if(!$this->user) get_json('未登陆',-1);
        //判断漫画是否存在
        $row = $this->mcdb->get_row_arr('comic_chapter','cion,vip',array('id'=>$zid,'yid'=>0));
        if(!$row) get_json('章节不存在',0);
        if($this->user['cion'] < $row['cion']) get_json('金币不足，请充值',0);
        app_comic_buy($mid,$zid,$row['cion'],$auto,$this->user);
        get_json('购买成功',1);
    }
    
    //获取分类
    public function type() {
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 50;
        $d['code'] = 1;
        $d['list'] = $this->mcdb->get_select('class','id,name',array(),'xid ASC',$size);
        get_json($d);
    }
    
    //热搜关键字
    public function search() {
        $app = require FCPATH.'sys/libs/app.php';
        //输出
        $d['code'] = 1;
        $d['list'] = $app['search'];
        get_json($d);
    }
}
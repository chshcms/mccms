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

class Data extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->library('user_agent');
        if(!$this->agent->is_referral()) get_json('非法请求');
	}

	//漫画
    public function comic() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 10;
        $cid = (int)$this->input->get_post('cid');
        $uid = (int)$this->input->get_post('uid');
        $tid = (int)$this->input->get_post('tid');
        $pay = (int)$this->input->get_post('pay');
        $serialize = $this->input->get_post('serialize',true);
        if(!empty($serialize) && $serialize != '连载') $serialize = '完结';
        $type = $this->input->get_post('type',true);
        $key = safe_replace(urldecode($this->input->get_post('key',true)));
        $order = $this->input->get_post('order',true);
        $oarr = array('id','addtime','hits','yhits','zhits','rhits','shits','shits','ticket','cion','score');
        if(!in_array($order, $oarr)) $order = 'id';

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
                $sql="select a.* from ".Mc_SqlPrefix."comic a inner join ".Mc_SqlPrefix."comic_type b on a.id = b.mid where a.yid=0";
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
                    $sql = "select * from ".Mc_SqlPrefix."comic where yid = 0".str_replace('a.','',implode('',$wh));
                }
                $sql .= " limit ".$size*($page-1).",".$size;
                $data = $this->db->query($sql)->result_array();
            }
        }else{
            $limit = array($size,$size*($page-1));
            $data = $this->mcdb->get_select('comic','*',$wh,$order.' DESC',$limit,$like);
        }
        foreach ($data as $k => $v) {
            $v['url'] = get_url('show',$v);
            $v['pic'] = getpic($v['pic']);
            $v['picx'] = getpic($v['picx']);
            $row = $this->mcdb->get_row_arr('comic_chapter','id,name',array('mid'=>$v['id']));
            if($row){
                $v['chapter_name'] = $row['name'];
                $v['chapter_url'] = get_url('pic',array('id'=>$row['id'],'mid'=>$v['id']));
            }else{
                $v['chapter_name'] = '待更新';
                $v['chapter_url'] = get_url('show',$v);
            }
            $v['chapter_nums'] = $v['nums'];
            $v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            if(empty($v['text'])) $v['text'] = sub_str($v['content'],10);
            unset($v['yname'],$v['sid'],$v['did'],$v['yid'],$v['msg'],$v['nums']);
            //tags
            $tags = $this->mcdb->get_select('comic_type','tid',array('mid'=>$v['id']),'id DESC',3);
            $tarr = array();
            foreach ($tags as $kk => $vv) {
                $tarr[] = getzd('type','name',$vv['tid']);
            }
            $v['tags'] = $tarr;
            $data[$k] = $v;
        }
        //输出
        $d['code'] = 1;
        $d['msg'] = '漫画列表';
        $d['data'] = $data;
        get_json($d);
	}

    //小说
    public function book() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 10;
        $cid = (int)$this->input->get_post('cid');
        $uid = (int)$this->input->get_post('uid');
        $tid = (int)$this->input->get_post('tid');
        $pay = (int)$this->input->get_post('pay');
        $serialize = $this->input->get_post('serialize',true);
        if(!empty($serialize) && $serialize != '连载') $serialize = '完结';
        $tags = (int)$this->input->get_post('tags');
        $key = safe_replace(urldecode($this->input->get_post('key',true)));
        $order = $this->input->get_post('order',true);
        $oarr = array('id','addtime','hits','yhits','zhits','rhits','shits','shits','ticket','cion','score');
        if(!in_array($order, $oarr)) $order = 'addtime';

        $wh = $like = array();
        $wh['yid'] = 0;
        $wh['sid'] = 0;
        if($cid > 0) $wh['cid'] = $cid;
        if($tid > 0) $wh['tid'] = $tid;
        if($uid > 0) $wh['uid'] = $uid;
        if($pay > 0) $wh['pay'] = $pay-1;
        if(!empty($serialize)) $wh['serialize'] = $serialize;
        if(!empty($key)) $like['name'] = $key;
        if($tags > 0){
            $tagarr = explode('|',Web_Book_Tags);
            $tagid = $tags-1;
            $tags = isset($tagarr[$tagid]) ? $tagarr[$tagid] : '';
            if(!empty($tags)) $like['tags'] = $tags;
        }
        $data = array();
        $limit = array($size,$size*($page-1));
        $data = $this->mcdb->get_select('book','*',$wh,$order.' DESC',$limit,$like);
        foreach ($data as $k => $v) {
            $v['url'] = get_url('book_info',$v);
            $v['pic'] = getpic($v['pic']);
            $v['picx'] = getpic($v['picx']);
            $table = get_chapter_table($v['id']);
            $row = $this->mcdb->get_row_arr($table,'id,name',array('bid'=>$v['id']));
            if($row){
                $v['chapter_name'] = $row['name'];
                $v['chapter_url'] = get_url('book_read',array('id'=>$row['id'],'bid'=>$v['id']));
            }else{
                $v['chapter_name'] = '待更新';
                $v['chapter_url'] = get_url('book_read',$v);
            }
            $v['chapter_nums'] = $v['nums'];
            $v['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
            if(empty($v['text'])) $v['text'] = sub_str($v['content'],10);
            unset($v['yname'],$v['sid'],$v['did'],$v['yid'],$v['msg'],$v['nums']);
            $data[$k] = $v;
        }
        //输出
        $d['code'] = 1;
        $d['msg'] = '小说列表';
        $d['data'] = $data;
        get_json($d);
    }

    //根据漫画ID获取章节
    public function chapter() {
        $mid = (int)$this->input->get_post('mid');
        $size = (int)$this->input->get_post('size');
        if($size == 0) $size = 2000;
        if($mid == 0) get_json('漫画ID错误');
        $data = $this->mcdb->get_select('comic_chapter','id,mid,name,vip,cion,pnum,addtime',array('yid'=>0,'mid'=>$mid),'id DESC',$size);
        foreach ($data as $k => $v) {
            $data[$k]['addtime'] = date('Y-m-d H:i:s',$data[$k]['addtime']);
        }
        //输出
        $d['code'] = 1;
        $d['msg'] = '章节列表';
        $d['data'] = $data;
        get_json($d);
    }

    //根据章节ID获取图片
    public function pic() {
        $cid = (int)$this->input->get_post('cid');
        $size = (int)$this->input->get_post('size');
        if($size == 0) $size = 2000;
        if($cid == 0) get_json('章节ID错误');
        $row = $this->mcdb->get_row_arr('comic_chapter','vip,cion',array('id'=>$cid));
        if(!$row) get_json('章节不存在');
        if(($row['vip'] > 0 || $row['cion'] > 0) && !$this->users->login(1)) get_json('需要登陆');
        //会员ID
        $uid = $this->cookie->get('user_id');
        //VIP章节
        if($row['vip'] > 0){
            $vip = (int)getzd('user','vip',$uid);
            if($vip == 0) get_json(array('msg'=>'VIP专属，级别不够','type'=>'vip'));
        }elseif($row['cion'] > 0){
            //判断是否购买过
            $row = $this->mcdb->get_row_arr('comic_buy','id',array('cid'=>$id,'uid'=>$uid));
            if(!$row) get_json(array('msg'=>'未购买','type'=>'cion','cion'=>$row['cion']));
        }
        //获取章节图片
        $data = $this->mcdb->get_select('comic_pic','*',array('cid'=>$cid),'id DESC',$size);
        $parr = array();
        foreach ($data as $k => $v) {
            $parr[$k]['id'] = $v['id'];
            $parr[$k]['img'] = getpic($v['img']);
            $parr[$k]['width'] = $v['width'];
            $parr[$k]['height'] = $v['height'];
        }
        //输出
        $d['code'] = 1;
        $d['msg'] = '图片列表';
        $d['data'] = $parr;
        get_json($d);
    }
}
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
        if($reco_size == 0) $reco_size = 4;
        if($news_size == 0) $news_size = 5;
        if($type_size == 0) $type_size = 4;
        //banner
        $banner = $this->mcdb->get_select('book','id,name,pic,picx',array('yid'=>0,'tid'=>1),'rhits DESC',8);
        $list['banner'] = get_app_data($banner);
        //热门推荐
        $reco = $this->mcdb->get_select('book','*',array('yid'=>0),'zhits DESC',$reco_size);
        $list['reco'] = get_app_data($reco);
        //近期更新
        $news = $this->mcdb->get_select('book','*',array('yid'=>0),'addtime DESC',$news_size);
        $list['news'] = get_app_data($news);
        //分类
        $list['class'] = $this->mcdb->get_select('book_class','id,name',array(),'xid ASC',6);
        foreach ($list['class'] as $k=>$v){
            $list['class'][$k]['book'] = get_app_data($this->mcdb->get_select('book','*',array('yid'=>0,'cid'=>$v['id']),'rhits DESC',$type_size));
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
        $tags = safe_replace($this->input->get_post('tags',true));
        $key = safe_replace($this->input->get_post('key',true));
        $bsize = (int)$this->input->get_post('bsize');
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
        if($bsize == 5) $wh['text_num>'] = 2000000;
        if($bsize == 4){
            $wh['text_num>'] = 999999;
            $wh['text_num<'] = 2000000;
        }
        if($bsize == 3){
            $wh['text_num>'] = 499999;
            $wh['text_num<'] = 1000000;
        }
        if($bsize == 2){
            $wh['text_num>'] = 299999;
            $wh['text_num<'] = 500000;
        }
        if($bsize == 1) $wh['text_num<'] = 299999;
        if(!empty($serialize)) $wh['serialize'] = $serialize;
        if(!empty($key)) $like['name'] = $key;
        if(!empty($tags)) $like['tags'] = $tags;

        $limit = array($size,$size*($page-1));
        $nums = $this->mcdb->get_nums('book',$wh,$like);
        $data = $this->mcdb->get_select('book','id,name,pic,picx,author,serialize state,score,text_num,hits,text,content,addtime',$wh,$order.' DESC',$limit,$like);
        //输出
        $d['code'] = 1;
        $d['msg'] = '小说列表';
        $d['nums'] = $nums;
        $d['size'] = $size;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['list'] = get_app_data($data);
        get_json($d);
    }
    
    //小说详情
    public function info() {
        $id = (int)$this->input->get_post('id');
        if($id == 0) get_json('小说ID为空',0);
        //判断小说是否存在
        $row = $this->mcdb->get_row_arr('book','id,cid,name,pic,picx,author,serialize state,score,cion,ticket,sid,text_num,hits,shits,text,content,nums,ly,did,addtime',array('id'=>$id,'yid'=>0));
        if(!$row) get_json('小说不存在',0);
        $row['addtime'] = datetime($row['addtime']);
        $row['cion_name'] = Pay_Cion_Name;
        $row['cion_rank'] = get_rank($id,'cion','book');
        $row['ticket_rank'] = get_rank($id,'ticket','book');
        //是否收藏
        $rowf = $this->mcdb->get_row_arr('book_fav','id',array('bid'=>$id,'uid'=>$this->uid));
        $row['fav'] = $rowf ? 1 : 0;
        //判断是否阅读
        $rowr = $this->mcdb->get_row_arr('book_read','cid',array('bid'=>$id,'uid'=>$this->uid));
        $row['zid'] = $rowr ? $rowr['cid'] : 0;
        //章节表
        $chapter_table = get_chapter_table($row['id']);
        //判断章节入库
        $zjnum = $this->mcdb->get_nums($chapter_table,array('bid'=>$row['id']));
        if(($row['nums'] == 0 || $zjnum == 0 || $zjnum < $row['nums']) && $row['did'] > 0){
            $this->load->model('collect');
            $arr = require MCCMSPATH.'libs/collect.php';
            if(!empty($row['ly'])){
                if(isset($arr['book_zyk'][$row['ly']])){
                    $this->collect->get_update_chapter($arr['book_zyk'][$row['ly']]['jxurl'].'/chapter/'.$row['did'],$row['id'],'book',$chapter_table,$arr['book_zyk'][$row['ly']]['token']);
                }
            }else{
                $this->collect->get_update_chapter(Book_Caiji_Tb_Url.'/chapter/'.$row['did'],$row['id'],'book',$chapter_table,Book_Caiji_Tb_Token);
            }
            $row['nums'] = $this->mcdb->get_nums($chapter_table,array('bid'=>$row['id']));
        }
        //章节列表
        $row['chapter_list'] = $this->mcdb->get_select($chapter_table,'id,name,cion,vip,addtime',array('bid'=>$id,'yid'=>0),'id ASC,xid ASC',5);
        //猜你喜欢
        $row['love_list'] = $this->mcdb->get_select('book','id,name,pic,picx,author,score',array('cid'=>$row['cid'],'yid'=>0),'rhits DESC',6);
        //评论列表
        $comment = $this->mcdb->get_select('comment','id,text,uid,reply_num,zan,addtime',array('bid'=>$row['id']),'id DESC',5);
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
        $row['share_txt'] = '我发现一个非常不错的小说APP，点击下面地址下载可以免费看~';
        $row['share_url'] = 'http://'.Web_Url.links('share/book/'.$id.'/'.($this->uid+10000));
        unset($row['did'],$row['ly']);
        //输出
        $d['code'] = 1;
        $d['book'] = get_app_data($row);
        get_json($d);   
    }
    
    //小说目录
    public function chapter() {
        $id = (int)$this->input->get_post('id');
        $size = (int)$this->input->get_post('size');
        $limit = $size > 0 ? array(5000,$size) : 5000;
        //章节表
        $chapter_table = get_chapter_table($id);
        $list = $this->mcdb->get_select($chapter_table,'id,name,cion,vip,addtime',array('bid'=>$id),'id ASC,xid ASC',$limit);
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
    
    //章节详情
    public function read(){
        $bid = (int)$this->input->get_post('bid');
        $zid = (int)$this->input->get_post('zid');
        if($bid == 0) get_json('小说ID为空',0);
        //判断小说是否存在
        $row = $this->mcdb->get_row_arr('book','id,name,cion,ticket,sid,text_num,nums,ly,did,addtime',array('id'=>$bid,'yid'=>0));
        if(!$row) get_json('小说不存在',0);
        $row['addtime'] = datetime($row['addtime']);
        $row['cion_name'] = Pay_Cion_Name;
        $row['cion_rank'] = get_rank($bid,'cion','book');
        $row['ticket_rank'] = get_rank($bid,'ticket','book');
        //是否收藏
        $rowf = $this->mcdb->get_row_arr('book_fav','id',array('bid'=>$bid,'uid'=>$this->uid));
        $row['fav'] = $rowf ? 1 : 0;
        //章节表
        $chapter_table = get_chapter_table($bid);
        //章节信息
        if($zid == 0){
            $rowz = $this->mcdb->get_row_arr($chapter_table,'*',array('bid'=>$bid,'yid'=>0),'xid ASC');
        }else{
            $rowz = $this->mcdb->get_row_arr($chapter_table,'*',array('id'=>$zid,'yid'=>0));
        }
        if(!$rowz) get_json('章节不存在',0);
        $zid = $rowz['id'];
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        $user = array('log'=>0,'cion'=>0,'vip'=>0,'ticket'=>0);
        if($this->user) $user = $this->user;
        //判断章节收费
        $rowz['pay'] = app_book_pay($this->mcdb,$bid,$zid,$rowz['cion'],$rowz['vip'],$user);
        $row['text'] = get_book_txt($bid,$rowz['id']);
        //同步远程TXT文本到本地
        if($row['did'] > 0 && empty($row['text'])){
            $this->load->model('collect');
            if(!empty($row['ly'])){
                if(isset($arr['book_zyk'][$row['ly']])){
                    $row['text'] = $this->collect->get_update_txt($arr['book_zyk'][$row['ly']]['jxurl'].'/txt/'.$row['did'].'/'.$rowz['xid'],$rowz['id'],$bid,$table,$arr['book_zyk'][$row['ly']]['token']);
                }
            }else{
                $row['text'] = $this->collect->get_update_txt(Book_Caiji_Tb_Url.'/txt/'.$row['did'].'/'.$rowz['xid'],$rowz['id'],$bid,$table,Book_Caiji_Tb_Token);
            }
        }
        $rowz['ispay'] = 0;
        if($rowz['pay'] == 3){
            $rowz['ispay'] = 1;
            $rowz['pay'] = 0;
        }
        if($rowz['pay'] !== 0) $row['text'] = str_replace("[n]","\n",sub_str(str_replace("\n","[n]",$row['text']),250));
        //分享地址、分享文本
        $row['share_txt'] = '我发现一个非常不错的小说APP，点击下面地址下载可以免费看~';
        $row['share_url'] = 'http://'.Web_Url.links('share/book/'.$bid.'/'.($this->uid+10000));
        //上下章ID
        $rows = $this->mcdb->get_row_arr($chapter_table,'id',array('bid'=>$bid,'xid<'=>$rowz['xid'],'yid'=>0),'xid DESC');
        $rowz['szid'] = $rows ? $rows['id'] : 0;
        $rowx = $this->mcdb->get_row_arr($chapter_table,'id',array('bid'=>$bid,'xid>'=>$rowz['xid'],'yid'=>0),'xid ASC');
        $rowz['xzid'] = $rowx ? $rowx['id'] : 0;
        unset($rowz['jxurl'],$row['ly'],$row['did']);
        //分割文本成数组
        $row['text'] = explode("\n",$row['text']);
        //输出
        $d['code'] = 1;
        $d['data'] = get_app_data($rowz);
        $d['data']['book'] = get_app_data($row);
        $d['data']['user'] = get_app_data($user);
        get_json($d);   
    }
    
    //购买章节
    public function buy() {
        $bid = (int)$this->input->get_post('bid');
        $zid = (int)$this->input->get_post('zid');
        $auto = (int)$this->input->get_post('auto');
        if($bid == 0 || $zid == 0) get_json('参数错误',0);
        //判断登录
        $this->user = get_app_log($this->uid,$this->token,$this->mcdb);
        if(!$this->user) get_json('未登陆',-1);
        //章节表
        $chapter_table = get_chapter_table($bid);
        //判断小说是否存在
        $row = $this->mcdb->get_row_arr($chapter_table,'cion,vip',array('id'=>$zid,'yid'=>0));
        if(!$row) get_json('章节不存在',0);
        if($this->user['cion'] < $row['cion']) get_json('金币不足，请充值',0);
        app_book_buy($bid,$zid,$row['cion'],$auto,$this->user);
        get_json('购买成功',1);
    }
    
    //获取分类
    public function type() {
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 50;
        $d['code'] = 1;
        $d['list'] = $this->mcdb->get_select('book_class','id,name',array(),'xid ASC',$size);
        get_json($d);
    }

    //主题tags标签
    public function tags() {
        //输出
        $d['code'] = 1;
        $d['list'] = explode('|',Web_Book_Tags);
        get_json($d);
    }
    
    //热搜关键字
    public function search() {
        $app = require FCPATH.'sys/libs/app.php';
        //输出
        $d['code'] = 1;
        $d['list'] = $app['book_search'];
        get_json($d);
    }
}
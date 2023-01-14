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

class Resources extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
        //判断收费
        $this->zyz = $this->get_pay();
	}

	//漫画资源库
    public function comic() {
        $ac = $this->input->get_post('ac');
        $key = $this->input->get_post('key');
        $cid = (int)$this->input->get_post('cid');
        $day = (int)$this->input->get_post('day'); //天数
        $ids = $this->input->get_post('ids');
        $size = (int)$this->input->get_post('size');
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        //SQL过滤
        $key = safe_replace(rawurldecode($key));
        //查询条件
        $wh = array();
        //天数
        if($day > 0){
            $time = strtotime(date("Y-m-d 0:0:0",strtotime('-'.$day.' day')))-1;
            $wh[] = 'addtime>'.$time;
        }
        //搜索
        if(!empty($key)) $wh[] = "name like '%".$key."%'";
        //选中采集
        if(preg_match('/^([0-9]+[,]?)+$/', $ids)){
            $wh[] = "id in(".$ids.")";
        }else{
            //分类
            if($cid > 0){
                $wh[] = 'cid='.$cid;
            }else{
                if(!empty($this->zyz['cid'])) $wh[] = 'cid in('.$this->zyz['cid'].')';
            }
        }
        $where = !empty($wh) ? 'where '.implode(' and ',$wh) : '';
        //每页数量
        if($size == 0 || $size > 100) $size = $this->zyz['size'];
        //数据列表
        if($ac == 'data'){
            $zd = 'id,name,cid,pic,pay,serialize,author,score,addtime update_time,nums chapter_num,content';
        }else{
            $zd = 'id,name,cid,pay,serialize,author,addtime update_time,nums chapter_num';
        }
        $sql = "select ".$zd." from ".Mc_SqlPrefix."comic ".$where;
        //总数量
        $nums = $this->mcdb->get_sql_nums($sql);
        //总页数
        $pagejs = ceil($nums / $size);
        if($page > $pagejs) $page = $pagejs;
        if($nums < $size) $size = $nums;
        if($page == 0) $page = 1;
        //偏移量
        $limit = $size*($page-1).','.$size;
        //数据
        $comic = $this->mcdb->get_sql($sql." order by addtime desc limit ".$limit,1);
        foreach ($comic as $key => $value) {
            $comic[$key]['cion'] = 0;
            if($value['pay'] > 0){
                $comic[$key]['pay'] = $value['pay'] == 2 ? 1 : 2;
                if($value['pay'] == 1){
                    $row1 = $this->mcdb->get_row_arr('comic_chapter','cion',array('mid'=>$value['id'],'cion>'=>0));
                    $comic[$key]['cion'] = $row1['cion'];
                }
            }
            $comic[$key]['id'] = (int)$comic[$key]['id'];
            $comic[$key]['cid'] = (int)$comic[$key]['cid'];
            if(isset($comic[$key]['pic'])) $comic[$key]['pic'] = $this->get_pic($comic[$key]['pic']);
            $comic[$key]['chapter_num'] = (int)$comic[$key]['chapter_num'];
            $tarr = $this->mcdb->get_select('comic_type','tid',array('mid'=>$value['id']));
            $tags = array();
            foreach ($tarr as $k => $v) {
                $row2 = $this->mcdb->get_row_arr('type','fid,name',array('id'=>$v['tid']));
                if($row2 && $row2['fid'] == 1) $tags[] = $row2['name'];
            }
            $comic[$key]['tags'] = !empty($tags) ? implode('/',$tags) : '';
            $comic[$key]['cname'] = getzd('class','name',$value['cid']);
            $comic[$key]['update_name'] = getzd('comic_chapter','name',$value['id'],'mid','xid desc');
            $comic[$key]['update_time'] = date('Y-m-d',$comic[$key]['update_time']);
        }
        $data['size'] = (int)$size;
        $data['nums'] = $nums;
        $data['page'] = $page;
        $data['pagejs'] = $pagejs;
        $data['comic'] = $comic;
        //分类
        if($ac != 'data'){
            $wh = !empty($this->zyz['cid']) ? 'id in('.$this->zyz['cid'].')' : '';
            $data['class'] = $this->mcdb->get_select('class','id,name',$wh,'xid asc',100);
        }
        $d['code'] = 1;
        $d['data'] = $data;
        get_json($d);
	}

    //解析漫画章节
    public function parsing_comic($op='index',$id=0,$xid=1) {
        $id = (int)$id;
        $xid = (int)$xid;
        if($xid == 0) $xid = 1;
        if($op == 'pic'){
            $chapter = $this->mcdb->get_select('comic_chapter','id',array('mid'=>$id),'xid asc',5000);
            $xid--;
            if(!isset($chapter[$xid])) get_json('章节不存在',0);
            $this->parsing_comic_chapter($chapter[$xid]['id']);
        }else{
            if($id == 0) get_json('漫画ID错误');
            $row = $this->mcdb->get_row_arr('comic','id,name,cid,pic,serialize,author,score,addtime update_time,nums chapter_num,content',array('id'=>$id));
            if(!$row) get_json('资源不存在');
            $row['update_time'] = date('Y-m-d',$row['update_time']);
            $row['update_name'] = getzd('comic_chapter','name',$row['id'],'mid','xid desc');
            $row['cname'] = getzd('class','name',$row['cid']);
            $row['pic'] = $this->get_pic($row['pic']);
            //章节
            $chapter = $this->mcdb->get_select('comic_chapter','id,name',array('mid'=>$id),'xid asc',10000);
            foreach ($chapter as $k => $v) {
                $chapter[$k]['url'] = is_ssl()?'https://':'http://'.Web_Url.Web_Path.'index.php/api/resources/parsing_comic_chapter/'.$v['id'];
            }
            $row['chapter'] = $chapter;
            $d['code'] = 1;
            $d['data'] = $row;
            get_json($d);
        }
    }

    //解析漫画图片
    public function parsing_comic_chapter($id=0) {
        $id = (int)$id;
        if($id == 0) get_json('章节ID错误');
        //章节
        $row = $this->mcdb->get_row_arr('comic_chapter','id,name,pnum image_num',array('id'=>$id));
        //图片
        $pic = $this->mcdb->get_select('comic_pic','img as src',array('cid'=>$id),'xid asc',10000);
        foreach ($pic as $k => $v) {
            $pic[$k]['src'] = $this->get_pic($pic[$k]['src']);
        }
        $row['image'] = $pic;
        $d['code'] = 1;
        $d['data'] = $row;
        get_json($d);
    }

    //小说资源
    public function book() {
        $ac = $this->input->get_post('ac');
        $key = $this->input->get_post('key');
        $cid = (int)$this->input->get_post('cid');
        $day = (int)$this->input->get_post('day'); //天数
        $ids = $this->input->get_post('ids');
        $size = (int)$this->input->get_post('size');
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        //SQL过滤
        $key = safe_replace(rawurldecode($key));
        //查询条件
        $wh = array();
        //天数
        if($day > 0){
            $time = strtotime(date("Y-m-d 0:0:0",strtotime('-'.$day.' day')))-1;
            $wh[] = 'addtime>'.$time;
        }
        //搜索
        if(!empty($key)) $wh[] = "name like '%".$key."%'";
        //选中采集
        if(preg_match('/^([0-9]+[,]?)+$/', $ids)){
            $wh[] = "id in(".$ids.")";
        }else{
            //分类
            if($cid > 0){
                $wh[] = 'cid='.$cid;
            }else{
                if(!empty($this->zyz['cid'])) $wh[] = 'cid in('.$this->zyz['cid'].')';
            }
        }
        $where = !empty($wh) ? 'where '.implode(' and ',$wh) : '';
        //每页数量
        if($size == 0 || $size > 100) $size = $this->zyz['size'];
        //数据列表
        if($ac == 'data'){
            $zd = 'id,name,cid,pay,pic,serialize,tags,author,score,addtime update_time,nums chapter_num,content';
        }else{
            $zd = 'id,name,cid,pay,serialize,author,addtime update_time,nums chapter_num';
        }
        $sql = "select ".$zd." from ".Mc_SqlPrefix."book ".$where;
        //总数量
        $nums = $this->mcdb->get_sql_nums($sql);
        //总页数
        $pagejs = ceil($nums / $size);
        if($page > $pagejs) $page = $pagejs;
        if($nums < $size) $size = $nums;
        if($page == 0) $page = 1;
        //偏移量
        $limit = $size*($page-1).','.$size;
        //数据
        $book = $this->mcdb->get_sql($sql." order by addtime desc limit ".$limit,1);
        foreach ($book as $key => $value) {
            $book[$key]['cion'] = 0;
            if($value['pay'] > 0){
                $book[$key]['pay'] = $value['pay'] == 2 ? 1 : 2;
                if($value['pay'] == 1){
                    $row1 = $this->mcdb->get_row_arr(get_chapter_table($value['id']),'cion',array('bid'=>$value['id'],'cion>'=>0));
                    $book[$key]['cion'] = $row1['cion'];
                }
            }
            $book[$key]['id'] = (int)$book[$key]['id'];
            $book[$key]['cid'] = (int)$book[$key]['cid'];
            if(isset($book[$key]['pic'])) $book[$key]['pic'] = $this->get_pic($book[$key]['pic']);
            $book[$key]['chapter_num'] = (int)$book[$key]['chapter_num'];
            $book[$key]['cname'] = getzd('class','name',$value['cid']);
            $book[$key]['update_name'] = getzd('book_chapter','name',$value['id'],'bid','xid desc');
            $book[$key]['update_time'] = date('Y-m-d',$book[$key]['update_time']);
        }
        $data['size'] = (int)$size;
        $data['nums'] = $nums;
        $data['page'] = $page;
        $data['pagejs'] = $pagejs;
        $data['book'] = $book;
        //分类
        if($ac != 'data'){
            $wh = !empty($this->zyz['cid']) ? 'id in('.$this->zyz['cid'].')' : '';
            $data['class'] = $this->mcdb->get_select('book_class','id,name',$wh,'xid asc',100);
        }
        $d['code'] = 1;
        $d['data'] = $data;
        get_json($d);
    }

    //小说解析
    public function parsing_book($op='chapter',$bid=0,$xid=0){
        if($op == 'txt'){
            $this->book_txt($bid,$xid);
        }else{
            $this->book_chapter($bid);
        }
    }

    //解析小说章节
    private function book_chapter($id=0) {
        $id = (int)$id;
        if($id == 0) get_json('小说ID错误');
        $row = $this->mcdb->get_row_arr('book','id,name',array('id'=>$id));
        if(!$row) get_json('资源不存在');
        //章节表
        $chapter_table = get_chapter_table($id);
        //章节
        $row['chapter'] = $this->mcdb->get_select($chapter_table,'xid,name',array('bid'=>$id),'xid asc',10000);
        $d['code'] = 1;
        $d['data'] = $row;
        get_json($d);
    }

    //解析章节TXT文本
    private function book_txt($bid=0,$xid=0) {
        $bid = (int)$bid;
        $xid = (int)$xid;
        if($xid == 0) $xid = 1;
        if($bid == 0) get_json('小说ID错误');
        $row = $this->mcdb->get_row_arr('book','id',array('id'=>$bid));
        if(!$row) get_json('小说资源不存在');
        //章节表
        $chapter_table = get_chapter_table($bid);
        $rowz = $this->mcdb->get_row_arr($chapter_table,'id',array('bid'=>$bid,'xid'=>$xid));
        if(!$rowz) get_json('章节不存在');
        $text = get_book_txt($bid,$rowz['id']);
        $d['code'] = 1;
        $d['data']['text'] = $text;
        get_json($d);
    }

    //补全图片前缀
    private function get_pic($pic){
        if($this->zyz['picurl'] == ''){
            $pic = getpic($pic);
            if(strpos($pic,'://') === false) $pic = is_ssl()?'https://':'http://'.Web_Url.$pic;
        }else{
            if(strpos($pic,'://') === false) $pic = $this->zyz['picurl'].$pic;
        }
        return $pic;
    }

    //判断资源站收费
    private function get_pay(){
        $zyz = require_once FCPATH.'sys/libs/zyz.php';
        $token = $this->input->get_post('token');
        $type = strpos($_SERVER['REQUEST_URI'],'comic') === false ? 'book' : 'comic';
        if($zyz[$type]['init'] == 0) get_json('资源站已关闭');
        //判断收费
        if($zyz[$type]['pay'] == 1 && !in_array($token,$zyz[$type]['token'])){
            get_json('秘钥不存在');
        }
        return $zyz[$type];
    }
}
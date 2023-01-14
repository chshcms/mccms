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
if (!defined('BASEPATH')) exit('No direct script access allowed');
//采集操作模型
class Collect extends CI_Model{
    function __construct (){
        parent:: __construct ();
        //拼音类
        $this->load->library('pinyin');
        $this->collect = require MCCMSPATH.'libs/collect.php';
    }
    //采集入库检测重复检测
    function get_query($arr=array(),$table='comic'){
        $wh = array();
        $Caiji_Inspect = $table == 'comic' ? Caiji_Inspect : Book_Caiji_Inspect;
        $carr = explode('|',$Caiji_Inspect);
        foreach ($carr as $k) {
            if(isset($arr[$k])) $wh[$k] = $arr[$k];
        }
        $row = $this->mcdb->get_row_arr($table,'id',$wh);
        return $row;
    }
    //新增内容
    function get_insert($arr=array(),$table='comic',$ly=''){
        //过滤标题
        if($this->get_is_name($arr['name'],$table)) return true;
        //判断过滤空章节的漫画
        if($table == 'comic' && Caiji_Chapter == 1 && $arr['chapter_num'] == 0) return true;
        if($table == 'book' && Book_Caiji_Chapter == 1 && $arr['chapter_num'] == 0) return true;
        //入库必选字段
        if($table == 'book'){
            $addzd = array('name','cid','pic','tags','text_num','serialize','author','content');
        }else{
            $addzd = array('name','cid','pic','serialize','author','content');  
        }
        $add = array();
        foreach ($addzd as $k) {
            $null_v = ($k == 'cid') ? 0 : '';
            $add[$k] = isset($arr[$k]) ? $arr[$k] : $null_v;
        }
        if(strpos($add['serialize'],'连载') !== false){
            $add['serialize'] = '连载';
        }else{
            $add['serialize'] = '完结';
        }
        //数据ID
        $add['did'] = $arr['id'];
        //英文别名
        $add['yname'] = $this->pinyin->send($arr['name']);
        //人气
        $hzd = array('hits','yhits','zhits','rhits');
        foreach ($hzd as $k) $add[$k] = $this->get_hits();
        //更新时间
        $add['addtime'] = $table == 'comic' ? (Caiji_Time == 0 ? time() : strtotime($arr['update_time'])) : (Book_Caiji_Time == 0 ? time() : strtotime($arr['update_time']));
        //状态
        $add['yid'] = Caiji_Sh;
        //缩略图
        if($table == 'comic' && Caiji_Pic == 1) $add['pic'] = get_downpic($add['pic']);
        if($table == 'book' && Book_Caiji_Pic == 1) $add['pic'] = get_downpic($add['pic'],'book');
        //章节总数
        $add['nums'] = isset($arr['chapter']) ? count($arr['chapter']) : $arr['chapter_num'];
        //最小一个会员ID
        $user = $this->mcdb->get_row_arr('user','id','','id asc');
        if($user) $add['uid'] = $user['id'];
        //入库
        $did = $this->mcdb->get_insert($table,$add);
        if($did){
            if($table == 'book'){
                $token = isset($this->collect['book_zyk'][$ly]) ? $this->collect['book_zyk'][$ly]['token'] : Book_Caiji_Tb_Token;
                if($table == 'book' && Book_Caiji_Tb_Chapter == 1){
                    if(empty($arr['chapter_url'])) $arr['chapter_url'] = isset($this->collect['book_zyk'][$ly]['jxurl']) ? $this->collect['book_zyk'][$ly]['jxurl'].'/chapter/'.$arr['id'] : Book_Caiji_Tb_Url.'/chapter/'.$arr['id'];
                    $this->get_update_chapter($arr['chapter_url'],$did,'book',get_chapter_table($did),$token);
                }
            }else{
                //标签类型
                $this->get_set_tags($arr['tags'],$did);
                //章节
                if(Caiji_Tb_Chapter == 1){
                    $token = isset($this->collect['zyk'][$ly]) ? $this->collect['zyk'][$ly]['token'] : Caiji_Tb_Token;
                    if(empty($arr['chapter_url'])) $arr['chapter_url'] = isset($this->collect['zyk'][$ly]['jxurl']) ? $this->collect['zyk'][$ly]['jxurl'].'/index/'.$arr['id'] : Caiji_Tb_Url.'/index/'.$arr['id'];
                    $this->get_update_chapter($arr['chapter_url'],$did,'comic','comic_chapter',$token);
                }
            }
        }
        return $did;
    }

    //更新内容
    function get_update($arr=array(),$id=0,$table='comic',$ly=''){
        //漫画ID为空
        if($id == 0) return true;
        //过滤标题
        if($this->get_is_name($arr['name'])) return true;
        //判断章节总数大于更新
        if($table == 'book'){
            $Caiji_Up = Book_Caiji_Up;
            $Caiji_Upzd = Book_Caiji_Upzd;
            $Caiji_Time = Book_Caiji_Time;
        }else{
            $Caiji_Up = Caiji_Up;
            $Caiji_Upzd = Caiji_Upzd;
            $Caiji_Time = Caiji_Time;
        }
        if($Caiji_Up == 1){
            if($table == 'book'){
                $chapter_table = get_chapter_table($id);
                $ynums = $this->mcdb->get_nums($chapter_table,array('bid'=>$id));
            }else{
                $chapter_table = 'comic_chapter';
                $ynums = $this->mcdb->get_nums($chapter_table,array('mid'=>$id));
            }
            if($ynums >= $arr['chapter_num']) return true;
        }
        $edit = array();
        $uparr = explode('|',$Caiji_Upzd);
        foreach ($uparr as $k) {
            if($k == 'chapter'){
                continue;
            }elseif($k == 'yname'){
                $edit[$k] = $this->pinyin->send($arr['name']);
            }elseif($k == 'type'){
                $this->get_set_tags($arr['tags'],$id);
            }elseif($k == 'tags'){
                $edit[$k] = $arr['tags'];
            }elseif($k == 'addtime'){
                $edit[$k] = $Caiji_Time == 0 ? time() : strtotime($arr['update_time']);
            }elseif(isset($arr[$k])){
                $edit[$k] = $arr[$k];
            }
        }
        //数据ID
        $edit['did'] = $arr['id'];
        if(strpos($edit['serialize'],'连载') !== false){
            $edit['serialize'] = '连载';
        }else{
            $edit['serialize'] = '完结';
        }
        $edit['nums'] = isset($arr['chapter']) ? count($arr['chapter']) : $arr['chapter_num'];
        //更新
        $this->mcdb->get_update($table,$id,$edit);
        //更新章节
        if(in_array('chapter',$uparr)){
            if(($table == 'comic' && Caiji_Tb_Chapter == 1) || ($table == 'book' && Book_Caiji_Tb_Chapter == 1)){
                if($table == 'book'){
                    if(empty($arr['chapter_url'])) $arr['chapter_url'] = isset($this->collect['book_zyk'][$ly]['jxurl']) ? $this->collect['book_zyk'][$ly]['jxurl'].'/chapter/' : Book_Caiji_Tb_Url.'/chapter/'.$arr['id'];
                    $token = isset($this->collect['book_zyk'][$ly]) ? $this->collect['book_zyk'][$ly]['token'] : Book_Caiji_Tb_Token;
                }else{
                    if(empty($arr['chapter_url'])) $arr['chapter_url'] = isset($this->collect['zyk'][$ly]['jxurl']) ? $this->collect['zyk'][$ly]['jxurl'].'/index/' : Caiji_Tb_Url.'/index/'.$arr['id'];
                    $token = isset($this->collect['zyk'][$ly]) ? $this->collect['zyk'][$ly]['token'] : Caiji_Tb_Token;
                }
                $this->get_update_chapter($arr['chapter_url'],$id,$table,$chapter_table,$token);
            }
        }
        return true;
    }
    //更新章节
    function get_update_chapter($jxurl='',$did=0,$table='comic',$chapter_table='',$token=''){
        set_time_limit(0); //不超时
        if($did == 0) return true;
        //解析章节
        $arr = json_decode(getcurl($jxurl.'?token='.$token),1);
        if($arr['code'] != 1) return array();
        $chapter = $arr['data']['chapter'];
        if(empty($chapter_table)){
            $chapter_table = $table == 'comic' ? 'comic_chapter' : get_chapter_table($did);
        }
        $zd = $table == 'comic' ? 'mid' : 'bid';
        //章节列表
        foreach ($chapter as $k => $v) {
            $xid = $k+1;
            $v = str_checkhtml($v,1);
            //更新章节
            $row = $this->mcdb->get_row_arr($chapter_table,'id',array($zd=>$did,'xid'=>$xid));
            if(!$row){
                $add = array('xid'=>$xid,$zd=>$did,'name'=>$v['name'],'addtime'=>time());
                if($table == 'comic') $add['jxurl'] = $v['url'];
                $zjid = $this->mcdb->get_insert($chapter_table,$add);
                if($table == 'comic'){
                    if((defined('IS_ADMIN') && Caiji_Tb_Pic == 1) || (!defined('IS_ADMIN') && Caiji_Tb_Pic == 0)){
                        $this->get_update_pic($chapter[$k]['url'],$zjid,$did,$token);
                    }
                }
                if($table == 'book'){
                    if((defined('IS_ADMIN') && Book_Caiji_Tb_Txt == 1) || (!defined('IS_ADMIN') && Book_Caiji_Tb_Txt == 0)){
                        $this->get_update_txt($chapter[$k]['url'],$zjid,$did,$chapter_table,$token);
                    }
                }
            }
        }
        //更新版块数据
        $this->mcdb->get_update($table,$did,array('addtime'=>time(),'nums'=>count($chapter)));
        //更新小数总字数
        if($table == 'book'){
            $this->get_book_txtnum($did,$chapter_table);
        }
        return $chapter;
    }
    //章节图片更新入库
    function get_update_pic($url,$zjid=0,$mid=0,$token='',$tb=0){
        $parr = json_decode(getcurl($url.'?token='.$token),1);
        if($parr['code'] != 1) return false;
        $pic = $parr['data']['image'];
        foreach ($pic as $k2 => $v2) {
            $md5 = md5($v2['src']);
            //判断图片是否存在
            $row2 = $this->mcdb->get_row_arr('comic_pic','id,img',array('md5'=>$md5));
            if(!$row2){
                $add['img'] = $v2['src'];
                //下载图片到本地
                if(Caiji_Tb_Pic == 1 || $tb == 1) $add['img'] = get_downpic($add['img']);
                $add['width'] = isset($v2['width']) ? (int)$v2['width'] : 0;
                $add['height'] = isset($v2['height']) ? (int)$v2['height'] : 0;
                $add['cid'] = $zjid;
                $add['mid'] = $mid;
                $add['md5'] = $md5;
                $add['xid'] = $k2;
                $this->mcdb->get_insert('comic_pic',$add);
            }elseif($tb == 1 && strpos($row2['img'],'://') !== false){
                $img = get_downpic($row2['img']);
                $this->mcdb->get_update('comic_pic',$row2['id'],array('img'=>$img));
            }
        }
        //更新章节图片总数
        $this->mcdb->get_update('comic_chapter',$zjid,array('pnum'=>$parr['data']['image_num']));
        return $pic;
    }
    //章节TXT文本更新入库
    function get_update_txt($jxurl,$zjid=0,$bid=0,$chapter_table='',$token=''){
        $parr = json_decode(getcurl($jxurl.'?token='.$token),1);
        if($parr['code'] != 1) return false;
        $text = strip_tags($parr['data']['text']);
        $text = str_replace("<br>","",$text);
        if(empty($text)) return false;
        //写入章节TXT文本内容
        $txt_file = FCPATH.'caches/txt/'.$bid.'/'.md5($zjid.Mc_Book_Key).'.txt';
        write_file($txt_file, $text);
        //更新章节字数
        if(empty($chapter_table)) $chapter_table = get_chapter_table($bid);
        $this->mcdb->get_update($chapter_table,$zjid,array('text_num'=>mb_strlen($text,"UTF-8")));
        $this->get_book_txtnum($bid,$chapter_table);
        return $text;
    }
    //增加TAGS主题
    function get_set_tags($tags='',$mid=0){
        $tarr = explode('/', $tags);
        foreach ($tarr as $v) {
            $row = $this->mcdb->get_row('type','id',array('name'=>$v,'zd'=>'tags'));
            if(!$row){
                //获取最大ID
                $row = $this->mcdb->get_row('type','id',array('zd'=>'tags'),'xid DESC');
                $tid = $this->mcdb->get_insert('type',array('name'=>$v,'zd'=>'tags','fid'=>1,'cid'=>1,'xid'=>($row->id+1)));
            }else{
                $tid = $row->id;
            }
            $this->mcdb->get_insert('comic_type',array('tid'=>$tid,'mid'=>$mid));
        }
        return true;
    }
    //随机人气
    function get_hits(){
        if(Caiji_Hits == 0) return 0;
        $rand = rand(Caiji_Hits_Ks,Caiji_Hits_Js);
        return $rand;
    }
    //标题替换
    function get_name_replace($str='',$table='comic'){
        $Caiji_Replace_name = $table == 'comic' ? Caiji_Replace_name : Book_Caiji_Replace_name;
        if($Caiji_Replace_name == '') return $str;
        $arr = explode('|',$Caiji_Replace_name);
        foreach ($arr as $v) {
            $arr2 = explode('->',$v);
            if(isset($arr2[0]) && isset($arr2[1])){
                $str = str_replace($arr2[0],$arr[1],$str);
            }
        }
        return $str;
    }
    //判断标题过滤
    function get_is_name($str='',$table='comic'){
        $Caiji_Filter_name = $table == 'comic' ? Caiji_Filter_name : Book_Caiji_Filter_name;
        if($Caiji_Filter_name == '') return false;
        $arr = explode('|',$Caiji_Filter_name);
        foreach ($arr as $v) {
            if(strpos($str,$v) !== false) return true;
        }
        return false;
    }
    //更新小说总字数
    function get_book_txtnum($bid,$chapter_table=''){
        if(empty($chapter_table)) $chapter_table = get_chapter_table($bid);
        $ytxtnum = getzd('book','text_num',$bid);
        $text_num = $this->mcdb->get_sum($chapter_table,'text_num',array('bid'=>$bid));
        if($text_num > $ytxtnum) $this->mcdb->get_update('book',$bid,array('text_num'=>$text_num));
        return true;
    }
}
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

//小说操作模型
class Novel extends CI_Model{
    
    function __construct (){
        parent:: __construct ();
		$this->load->model('tongbu');
    }

    //删除小说
    function del($id=0){
    	$id = (int)$id;
        if($id == 0) return false;
        //删除缩略图
        $picurl = getzd('book','pic',$id);
        $this->tongbu->del($picurl);
        $picxurl = getzd('book','picx',$id);
        $this->tongbu->del($picxurl);
        //删除小说记录
        $this->mcdb->get_del('book',$id);
        //删除章节记录
        $chapter_table = get_chapter_table($id);
        $this->mcdb->get_del($chapter_table,$id,'bid');
        //删除TXT文本
        $this->txt_del($id);
        //删除评分记录
        $this->mcdb->get_del('book_score',$id,'bid');
        //删除收藏记录
        $this->mcdb->get_del('book_fav',$id,'bid');
        //删除阅读记录
        $this->mcdb->get_del('book_read',$id,'bid');
        //删除打赏记录
        $this->mcdb->get_del('gift_reward',$id,'bid');
        //删除评论
        $this->mcdb->get_del('comment',$id,'bid');
        //删除评论回复
        $this->mcdb->get_del('comment_reply',$id,'bid');
        return true;
    }

    //删除章节
    function chapter_del($id=0,$chapter_table=''){
        $bid = getzd($chapter_table,'bid',$id);
    	//删除章节记录
        $this->mcdb->get_del($chapter_table,$id);
        //删除TXT文本
        $this->txt_del($bid,$id);
        //更新总字数和章节总数
        $this->get_update_nums($bid,$chapter_table);
    }

    //删除TXT文本
    function txt_del($bid=0,$zid=0){
        if($bid == 0) return false;
        if($zid > 0){
            unlink(FCPATH.'caches/txt/'.$bid.'/'.md5($zid.Mc_Book_Key).'.txt');
        }else{
            deldir(FCPATH.'caches/txt/'.$bid.'/','ok');
        }
        return true;
    }

    //更新小说章节数量以及总字数
    function get_update_nums($bid=0,$chapter_table='',$arr=array()){
        $arr['nums'] = $this->mcdb->get_nums($chapter_table,array('bid'=>$bid));
        $arr['text_num'] = $this->mcdb->get_sum($chapter_table,'text_num',array('bid'=>$bid));
        //更新小说总章节数
        $this->mcdb->get_update('book',$bid,$arr);
    }
}

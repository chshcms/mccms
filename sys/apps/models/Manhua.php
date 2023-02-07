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

//漫画操作模型
class Manhua extends CI_Model{
    
    function __construct (){
        parent:: __construct ();
		$this->load->model('tongbu');
    }

    //删除漫画
    function del($id=0){
    	$id = (int)$id;
        if($id == 0) return false;
        //删除缩略图
        $picurl = getzd('comic','pic',$id);
        $this->tongbu->del($picurl);
        $picxurl = getzd('comic','picx',$id);
        $this->tongbu->del($picxurl);
        //删除漫画记录
        $this->mcdb->get_del('comic',$id);
        //删除章节记录
        $this->mcdb->get_del('comic_chapter',$id,'mid');
        //删除图片记录
        $this->pic_del($id);
        //删除类型
        $this->mcdb->get_del('comic_type',$id,'mid');
        //删除评分记录
        $this->mcdb->get_del('comic_score',$id,'mid');
        //删除收藏记录
        $this->mcdb->get_del('fav',$id,'mid');
        //删除阅读记录
        $this->mcdb->get_del('read',$id,'mid');
        //删除打赏记录
        $this->mcdb->get_del('gift_reward',$id,'mid');
        //删除评论
        $this->mcdb->get_del('comment',$id,'mid');
        //删除评论回复
        $this->mcdb->get_del('comment_reply',$id,'mid');
        return true;
    }

    //删除章节
    function chapter_del($id=0){
    	//删除章节记录
        $this->mcdb->get_del('comic_chapter',$id);
        //删除图片记录
        $this->pic_del($id,'chapter');
    }

    //删除章节图片
    function pic_del($id=0,$ac='comic'){
        if($ac=='pic'){
            $wh = array('id'=>$id);
        }elseif($ac=='comic'){
            $wh = array('mid'=>$id);
        }else{
            $wh = array('cid'=>$id);
        }
        //删除漫画章节图片
        $array = $this->mcdb->get_select('comic_pic','id,img',$wh,'id DESC',10000);
        foreach ($array as $row) {
        	//删除图片文件
        	$this->tongbu->del($row['img']);
        	//删除记录
        	$this->mcdb->get_del('comic_pic',$row['id']);
        }
        return true;
    }

    //根据章节ID一键清空所有图片
    function pic_del_all($id){
        //删除漫画章节图片
        $array = $this->mcdb->get_select('comic_pic','id,img',array('cid'=>$id),'id DESC',10000);
        foreach ($array as $row) {
            //删除图片文件
            $this->tongbu->del($row['img']);
            //删除记录
            $this->mcdb->get_del('comic_pic',$row['id']);
        }
        return true;
    }

    //更新漫画的type表记录
    function get_set_type($arr=array(),$mid=0){
        if(!empty($arr)){
            //定义新的ID
            $new_id = array();
            foreach ($arr as $zd=>$v) {
                foreach ($v as $_id){
                    $_id = (int)$_id;
                    $row = $this->mcdb->get_row('comic_type','id',array('tid'=>$_id,'mid'=>$mid));
                    if(!$row){
                        $new_id[] = $this->mcdb->get_insert('comic_type',array('tid'=>$_id,'mid'=>$mid));
                    }else{
                        $new_id[] = $row->id;
                    }
                }
            }
        }
        //先获取去原有的记录
        $array = $this->mcdb->get_select('comic_type','id',array('mid'=>$mid),'id DESC',10000);
        //删除不需要的
        foreach ($array as $row) {
            if(!in_array($row['id'],$new_id)){
                $this->mcdb->get_del('comic_type',$row['id']);
            }
        }
        return true;
    }
}

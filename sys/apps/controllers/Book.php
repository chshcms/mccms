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
	}

    //主页
    public function index() {
    	//判断纯静态
    	if(Url_Mode > 0){
    		header("location:".get_url('book'));
    		exit;
    	}
        $cache_id = 'book-index';
        if(!$this->caches->start($cache_id,Cache_Time_Show)){
    	   echo $this->tpl->book_index();
           $this->caches->end();
        }
	}

    //分类页
    public function lists($id='',$page=1) {
        if(!ctype_alnum($id)) get_err();
        $page = (int)$page;
        if($page == 0) $page = 1;
        //判断纯静态
        if(Url_Mode > 0){
            if(is_numeric($id)){
                $en = getzd('book_class','yname',$id);
            }else{
                $en = $id;
                $id = getzd('book_class','id',$id,'yname');
            }
            header("location:".get_url('book_lists',array('id'=>$id,'yname'=>$en,'page'=>$page)));
            exit;
        }
        $cache_id = 'book-list-'.$id.'-'.$page;
        if(!$this->caches->start($cache_id,Cache_Time_List)){
           echo $this->tpl->book_lists($id,$page);
           $this->caches->end();
       }
    }

    //标签页
    public function category() {
        $uri = $this->uri->uri_string();
        $n = strpos($uri,'/index') !== false ? 3 : 2;
        if(strpos($uri,'book/') !== false) $n++;
        $arr = safe_replace($this->uri->uri_to_assoc($n));
        echo $this->tpl->book_category($arr);
    }

    //搜索页
    public function search($key='',$page=1) {
        $page = (int)$page;
        if($page == 0) $page =1;
        if(empty($key)) $key = $this->input->get_post('key',true);
        $key = safe_replace(urldecode($key));
        echo $this->tpl->book_search($key,$page);
    }

    //详情页
    public function info($id='') {
        if(!ctype_alnum($id)) get_err('book');
        //判断纯静态
        if(Url_Mode > 0){
            if(is_numeric($id)){
                $en = getzd('book','yname',$id);
            }else{
                $en = $id;
                $id = getzd('book','id',$id,'yname');
            }
            header("location:".get_url('book_info',array('id'=>$id,'yname'=>$en)));
            exit;
        }
        $cache_id = 'book-info-'.$id;
        if(!$this->caches->start($cache_id,Cache_Time_Show)){
           echo $this->tpl->book_info($id);
           $this->caches->end();
        }
    }

    //阅读页
    public function read($bid=0,$id=0) {
        $bid = (int)$bid;
        $id = (int)$id;
        if(empty($bid)) get_err();
        //判断纯静态
        if(Url_Mode > 0){
            header("location:".get_url('book_read',array('bid'=>$bid,'id'=>$id)));
            exit;
        }
        $cache_id = 'book-read-'.$bid.'-'.$id;
        if(!$this->caches->start($cache_id,Cache_Time_Pic)){
           echo $this->tpl->book_read($bid,$id);
           $this->caches->end();
        }
    }
}
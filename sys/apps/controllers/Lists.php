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
class Lists extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index($id='',$page=1) {
    	if(!ctype_alnum($id)) get_err();
    	$page = (int)$page;
    	if($page == 0) $page = 1;
    	//判断纯静态
    	if(Url_Mode > 0){
            if(is_numeric($id)){
                $en = getzd('class','yname',$id);
            }else{
                $en = safe_replace($id);
                $id = getzd('class','id',$id,'yname');
            }
    		header("location:".get_url('lists',array('id'=>$id,'yname'=>$id,'page'=>$page)));
    		exit;
    	}
        $cache_id = 'list-'.$id.'-'.$page;
        if(!$this->caches->start($cache_id,Cache_Time_List)){
    	   echo $this->tpl->lists($id,$page);
           $this->caches->end();
       }
	}
}
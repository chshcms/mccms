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
class Chapter extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index($mid=0,$id=0) {
    	$mid = (int)$mid;
    	$id = (int)$id;
    	if($id == 0) $id = $mid;
    	if(empty($id)) get_err();
    	//判断纯静态
    	if(Url_Mode > 0){
    		header("location:".get_url('pic',array('id'=>$id,'en'=>$id)));
    		exit;
    	}
        $cache_id = 'chapter-'.$id;
        if(!$this->caches->start($cache_id,Cache_Time_Pic)){
    	   echo $this->tpl->chapter($id);
           $this->caches->end();
        }
	}
}
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
	}

    public function index($id='') {
        if(!ctype_alnum($id)) get_err('comic');
    	//判断纯静态
    	if(Url_Mode > 0){
            if(is_numeric($id)){
                $en = getzd('comic','yname',$id);
            }else{
                $en = $id;
                $id = getzd('comic','id',$id,'yname');
            }
    		header("location:".get_url('show',array('id'=>$id,'yname'=>$id)));
    		exit;
    	}
        $cache_id = 'comic-'.$id;
        if(!$this->caches->start($cache_id,Cache_Time_Show)){
    	   echo $this->tpl->comic($id);
           $this->caches->end();
        }
	}
}
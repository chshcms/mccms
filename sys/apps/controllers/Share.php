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
class Share extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->get_templates('app');
	}

    //APP
    public function app($uid=0) {
        $data['uid'] = (int)$uid;
        $app = require FCPATH.'sys/libs/app.php';
        $data['android_downurl'] = $app['update']['android']['downurl'];
        $data['ios_downurl'] = $app['update']['ios']['downurl'];
        //模板
        $this->load->view('share_app.tpl',$data);
    }

    //漫画
    public function comic($mid=0,$uid=0) {
        $mid = (int)$mid;
        $data['uid'] = (int)$uid;
        $data['comic'] = $this->mcdb->get_row_arr('comic','id,pic,name,content',array('id'=>$mid));

        $app = require FCPATH.'sys/libs/app.php';
        $data['android_downurl'] = $app['update']['android']['downurl'];
        $data['ios_downurl'] = $app['update']['ios']['downurl'];
        //模板
        $this->load->view('share_comic.tpl',$data);
	}

    //小说
    public function book($bid=0,$uid=0) {
        $bid = (int)$bid;
        $data['uid'] = (int)$uid;
        $data['book'] = $this->mcdb->get_row_arr('book','id,pic,name,content',array('id'=>$bid));

        $app = require FCPATH.'sys/libs/app.php';
        $data['android_downurl'] = $app['update']['android']['downurl'];
        $data['ios_downurl'] = $app['update']['ios']['downurl'];
        //模板
        $this->load->view('share_book.tpl',$data);
    }
}
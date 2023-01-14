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

class Gf extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index() {
        $pic = $this->input->get('pic');
        $pic = sys_auth($pic,1);
        if(empty($pic)) exit('no');
        header("Content-Type:image/png");
        echo $this->geturl($pic);
	}

    private function geturl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        $header = array(
            'Host: res1.xiaoqinre.com',
            'Referer: https://www.gufengmh9.com/manhua/',
            'Sec-Fetch-Dest: image',
            'Sec-Fetch-Mode: no-cors',
            'Sec-Fetch-Site: cross-site',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//获取跳转后的
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } 
}
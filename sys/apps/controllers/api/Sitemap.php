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

class Sitemap extends Mccms_Controller {

    public function __construct(){
        parent::__construct();
        $this->ssl = is_ssl() ? 'https://' : 'http://';
    }

    public function index($type='baidu') {
        $type = str_replace('.xml','',$type);
        $arr = array('baidu','google','so','shenma','sogou','bing');
        if(!in_array($type,$arr)) $type = 'baidu';
        $jtime = strtotime(date('Y-m-d 0:0:0'))-1;
        if($type == 'google'){
            $xml = '<?xml version="1.0" encoding="UTF-8" ?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        }elseif($type == 'so'){
            $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex>';
        }else{
            $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset>';
        }
        $data = $this->mcdb->get_select('book','id,yname,addtime',array('yid'=>0,'addtime>'=>$jtime),'addtime DESC',5000);
        foreach ($data as $k => $v) {
            $url = get_url('book_info',$v);
            if(!strstr($url,'://')) $url = $this->ssl.Web_Url.$url;
            if($type == 'so'){
                $xml .= '<sitemap><loc>'.$url.'</loc><lastmod>'.date('Y-m-d',$v['addtime']).'</lastmod></sitemap>';
            }else{
                $xml .= '<url><loc>'.$url.'</loc><lastmod>'.date('Y-m-d',$v['addtime']).'</lastmod><changefreq>always</changefreq><priority>0.8</priority></url>';
            }
        }
        $data = $this->mcdb->get_select('comic','id,yname,addtime',array('yid'=>0,'addtime>'=>$jtime),'addtime DESC',5000);
        foreach ($data as $k => $v) {
            $url = get_url('show',$v);
            if(!strstr($url,'://')) $url = $this->ssl.Web_Url.$url;
            if($type == 'so'){
                $xml .= '<sitemap><loc>'.$url.'</loc><lastmod>'.date('Y-m-d',$v['addtime']).'</lastmod></sitemap>';
            }else{
                $xml .= '<url><loc>'.$url.'</loc><lastmod>'.date('Y-m-d',$v['addtime']).'</lastmod><changefreq>always</changefreq><priority>0.8</priority></url>';
            }
        }
        if($type == 'so'){
            $xml .= '</sitemapindex>';
        }else{
            $xml .= '</urlset>';
        }
        header('Content-Type: text/xml;charset=UTF-8');
        echo $xml;
    }
}
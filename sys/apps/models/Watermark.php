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

//图片水印处理模型
class Watermark extends CI_Model{
    
    function __construct (){
        parent:: __construct ();
        $this->load->library('image_lib');
    }

    //水印生成
    function send($imgpath=''){
        //已关闭水印
        if(Img_Type == '' || empty($imgpath) || !file_exists($imgpath)) return FALSE;
        //图片水印不存在
        if(Img_Type == 'overlay' && !file_exists(Img_Pic_Path)) return FALSE;

        $config['source_image'] = $imgpath;
        $config['wm_type'] = Img_Type;
        $config['wm_vrt_alignment'] = Img_Vrt;
        $config['wm_hor_alignment'] = Img_Hor;
        if(Img_Hor_Offset > 0) $config['wm_hor_offset'] = Img_Hor_Offset-Img_Hor_Offset*2;
        if(Img_Vrt_Offset > 0) $config['wm_vrt_offset'] = Img_Vrt_Offset-Img_Vrt_Offset*2;
        if(Img_Padding > 0) $config['wm_padding'] = Img_Padding-Img_Padding*2;

        //水印类型
        if(Img_Type == 'overlay'){
            $config['wm_overlay_path'] = Img_Pic_Path;
            $config['wm_opacity'] = Img_Pic_Opacity;
        }else{
            $config['wm_text'] = Img_Text_Txt;
            $config['wm_font_path'] = Img_Text_Ttf;
            $config['wm_font_size'] = Img_Text_Size;
            $config['wm_font_color'] = Img_Text_Color;
            $config['wm_shadow_color'] = Img_Text_Shadow_Color;
        }
        //print_r($config);exit;

        $this->image_lib->initialize($config);
        $res = $this->image_lib->watermark();
        $this->image_lib->clear();
        if(!$res){
            //echo $this->image_lib->display_errors();  //返回信息
            return FALSE;
        }else{
            return TRUE;
        }
    }
}
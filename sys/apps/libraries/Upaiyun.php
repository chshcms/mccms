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

require_once MCCMSPATH.'class/up_yun/vendor/autoload.php';
use Upyun\Upyun;
use Upyun\Config;
 
Class Upaiyun{
 
    public function __construct(){
        log_message('debug', "Native Upyun Class Initialized");
    }

    //上传
    public function upload($file_path){
        $upyun_file_path = str_replace(FCPATH, '/', $file_path);
        $serviceConfig = new Config(Annex_Up_Name, Annex_Up_User, Annex_Up_Pass);
        $client = new Upyun($serviceConfig);
        // 读文件
        $file = fopen($file_path, 'r');
        // 上传文件
        $res = $client->write($upyun_file_path, $file);
        // 打印上传结果
        //print_r($res);exit;
        if(isset($res['x-upyun-content-length'])){
            //判断删除本地文件
            if(Annex_Pic_Del == 0) unlink($file_path);
            return $upyun_file_path;
        }else{
            unlink($file_path);
            return false;
        }
    }
 
    //删除
    public function del($file_path){
        $serviceConfig = new Config(Annex_Up_Name, Annex_Up_User, Annex_Up_Pass);
        $client = new Upyun($serviceConfig);
        return $client->delete($file_path);
    }
}
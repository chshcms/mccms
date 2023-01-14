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

require_once MCCMSPATH.'class/qiniu_yun/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager; 
 
Class Qiniu{
 
    public function __construct(){
        log_message('debug', "Native Qiniu Class Initialized");
    }

    //上传
    public function upload($file_path){
        $qiniu_file_path = str_replace(FCPATH, '', $file_path);
        $auth = new Auth(Annex_Qniu_Ak, Annex_Qniu_Sk);  //实例化
        $token = $auth->uploadToken(Annex_Qniu_Name);
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $qiniu_file_path, $file_path);
        if($err === null) {
            //判断删除本地文件
            if(Annex_Pic_Del == 0) unlink($file_path);
            return $qiniu_file_path;
        }else{
            //exit($err);
            unlink($file_path);
            return false;
        }
    }
 
    //删除
    public function del($file_path){
        $auth = new Auth(Annex_Qniu_Ak, Annex_Qniu_Sk);
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        $err = $bucketManager->delete(Annex_Qniu_Name, $file_path);
        if($err) return false;
        return true;
    }
}
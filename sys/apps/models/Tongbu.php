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

//附件上传同步
class Tongbu extends CI_Model{
    
    function __construct (){
        parent:: __construct ();
    }

    //同步
    function send($file_path=''){
    	//本地,返回文件名,0本地，1FTP，2阿里云OSS，3七牛云，4又拍云
    	switch (Annex_Mode) {
    		case 4:
    			$this->load->library('upaiyun');
    			return $this->upaiyun->upload($file_path);
    			break;
    		case 3:
    			$this->load->library('qiniu');
    			return $this->qiniu->upload($file_path);
    			break;
    		case 2:
    			$this->load->library('alioss');
    			return $this->alioss->upload($file_path);
    			break;
    		case 1:
    			return $this->ftp($file_path);
    			break;
    		default:
    			return $file_path;
    			break;
    	}
    	return $file_path;
    }

    //删除远程文件
    function del($file_path=''){
    	//本地,返回文件名,0本地，1FTP，2阿里云OSS，3七牛云，4又拍云
    	switch (Annex_Mode) {
    		case 4:
    			$this->load->library('upaiyun');
    			return $this->upaiyun->del($file_path);
    			break;
    		case 3:
    			$this->load->library('qiniu');
    			return $this->qiniu->del($file_path);
    			break;
    		case 2:
    			$this->load->library('alioss');
    			return $this->alioss->del($file_path);
    			break;
    		case 1:
    			return $this->ftp($file_path,'del');
    			break;
    		default:
    			return unlink(FCPATH.$file_path);
    			break;
    	}
        return true;
    }

    //FTP
    function ftp($file_path,$mode='add'){
    	if(!file_exists($file_path)) return false;
    	$this->load->library('ftp');
    	$config['hostname'] = Annex_Ftp_Host;
		$config['username'] = Annex_Ftp_User;
		$config['password'] = Annex_Ftp_Pass;
		$config['port']     = Annex_Ftp_Port;
        $config['debug']    = TRUE;
		$this->ftp->connect($config);
		$Annex_Ftp_Dir = Annex_Ftp_Dir == '' ? '/' : Annex_Ftp_Dir;
		$ftp_file_path = str_replace(FCPATH, $Annex_Ftp_Dir, $file_path);
		if($mode == 'add'){
			$dir = '/'.dirname($ftp_file_path).'/';
			$dir = str_replace('//', '/',$dir);
            $darr = explode('/',$dir);
            $dir2 = '';
            foreach ($darr as $v) {
                if(!empty($v)){
                    $dir2 .= '/'.$v;
                    $res = $this->ftp->changedir($dir2,true);
                    if(!$res) $this->ftp->mkdir($dir2);
                }
            }
			$res = $this->ftp->upload($file_path, $ftp_file_path);
			$this->ftp->close();
			if($res){
				//判断删除本地文件
				if(Annex_Pic_Del == 0) unlink($file_path);
				return $ftp_file_path;
			}
			unlink($file_path);
			return false;
		}else{
			return $this->ftp->delete_file($ftp_file_path);
		}
    }
}
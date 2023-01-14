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

class Update extends Mccms_Controller {
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	//更新
	public function index()
	{
		$zipurl = $this->input->get('url',true);
		$token = $this->input->get('token',true);
		if(empty($zipurl)) $this->msg('更新包地址为空');
		$md5 = md5($zipurl.Mc_Encryption_Key);
		if($token != $md5) $this->msg('Token非法');
		//下载文件
		$zipurl = sys_auth($zipurl,1);
		$zarr = explode('/',$zipurl);
		if(empty($zipurl) || $zarr[2] != 'www.chshcms.net') $this->msg('更新包地址错误');
		//获取文件头信息
		$arr = get_headers($zipurl,true);
		if($arr['Content-Type'] != 'application/zip' && $arr['Content-Type'][1] != 'application/zip') $this->msg('压缩包不zip的类型文件');
		$data = getcurl($zipurl);
		if(empty($data)) $this->msg('获取压缩包失败');
		$file_zip = FCPATH."caches/upzip/".end(explode('/',$zipurl));
		if(!file_put_contents($file_zip, $data)) $this->msg('压缩包下载失败');
		//解压
		$this->load->library('mczip');
		$this->mczip->PclZip($file_zip);
		if ($this->mczip->extract(PCLZIP_OPT_PATH, FCPATH, PCLZIP_OPT_REPLACE_NEWER) == 0) {
            unlink($file_zip);
			$this->msg('文件解压失败，或者没有权限覆盖文件~！');
		}else{
			unlink($file_zip);
			$this->msg('版本升级成功~！',1);
		}
	}

	public function msg($txt,$zt=0){
		$color = $zt == 0 ? 'red' : '#080';
		echo '<link rel="stylesheet" href="'.Web_Path.'packs/admin/css/style.css"><div style="padding:50px;"><fieldset class="layui-elem-field"><legend><b>更新状态</b></legend><div class="layui-field-box" style="color:'.$color.';font-size:16px;">'.$txt.'</div></fieldset></div>';
		if($zt == 1){
			echo '<script>setTimeout(function(){parent.location.reload();},2000);</script>';
		}
		exit;
	}
}
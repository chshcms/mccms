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

class Setting extends Mccms_Controller {

	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
	}

	public function index($type='pc')
	{
		$data['tabid'] = $type;
		$this->load->view('setting/index.tpl',$data);
	}

	public function save()
	{
		$Web_Name = $this->input->post('Web_Name', TRUE);
		$Web_Url = $this->input->post('Web_Url', TRUE);
		$Web_Path = $this->input->post('Web_Path', TRUE);
		$Web_Book_Url = $this->input->post('Web_Book_Url', TRUE);
		$Web_Mode = (int)$this->input->post('Web_Mode');
		$Web_Ssl_Mode = (int)$this->input->post('Web_Ssl_Mode');
		$Web_Close_Txt = $this->input->post('Web_Close_Txt');
		$Web_Icp = $this->input->post('Web_Icp', TRUE);
		$Web_QQ = $this->input->post('Web_QQ', TRUE);
		$Web_Tel = $this->input->post('Web_Tel', TRUE);
		$Web_Mail = $this->input->post('Web_Mail', TRUE);
		$Web_Stat = $this->input->post('Web_Stat');
		$Web_Base_Path = $this->input->post('Web_Base_Path', TRUE);
		$Web_Rkpass = $this->input->post('Web_Rkpass', TRUE);
		$Web_Book_Tags = $this->input->post('Web_Book_Tags', TRUE);

		$Admin_Code = $this->input->post('Admin_Code', TRUE);
		$Admin_Log_Day = (int)$this->input->post('Admin_Log_Day');
		$Admin_Ip = $this->input->post('Admin_Ip', TRUE);
		if($Admin_Log_Day == 0) $Admin_Log_Day = 7;

		$Pl_Mode = (int)$this->input->post('Pl_Mode');
		$Pl_Time = (int)$this->input->post('Pl_Time');
		$Pl_Add_Num = (int)$this->input->post('Pl_Add_Num');
		$Pl_Str = $this->input->post('Pl_Str', TRUE);
		if($Pl_Time == 0) $Pl_Time = 10;
		if($Pl_Add_Num == 0) $Pl_Add_Num = 5;

		$Url_Mode = (int)$this->input->post('Url_Mode');
		$Url_Index_Mode = (int)$this->input->post('Url_Index_Mode');

		$Url_Web_List = $this->input->post('Url_Web_List', TRUE);
		$Url_Web_Show = $this->input->post('Url_Web_Show', TRUE);
		$Url_Web_Pic = $this->input->post('Url_Web_Pic', TRUE);
		$Url_Book_Web_List = $this->input->post('Url_Book_Web_List', TRUE);
		$Url_Book_Web_Info = $this->input->post('Url_Book_Web_Info', TRUE);
		$Url_Book_Web_Read = $this->input->post('Url_Book_Web_Read', TRUE);

		$Wap_Html_Dir = $this->input->post('Wap_Html_Dir', TRUE);
		$Wap_Html_Url = $this->input->post('Wap_Html_Url', TRUE);
		$Wap_Book_Html_Dir = $this->input->post('Wap_Book_Html_Dir', TRUE);
		$Wap_Book_Html_Url = $this->input->post('Wap_Book_Html_Url', TRUE);
		
		$Url_Html_Index = $this->input->post('Url_Html_Index', TRUE);
		$Url_Html_List = $this->input->post('Url_Html_List', TRUE);
		$Url_Html_Show = $this->input->post('Url_Html_Show', TRUE);
		$Url_Html_Pic = $this->input->post('Url_Html_Pic', TRUE);
		$Url_Book_Html_Index = $this->input->post('Url_Book_Html_Index', TRUE);
		$Url_Book_Html_List = $this->input->post('Url_Book_Html_List', TRUE);
		$Url_Book_Html_Info = $this->input->post('Url_Book_Html_Info', TRUE);
		$Url_Book_Html_Read = $this->input->post('Url_Book_Html_Read', TRUE);

		$Skin_Pc_Path = $this->input->post('Skin_Pc_Path', TRUE);
		$Skin_Wap_Path = $this->input->post('Skin_Wap_Path', TRUE);

		$Seo_Title = $this->input->post('Seo_Title', TRUE);
		$Seo_Keywords = $this->input->post('Seo_Keywords', TRUE);
		$Seo_Description = $this->input->post('Seo_Description', TRUE);

		$Wx_Token = $this->input->post('Wx_Token', TRUE);
		$Wx_Gz_Msg = $this->input->post('Wx_Gz_Msg');
		$Wx_Key_Msg = $this->input->post('Wx_Key_Msg');

        //HTML转码
        $Web_Close_Txt = str_encode($Web_Close_Txt);
		$Web_Stat = str_encode($Web_Stat);
        $Wx_Gz_Msg = str_encode($Wx_Gz_Msg);
        $Wx_Key_Msg = str_encode($Wx_Key_Msg);

        //判断主要数据不能为空
		if(empty($Web_Name)) get_json('网站名称不能为空');
		if(empty($Web_Url)) get_json('网站域名不能为空');
		if(empty($Web_Path)) get_json('网站安装路径不能为空');
		if(empty($Admin_Code)) get_json('后台认证码不能为空');

		$strs="<?php"."\r\n";
		$strs.="define('Web_Name','".$Web_Name."');\r\n";
		$strs.="define('Web_Url','".$Web_Url."');\r\n";
		$strs.="define('Web_Path','".$Web_Path."');\r\n";
		$strs.="define('Web_Book_Url','".$Web_Book_Url."');\r\n";
		$strs.="define('Web_Mode',".$Web_Mode.");\r\n";
		$strs.="define('Web_Ssl_Mode',".$Web_Ssl_Mode.");\r\n";
		$strs.="define('Web_Close_Txt','".$Web_Close_Txt."');\r\n";
		$strs.="define('Web_Book_Tags','".$Web_Book_Tags."');\r\n";
		$strs.="define('Web_Icp','".$Web_Icp."');\r\n";
		$strs.="define('Web_QQ','".$Web_QQ."');\r\n";
		$strs.="define('Web_Tel','".$Web_Tel."');\r\n";
		$strs.="define('Web_Mail','".$Web_Mail."');\r\n";
		$strs.="define('Web_Stat','".$Web_Stat."');\r\n";
		$strs.="define('Web_Base_Path','".$Web_Base_Path."');\r\n";
		$strs.="define('Web_Rkpass','".$Web_Rkpass."');\r\n\r\n";
		$strs.="define('Admin_Code','".$Admin_Code."');\r\n";
		$strs.="define('Admin_Log_Day',".$Admin_Log_Day.");\r\n";
		$strs.="define('Admin_Ip','".$Admin_Ip."');\r\n\r\n";
		$strs.="define('Pl_Mode',".$Pl_Mode.");\r\n";
		$strs.="define('Pl_Time',".$Pl_Time.");\r\n";
		$strs.="define('Pl_Add_Num',".$Pl_Add_Num.");\r\n";
		$strs.="define('Pl_Str','".$Pl_Str."');\r\n\r\n";
		$strs.="define('Url_Mode',".$Url_Mode.");\r\n";
		$strs.="define('Url_Index_Mode',".$Url_Index_Mode.");\r\n";

		$strs.="define('Url_Web_List','".$Url_Web_List."');\r\n";
		$strs.="define('Url_Web_Show','".$Url_Web_Show."');\r\n";
		$strs.="define('Url_Web_Pic','".$Url_Web_Pic."');\r\n";
		$strs.="define('Url_Book_Web_List','".$Url_Book_Web_List."');\r\n";
		$strs.="define('Url_Book_Web_Info','".$Url_Book_Web_Info."');\r\n";
		$strs.="define('Url_Book_Web_Read','".$Url_Book_Web_Read."');\r\n\r\n";

		$strs.="define('Wap_Html_Dir','".$Wap_Html_Dir."');\r\n";
		$strs.="define('Wap_Html_Url','".$Wap_Html_Url."');\r\n";
		$strs.="define('Wap_Book_Html_Dir','".$Wap_Book_Html_Dir."');\r\n";
		$strs.="define('Wap_Book_Html_Url','".$Wap_Book_Html_Url."');\r\n\r\n";

		$strs.="define('Url_Html_Index','".$Url_Html_Index."');\r\n";
		$strs.="define('Url_Html_List','".$Url_Html_List."');\r\n";
		$strs.="define('Url_Html_Show','".$Url_Html_Show."');\r\n";
		$strs.="define('Url_Html_Pic','".$Url_Html_Pic."');\r\n";
		$strs.="define('Url_Book_Html_Index','".$Url_Book_Html_Index."');\r\n";
		$strs.="define('Url_Book_Html_List','".$Url_Book_Html_List."');\r\n";
		$strs.="define('Url_Book_Html_Info','".$Url_Book_Html_Info."');\r\n";
		$strs.="define('Url_Book_Html_Read','".$Url_Book_Html_Read."');\r\n\r\n";

		$strs.="define('Seo_Title','".$Seo_Title."');\r\n";
		$strs.="define('Seo_Keywords','".$Seo_Keywords."');\r\n";
		$strs.="define('Seo_Description','".$Seo_Description."');\r\n\r\n";
		$strs.="define('Wx_Token','".$Wx_Token."');\r\n";
		$strs.="define('Wx_Gz_Msg','".$Wx_Gz_Msg."');\r\n";
		$strs.="define('Wx_Key_Msg','".$Wx_Key_Msg."');\r\n\r\n";
		$strs.="define('Skin_Pc_Path','".Skin_Pc_Path."');\r\n";
		$strs.="define('Skin_Wap_Path','".Skin_Wap_Path."');";

		//URL路由
		if($Url_Mode == 0){
			if(!empty($Url_Web_List) || !empty($Url_Web_Show) || !empty($Url_Web_Pic) || !empty($Url_Book_Web_List) || !empty($Url_book_Web_Info) || !empty($Url_book_Web_Read)){
	            $uri = array(
	                'lists' => $Url_Web_List,
	                'show'  => $Url_Web_Show,
	                'pic'  => $Url_Web_Pic,
	                'book_lists' => $Url_Book_Web_List,
	                'book_info'  => $Url_Book_Web_Info,
	                'book_read'  => $Url_Book_Web_Read
				);
	            $this->_route_file($uri);
	        }
		}

        //写文件
        if (!write_file(MCCMSPATH.'libs/config.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting');
            get_json($arr,1);
		}
	}

	public function skins($op = 'pc')
	{
		$this->load->helper('directory');
		//获取所有PC模板
		$pcpath = FCPATH.'template/pc/';
		$pcarr = directory_map($pcpath, 1);
		$pcskin = array();
        foreach ($pcarr as $dir) {
			$dir = str_replace("\\","/",$dir);
			if(file_exists($pcpath.$dir.'/tpl.php')){
				$skinarr = require $pcpath.$dir.'/tpl.php';
				$skin_pic = $pcpath.$dir.'/pic.png';
				if(!file_exists($skin_pic)){
					$skinarr['pic'] = Web_Base_Path.'admin/images/skin_no.png';
				}else{
					$skinarr['pic'] = str_replace(FCPATH,Web_Path,$skin_pic);
				}
				$skinarr['init'] = $skinarr['path'] == Skin_Pc_Path ? 1 : 0;
				$skinarr['path'] =sys_auth($skinarr['path']);
				$pcskin[] = $skinarr;
			}
		}
        $data['pc'] = $pcskin;
		//获取所有手机模板
		$wappath = FCPATH.'template/wap/';
		$waparr = directory_map($wappath, 1);
		$wapskin = array();
        foreach ($waparr as $dir) {
			$dir = str_replace("\\","/",$dir);
			if(file_exists($wappath.$dir.'/tpl.php')){
				$skinarr = require $wappath.$dir.'/tpl.php';
				$skin_pic = $wappath.$dir.'/pic.png';
				if(!file_exists($skin_pic)){
					$skinarr['pic'] = Web_Base_Path.'admin/images/skin_no.png';
				}else{
					$skinarr['pic'] = str_replace(FCPATH,Web_Path,$skin_pic);
				}
				$skinarr['init'] = $skinarr['path'] == Skin_Wap_Path ? 1 : 0;
				$skinarr['path'] =sys_auth($skinarr['path']);
				$wapskin[] = $skinarr;
			}
		}
        $data['wap'] = $wapskin;
        $data['tabid'] = $op;
		$this->load->view('setting/skins.tpl',$data);
	}

	//设置默认模版
	public function skins_init($op = 'pc')
	{
		$this->load->helper('file');
		$path = $this->input->get_post('path',true);
		$path = sys_auth($path,1);
		if(empty($path) || !is_dir(VIEWPATH.$path)) get_json('模版目录不存在');
		$conf = read_file(MCCMSPATH.'libs/config.php');
		if($op == 'wap'){
			$conf = preg_replace("/'Skin_Wap_Path','(.*?)'/","'Skin_Wap_Path','".$path."'",$conf);
		}else{
			$conf = preg_replace("/'Skin_Pc_Path','(.*?)'/","'Skin_Pc_Path','".$path."'",$conf);
		}
		$res = write_file(MCCMSPATH.'libs/config.php', $conf);
		if(!$res) get_json('配置文件没有修改权限!!!');
		$arr['msg'] = '模版设置成功~！';
		$arr['url'] =  links('setting','skins',$op);
		get_json($arr,1);
	}

	//删除模版
	public function skins_del($op = 'pc')
	{
		$path = $this->input->get_post('path',true);
		$path = sys_auth($path,1);
		if(empty($path) || !is_dir(VIEWPATH.$path)) get_json('模版目录不存在');
		if($op == 'wap'){
			if(Skin_Wap_Path == $path) get_json('默认模版不能删除');
		}else{
			if(Skin_Pc_Path == $path) get_json('默认模版不能删除');
		}
	    $TempImg = str_replace('//','/',$path);
	    $end = strrpos(substr($TempImg,0,strlen($TempImg)-1),'/')+1;
    	$tpl_dir = substr(substr($TempImg,0,strlen($TempImg)-1),0,$end);
		$res = deldir(str_replace("\\",'/',VIEWPATH.$tpl_dir),'ok');
		if(!$res) get_json('模版目录没有删除权限!!!');
		$arr['msg'] = '模版删除成功~！';
		$arr['url'] =  links('setting','skins',$op);
		get_json($arr,1);
	}

	public function cache()
	{
		$this->load->view('setting/cache.tpl');
	}

	public function cache_save()
	{
		$Cache_Mode = (int)$this->input->post('Cache_Mode');
		$Cache_Rand = $this->input->post('Cache_Rand', TRUE);
		$Cache_Mem_Ip = $this->input->post('Cache_Mem_Ip', TRUE);
		$Cache_Mem_Port = (int)$this->input->post('Cache_Mem_Port');
		$Cache_Mem_Pass = $this->input->post('Cache_Mem_Pass', TRUE);
		$Cache_Redis_Ip = $this->input->post('Cache_Redis_Ip', TRUE);
		$Cache_Redis_Port = (int)$this->input->post('Cache_Redis_Port');
		$Cache_Redis_Pass = $this->input->post('Cache_Redis_Pass', TRUE);
		$Cache_Time = (int)$this->input->post('Cache_Time');
		$Cache_Time_Index = (int)$this->input->post('Cache_Time_Index');
		$Cache_Time_List = (int)$this->input->post('Cache_Time_List');
		$Cache_Time_Show = (int)$this->input->post('Cache_Time_Show');
		$Cache_Time_Pic = (int)$this->input->post('Cache_Time_Pic');

		if($Cache_Mode == 2){
			if(empty($Cache_Mem_Ip)) get_json('Memcache缓存主机不能为空');
			if($Cache_Mem_Port == 0) get_json('Memcache缓存端口不能为空');
		}
		if($Cache_Mode == 3){
			if(empty($Cache_Redis_Ip)) get_json('Redis缓存主机不能为空');
			if($Cache_Redis_Port == 0) get_json('Redis缓存端口不能为空');
		}

		$strs="<?php"."\r\n";
		$strs.="define('Cache_Mode',".$Cache_Mode.");\r\n";
		$strs.="define('Cache_Rand','".$Cache_Rand."');\r\n";
		$strs.="define('Cache_Mem_Ip','".$Cache_Mem_Ip."');\r\n";
		$strs.="define('Cache_Mem_Port',".$Cache_Mem_Port.");\r\n";
		$strs.="define('Cache_Mem_Pass','".$Cache_Mem_Pass."');\r\n";
		$strs.="define('Cache_Redis_Ip','".$Cache_Redis_Ip."');\r\n";
		$strs.="define('Cache_Redis_Port',".$Cache_Redis_Port.");\r\n";
		$strs.="define('Cache_Redis_Pass','".$Cache_Redis_Pass."');\r\n";
		$strs.="define('Cache_Time_Index',".$Cache_Time_Index.");\r\n";
		$strs.="define('Cache_Time_List',".$Cache_Time_List.");\r\n";
		$strs.="define('Cache_Time_Show',".$Cache_Time_Show.");\r\n";
		$strs.="define('Cache_Time_Pic',".$Cache_Time_Pic.");\r\n";
		$strs.="define('Cache_Time',".$Cache_Time.");";

        //写文件
        if (!write_file(MCCMSPATH.'libs/cache.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting','cache');
            get_json($arr,1);
		}
	}

	public function user()
	{
		$this->load->view('setting/user.tpl');
	}

	public function user_save()
	{
		$User_Reg = (int)$this->input->post('User_Reg');
		$User_Reg_Tel = (int)$this->input->post('User_Reg_Tel');
		$User_Reg_Vip = (int)$this->input->post('User_Reg_Vip');
		$User_Reg_Cion = (int)$this->input->post('User_Reg_Cion');
		$User_Reg_Vip_Day = (int)$this->input->post('User_Reg_Vip_Day');
		$User_Tg_Cion = (int)$this->input->post('User_Tg_Cion');
		$User_Pl_Cion = (int)$this->input->post('User_Pl_Cion');
		$User_Pl_Num = (int)$this->input->post('User_Pl_Num');
		$User_Gg = $this->input->post('User_Gg', TRUE);

		$Author_Mode = (int)$this->input->post('Author_Mode');
		$Author_Rz = (int)$this->input->post('Author_Rz');
		$Author_Tx_Rmb = (int)$this->input->post('Author_Tx_Rmb');
		$Author_Add_Cion = (int)$this->input->post('Author_Add_Cion');
		$Author_Comic_Cion = (int)$this->input->post('Author_Comic_Cion');
		$Author_Book_Cion = (int)$this->input->post('Author_Book_Cion');
		$Author_Fc_Ds = (int)$this->input->post('Author_Fc_Ds');
		$Author_Fc_Yp = (int)$this->input->post('Author_Fc_Yp');
		$Author_Fc_Comic = (int)$this->input->post('Author_Fc_Comic');
		$Author_Fc_Book = (int)$this->input->post('Author_Fc_Book');

		$Mail_Type = $this->input->post('Mail_Type', TRUE);
		$Mail_Host = $this->input->post('Mail_Host', TRUE);
		$Mail_Port = (int)$this->input->post('Mail_Port');
		$Mail_Name = $this->input->post('Mail_Name', TRUE);
		$Mail_Email = $this->input->post('Mail_Email', TRUE);
		$Mail_User = $this->input->post('Mail_User', TRUE);
		$Mail_Pass = $this->input->post('Mail_Pass', TRUE);
		$Mail_Crypto = $this->input->post('Mail_Crypto', TRUE);
		$Mail_Demo = $this->input->post('Mail_Demo', TRUE);
		$Mail_Code_Title = $this->input->post('Mail_Code_Title', TRUE);
		$Mail_Code_Msg = $this->input->post('Mail_Code_Msg');
		$Mail_Remind = (int)$this->input->post('Mail_Remind');
		$Mail_Remind_Title = $this->input->post('Mail_Remind_Title', TRUE);
		$Mail_Remind_Msg = $this->input->post('Mail_Remind_Msg');
		$Mail_Drawing = (int)$this->input->post('Mail_Drawing');
		$Mail_Drawing_Title = $this->input->post('Mail_Drawing_Title', TRUE);
		$Mail_Drawing_Msg = $this->input->post('Mail_Drawing_Msg');
		$Sms_Mode = (int)$this->input->post('Sms_Mode');
		$Sms_Appid = $this->input->post('Sms_Appid', TRUE);
		$Sms_Appkey = $this->input->post('Sms_Appkey', TRUE);
		$Sms_Name = $this->input->post('Sms_Name', TRUE);
		$Sms_Tpl_Log = $this->input->post('Sms_Tpl_Log', TRUE);
		$Sms_Tpl_Bind = $this->input->post('Sms_Tpl_Bind', TRUE);
		$Sms_Tpl_Pass = $this->input->post('Sms_Tpl_Pass', TRUE);
		$Land_QQ_Appid = $this->input->post('Land_QQ_Appid', TRUE);
		$Land_QQ_Appkey = $this->input->post('Land_QQ_Appkey', TRUE);
		$Land_QQ_Url = $this->input->post('Land_QQ_Url', TRUE);
		$Land_Wx_Appid = $this->input->post('Land_Wx_Appid', TRUE);
		$Land_Wx_Appkey = $this->input->post('Land_Wx_Appkey', TRUE);
		$Land_Wx_Url = $this->input->post('Land_Wx_Url', TRUE);
		$Land_Wb_Appid = $this->input->post('Land_Wb_Appid', TRUE);
		$Land_Wb_Appkey = $this->input->post('Land_Wb_Appkey', TRUE);
		$Land_Wb_Url = $this->input->post('Land_Wb_Url', TRUE);

		if($Mail_Pass == get_pass(Mail_Pass)) $Mail_Pass = Mail_Pass;

        //HTML转码
        $Mail_Code_Msg = str_replace("\n","",str_encode($Mail_Code_Msg));
        $Mail_Remind_Msg = str_replace("\n","",str_encode($Mail_Remind_Msg));
        $Mail_Drawing_Msg = str_replace("\n","",str_encode($Mail_Drawing_Msg));

		$strs="<?php"."\r\n";
		$strs.="define('User_Reg',".$User_Reg.");\r\n";
		$strs.="define('User_Reg_Tel',".$User_Reg_Tel.");\r\n";
		$strs.="define('User_Reg_Vip',".$User_Reg_Vip.");\r\n";
		$strs.="define('User_Reg_Cion',".$User_Reg_Cion.");\r\n";
		$strs.="define('User_Reg_Vip_Day',".$User_Reg_Vip_Day.");\r\n";
		$strs.="define('User_Tg_Cion',".$User_Tg_Cion.");\r\n";
		$strs.="define('User_Pl_Cion',".$User_Pl_Cion.");\r\n";
		$strs.="define('User_Pl_Num',".$User_Pl_Num.");\r\n";
		$strs.="define('User_Gg','".$User_Gg."');\r\n\r\n";

		$strs.="define('Author_Mode',".$Author_Mode.");\r\n";
		$strs.="define('Author_Rz',".$Author_Rz.");\r\n";
		$strs.="define('Author_Tx_Rmb',".$Author_Tx_Rmb.");\r\n";
		$strs.="define('Author_Add_Cion',".$Author_Add_Cion.");\r\n";
		$strs.="define('Author_Comic_Cion',".$Author_Comic_Cion.");\r\n";
		$strs.="define('Author_Book_Cion',".$Author_Book_Cion.");\r\n";
		$strs.="define('Author_Fc_Ds',".$Author_Fc_Ds.");\r\n";
		$strs.="define('Author_Fc_Yp',".$Author_Fc_Yp.");\r\n";
		$strs.="define('Author_Fc_Comic',".$Author_Fc_Comic.");\r\n";
		$strs.="define('Author_Fc_Book',".$Author_Fc_Book.");\r\n\r\n";

		$strs.="define('Mail_Type','".$Mail_Type."');\r\n";
		$strs.="define('Mail_Host','".$Mail_Host."');\r\n";
		$strs.="define('Mail_Port',".$Mail_Port.");\r\n";
		$strs.="define('Mail_Name','".$Mail_Name."');\r\n";
		$strs.="define('Mail_Email','".$Mail_Email."');\r\n";
		$strs.="define('Mail_User','".$Mail_User."');\r\n";
		$strs.="define('Mail_Pass','".$Mail_Pass."');\r\n";
		$strs.="define('Mail_Crypto','".$Mail_Crypto."');\r\n";
		$strs.="define('Mail_Demo','".$Mail_Demo."');\r\n";
		$strs.="define('Mail_Code_Title','".$Mail_Code_Title."');\r\n";
		$strs.="define('Mail_Code_Msg','".$Mail_Code_Msg."');\r\n";
		$strs.="define('Mail_Drawing','".$Mail_Drawing."');\r\n";
		$strs.="define('Mail_Drawing_Title','".$Mail_Drawing_Title."');\r\n";
		$strs.="define('Mail_Drawing_Msg','".$Mail_Drawing_Msg."');\r\n";
		$strs.="define('Mail_Remind',".$Mail_Remind.");\r\n";
		$strs.="define('Mail_Remind_Title','".$Mail_Remind_Title."');\r\n";
		$strs.="define('Mail_Remind_Msg','".$Mail_Remind_Msg."');\r\n\r\n";

		$strs.="define('Sms_Mode',".$Sms_Mode.");\r\n";
		$strs.="define('Sms_Appid','".$Sms_Appid."');\r\n";
		$strs.="define('Sms_Appkey','".$Sms_Appkey."');\r\n";
		$strs.="define('Sms_Name','".$Sms_Name."');\r\n";
		$strs.="define('Sms_Tpl_Log','".$Sms_Tpl_Log."');\r\n";
		$strs.="define('Sms_Tpl_Bind','".$Sms_Tpl_Bind."');\r\n";
		$strs.="define('Sms_Tpl_Pass','".$Sms_Tpl_Pass."');\r\n\r\n";

		$strs.="define('Land_QQ_Appid','".$Land_QQ_Appid."');\r\n";
		$strs.="define('Land_QQ_Appkey','".$Land_QQ_Appkey."');\r\n";
		$strs.="define('Land_QQ_Url','".$Land_QQ_Url."');\r\n";
		$strs.="define('Land_Wx_Appid','".$Land_Wx_Appid."');\r\n";
		$strs.="define('Land_Wx_Appkey','".$Land_Wx_Appkey."');\r\n";
		$strs.="define('Land_Wx_Url','".$Land_Wx_Url."');\r\n";
		$strs.="define('Land_Wb_Appid','".$Land_Wb_Appid."');\r\n";
		$strs.="define('Land_Wb_Appkey','".$Land_Wb_Appkey."');\r\n";
		$strs.="define('Land_Wb_Url','".$Land_Wb_Url."');";
		
        //写文件
        if(!write_file(MCCMSPATH.'libs/user.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting','user');
            get_json($arr,1);
		}
	}

	public function annex()
	{
		$this->load->view('setting/annex.tpl');
	}

	public function annex_save()
	{
		$Annex_Dir = $this->input->post('Annex_Dir', TRUE);
		$Annex_Path = $this->input->post('Annex_Path', TRUE);
		$Annex_Ext = $this->input->post('Annex_Ext', TRUE);
		$Annex_Size = (int)$this->input->post('Annex_Size');
		$Annex_Mode = (int)$this->input->post('Annex_Mode');
		$Annex_Pic_Del = (int)$this->input->post('Annex_Pic_Del');
		$Annex_Ftp_Host = $this->input->post('Annex_Ftp_Host', TRUE);
		$Annex_Ftp_Port = (int)$this->input->post('Annex_Ftp_Port');
		$Annex_Ftp_User = $this->input->post('Annex_Ftp_User', TRUE);
		$Annex_Ftp_Pass = $this->input->post('Annex_Ftp_Pass', TRUE);
		$Annex_Ftp_Dir = $this->input->post('Annex_Ftp_Dir', TRUE);
		$Annex_Ftp_Url = $this->input->post('Annex_Ftp_Url', TRUE);
		$Annex_Oss_Bucket = $this->input->post('Annex_Oss_Bucket', TRUE);
		$Annex_Oss_Aid = $this->input->post('Annex_Oss_Aid', TRUE);
		$Annex_Oss_Key = $this->input->post('Annex_Oss_Key', TRUE);
		$Annex_Oss_End = $this->input->post('Annex_Oss_End', TRUE);
		$Annex_Oss_Url = $this->input->post('Annex_Oss_Url', TRUE);
		$Annex_Qniu_Name = $this->input->post('Annex_Qniu_Name', TRUE);
		$Annex_Qniu_Ak = $this->input->post('Annex_Qniu_Ak', TRUE);
		$Annex_Qniu_Sk = $this->input->post('Annex_Qniu_Sk', TRUE);
		$Annex_Qniu_Url = $this->input->post('Annex_Qniu_Url', TRUE);
		$Annex_Up_Name = $this->input->post('Annex_Up_Name', TRUE);
		$Annex_Up_User = $this->input->post('Annex_Up_User', TRUE);
		$Annex_Up_Pass = $this->input->post('Annex_Up_Pass', TRUE);
		$Annex_Up_Url = $this->input->post('Annex_Up_Url', TRUE);

		$Img_Type = $this->input->post('Img_Type',TRUE);
		$Img_Padding = (int)$this->input->post('Img_Padding');
		$Img_Vrt = $this->input->post('Img_Vrt', TRUE);
		$Img_Vrt_Offset = (int)$this->input->post('Img_Vrt_Offset');
		$Img_Hor = $this->input->post('Img_Hor', TRUE);
		$Img_Hor_Offset = (int)$this->input->post('Img_Hor_Offset');
		$Img_Text_Txt = $this->input->post('Img_Text_Txt', TRUE);
		$Img_Text_Ttf = $this->input->post('Img_Text_Ttf', TRUE);
		$Img_Text_Size = (int)$this->input->post('Img_Text_Size');
		$Img_Text_Color = $this->input->post('Img_Text_Color', TRUE);
		$Img_Text_Shadow_Color = $this->input->post('Img_Text_Shadow_Color', TRUE);
		$Img_Pic_Path = $this->input->post('Img_Pic_Path',TRUE);
		$Img_Pic_Opacity = (int)$this->input->post('Img_Pic_Opacity');
		if(empty($Annex_Ext)) $Annex_Ext = 'jpg|gif|png|jpeg|bmp';
		if($Annex_Size == 0) $Annex_Size = 102400;
		if($Img_Type != 'text' && $Img_Type != 'overlay') $Img_Type = '';

		if(empty($Annex_Dir)) get_json('附件存储目录不能为空~!');
		if(empty($Annex_Path)) get_json('附件路径格式不能为空~!');
		if($Annex_Ftp_Pass == get_pass(Annex_Ftp_Pass)) $Annex_Ftp_Pass = Annex_Ftp_Pass;
		if($Img_Pic_Opacity == 0) $Img_Pic_Opacity = 1;
		//判断是否开启FTP扩展
		if(Annex_Mode == 1 && !function_exists('ftp_connect')){
			get_json('PHP环境未开启php_ftp扩展~!');
		}

		$strs="<?php"."\r\n";
		$strs.="define('Annex_Dir','".$Annex_Dir."');\r\n";
		$strs.="define('Annex_Path','".$Annex_Path."');\r\n";
		$strs.="define('Annex_Ext','".$Annex_Ext."');\r\n";
		$strs.="define('Annex_Size',".$Annex_Size.");\r\n";
		$strs.="define('Annex_Mode',".$Annex_Mode.");\r\n";
		$strs.="define('Annex_Pic_Del',".$Annex_Pic_Del.");\r\n";
		$strs.="define('Annex_Ftp_Host','".$Annex_Ftp_Host."');\r\n";
		$strs.="define('Annex_Ftp_Port',".$Annex_Ftp_Port.");\r\n";
		$strs.="define('Annex_Ftp_User','".$Annex_Ftp_User."');\r\n";
		$strs.="define('Annex_Ftp_Pass','".$Annex_Ftp_Pass."');\r\n";
		$strs.="define('Annex_Ftp_Dir','".$Annex_Ftp_Dir."');\r\n";
		$strs.="define('Annex_Ftp_Url','".$Annex_Ftp_Url."');\r\n";
		$strs.="define('Annex_Oss_Bucket','".$Annex_Oss_Bucket."');\r\n";
		$strs.="define('Annex_Oss_Aid','".$Annex_Oss_Aid."');\r\n";
		$strs.="define('Annex_Oss_Key','".$Annex_Oss_Key."');\r\n";
		$strs.="define('Annex_Oss_Url','".$Annex_Oss_Url."');\r\n";
		$strs.="define('Annex_Oss_End','".$Annex_Oss_End."');\r\n";
		$strs.="define('Annex_Qniu_Name','".$Annex_Qniu_Name."');\r\n";
		$strs.="define('Annex_Qniu_Ak','".$Annex_Qniu_Ak."');\r\n";
		$strs.="define('Annex_Qniu_Sk','".$Annex_Qniu_Sk."');\r\n";
		$strs.="define('Annex_Qniu_Url','".$Annex_Qniu_Url."');\r\n";
		$strs.="define('Annex_Up_Name','".$Annex_Up_Name."');\r\n";
		$strs.="define('Annex_Up_User','".$Annex_Up_User."');\r\n";
		$strs.="define('Annex_Up_Pass','".$Annex_Up_Pass."');\r\n";
		$strs.="define('Annex_Up_Url','".$Annex_Up_Url."');\r\n\r\n";
		$strs.="define('Img_Type','".$Img_Type."');\r\n";
		$strs.="define('Img_Padding',".$Img_Padding.");\r\n";
		$strs.="define('Img_Vrt','".$Img_Vrt."');\r\n";
		$strs.="define('Img_Vrt_Offset',".$Img_Vrt_Offset.");\r\n";
		$strs.="define('Img_Hor','".$Img_Hor."');\r\n";
		$strs.="define('Img_Hor_Offset',".$Img_Hor_Offset.");\r\n";
		$strs.="define('Img_Text_Txt','".$Img_Text_Txt."');\r\n";
		$strs.="define('Img_Text_Ttf','".$Img_Text_Ttf."');\r\n";
		$strs.="define('Img_Text_Size',".$Img_Text_Size.");\r\n";
		$strs.="define('Img_Text_Color','".$Img_Text_Color."');\r\n";
		$strs.="define('Img_Text_Shadow_Color','".$Img_Text_Shadow_Color."');\r\n";
		$strs.="define('Img_Pic_Path','".$Img_Pic_Path."');\r\n";
		$strs.="define('Img_Pic_Opacity',".$Img_Pic_Opacity.");";

        //写文件
        if(!write_file(MCCMSPATH.'libs/annex.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting','annex');
            get_json($arr,1);
		}
	}
	
	public function pay()
	{
		$this->load->view('setting/pay.tpl');
	}

	public function pay_save()
	{
		$Pay_Cion_Name = $this->input->post('Pay_Cion_Name', TRUE);
		$Pay_Rmb_Cion = (int)$this->input->post('Pay_Rmb_Cion');
		$Pay_Rmb_Min = (int)$this->input->post('Pay_Rmb_Min');
		$Pay_Vip_Rmb1 = (int)$this->input->post('Pay_Vip_Rmb1');
		$Pay_Vip_Rmb2 = (int)$this->input->post('Pay_Vip_Rmb2');
		$Pay_Vip_Rmb3 = (int)$this->input->post('Pay_Vip_Rmb3');
		$Pay_Vip_Rmb4 = (int)$this->input->post('Pay_Vip_Rmb4');
		$Pay_Vip_Month = (int)$this->input->post('Pay_Vip_Month');
		$Pay_Vip_Day = (int)$this->input->post('Pay_Vip_Day');
		$Pay_Card_Url = $this->input->post('Pay_Card_Url', TRUE);
		$Pay_Ali_ID = $this->input->post('Pay_Ali_ID', TRUE);
		$Pay_Ali_Pubkey = $this->input->post('Pay_Ali_Pubkey');
		$Pay_Ali_Prikey = $this->input->post('Pay_Ali_Prikey');
		$Pay_Ali_Mode = (int)$this->input->post('Pay_Ali_Mode');
		$Pay_QQ_ID = $this->input->post('Pay_QQ_ID', TRUE);
		$Pay_QQ_Key = $this->input->post('Pay_QQ_Key', TRUE);
		$Pay_QQ_User = $this->input->post('Pay_QQ_User', TRUE);
		$Pay_QQ_Mode = (int)$this->input->post('Pay_QQ_Mode');
		$Pay_Wx_ID = $this->input->post('Pay_Wx_ID', TRUE);
		$Pay_Wx_Key = $this->input->post('Pay_Wx_Key', TRUE);
		$Pay_Wx_User = $this->input->post('Pay_Wx_User', TRUE);
		$Pay_Wx_Mode = (int)$this->input->post('Pay_Wx_Mode');

		if(empty($Pay_Cion_Name)) $Pay_Cion_Name = '积分';
		if($Pay_Rmb_Cion == 0) $Pay_Rmb_Cion = 1;
		if($Pay_Rmb_Min == 0) $Pay_Rmb_Min = 1;


		$strs="<?php"."\r\n";
		$strs.="define('Pay_Cion_Name','".$Pay_Cion_Name."');\r\n";
		$strs.="define('Pay_Rmb_Cion',".$Pay_Rmb_Cion.");\r\n";
		$strs.="define('Pay_Rmb_Min',".$Pay_Rmb_Min.");\r\n";
		$strs.="define('Pay_Vip_Rmb1',".$Pay_Vip_Rmb1.");\r\n";
		$strs.="define('Pay_Vip_Rmb2',".$Pay_Vip_Rmb2.");\r\n";
		$strs.="define('Pay_Vip_Rmb3',".$Pay_Vip_Rmb3.");\r\n";
		$strs.="define('Pay_Vip_Rmb4',".$Pay_Vip_Rmb4.");\r\n";
		$strs.="define('Pay_Vip_Month',".$Pay_Vip_Month.");\r\n";
		$strs.="define('Pay_Vip_Day',".$Pay_Vip_Day.");\r\n";
		$strs.="define('Pay_Card_Url','".$Pay_Card_Url."');\r\n";
		$strs.="define('Pay_Ali_ID','".$Pay_Ali_ID."');\r\n";
		$strs.="define('Pay_Ali_Pubkey','".$Pay_Ali_Pubkey."');\r\n";
		$strs.="define('Pay_Ali_Prikey','".$Pay_Ali_Prikey."');\r\n";
		$strs.="define('Pay_Ali_Mode',".$Pay_Ali_Mode.");\r\n";
		$strs.="define('Pay_QQ_ID','".$Pay_QQ_ID."');\r\n";
		$strs.="define('Pay_QQ_Key','".$Pay_QQ_Key."');\r\n";
		$strs.="define('Pay_QQ_User','".$Pay_QQ_User."');\r\n";
		$strs.="define('Pay_QQ_Mode',".$Pay_QQ_Mode.");\r\n";
		$strs.="define('Pay_Wx_ID','".$Pay_Wx_ID."');\r\n";
		$strs.="define('Pay_Wx_Key','".$Pay_Wx_Key."');\r\n";
		$strs.="define('Pay_Wx_User','".$Pay_Wx_User."');\r\n";
		$strs.="define('Pay_Wx_Mode',".$Pay_Wx_Mode.");";

        //写文件
        if(!write_file(MCCMSPATH.'libs/pay.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting','pay');
            get_json($arr,1);
		}
	}

	public function push()
	{
		$this->load->view('setting/push.tpl');
	}

	public function push_save()
	{
		$Push_Bd_Token = $this->input->post('Push_Bd_Token', TRUE);
		$Push_Xz_Appid = $this->input->post('Push_Xz_Appid', TRUE);
		$Push_Xz_Token = $this->input->post('Push_Xz_Token', TRUE);
		$Push_Sm_User = $this->input->post('Push_Sm_User', TRUE);
		$Push_Sm_Token = $this->input->post('Push_Sm_Token', TRUE);
		$Push_Host = $this->input->post('Push_Host', TRUE);
		$Push_Type = $this->input->post('Push_Type', TRUE);
		$Push_Add_Mode = (int)$this->input->post('Push_Add_Mode');
		$Push_Cj_Mode = (int)$this->input->post('Push_Cj_Mode');
		if(substr($Push_Host,-1) == '/') $Push_Host = substr($Push_Host,0,-1);
		if(!empty($Push_Host) && substr($Push_Host,0,7) !== 'http://' && substr($Push_Host,0,8) !== 'https://'){
			get_json('推送域名必须加http://或者https://前缀');
		}

		//推送方式
		if(!empty($Push_Type)){
			$tarr = array();
			foreach ($Push_Type as $k => $v) $tarr[] = $k;
			$Push_Type = $tarr;
		}

		if($Push_Add_Mode == 0 || $Push_Cj_Mode == 0){
			if(in_array('bd',$Push_Type) && empty($Push_Bd_Token)) get_json('百度推送Token不能为空');
			if(in_array('xz',$Push_Type) && empty($Push_Xz_Appid) && empty($Push_Xz_Token)) get_json('熊掌推送信息不完整');
			if(in_array('sm',$Push_Type) && empty($Push_Sm_User) && empty($Push_Sm_Token)) get_json('神马推送信息不完整');
		}
		if(!empty($Push_Type)) $Push_Type = implode('|',$Push_Type);

		$strs="<?php"."\r\n";
		$strs.="define('Push_Bd_Token','".$Push_Bd_Token."');\r\n";
		$strs.="define('Push_Xz_Appid','".$Push_Xz_Appid."');\r\n";
		$strs.="define('Push_Xz_Token','".$Push_Xz_Token."');\r\n";
		$strs.="define('Push_Sm_User','".$Push_Sm_User."');\r\n";
		$strs.="define('Push_Sm_Token','".$Push_Sm_Token."');\r\n";
		$strs.="define('Push_Host','".$Push_Host."');\r\n";
		$strs.="define('Push_Type','".$Push_Type."');\r\n";
		$strs.="define('Push_Add_Mode',".$Push_Add_Mode.");\r\n";
		$strs.="define('Push_Cj_Mode',".$Push_Cj_Mode.");";

        //写文件
        if(!write_file(MCCMSPATH.'libs/push.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('setting','push');
            get_json($arr,1);
		}
	}

	public function zyz()
	{
		$data['zyz'] = require_once FCPATH.'sys/libs/zyz.php';
		$this->load->view('setting/zyz.tpl',$data);
	}

	//保存资源站入库
	public function zyz_save()
	{
		$post = $this->input->post();
		$post['comic']['token'] = !empty($post['comic']['token']) ? explode("\n", $post['comic']['token']) : array();
		$post['book']['token'] = !empty($post['book']['token']) ? explode("\n", $post['book']['token']) : array();
		$res = arr_file_edit($post,FCPATH.'sys/libs/zyz.php');
		if(!$res) get_json('抱歉，修改失败，请检查文件写入权限~!');
		$arr['msg'] = '恭喜您，配置修改成功~！';
		$arr['url'] =  links('setting','zyz');
        get_json($arr,1);
	}

	//将路由规则生成至文件
	public function _route_file($uri) {
        $yuri = array(
			'lists' => 'lists/index/[id]/[page]',
			'show'  => 'comic/index/[id]',
			'pic'   => 'chapter/index/[mid]/[id]',
			'book_lists' => 'book/lists/[id]/[page]',
			'book_info'  => 'book/info/[id]',
			'book_read'   => 'book/read/[bid]/[id]'
	    );
		$string = '<?php'.PHP_EOL;
		$string.= 'if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';
		if($uri) {
			arsort($uri);
			foreach ($uri as $key => $val1) {
				if(substr($val1,0,1) === '/') $val1 = substr($val1,1);
				$var0 = $val1;
				$val2 = $yuri[$key];
				if($key == 'lists' ){
					$val1 = str_replace(array('[id]','[en]','[page]'),array('(\d+)','([a-zA-Z0-9\-\_]+)','(\d+)'),$val1);
					$val2 = str_replace(array('[id]','[en]','[page]'),array('$1','$1','$2'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
					$var0 = str_replace('/[page]','',$var0);
					$val3 = str_replace('/[page]','',$yuri[$key]);
					$var0 = str_replace(array('[id]','[en]','[page]'),array('(\d+)','([a-zA-Z0-9\-\_]+)','(\d+)'),$var0);
					$val3 = str_replace(array('[id]','[en]','[page]'),array('$1','$1','$2'),$val3);
				    $string.= PHP_EOL.'$route[\''.$var0.'\'] = \''.$val3.'\';';
				}elseif($key == 'show' ){
					$val1 = str_replace(array('[id]','[en]'),array('(\d+)','([a-zA-Z0-9\-\_]+)'),$val1);
					$val2 = str_replace(array('[id]','[en]'),array('$1','$1'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
				}elseif($key == 'pic' ){
					$val1 = str_replace(array('[mid]','[id]'),array('(\d+)','(\d+)'),$val1);
					$val2 = str_replace(array('[mid]','[id]'),array('$1','$2'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
				}elseif($key == 'book_lists' ){
					$val1 = str_replace(array('[id]','[en]','[page]'),array('(\d+)','([a-zA-Z0-9\-\_]+)','(\d+)'),$val1);
					$val2 = str_replace(array('[id]','[en]','[page]'),array('$1','$1','$2'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
					$var0 = str_replace('/[page]','',$var0);
					$val3 = str_replace('/[page]','',$yuri[$key]);
					$var0 = str_replace(array('[id]','[en]','[page]'),array('(\d+)','([a-zA-Z0-9\-\_]+)','(\d+)'),$var0);
					$val3 = str_replace(array('[id]','[en]','[page]'),array('$1','$1','$2'),$val3);
				    $string.= PHP_EOL.'$route[\''.$var0.'\'] = \''.$val3.'\';';
				}elseif($key == 'book_info' ){
					$val1 = str_replace(array('[id]','[en]'),array('(\d+)','([a-zA-Z0-9\-\_]+)'),$val1);
					$val2 = str_replace(array('[id]','[en]'),array('$1','$1'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
				}elseif($key == 'book_read' ){
					$val1 = str_replace(array('[bid]','[id]'),array('(\d+)','(\d+)'),$val1);
					$val2 = str_replace(array('[bid]','[id]'),array('$1','$2'),$val2);
				    $string.= PHP_EOL.'$route[\''.$val1.'\'] = \''.$val2.'\';';
				}
			}
		}
		write_file(MCCMSPATH.'libs/rewrite.php', $string);
	}
}
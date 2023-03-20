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
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Install extends Mccms_Controller {

	function __construct(){
	    parent::__construct();
        $this->load->helper('file');
		$this->load->get_templates('install');
		$uri = str_replace('index.php/', '', $_SERVER['REQUEST_URI']);
		$arr = explode('install', $uri);
		define('install_path',$arr[0]);
	}

	public function index(){
		$data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
        }else{
            $data['install'] = 'no';
            //修改配置文件
            $config = read_file(MCCMSPATH.'libs/config.php');
            $config = preg_replace("/'Web_Path','(.*?)'/","'Web_Path','".install_path."'",$config);
            if(!write_file(MCCMSPATH.'libs/config.php', $config)){
                exit('<font color = red>文件./sys/libs/config.php，没有修改权限！</font>，<a href="javascript:history.back();">返回<<</a>');
			}
        }
        $this->load->view('temp_1.html',$data);
	}

	public function save1(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
            $this->load->view('temp_2.html',$data);
        }
	}

	public function save2(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
            $this->load->view('temp_3.html',$data);
        }
	}

	public function save3(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
			$this->load->model('mcdb');
            //导入数据表
            $sql = read_file("./caches/mccms_table.sql");
            $sql = str_replace('{prefix}',Mc_SqlPrefix,$sql);
            preg_match_all('/DROP TABLE IF EXISTS `(.*?)`/',$sql,$arr);
            $data['table'] = $arr[1];
            $sqlarr = explode(";",$sql);
            for($i=0;$i<count($sqlarr);$i++){
            	if(!empty($sqlarr[$i])) $this->db->query($sqlarr[$i]);
            }
            //导入默认数据
            $sql = read_file("./caches/mccms_data.sql");
            $sql = str_replace('{prefix}',Mc_SqlPrefix,$sql);
            $sqlarr = explode(";",$sql);
            for($i=0;$i<count($sqlarr);$i++){
				if(!empty($sqlarr[$i])) $this->db->query($sqlarr[$i]);
            }
            header("location:".links('install','save32'));
        }
	}

	public function save32(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
            //导入数据表
            $sql = read_file("./caches/mccms_table.sql");
            $sql = str_replace('{prefix}',Mc_SqlPrefix,$sql);
            preg_match_all('/DROP TABLE IF EXISTS `(.*?)`/',$sql,$arr);
            $data['table'] = $arr[1];
            $this->load->view('temp_5.html',$data);
        }
	}
  
	public function save4(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
            $this->load->view('temp_6.html',$data);
        }
	}

	public function save5(){
        $data = array();
        if(file_exists(FCPATH.'caches/install.lock')){
            $data['install'] = 'ok';
            $this->load->view('temp_1.html',$data);
        }else{
            $this->load->helper('string');
            $web_name = $this->input->post('web_name');
            $web_url = $this->input->post('web_url');
            $admin_name = $this->input->post('admin_name');
            $admin_pass = $this->input->post('admin_pass');
            $admin_nichen = $this->input->post('admin_nichen');
            $admin_code = $this->input->post('admin_code');
            if(empty($web_name)||empty($web_url)||empty($admin_name)||empty($admin_pass)||empty($admin_code)){
            	exit('<font color=red>请把数据填写完整！</font>，<a href="javascript:history.back();">返回<<</a>');
            }
            //修改配置文件
            $config = read_file(MCCMSPATH.'libs/config.php');
            $config = preg_replace("/'Web_Name','(.*?)'/","'Web_Name','".$web_name."'",$config);
            $config = preg_replace("/'Web_Url','(.*?)'/","'Web_Url','".$web_url."'",$config);
            $config = preg_replace("/'Admin_Code','(.*?)'/","'Admin_Code','".$admin_code."'",$config);
            if(!write_file(MCCMSPATH.'libs/config.php', $config)){
                exit('<font color = red>文件./sys/libs/config.php，没有写入权限！</font>，<a href="javascript:history.back();">返回<<</a>');
			}
            if(!write_file('./caches/install.lock', 'mccms')){
                exit('<font color=red>目录./packs/install/，没有写入权限！</font>，<a href="javascript:history.back();">返回<<</a>');
            }
            //修改后台入口
            $adminfile = random_string('alnum',8);
            if(!rename(FCPATH.'admin.php',FCPATH.$adminfile.'.php')){
                exit('<font color=red>文件./admin.php，没有修改权限！</font>，<a href="javascript:history.back();">返回<<</a>');
            }
            //写入管理员
            $this->load->model('mcdb');
            $data['name'] = $admin_name;
            $data['pass'] = md5($admin_pass);
            $data['nichen'] = $admin_nichen;
            $res = $this->mcdb->get_insert('admin',$data);
            if(!$res) exit('<font color=red>增加管理员失败！</font>，<a href="javascript:history.back();">返回<<</a>');
            header("location:".links('install/save6').'?file='.$adminfile);
        }
	}
  
	public function save6(){
        $data['adminname'] = $this->input->get('file');
        $this->load->view('temp_7.html',$data);
    }

	public function dbtest(){
        if(file_exists(FCPATH.'caches/install.lock')){
            get_json('重新安装网站，请删除./caches/install.lock');
        }else{
            $dbdriver = rawurldecode($_GET['dbdriver']);
            $dbhost = rawurldecode($_GET['dbhost']);
            $dbuser = rawurldecode($_GET['dbuser']);
            $dbpwd = rawurldecode($_GET['dbpwd']);
            $dbname = rawurldecode($_GET['dbname']);
            $dbprefix = rawurldecode($_GET['dbprefix']);
			if(is_numeric($dbname)) get_json('数据库名不能为纯数字，请修改！');
			if(empty($dbdriver)) $dbdriver='mysql';
			if($dbdriver == 'mysqli'){
				$mysqli = new mysqli($dbhost,$dbuser,$dbpwd);
				if(mysqli_connect_errno()){
					get_json('无法连接数据库服务器，请检查配置！');
				}else{
					if(!$mysqli->select_db($dbname) && !$mysqli->query("CREATE DATABASE `".$dbname."`")) get_json('成功连接数据库，但是指定的数据库不存在并且无法自动创建，请先通过其他方式建立数据库！');
					mysqli_select_db($mysqli,$dbname);
					//修改数据库配置
					$this->load->helper('string');
					$Mc_Encryption_Key = random_string('alnum',15);
					//修改数据库配置文件
					$config = read_file(MCCMSPATH.'libs/db.php');
					$config = preg_replace("/'Mc_Sqlserver','(.*?)'/","'Mc_Sqlserver','".$dbhost."'",$config);
					$config = preg_replace("/'Mc_Sqlname','(.*?)'/","'Mc_Sqlname','".$dbname."'",$config);
					$config = preg_replace("/'Mc_Sqluid','(.*?)'/","'Mc_Sqluid','".$dbuser."'",$config);
					$config = preg_replace("/'Mc_Sqlpwd','(.*?)'/","'Mc_Sqlpwd','".$dbpwd."'",$config);
					$config = preg_replace("/'Mc_Dbdriver','(.*?)'/","'Mc_Dbdriver','".$dbdriver."'",$config);
					$config = preg_replace("/'Mc_SqlPrefix','(.*?)'/","'Mc_SqlPrefix','".$dbprefix."'",$config);
					$config = preg_replace("/'Mc_Encryption_Key','(.*?)'/","'Mc_Encryption_Key','".$Mc_Encryption_Key."'",$config);
                    if(Mc_Book_Key == ''){
                        $Mc_Book_Key = random_string('alnum',20);
                        $config = preg_replace("/'Mc_Book_Key','(.*?)'/","'Mc_Book_Key','".$Mc_Book_Key."'",$config);
                    }
					if(!write_file(MCCMSPATH.'libs/db.php', $config)) get_json('./sys/libs/db.php文件没有修改权限，请先增加权限！');
					//判断是否存在相同表
                    $res = $mysqli->query("SHOW TABLES LIKE '%".$dbprefix."comic%'");
                    $row = mysqli_fetch_array($res,MYSQLI_ASSOC);
					if($row) get_json('系统检测到数据库有数据，重新安装会删除原来的老数据！',0);
					get_json('ok',0);
				}
			}else{
				$lnk = mysql_connect($dbhost,$dbuser,$dbpwd);
				if(!$lnk) {
					get_json('无法连接数据库服务器，请检查配置！');
				}else{
				   	if(!mysql_select_db($dbname) && !mysql_query("CREATE DATABASE `".$dbname."`")) get_json('成功连接数据库，但是指定的数据库不存在并且无法自动创建，请先通过其他方式建立数据库！');
				   	//修改数据库配置
				   	if(mysql_select_db($dbname)){
						$this->load->helper('string');
						$Mc_Encryption_Key = random_string('alnum',15);
						//修改数据库配置文件
						$config = read_file(MCCMSPATH.'libs/db.php');
						$config = preg_replace("/'Mc_Sqlserver','(.*?)'/","'Mc_Sqlserver','".$dbhost."'",$config);
						$config = preg_replace("/'Mc_Sqlname','(.*?)'/","'Mc_Sqlname','".$dbname."'",$config);
						$config = preg_replace("/'Mc_Sqluid','(.*?)'/","'Mc_Sqluid','".$dbuser."'",$config);
						$config = preg_replace("/'Mc_Sqlpwd','(.*?)'/","'Mc_Sqlpwd','".$dbpwd."'",$config);
						$config = preg_replace("/'Mc_Dbdriver','(.*?)'/","'Mc_Dbdriver','".$dbdriver."'",$config);
						$config = preg_replace("/'Mc_SqlPrefix','(.*?)'/","'Mc_SqlPrefix','".$dbprefix."'",$config);
						$config = preg_replace("/'Mc_Encryption_Key','(.*?)'/","'Mc_Encryption_Key','".$Mc_Encryption_Key."'",$config);
                        if(Mc_Book_Key == ''){
                            $Mc_Book_Key = random_string('alnum',20);
                            $config = preg_replace("/'Mc_Book_Key','(.*?)'/","'Mc_Book_Key','".$Mc_Book_Key."'",$config);
                        }
						if(!write_file(MCCMSPATH.'libs/db.php', $config)) get_json('./sys/libs/db.php文件没有修改权限，请先增加权限！');
				   	}
				   	//判断是否存在相同表
				   	if(mysql_query("SHOW TABLES LIKE '%".$dbdriver."comic%'")) get_json('系统检测到数据库有数据，重新安装会删除原来的老数据！',0);
				   	get_json('ok',0);
				}
			}
        }
	}
}
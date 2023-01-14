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

class Backups extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('directory');
	}
	//数据备份
	public function index(){
		$array = $this->db->query("SHOW TABLE STATUS FROM `".Mc_Sqlname."`")->result_array();
		$table = array();
		foreach($array as $k=>$row){
			$table[$k]['name'] = $row['Name'];
			$table[$k]['text'] = $row['Comment'];
			$table[$k]['type'] = $row['Engine'];
			$table[$k]['rows'] = $row['Rows'];
			$table[$k]['size'] = formatsize($row['Data_length']);
			$table[$k]['free'] = formatsize($row['Data_free']);
			$table[$k]['cmd'] = '<a class="layui-btn layui-btn-xs" href="javascript:get_optimize(\''.$row['Name'].'\');">优化</a><a class="layui-btn layui-btn-xs layui-btn-danger" href="javascript:get_repair(\''.$row['Name'].'\');">修复</a><a class="layui-btn layui-btn-xs layui-btn-normal" href="javascript:Admin.open(\'数据结构\',\''.links('backups','fileds').'?table='.$row['Name'].'\',500);">结构</a>';
		}
		$data['table'] = $table;
		$this->load->view('sys/backups.tpl',$data);
	}
	//数据还原
	public function restore(){
        $arr = get_dir_file_info(FCPATH.'caches/backup/');
		$dir = array();
		$i = 0;
        foreach ($arr as $k=>$row){
        	$dirs = get_dir_file_info(FCPATH.'caches/backup/'.$k);
        	$size = $row['size'];
        	$rows = 0;
        	foreach ($dirs as $key => $v) {
        		$size = $size+$v['size'];
        		$rows++;
        	}
        	$dir[$i]['name'] = $row['name'];
        	$dir[$i]['dname'] = sys_auth($row['name']);
        	$dir[$i]['size'] = formatsize($size);
        	$dir[$i]['rows'] = $rows;
        	$dir[$i]['time'] = date('Y-m-d H:i:s',$row['date']);
        	$dir[$i]['cmd'] = '<a class="layui-btn layui-btn-xs" href="'.links('backups','zip').'?dir='.sys_auth($row['name']).'">打包下载</a><a data-dir="'.sys_auth($row['name']).'" class="layui-btn layui-btn-xs layui-btn-normal get_restore" href="javascript:;">还原数据</a>';
        	$i++;
        }
		$data['dir'] = $dir;
		$this->load->view('sys/backups_restore.tpl',$data);
	}
	//优化表
	public function optimize(){
		$table = $this->input->get_post('table',true);
		if(empty($table)) get_json('请选择要优化的数据表');
		if(is_array($table)){
			foreach ($table as $key => $value) {
				$this->dbutil->optimize_table($value);
			}
		}else{
			$this->dbutil->optimize_table($table);
		}
		get_json('优化完成',1);
	}
	//修复表
	public function repair(){
		$table = $this->input->get_post('table',true);
		if(empty($table)) get_json('请选择要修复的数据表');
		if(is_array($table)){
			foreach ($table as $key => $value) {
				$this->dbutil->repair_table($value);
			}
		}else{
			$this->dbutil->repair_table($table);
		}
		get_json('修复完成',1);
	}
	//清空表
	public function truncate(){
		$table = $this->input->get_post('table',true);
		if(empty($table)) get_json('请选择要修复的数据表');
		foreach ($table as $key => $value) {
			if($value != Mc_SqlPrefix.'admin' && $value != Mc_SqlPrefix.'type'){
				$this->db->query('truncate table '.$value);
			}
		}
		get_json('修复完成',1);
	}
	//数据结构
	public function fileds(){
		$table = $this->input->get_post('table',true);
		if(empty($table)) exit('请选择要查看的数据表');
        $output = "";
	    $i = 0;
	    $result = $this->db->query("SHOW CREATE TABLE `".$this->db->database.'`.`'.$table.'`')->result_array();
	    foreach ($result[0] as $val){
		    if ($i++ % 2){
			    $output .= $val.';';
		    }
		}
		$data['table'] = $output;
		echo '<pre class="layui-code" style="min-height:350px;margin:0px;border:0px" lay-encode="MySql">'.str_replace("\n\n","\n",$output).'</pre>';
	}
	//数据备份
	public function beifen(){
		$tarr= $this->input->get_post('table',true);
		$n = (int)$this->input->get_post('n');
		$p = (int)$this->input->get_post('p');
		if($p == 0) $p = 1;
		if(empty($tarr)) get_json('请选择要备份的数据表');
		$num = count($tarr);
		if($n > $num) get_json('恭喜您，全部备份完成!!!',1);
		//创建备份目录
		$backup_dir = FCPATH.'caches/backup/Mccms-'.date('Ymd').'/';
		mkdirss($backup_dir);
		//备份表结构
		if($n == 0){
			$table_arr = array();
			foreach ($tarr as $key => $value) {
				$result = $this->db->query("SHOW CREATE TABLE `".$this->db->database.'`.`'.$value.'`')->result_array();
				$table_arr[] = 'DROP TABLE IF EXISTS '.$value.';';
				$table_arr[] = $result[0]['Create Table'].';';
			}
			$backup_file = 'mccms_table.php';
			$res = arr_file_edit($table_arr,$backup_dir.$backup_file);
			if($res){
				get_json('恭喜您,数据表结构备份完成，请稍后...',2);
			}else{
				get_json('数据备份失败，请检查./cache/backup目录是否有修改权限');
			}
		}else{
			$n--;
			$table = $tarr[$n];
			//获取数据总数
			$limit = $this->mcdb->get_nums($table);
			if($limit == 0){
				$marr['n'] = $n+2;
				$marr['p'] = 1;
				$marr['msg'] = '《'.$table.'》表没有记录跳过，请稍后...';
				get_json($marr,2);
			}
			$pagejs = 1;
			if($limit > 2000){
				$pagejs = ceil($limit / 2000);
				$limit = 2000*($p-1).',2000';
			}
			$result = $this->db->query('SELECT * FROM '.$table.' order by id asc limit '.$limit)->result();
			$data_arr = array();
			foreach ($result as $key => $row) {
				$filed = array();
				foreach ($row as $k => $v){
					$filed[] = "`".$k."`='".safe_replace($v)."'";
				}
				$data_arr[] = "INSERT INTO `$table` SET ".implode(',',$filed).";";
			}
			$backup_path = $backup_dir.$table.'_'.$p.'.php';
			$res = arr_file_edit($data_arr,$backup_path);
			if($res){
				if($pagejs >= $p){
					$marr['n'] = $n+2;
					$marr['p'] = 1;
					$marr['msg'] = '恭喜您,《'.$table.'》表备份完成，请稍后...';
				}else{
					$marr['n'] = $n+1;
					$marr['p'] = $p+1;
					$marr['msg'] = '《'.$table.'》太大，需要分次备份，当前备份第'.$p.'页，请稍后...';
				}
				$marr['code'] = 2;
				get_json($marr);
			}else{
				get_json('数据表《'.$table.'》备份失败');
			}
		}
	}
	//打包下载
	public function zip(){
		$dir = sys_auth($this->input->get('dir',true),1);
		if(empty($dir)) exit('请选择要下载的备份!');
		$dir_path = './caches/backup/'.$dir.'/';
		if(!is_dir($dir_path)) exit('备份文件不存在!');

		$this->load->library('zip');
		$this->zip->read_dir($dir_path, FALSE);
		$this->zip->download($dir.'-backup.zip');
	}
	//删除备份目录
	public function restore_del(){
		$dirs = $this->input->post('dirs',true);
		if(empty($dirs)) get_json('请选择要删除的备份!');
		foreach ($dirs as $key => $dir) {
			$dir = sys_auth($dir,1);
			if(!empty($dir)){
				delete_files('./caches/backup/'.$dir.'/', TRUE);
				//删除本身目录
				rmdir('./caches/backup/'.$dir.'/');
			}
		}
		get_json('删除成功',1);
	}
	//还原备份目录
	public function restore_save(){
		$dirs = $this->input->post('dir',true);
		$dir = sys_auth($dirs,1);
		$table = $this->input->post('table',true);
		if(empty($dir)) get_json('请选择要还原的备份!');

		$dir_path = FCPATH.'caches/backup/'.$dir.'/';
		if(!is_dir($dir_path)) get_json('备份目录不存在!');
		if(!file_exists($dir_path.'mccms_table.php')) get_json('数据结构备份文件不存在!');

		//获取所有备份文件
		$file_arr = get_filenames($dir_path);
		//判断还原表还是数据结构
		if(empty($table)){
			//还原表结构
			$array = require $dir_path.'mccms_table.php';
			foreach ($array as $key => $sql) {
				$this->db->query($sql);
			}
			//获取文件
			foreach ($file_arr as $k => $v) {
				if($v != 'mccms_table.php'){
					$table = str_replace('.php','',$v);
					break;
				}
			}
			if(empty($table)) get_json('数据全部还原成功!!!',1);
			get_json(array('msg'=>'数据表结构还原完成，请稍后...','dir'=>$dirs,'table'=>$table),1);
		}else{
			if(!file_exists($dir_path.$table.'.php')) get_json('表《'.$table.'》备份文件不存在!');
			//还原表数据
			$array = require $dir_path.$table.'.php';
			foreach ($array as $key => $sql) {
				$this->db->query($sql);
			}
			$keys = array_keys($file_arr,$table.'.php',true);
			$i = $keys[0]+1;
			if(!isset($file_arr[$i])){
				get_json('数据全部还原成功!!!',1);
			}else{
				$table = str_replace('.php','',$file_arr[$i]);
				get_json(array('msg'=>'数据表《'.$table.'》还原完成，请稍后...','dir'=>$dirs,'table'=>$table),2);
			}
		}
	}
}
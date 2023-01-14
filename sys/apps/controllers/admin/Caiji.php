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

class Caiji extends Mccms_Controller {
	
	function __construct(){
	    parent::__construct();
		//判断是否登陆
		$this->admin->login();
		$this->load->model('collect');
	}

	//采集配置
	public function setting($type='comic')
	{
		$tpl = $type == 'book' ? 'setting_book' : 'setting';
		$data['type'] = $type;
		$this->load->view('caiji/'.$tpl.'.tpl',$data);
	}

	//采集配置修改
	public function save()
	{
		$Caiji_Sh = (int)$this->input->post('Caiji_Sh');
		$Caiji_Pic = (int)$this->input->post('Caiji_Pic');
		$Caiji_Time = (int)$this->input->post('Caiji_Time');
		$Caiji_Chapter = (int)$this->input->post('Caiji_Chapter');
		$Caiji_Up = (int)$this->input->post('Caiji_Up');
		$Caiji_Hits = (int)$this->input->post('Caiji_Hits');
		$Caiji_Inspect = $this->input->post('Caiji_Inspect',true);
		$Caiji_Upzd = $this->input->post('Caiji_Upzd',true);
		$Caiji_Hits_Ks = (int)$this->input->post('Caiji_Hits_Ks');
		$Caiji_Hits_Js = (int)$this->input->post('Caiji_Hits_Js');
		$Caiji_Replace_name = $this->input->post('Caiji_Replace_name',true);
		$Caiji_Filter_name = $this->input->post('Caiji_Filter_name',true);
		$Caiji_Tb_Chapter = (int)$this->input->post('Caiji_Tb_Chapter');
		$Caiji_Tb_Pic = (int)$this->input->post('Caiji_Tb_Pic');
		$Caiji_Tb_Url = $this->input->post('Caiji_Tb_Url',true);
		$Caiji_Tb_Token = $this->input->post('Caiji_Tb_Token',true);
		if($Caiji_Hits_Js <= $Caiji_Hits_Ks) get_json('人气范围结束应该大于起始的值');

		$carr = array();
		foreach ($Caiji_Inspect as $k => $v) $carr[] = $k;
		$Caiji_Inspect = implode('|', $carr);

		$parr = array();
		foreach ($Caiji_Upzd as $k => $v) $parr[] = $k;
		$Caiji_Upzd = implode('|', $parr);

		$strs="<?php"."\r\n";
		$strs.="define('Caiji_Sh',".$Caiji_Sh.");\r\n";
		$strs.="define('Caiji_Pic',".$Caiji_Pic.");\r\n";
		$strs.="define('Caiji_Time',".$Caiji_Time.");\r\n";
		$strs.="define('Caiji_Chapter',".$Caiji_Chapter.");\r\n";
		$strs.="define('Caiji_Up',".$Caiji_Up.");\r\n";
		$strs.="define('Caiji_Hits',".$Caiji_Hits.");\r\n";
		$strs.="define('Caiji_Inspect','".$Caiji_Inspect."');\r\n";
		$strs.="define('Caiji_Upzd','".$Caiji_Upzd."');\r\n";
		$strs.="define('Caiji_Hits_Ks',".$Caiji_Hits_Ks.");\r\n";
		$strs.="define('Caiji_Hits_Js',".$Caiji_Hits_Js.");\r\n";
		$strs.="define('Caiji_Replace_name','".$Caiji_Replace_name."');\r\n";
		$strs.="define('Caiji_Filter_name','".$Caiji_Filter_name."');\r\n";
		$strs.="define('Caiji_Tb_Chapter',".$Caiji_Tb_Chapter.");\r\n";
		$strs.="define('Caiji_Tb_Pic',".$Caiji_Tb_Pic.");\r\n";
		$strs.="define('Caiji_Tb_Url','".$Caiji_Tb_Url."');\r\n";
		$strs.="define('Caiji_Tb_Token','".$Caiji_Tb_Token."');\r\n\r\n";

		$strs.="define('Book_Caiji_Sh',".(defined('Book_Caiji_Sh') ? Book_Caiji_Sh : 0).");\r\n";
		$strs.="define('Book_Caiji_Pic',".(defined('Book_Caiji_Pic') ? Book_Caiji_Pic : 0).");\r\n";
		$strs.="define('Book_Caiji_Time',".(defined('Book_Caiji_Time') ? Book_Caiji_Time : 0).");\r\n";
		$strs.="define('Book_Caiji_Chapter',".(defined('Book_Caiji_Chapter') ? Book_Caiji_Chapter : 1).");\r\n";
		$strs.="define('Book_Caiji_Up',".(defined('Book_Caiji_Up') ? Book_Caiji_Up : 1).");\r\n";
		$strs.="define('Book_Caiji_Hits',".(defined('Book_Caiji_Hits') ? Book_Caiji_Hits : 1).");\r\n";
		$strs.="define('Book_Caiji_Inspect','".(defined('Book_Caiji_Inspect') ? Book_Caiji_Inspect : 'name|cid')."');\r\n";
		$strs.="define('Book_Caiji_Upzd','".(defined('Book_Caiji_Upzd') ? Book_Caiji_Upzd : 'chapter|serialize|addtime')."');\r\n";
		$strs.="define('Book_Caiji_Hits_Ks',".(defined('Book_Caiji_Hits_Ks') ? Book_Caiji_Hits_Ks : 1000).");\r\n";
		$strs.="define('Book_Caiji_Hits_Js',".(defined('Book_Caiji_Hits_Js') ? Book_Caiji_Hits_Js : 99999).");\r\n";
		$strs.="define('Book_Caiji_Replace_name','".(defined('Book_Caiji_Replace_name') ? Book_Caiji_Replace_name : '')."');\r\n";
		$strs.="define('Book_Caiji_Filter_name','".(defined('Book_Caiji_Filter_name') ? Book_Caiji_Filter_name : '')."');\r\n";
		$strs.="define('Book_Caiji_Tb_Chapter',".(defined('Book_Caiji_Tb_Chapter') ? Book_Caiji_Tb_Chapter : 0).");\r\n";
		$strs.="define('Book_Caiji_Tb_Txt',".(defined('Book_Caiji_Tb_Txt') ? Book_Caiji_Tb_Txt : 1).");\r\n";
		$strs.="define('Book_Caiji_Tb_Url','".(defined('Book_Caiji_Tb_Url') ? Book_Caiji_Tb_Url : 'http://211.149.130.175:12359/book/api')."');\r\n";
		$strs.="define('Book_Caiji_Tb_Token','".(defined('Book_Caiji_Tb_Token') ? Book_Caiji_Tb_Token : '')."');";
        //写文件
        if (!write_file(MCCMSPATH.'libs/caiji.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('caiji','setting');
            get_json($arr,1);
		}
	}

	//采集配置修改
	public function book_save()
	{
		$Caiji_Sh = (int)$this->input->post('Book_Caiji_Sh');
		$Caiji_Pic = (int)$this->input->post('Book_Caiji_Pic');
		$Caiji_Time = (int)$this->input->post('Book_Caiji_Time');
		$Caiji_Chapter = (int)$this->input->post('Book_Caiji_Chapter');
		$Caiji_Up = (int)$this->input->post('Book_Caiji_Up');
		$Caiji_Hits = (int)$this->input->post('Book_Caiji_Hits');
		$Caiji_Inspect = $this->input->post('Book_Caiji_Inspect',true);
		$Caiji_Upzd = $this->input->post('Book_Caiji_Upzd',true);
		$Caiji_Hits_Ks = (int)$this->input->post('Book_Caiji_Hits_Ks');
		$Caiji_Hits_Js = (int)$this->input->post('Book_Caiji_Hits_Js');
		$Caiji_Replace_name = $this->input->post('Book_Caiji_Replace_name',true);
		$Caiji_Filter_name = $this->input->post('Book_Caiji_Filter_name',true);
		$Caiji_Tb_Chapter = (int)$this->input->post('Book_Caiji_Tb_Chapter');
		$Caiji_Tb_Txt = (int)$this->input->post('Book_Caiji_Tb_Txt');
		$Caiji_Tb_Url = $this->input->post('Book_Caiji_Tb_Url',true);
		$Caiji_Tb_Token = $this->input->post('Book_Caiji_Tb_Token',true);
		if($Caiji_Hits_Js <= $Caiji_Hits_Ks) get_json('人气范围结束应该大于起始的值');

		$carr = array();
		foreach ($Caiji_Inspect as $k => $v) $carr[] = $k;
		$Caiji_Inspect = implode('|', $carr);

		$parr = array();
		foreach ($Caiji_Upzd as $k => $v) $parr[] = $k;
		$Caiji_Upzd = implode('|', $parr);

		$strs="<?php"."\r\n";
		$strs.="define('Caiji_Sh',".Caiji_Sh.");\r\n";
		$strs.="define('Caiji_Pic',".Caiji_Pic.");\r\n";
		$strs.="define('Caiji_Time',".Caiji_Time.");\r\n";
		$strs.="define('Caiji_Chapter',".Caiji_Chapter.");\r\n";
		$strs.="define('Caiji_Up',".Caiji_Up.");\r\n";
		$strs.="define('Caiji_Hits',".Caiji_Hits.");\r\n";
		$strs.="define('Caiji_Inspect','".Caiji_Inspect."');\r\n";
		$strs.="define('Caiji_Upzd','".Caiji_Upzd."');\r\n";
		$strs.="define('Caiji_Hits_Ks',".Caiji_Hits_Ks.");\r\n";
		$strs.="define('Caiji_Hits_Js',".Caiji_Hits_Js.");\r\n";
		$strs.="define('Caiji_Replace_name','".Caiji_Replace_name."');\r\n";
		$strs.="define('Caiji_Filter_name','".Caiji_Filter_name."');\r\n";
		$strs.="define('Caiji_Tb_Chapter',".Caiji_Tb_Chapter.");\r\n";
		$strs.="define('Caiji_Tb_Pic',".Caiji_Tb_Pic.");\r\n";
		$strs.="define('Caiji_Tb_Url','".Caiji_Tb_Url."');\r\n";
		$strs.="define('Caiji_Tb_Token','".Caiji_Tb_Token."');\r\n\r\n";

		$strs.="define('Book_Caiji_Sh',".$Caiji_Sh.");\r\n";
		$strs.="define('Book_Caiji_Pic',".$Caiji_Pic.");\r\n";
		$strs.="define('Book_Caiji_Time',".$Caiji_Time.");\r\n";
		$strs.="define('Book_Caiji_Chapter',".$Caiji_Chapter.");\r\n";
		$strs.="define('Book_Caiji_Up',".$Caiji_Up.");\r\n";
		$strs.="define('Book_Caiji_Hits',".$Caiji_Hits.");\r\n";
		$strs.="define('Book_Caiji_Inspect','".$Caiji_Inspect."');\r\n";
		$strs.="define('Book_Caiji_Upzd','".$Caiji_Upzd."');\r\n";
		$strs.="define('Book_Caiji_Hits_Ks',".$Caiji_Hits_Ks.");\r\n";
		$strs.="define('Book_Caiji_Hits_Js',".$Caiji_Hits_Js.");\r\n";
		$strs.="define('Book_Caiji_Replace_name','".$Caiji_Replace_name."');\r\n";
		$strs.="define('Book_Caiji_Filter_name','".$Caiji_Filter_name."');\r\n";
		$strs.="define('Book_Caiji_Tb_Chapter',".$Caiji_Tb_Chapter.");\r\n";
		$strs.="define('Book_Caiji_Tb_Txt',".$Caiji_Tb_Txt.");\r\n";
		$strs.="define('Book_Caiji_Tb_Url','".$Caiji_Tb_Url."');\r\n";
		$strs.="define('Book_Caiji_Tb_Token','".$Caiji_Tb_Token."');";

        //写文件
        if (!write_file(MCCMSPATH.'libs/caiji.php', $strs)){
            get_json('抱歉，修改失败，请检查文件写入权限~!');
		}else{
			$arr['msg'] = '恭喜您，配置修改成功~！';
			$arr['url'] =  links('caiji','setting','book');
            get_json($arr,1);
		}
	}

	//资源库
	public function index($type='comic')
	{
		$data['type'] = $type;
		$this->load->view('caiji/index.tpl',$data);
	}

	//链接json
	public function json($type='comic')
	{
		$zyk = require MCCMSPATH.'libs/collect.php';
		$zykurl = $type == 'book' ? Zykbookurl : Zykurl;
		$json = getcurl(sys_auth($zykurl,1,'mccms_zyk'));
		$array = json_decode($json,1);
		if(empty($array)) $array = array();
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		if(!empty($zyk[$zd])) $array = array_merge($array,$zyk[$zd]);
		$arr = array();
		$i = 0;
		foreach ($array as $k => $v) {
			$id = $i+1;
			$cmd = '<a style="margin-right: 5px;" href="'.links('caiji','show',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'" title="浏览资源库"><span class="layui-btn layui-btn-xs" style="background-color: #ff7600;"><i class="layui-icon">&#xe615;</i>浏览</span></a><a href="'.links('caiji','daochu',$type).'?ly='.$k.'"><span class="layui-btn layui-btn-xs layui-btn-normal"><i class="layui-icon">&#xe601;</i>导出</span></a>';
			if(substr($k,0,6) != 'mccms_'){
				$cmd = '<a style="margin-right: 5px;" href="'.links('caiji','show',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'" title="浏览资源库"><span class="layui-btn layui-btn-xs" style="background-color: #ff7600;"><i class="layui-icon">&#xe615;</i></span></a><a style="margin-right: 5px;" href="'.links('caiji','daochu',$type).'?ly='.$k.'"><span class="layui-btn layui-btn-xs layui-btn-normal" title="导出"><i class="layui-icon">&#xe601;</i></span></a><a style="margin-right: 5px;" href="javascript:;" onclick="Admin.open(\'修改资源库\',\''.links('caiji','edit/'.$type,$k).'\',600,550);" title="修改"><span class="layui-btn layui-btn-xs"><i class="layui-icon">&#xe642;</i></span></a><a href="javascript:;" onclick="get_del(\''.$k.'\',\''.$type.'\',this);" title="删除"><span class="layui-btn layui-btn-xs layui-btn-danger" title="删除"><i class="layui-icon">&#xe640;</i></span></a>';
			}
			$arr[$i] = array(
				'id' => $id< 10 ? '0'.$id : $id,
				'name' => '<a onclick="layer.load();" href="'.links('caiji','show',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'">'.$v['name'].'</a>',
				'text' => '<a onclick="layer.load();" href="'.links('caiji','show',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'">'.$v['text'].'</a>',
				'zt' => $v['zt']==0 ? '<span class="layui-btn layui-btn-xs" onclick="get_zt(0,\''.$k.'\',\''.$type.'\');">开启</span>' : '<span class="layui-btn layui-btn-xs layui-btn-danger" onclick="get_zt(1,\''.$k.'\',\''.$type.'\');">关闭</span>',
				'day' => '<a onclick="layer.load();" href="'.links('caiji','ruku',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'&day=1">采集当天</a>',
				'week'=> '<a onclick="layer.load();" href="'.links('caiji','ruku',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'&day=7">采集本周</a>',
				'all' => '<a onclick="layer.load();" href="'.links('caiji','ruku',$type).'?ly='.$k.'&apiurl='.urlencode($v['url']).'">采集所有</a>',
				'cmd' => $cmd
			);
			$i++;
		}
		$data['data'] = $arr;
		get_json($data,0);
	}

	//资源开关
	public function init($type='comic'){
		$zt = (int)$this->input->post('zt');
		$ly = $this->input->post('ly',true);
		if(empty($ly)) get_json('参数错误!');
		if(substr($ly,0,6) == 'mccms_') get_json('该资源无法停用');
		$zyk = require MCCMSPATH.'libs/collect.php';
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		if(!isset($zyk[$zd][$ly])) get_json('资源不存在');
		$zyk[$zd][$ly]['zt'] = $zt == 0 ? 1 : 0;
		$res = arr_file_edit($zyk,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('设置失败，文件没有权限修改');
		$msg = $zt == 0 ? '资源关闭成功':'资源开启成功';
		get_json($msg,1);
	}

	//资源删除
	public function del($type='comic'){
		$ly = $this->input->get_post('ly',true);
		if(empty($ly)) get_json('参数错误!');
		$arr = require MCCMSPATH.'libs/collect.php';
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		$zyk = array();
		foreach ($arr[$zd] as $k => $v) {
			if($k != $ly) $zyk[$k] = $v;
		}
		$arr[$zd] = $zyk;
		$res = arr_file_edit($arr,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('删除失败，请重试!');
		get_json('资源删除成功',1);
	}

	//资源导出
	public function daochu($type='comic'){
		$ly = $this->input->get_post('ly',true);
		if(substr($ly,0,6) == 'mccms_'){
			$zyurl = $type == 'book' ? Zykbookurl : Zykurl;
			$json = getcurl(sys_auth($zyurl,1,'mccms_zyk'));
			$zyk = json_decode($json,1);
		}else{
			$arr = require MCCMSPATH.'libs/collect.php';
			$zd = $type == 'book' ? 'book_zyk' : 'zyk';
			$zyk = $arr[$zd];
		}
		$newzy = array();
		$newzy[$ly] = $zyk[$ly];
		$data = base64encode(json_encode($newzy));
		$this->load->helper('download');
		force_download('Mccms_collect_'.$type.'_'.time().'.txt', $data);
	}

	//资源导入
	public function uptxt($type='comic'){
		if(empty($_FILES['file'])) get_json('没发现上传文件!!!');
		$tempFile = $_FILES['file']['tmp_name'];
		$str = file_get_contents($tempFile);
		$json = base64decode($str);
		$arr = json_decode($json,1);
		$ly = key($arr);
		if(empty($ly) || empty($arr[$ly]['name']) || empty($arr[$ly]['url']) || !isset($arr[$ly]['zt']) || !isset($arr[$ly]['text'])){
			get_json('资源内容格式错误!!!');
		}
		$zyarr = require MCCMSPATH.'libs/collect.php';
		$xly = str_replace('mccms_','',$ly);
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		if(isset($zyarr[$zd][$ly])) get_json('该资源已经存在，不能重复导入!');
		$zyarr[$zd][$xly] = $arr[$ly];
		$res = arr_file_edit($zyarr,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('导入失败，请重试!');
		get_json('资源导入成功',0);
	}

	//资源库修改、增加
	public function edit($type='comic',$ly='')
	{
		$data = array(
			'name' => '',
			'url' => '',
			'jxurl' => '',
			'token' => '',
			'zt' => 0,
			'text' => ''
		);
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		if(!empty($ly)){
			$arr = require MCCMSPATH.'libs/collect.php';
			if(isset($arr[$zd][$ly])){
				$data = $arr[$zd][$ly];
			}
		}
		$data['ly'] = $ly;
		$data['type'] = $type;
		$this->load->view('caiji/edit.tpl',$data);
	}
	//资源修改入库
	public function zysave($type='comic'){
		$ly = $this->input->get_post('ly',true);
		$name = $this->input->get_post('name',true);
		$url = $this->input->get_post('url',true);
		$jxurl = $this->input->get_post('jxurl',true);
		$token = $this->input->get_post('token',true);
		$zt = (int)$this->input->get_post('zt');
		$text = $this->input->get_post('text',true);
		if(empty($ly)) get_json('资源唯一标示不能为空');
		if(empty($name)) get_json('资源名称不能为空');
		if(empty($url)) get_json('资源接口地址不能为空');
		if(empty($jxurl)) get_json('资源接口解析地址不能为空');

		$arr = require MCCMSPATH.'libs/collect.php';
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		$arr[$zd][$ly] = array(
			'name' => $name,
			'url' => $url,
			'jxurl' => $jxurl,
			'token' => $token,
			'zt' => $zt,
			'text' => $text
		);
		$res = arr_file_edit($arr,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('操作失败，请重试!');
		get_json(array('msg'=>'资源操作成功','parent'=>1),1);
	}

	//资源详情
	public function show($type='comic'){
		$apiurl = $this->input->get_post('apiurl',true);
		$ly = $this->input->get_post('ly',true);
		$key = $this->input->get_post('key',true);
		$cid = (int)$this->input->get_post('cid');
		$page = (int)$this->input->get_post('page');
		$zyurl = strpos($apiurl,'://') === false ? sys_auth($apiurl,1,'mccms_zyk') : $apiurl;
		$json = getcurl($zyurl.'?cid='.$cid.'&key='.$key.'&page='.$page.'&token='.Caiji_Tb_Token);
		$zyarr = json_decode($json,1);
		$data['cid'] = $cid;
		$data['ly'] = $ly;
		$data['key'] = $key;
		$data['page'] = $page;
		$data['apiurl'] = $apiurl;
		$data['zyarr'] = $zyarr;
		$data['type'] = $type;
		//站内分类
		$table = $type == 'comic' ? 'class' : 'book_class';
		$data['class'] = $this->mcdb->get_select($table,'id,name','','xid ASC',100);
		$this->load->view('caiji/show.tpl',$data);
	}

	//资源分类绑定
	public function bind(){
		$op = $this->input->get_post('op',true);
		$ly = $this->input->get_post('ly',true);
		$cid = (int)$this->input->get_post('cid');
		$zycid = (int)$this->input->get_post('zycid');
		if($op == 'delall'){
			$res = get_zyk_class('delall',$ly);
		}elseif($cid == 0){
			$res = get_zyk_class('del',$ly,$zycid);
		}else{
			$res = get_zyk_class('set',$ly,$zycid,$cid);
		}
		if(!$res) get_json('绑定失败，请重试!');
		get_json('分类绑定成功',1);
	}
	//定时任务
	public function timming($type = 'comic'){
		$zykarr = require MCCMSPATH.'libs/collect.php';
		$data['timming'] = $type == 'book' ? $zykarr['timming_book'] : $zykarr['timming'];
		$data['type'] = $type;
		$this->load->view('caiji/timming.tpl',$data);
	}
	//定时修改
	public function timming_edit($type = 'comic',$ly = ''){
		$arr = require MCCMSPATH.'libs/collect.php';
		$this->load->helper('string');
		$data = array(
			'name' => '',
			'day' => 0,
			'zt' => 0,
			'i' => 3600,
			'html' => 0,
			'pass' => random_string('alnum',16)
		);
		$data['op'] = 'add';
		if(!empty($ly)){
			$azd = $type == 'book' ? 'timming_book' : 'timming';
			if(isset($arr[$azd][$ly])){
				$data = $arr[$azd][$ly];
			}
			$data['op'] = 'edit';
		}
		$data['ly'] = $ly;
		$zykurl = $type == 'book' ? Zykbookurl : Zykurl;
		$json = getcurl(sys_auth($zykurl,1,'mccms_zyk'));
		$garr = json_decode($json,1);
		$zd = $type == 'book' ? 'book_zyk' : 'zyk';
		if(!empty($arr[$zd])) $garr = array_merge($garr,$arr[$zd]);
		$data['zyk'] = $garr;
		$data['type'] = $type;
		$this->load->view('caiji/timming_edit.tpl',$data);
	}
	//定时任务修改入库
	public function timming_save($op='',$type='comic'){
		$ly = $this->input->get_post('ly',true);
		$name = $this->input->get_post('name',true);
		$pass = $this->input->get_post('pass',true);
		$zt = (int)$this->input->get_post('zt');
		$i = (int)$this->input->get_post('i');
		$html = (int)$this->input->get_post('html');
		$day = (int)$this->input->get_post('day');
		if(empty($ly)) get_json('请选择任务资源');
		if(empty($name)) get_json('任务名称不能为空');
		if(empty($pass)) get_json('任务密码不能为空');

		$arr = require MCCMSPATH.'libs/collect.php';
		if(substr($ly,0,6) == 'mccms_'){
			$zyurl = $type == 'book' ? Zykbookurl : Zykurl;
			$garr = json_decode(getcurl(sys_auth($zyurl,1,'mccms_zyk')),1);
			$url = $garr[$ly]['url'];
		}else{
			$zd = $type == 'book' ? 'book_zyk' : 'zyk';
			$url = $arr[$zd][$ly]['url'];
		}
		$azd = $type == 'book' ? 'timming_book' : 'timming';
		if($op == 'add' && isset($arr[$azd][$ly])){
			get_json('该资源的任务已经存在，不能重复增加!');
		}
		$arr[$azd][$ly] = array(
			'name' => $name,
			'url' => $url,
			'time' => '未执行',
		    'zt' => $zt,
		    'day' => $day,
		    'i' => $i,
		    'html' => $html,
		    'pass' => $pass
		);
		$res = arr_file_edit($arr,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('操作失败，请重试!');
		get_json(array('msg'=>'任务操作成功','parent'=>1),1);
	}
	//定时任务开关
	public function timming_init($type = 'comic'){
		$zt = (int)$this->input->post('zt');
		$ly = $this->input->post('ly',true);
		if(empty($ly)) get_json('参数错误!');
		$zyk = require MCCMSPATH.'libs/collect.php';
		$zd = $type == 'book' ? 'timming_book' : 'timming';
		if(!isset($zyk[$zd][$ly])) get_json('资源不存在');
		$zyk[$zd][$ly]['zt'] = $zt == 0 ? 1 : 0;
		$res = arr_file_edit($zyk,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('设置失败，文件没有权限修改');
		$msg = $zt == 0 ? '任务关闭成功':'任务开启成功';
		get_json($msg,1);
	}
	//定时任务删除
	public function timming_del($type = 'comic'){
		$ly = $this->input->get_post('ly',true);
		if(empty($ly)) get_json('参数错误!');
		$arr = require MCCMSPATH.'libs/collect.php';
		$zyk = array();
		$azd = $type == 'book' ? 'timming_book' : 'timming';
		foreach ($arr[$azd] as $k => $v) {
			if($k != $ly) $zyk[$k] = $v;
		}
		$arr[$azd] = $zyk;
		$res = arr_file_edit($arr,MCCMSPATH.'libs/collect.php');
		if(!$res) get_json('删除失败，请重试!');
		get_json('任务删除成功',1);
	}
	//任务地址
	public function timming_url($type = 'comic',$ly=''){
		$arr = require MCCMSPATH.'libs/collect.php';
		$tim = $type == 'book' ? $arr['timming_book'][$ly] : $arr['timming'][$ly];
		$qz = is_ssl() ? 'https://':'http://';
		$data['winurl'] = $qz.Web_Url.Web_Path.'index.php/api/timming/win/'.$type.'/'.$ly.'?pass='.$tim['pass'];
		$data['osurl'] = $qz.Web_Url.Web_Path.'index.php/api/timming/os/'.$type.'/'.$ly.'?pass='.$tim['pass'];
		$this->load->view('caiji/timming_url.tpl',$data);
	}
	//资源入库
	public function ruku($type='comic'){
		set_time_limit(0); //不超时
		$apiurl = $this->input->get_post('apiurl',true);
		$ly = $this->input->get_post('ly',true);
		$ids = $this->input->get_post('ids');
		$page = (int)$this->input->get_post('page');
		$cid = (int)$this->input->get_post('cid');
		$day = (int)$this->input->get_post('day');
		$n = (int)$this->input->get_post('n');
		if($page == 0) $page = 1;
		if(!empty($ids)){
			$idarr = explode(',',$ids);
			if(count($idarr) > 5) exit('每次只能采集5条');
		}
		$zyurl = strpos($apiurl,'://') === false ? sys_auth($apiurl,1,'mccms_zyk') : $apiurl;
		$json = getcurl($zyurl.'?ac=data&cid='.$cid.'&ids='.$ids.'&day='.$day.'&page='.$page.'&token='.Caiji_Tb_Token);
		$apiarr = json_decode($json,1);
		$data['page'] = $page;
		$data['pagejs'] = $apiarr['data']['pagejs'];
		$data['nums'] = $apiarr['data']['nums'];
		$data['size'] = $apiarr['data']['size'];

		$zykarr = require MCCMSPATH.'libs/collect.php';
		$bind = $zykarr['bind'];
		//表
		$table = $type == 'book' ? 'book' : 'comic';
		$msg = array();
		$zyk = $type == 'book' ? $zykarr['book_zyk'] : $zykarr['zyk'];
        if(isset($zyk[$ly])){
            $chapter_url = $zyk['jxurl'].'/chapter/';
        }else{
        	$chapter_url = Book_Caiji_Tb_Url.'/chapter/';
    	}
		//循环入库
		foreach ($apiarr['data'][$table] as $k => $v) {
			$v = str_checkhtml($v,1);
			//标题替换
			$v['name'] = $this->collect->get_name_replace($v['name'],$type);
			$zycid = $v['cid'];
			$v['cid'] = isset($zykarr['bind'][$ly][$zycid]) ? (int)$zykarr['bind'][$ly][$zycid] : 0;
			$msgstr = '第'.($k+1).'条数据《'.$v['name'].'》共有'.$v['chapter_num'].'话，';
			//判断绑定分类
			if($v['cid'] == 0){ //未绑定
				$msgstr .= '<font color=red>未绑定分类，跳过</font>';
			}else{
				$row = $this->collect->get_query($v,$type);
				//数据存在
				if($row){
					$this->collect->get_update($v,$row['id'],$type,$ly);//更新入库
					$msgstr .= '<font color=#1E9FFF>数据存在，更新成功</font>';
				}else{
					$this->collect->get_insert($v,$type,$ly);//新增入库
					$msgstr .= '<font color=#080>数据不存在，添加成功</font>';
				}
			}
			$msg[$k]['str'] = $msgstr;
		}
		$data['type'] = $type;
		$data['msg'] = $msg;
		$data['finish'] = 0; //是否全部采集完成
		//下一页地址
		$data['next_link'] = links('caiji','ruku',$type).'?apiurl='.urlencode($apiurl).'&ly='.$ly.'&day='.$day.'&ids='.$ids.'&cid='.$cid.'&page='.($page+1);
		//判断采集页数
		if($page >= $apiarr['data']['pagejs']){
			$data['next_link'] = links('caiji','show',$type).'?apiurl='.urlencode($apiurl).'&ly='.$ly.'&day='.$day.'&cid='.$cid;
			$data['finish'] = 1;
		}
		$this->load->view('caiji/ruku.tpl',$data);
	}
}
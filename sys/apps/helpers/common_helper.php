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
//获取任意字段信息
function getzd($table,$zd,$id,$cha='id',$order='id desc'){
	$ci = &get_instance();
	if(!isset($ci->db)) $ci->load->database();
	$zds= ($zd=='nichen') ? 'name,nichen' : $zd;
	$str = "";
	if($table && $zd && $id){
		if($table == 'book_chapter') $table = get_chapter_table($id);
		$row = $ci->db->where($cha,$id)->select($zds)->order_by($order)->get($table)->row();
		if($row){
			$str = $row->$zd;
			if($zd=='nichen' && empty($str)) $str=$row->name;
		}
		if($zd == 'pic' || $zd == 'picx') $str = getpic($str,'user');
		if(empty($str) && $table == 'comic_chapter' && $zd == 'name'){
			$str = '待浏览';
		}
		return $str;
	}
}
//获取漫画类别
function get_type($mid,$zd='tags',$fg=' '){
	$ci = &get_instance();
	$result = $ci->db->query("select tid from ".Mc_SqlPrefix."comic_type where mid=".$mid)->result();
	$arr = array();
	foreach ($result as $row) {
		$row2 = $ci->db->query("select zd,name from ".Mc_SqlPrefix."type where id=".$row->tid)->row();
		if($row2->zd == $zd){
			$arr[] = $row2->name;
		}
	}
	return implode($fg,$arr);
}
//解析多个分类ID  如 cid=1,2,3,4,5,6
function getcid($CID,$type='class',$zd='fid'){
	$ci = &get_instance();
	if(!empty($CID)){
		$ClassArr=explode(',',$CID);
		for($i=0;$i<count($ClassArr);$i++){
			$sql="select id from ".Mc_SqlPrefix.$type." where ".$zd."='$ClassArr[$i]'";//sql语句的组织返回
			$result=$ci->db->query($sql)->result();
			if(!empty($result)){
				foreach ($result as $row) {
					$ClassArr[]=$row->id;
				}
			}
			$CID=implode(',',$ClassArr);
		}
	}
	return $CID;
}
//查询漫画打赏、月票排名
function get_rank($did,$zd='cion',$type='comic'){
	$ci = &get_instance();
	$sql = "SELECT * FROM (SELECT id,(@rowNum:=@rowNum+1) AS rowNo FROM ".Mc_SqlPrefix.$type.",(SELECT (@rowNum :=0) ) b ORDER BY ".$zd." DESC) c WHERE id=".$did;
	$row = $ci->db->query($sql)->row_array();
	if($row){
		return $row['rowNo'];
	}else{
		return '100名以外';
	}
}
//截取字符串的函数
function sub_str($str, $length, $start=0, $suffix="...", $charset="utf-8"){
	$str = str_checkhtml($str);
	if(($length+2) >= strlen($str)) return $str;
	if(function_exists("mb_substr")){
		$xstr = mb_substr($str, $start, $length, $charset);
		if(strlen($str) != strlen($xstr)) $xstr .= $suffix;
		return $xstr;
	}elseif(function_exists('iconv_substr')){
		$xstr = iconv_substr($str,$start,$length,$charset);
		if(strlen($str) != strlen($xstr)) $xstr .= $suffix;
		return $xstr;
	}
	$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if(strlen($str) != strlen($slice)) $slice .= $suffix;
	return $slice;
}
//读文件
function load_file($file=''){
	if(empty($file)) return '';
	$dir = defined('MOBILE') ? Skin_Wap_Path : Skin_Pc_Path;
	if(defined('HTML_DIR')) $dir = HTML_DIR == 'wap' ? Skin_Wap_Path : Skin_Pc_Path;
	$path = VIEWPATH.$dir.$file;
	if(!file_exists($path)){
		if(strpos($_SERVER['HTTP_ACCEPT'],'application/json') !== false){
			get_json('缺少模板文件：'.$file,0);
		}else{
			exit('缺少模板文件：'.$file);
		}
	}
	return file_get_contents($path);
}
//写文件
function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE){
	$dir = dirname($path);
	if(!is_dir($dir)) mkdirss($dir);
	if(!$fp = @fopen($path,$mode)) return FALSE;
	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);
	return TRUE;
}
//递归创建文件夹
function mkdirss($dir) {
	if(substr($dir,0,2) == './') $dir = str_replace('./',FCPATH,$dir);
    if(!$dir) return FALSE;
    $arr1 = explode(FCPATH,$dir);
    $arr2 = !empty($arr1[1]) ? explode('/',$arr1[1]) : array();
    $xdir = FCPATH;
    foreach($arr2 as $k => $v) {
    	$xdir .= $k > 0 ? '/'.$v : $v;
    	if(!is_dir($xdir)) mkdir($xdir, 0777);
    }
    return true;
}
//写入新数组到文件
function arr_file_edit($arr,$file=''){
	if(empty($file)) return false;
	if(is_array($arr)){
	    $con = var_export($arr,true);
	} else{
	    $con = $arr;
	}
	$strs="<?php if (!defined('FCPATH')) exit('No direct script access allowed');".PHP_EOL;
	$strs.="return $con;";
	return write_file($file, $strs);
}
// HTML转JS  
function htmltojs($str){
    $re='';
    $str=str_replace('\\','\\\\',$str);
    $str=str_replace("'","\'",$str);
    $str=str_replace('"','\"',$str);
    $str=str_replace('\t','',$str);
    $str= explode("\r\n",$str);
    for($i=0;$i<count($str);$i++){
        $re.="document.writeln(\"".$str[$i]."\");\r\n";
    }
    return $re;
}
//删除所有空格
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
    return str_replace($qian,$hou,$str);    
}
//HTML转字符
function str_encode($str){
	if(is_array($str)) {
		foreach($str as $k => $v) {
			$str[$k] = str_encode($v); 
		}
	}else{
		$str=str_replace("<","&lt;",$str);
		$str=str_replace(">","&gt;",$str);
		$str=str_replace('"',"&quot;",$str);
		$str=str_replace("'",'&#039;',$str);
		$str=str_replace("$","&#36;",$str);
		$str=str_replace("{","&#123;",$str);
		$str=str_replace("}","&#125;",$str);
		$str=str_replace("(","&#40;",$str);
		$str=str_replace(")","&#41;",$str);
		$str=str_replace("%","&#37",$str);
		$str=str_replace('\\','&#92',$str);
	}
	return $str;
}
//字符转HTML
function str_decode($str){
	if(is_array($str)) {
		foreach($str as $k => $v) {
			$str[$k] = str_decode($v); 
		}
	}else{
		$str=str_replace("&lt;","<",$str);
		$str=str_replace("&gt;",">",$str);
		$str=str_replace("&quot;",'"',$str);
		$str=str_replace("&#039;","'",$str);
		$str=str_replace("&#36;","$",$str);
		$str=str_replace("&#123;","{",$str);
		$str=str_replace("&#125;","}",$str);
		$str=str_replace("&#40;","(",$str);
		$str=str_replace("&#41;",")",$str);
		$str=str_replace("&#37","%",$str);
		$str=str_replace('&#92','\\',$str);
	}
	return $str;
}
//SQL过滤
function safe_replace($string){
	if(is_array($string)) {
		foreach($string as $k => $v) {
			$string[safe_replace($k)] = safe_replace($v); 
		}
	}else{
		if(!is_numeric($string)){
			$string = urldecode($string);
			$string = str_replace('%20','',$string);
			$string = str_replace('%27','',$string);
			$string = str_replace('%2527','',$string);
			$string = str_replace(';','',$string);
			$string = str_replace('*','',$string);
			$string = str_replace('<','&lt;',$string);
			$string = str_replace('>','&gt;',$string);
			$string = str_replace('\\','',$string);
			$string = str_replace('%','',$string);
			$pattern = '/\b(base64_decode|eval|exec|system|passthru|shell_exec|proc_open|popen|pcntl_exec|assert|file.*|fopen|fwrite|fread|unlink|curl_exec|readfile|phpinfo|chmod|chown|symlink|putenv|dl|ini_set|error_log|mb_ereg_replace|preg_replace\s*\(.*\/e|create_function|str_decode|sys_auth|write_file)\s*\(/i';
			$string = preg_replace($pattern, '', $string);
			$string = str_encode($string);
		}
	}
	return $string;
}
//屏蔽所有html
function str_checkhtml($str,$sql=0) {
	if(is_array($str)) {
		foreach($str as $k => $v) {
			$str[$k] = str_checkhtml($v); 
		}
	}else{
		$str = preg_replace("/\s+/"," ", $str);
		$str = preg_replace("/&nbsp;/","",$str);
		$str = preg_replace("/\r\n/","",$str);
		$str = preg_replace("/\n/","",$str);
		$str = str_replace(chr(13),"",$str);
		$str = str_replace(chr(10),"",$str);
		$str = str_replace(chr(9),"",$str);
		$str = strip_tags($str);
		$str = str_encode($str);
	}
	if($sql==1) $str = safe_replace($str);
	return $str;
}
//判断email格式是否正确
function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}
//判断手机号码格式是否正确
function is_tel($tel) {
	return preg_match("/^1[3456789]\d{9}$/", $tel);
}
//编码转换
function get_bm($string,$s1='gbk',$s2='utf-8') {
	if(is_array($string)) {
		foreach($string as $k => $v) { 
			$string[$k] = get_bm($v); 
		} 
	}else{
		if(function_exists("mb_convert_encoding")){
			$string = mb_convert_encoding($string, $s2, $s1);
		}else{
			$string = iconv($s1, $s2, $string);
		}
	}
	return $string;
}
//获取IP
function getip(){ 
	$ci = &get_instance();
	$ip = $ci->input->ip_address();
	if(preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/",$ip)){
		return $ip; 
	}
	return "";
}
//获取远程内容
function getcurl($url,$post=''){
	$data = '';
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if(!empty($post)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if(strpos($url,'/manhua.qpic.cn/') !== false){
			curl_setopt($ch, CURLOPT_REFERER, 'https://ac.qq.com/');
		}elseif(empty($_SERVER['HTTP_REFERER'])){
			curl_setopt($ch, CURLOPT_REFERER, 'http://'.$_SERVER['HTTP_HOST']);
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//获取跳转后的
		$data = curl_exec($ch);
		curl_close($ch);
	}
	return $data;
}
//字符加密、解密
function sys_auth($string, $type = 0, $key = '', $expiry = 0) {
	if(is_array($string)) $string = json_encode($string);
	if($type == 1) $string = str_replace('-','+',$string);
	$ckey_length = 4;
	$key = md5($key ? $key : Mc_Encryption_Key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($type == 1 ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $type == 1 ? base64_decode(substr($string, $ckey_length)) :  sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	} 
	if($type == 1) {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			$result = substr($result, 26);
			$json = json_decode($result,1);
			if(!is_numeric($result) && $json){
				return $json;
			}else{
				return $result;
			}
		}
		return '';
	}
	return str_replace('+', '-', $keyc.str_replace('=', '', base64_encode($result)));
}
//删除目录和文件
function deldir($dir,$sid='no') {
	//目录不存在
	if(!is_dir($dir)) return true;
	//先删除目录下的文件
	$ci = &get_instance();
	$ci->load->helper('file');
	$res = delete_files($dir, TRUE);
	//删除当前文件夹：
	if($sid=='ok'){
		if(!rmdir($dir)) return false;
	}
	return true;
}
//json输出
function get_json($arr,$code=-1){
	if(!is_array($arr)){
		$data['msg'] = $arr;
		$data['code'] = $code;
	}else{
		$data = $arr;
		if(!isset($data['code'])) $data['code'] = $code;
	}
	//强制编码
	header('Content-Type:application/json;Charset=utf-8');
	$ci = &get_instance();
	$callback = $ci->input->get_post('callback');
	if(!empty($callback)){
		echo $callback.'('.json_encode($data,JSON_UNESCAPED_UNICODE).');';
	}else{
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	exit;
}
//计算漫画评分
function get_score($mid=0){
	if($mid == 0) return 9.8;
	$ci = &get_instance();
	//获取总次数
	$znum = $ci->mcdb->get_nums('comic_score',array('mid'=>$mid));
	if($znum == 0)  return 9.8;
	//获取总分数
	$voter = $ci->mcdb->get_sum('comic_score','pf',array('mid'=>$mid));
	//计算平均分
    $score = round($voter/$znum,1);
    if(strpos($score,'.') === false) $score .= '.0';
    return $score;
}
/**
 * 获取客户端浏览器以及版本号
 * @param $agent    //$_SERVER['HTTP_USER_AGENT']
 * @return array[browser]       浏览器名称
 * @return array[browser_ver]   浏览器版本号
 */    
function getClientBrowser($agent = '') {
	if(empty($agent)) $agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = '';
    $browser_ver = '';
    if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'OmniWeb';
        $browser_ver = $regs[2];
    }
    if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Netscape';
        $browser_ver = $regs[2];
    }
    if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Safari';
        $browser_ver = $regs[1];
    }
    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'Internet Explorer';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
        $browser = 'Opera';
        $browser_ver = $regs[1];
    }
    if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
        $browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Maxthon/i', $agent, $regs)) {
        $browser = '(Internet Explorer '.$browser_ver.') Maxthon';
        $browser_ver = '';
    }
    if (preg_match('/360SE/i', $agent, $regs)) {
        $browser = '(Internet Explorer '.$browser_ver.') 360SE';
        $browser_ver = '';
    }
    if (preg_match('/SE 2.x/i', $agent, $regs)) {
        $browser = '(Internet Explorer '.$browser_ver.') 搜狗';
        $browser_ver = '';
    }
    if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'FireFox';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Lynx';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Chrome';
        $browser_ver = $regs[1];
    }
    if (preg_match('/MicroMessenger\/([^\s]+)/i', $agent, $regs)) {
        $browser = '微信浏览器';
        $browser_ver = $regs[1];
    }
    if (preg_match('/QQ\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'QQ浏览器';
        $browser_ver = $regs[1];
    }
    if ($browser != '') {
        return array('browser'=>$browser, 'ver'=>$browser_ver);
    } else {
        return array('browser'=>'未知','ver'=> '');
    }
}
//Base64加密
function base64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    return $data;
}
//Base64解密
function base64decode($string) {
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data.= substr('====', $mod4);
    }
    return base64_decode($data);
}
//以万为单位格式化
function format_wan($hits = 0){
	$hits = round($hits,1);
    if($hits > 99999999){
    	return round($hits/100000000,1)." 亿";
    }elseif($hits > 999999){
    	return round($hits/10000)." 万";
    }elseif($hits > 9999){
    	return round($hits/10000,1)." 万";
    }elseif($hits > 999){
    	return round($hits);
    }else{
    	return number_format($hits);
    }
}
//大小转换
function formatsize($size, $dec=2){
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}
//主动判断是否HTTPS
function is_ssl(){
	if(Web_Ssl_Mode == 1) return TRUE;
    if(!isset($_SERVER)) return FALSE;
    if(isset($_SERVER['HTTPS'])){
    	if($_SERVER['HTTPS'] === 1) {  //Apache
        	return TRUE;
	    } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
	        return TRUE;
	    }
	}elseif(isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'https'){
		return TRUE;
    }elseif($_SERVER['SERVER_PORT'] == 443 || $_SERVER['REQUEST_SCHEME'] == 'https'){ //协议头
        return TRUE;
    }
    return FALSE;
}
//年月日时间替换
function get_str_date($str){
	$a1 = array('年','月','日');
	$a2 = array(date('Y'),date('m'),date('d'));
	return str_replace($a1,$a2,$str);
}
//真实密码隐藏
function get_pass($pass,$len=2,$x=6){
	if(empty($pass)) return '';
	$xh = '';
	for($i=0;$i<$x;$i++) $xh.='*';
	return substr($pass,0,$len).$xh.substr($pass,-$len);
}
//给推送地址加上前缀域名
function get_push_host($url){
	if(Push_Host == ''){
		$url = (Web_Ssl_Mode == 0 ? 'http://' : 'https://').Web_Url.$url;
	}else{
		$url = Push_Host.$url;
	}
	return $url;
}
//判断资源库分类是否绑定
function get_zyk_class($ac='del',$ly='',$zycid=0,$cid=0){
	$zyk_file = MCCMSPATH.'libs/collect.php';
	if(empty($ly)) return false;
	$zyk = require $zyk_file;
	if($ac == 'get'){ //是否绑定
		return (isset($zyk['bind'][$ly][$zycid]) && !empty($zyk['bind'][$ly][$zycid]));
	}elseif($ac == 'delall'){ //一键清除所有绑定
		$zyk['bind'][$ly] = array();
	}elseif($ac == 'del'){ //解除绑定
		$b = array();
		foreach ($zyk['bind'][$ly] as $k => $v) {
			if($k != $zycid) $b[$k] = $v;
		}
		$zyk['bind'][$ly] = $b;
	}elseif($ac == 'set'){ //绑定
		$zyk['bind'][$ly][$zycid] = $cid;
	}
	return arr_file_edit($zyk,$zyk_file);
}
//下载远程图片到本地
function get_downpic($picurl,$dir='comic'){
    $img = getcurl($picurl);
    $ext = strtolower(trim(substr(strrchr($picurl, '.'), 1)));
    if(!in_array($ext,array('jpg','png','jpeg','gif'))) $ext = 'jpg';
    $img_path_file = FCPATH.Annex_Dir.'/'.$dir.'/'.get_str_date(Annex_Path).'/';
    mkdirss($img_path_file);
    $img_path_file .= md5($picurl).'.'.$ext;
    $fp = fopen($img_path_file,'w');
    fwrite($fp, $img);
    //水印
    get_watermark($img_path_file);
    //同步
    get_tongbu($img_path_file);
    return str_replace(FCPATH,Web_Path,$img_path_file);
}
//给图片加水印
function get_watermark($img_path=''){
	//判断开启水印
	if(Img_Type == '') return true;
	$ci = &get_instance();
	if(!isset($ci->watermark)) 
		$ci->load->model('watermark');
	return $ci->watermark->send($img_path);
}
//附件图片同步
function get_tongbu($file_path='',$mode='add'){
	//站内
	if(Annex_Mode == 0) return $file_path;
	$ci = &get_instance();
	if(!isset($ci->tongbu)) 
		$ci->load->model('tongbu');
	if($mode == 'add'){
		return $ci->tongbu->send($file_path);
	}else{
		return $ci->tongbu->del($file_path);
	}
}
//发送邮件标题和内容替换
function email_replace($str,$arr=array()){
	$a1 = array(
		'{site_name}',
		'{web_url}',
		'{user_nichen}',
		'{user_email}',
		'{drawing_dd}',
		'{drawing_zt}',
		'{drawing_rmb}',
		'{drawing_addtime}',
		'{comic_remind_url}',
		'{comic_addtime}',
		'{comic_serialize}',
		'{comic_pic}',
		'{comic_text}',
		'{comic_cname}',
		'{comic_url}',
		'{comic_chapter_name}',
		'{comic_name}'
	);
	$a2 = array(
		Web_Name,
		Web_Url,
		$arr['user']['nichen'],
		$arr['user']['email'],
		$arr['drawing']['dd'],
		$arr['drawing']['tz'],
		$arr['drawing']['rmb'],
		date('Y-m-d H:i:s',$arr['drawing']['addtime']),
		$arr['comic']['remind_url'],
		date('Y-m-d H:i:s',$arr['comic']['addtime']),
		$arr['comic']['serialize'],
		getpic($arr['comic']['pic']),
		$arr['comic']['text'],
		$arr['comic']['cname'],
		$arr['comic']['url'],
		$arr['comic']['chapter_name'],
		$arr['comic']['name']
	);
	return str_replace($a1,$a2,$str);
}
//评论表情替换
function get_face($str){
	if(empty($str)) return $str;
	for ($i=1; $i < 11; $i++) {
		$str = str_replace('[em:'.$i.']','<img class="pic" src="'.Web_Base_Path.'face/'.$i.'.jpg">',$str);
	}
	return $str;
}
//过滤评论内容
function get_comment_text($str){
	$arr = explode('|',Pl_Str);
	for ($i=0; $i < count($arr); $i++) { 
		$str = str_replace($arr[$i],'**',$str);
	}
	return $str;
}
//控制器和页面404
function get_err($type='404'){
	if($type=='404'){
		header("location:".links('err','404'));
	}else{
		header("location:".links('err',$type));
	}
    exit;
}
//判断标签是否选中
function get_is_type($mid=0,$tid=0){
	$ci = &get_instance();
	$isrow = $ci->mcdb->get_row('comic_type','id',array('mid'=>$mid,'tid'=>$tid));
	if($isrow) return true;
	return false;
}
//时间格式转换
function datetime($TimeTime){
	$limit=time()-$TimeTime;
	if ($limit <5) {$show_t = '刚刚';}
	if ($limit >= 5 and $limit <60) {$show_t = $limit.'秒前';}
	if ($limit >= 60 and $limit <3600) {$show_t = sprintf("%01.0f",$limit/60).'分钟前';}
	if ($limit >= 3600 and $limit <86400) {$show_t = sprintf("%01.0f",$limit/3600).'小时前';}
	if ($limit >= 86400 and $limit <2592000) {$show_t = sprintf("%01.0f",$limit/86400).'天前';}
	if ($limit >= 2592000 and $limit <31104000) {$show_t = sprintf("%01.0f",$limit/2592000).'个月前';}
	if ($limit >= 31104000) {$show_t = '1年以前';}
	return $show_t;
}
//章节分表，bid小说ID
function get_chapter_table($bid){
	$i = $bid % 100;
    $table = sprintf("book_chapter_%s", $i);
	$ci = &get_instance();
	if(!isset($ci->db)) $ci->load->database();
	//数据分表不存在，则创建
	$res = $ci->db->table_exists(Mc_SqlPrefix.$table);
	if(!$res){
		$sql = "DROP TABLE IF EXISTS `".Mc_SqlPrefix.$table."`";
		$ci->db->query($sql);
		$sql = "CREATE TABLE `".Mc_SqlPrefix.$table."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `bid` int(11) DEFAULT '0' COMMENT '小说ID',
			  `xid` int(11) DEFAULT '0' COMMENT '排序ID',
			  `name` varchar(128) DEFAULT '' COMMENT '标题',
			  `vip` tinyint(1) DEFAULT '0' COMMENT 'VIP阅读，0否1是',
			  `cion` int(11) DEFAULT '0' COMMENT '章节需要金币',
			  `text_num` int(11) DEFAULT '0' COMMENT '章节字数',
			  `yid` tinyint(1) DEFAULT '0' COMMENT '0已审核，1待审核，2未通过',
			  `msg` varchar(128) DEFAULT '' COMMENT '未通过原因',
			  `addtime` int(11) DEFAULT '0' COMMENT '入库时间',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `bid_xid` (`bid`,`xid`) USING BTREE
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小说章节';";
		$ci->db->query($sql);
	}
    return $table;
}
//获取小说txt文本
function get_book_txt($bid,$zid,$text=''){
	$txt_file = FCPATH.'caches/txt/'.$bid.'/'.md5($zid.Mc_Book_Key).'.txt';
	if(!empty($text)){
		return write_file($txt_file, $text);
	}else{
		if(!file_exists($txt_file)) return false;
		return file_get_contents($txt_file);
	}
}
//检查上传图片是否包含木马
function checkPicHex($file) {
    if(file_exists($file)) {
        $resource = fopen($file,'rb');
        $fileSize = filesize($file);
        fseek($resource, 0);//把文件指针移到文件的开头
        if($fileSize > 512){ // 若文件大于521B文件取头和尾
            $hexCode = bin2hex(fread($resource, 512));
            fseek($resource, $fileSize - 512);//把文件指针移到文件尾部
            $hexCode .= bin2hex(fread($resource,512));
        } else { // 取全部
            $hexCode = bin2hex(fread($resource, $fileSize));
        }
        fclose($resource);
        if(preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is",$hexCode)){
        	//删除文件
        	unlink($file);
            return 1;
        }else{
            return 0;
        }
    } else {
        return -1;
    }
}
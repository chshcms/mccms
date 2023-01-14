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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mc_Parser extends CI_Parser {
	public $l_delim = '[';
	public $r_delim = ']';
    //模板解析
	public function parse_string($template, $data = array(), $return = FALSE){
		return $this->_parse($template, $data, $return);
	}
    //全局解析
	protected function _parse($template, $data = array(), $return = FALSE, $IF = TRUE)
	{
		$data = (array)$data;
		if ($template === ''){
			return FALSE;
		}

		//解析顶部和底部
		$head = $left = $bottom = $packs = $userhead = $userleft = $userbottom = $userpacks = '';
		if(defined('MOBILE')){
			$logurl = links('api','ulog','wap');
			$templets_path = FCPATH.'template/'.Skin_Wap_Path;
		}else{
			$logurl = links('api','ulog','pc');
			$templets_path = FCPATH.'template/'.Skin_Pc_Path;
		}
		if(defined('HTML_DIR')){
			if(HTML_DIR == 'wap'){
				$logurl = links('api','ulog','wap');
				$templets_path = FCPATH.'template/'.Skin_Wap_Path;
			}else{
				$logurl = links('api','ulog','pc');
				$templets_path = FCPATH.'template/'.Skin_Pc_Path;
			}
		}
        if(preg_match('/[mccms_head]/',$template)){
			if(file_exists($templets_path.'head.html')) $head = file_get_contents($templets_path.'head.html');
        }
        if(preg_match('/[mccms_left]/',$template)){
			if(file_exists($templets_path.'left.html')) $left = file_get_contents($templets_path.'left.html');
        }
        if(preg_match('/[mccms_bottom]/',$template)){
			if(file_exists($templets_path.'bottom.html')) $bottom = file_get_contents($templets_path.'bottom.html');
        }
        if(preg_match('/[mccms_packs]/',$template)){
			if(file_exists($templets_path.'packs.html')) $packs = file_get_contents($templets_path.'packs.html');
        }
		$template = str_replace('[mccms_head]',$head,$template);
		$template = str_replace('[mccms_left]',$left,$template);
		$template = str_replace('[mccms_bottom]',$bottom,$template);
		$template = str_replace('[mccms_packs]',$packs,$template);
		//自定义目录
        preg_match_all('/\[mccms_head_([0-9a-zA-Z\_\-]+)\]/',$template,$harr);
        preg_match_all('/\[mccms_left_([0-9a-zA-Z\_\-]+)\]/',$template,$larr);
        preg_match_all('/\[mccms_bottom_([0-9a-zA-Z\_\-]+)\]/',$template,$barr);
        preg_match_all('/\[mccms_packs_([0-9a-zA-Z\_\-]+)\]/',$template,$parr);
        if(!empty($harr[1])){
        	for($i=0;$i<count($harr[1]);$i++){
        		$uhead = '';
				if(file_exists($templets_path.$harr[1][$i].'/head.html')){
					$uhead = file_get_contents($templets_path.$harr[1][$i].'/head.html');
				}
				$template = str_replace($harr[0][$i],$uhead,$template);
        	}
        }
        if(!empty($larr[1])){
        	for($i=0;$i<count($larr[1]);$i++){
        		$uleft = '';
				if(file_exists($templets_path.$larr[1][$i].'/left.html')){
					$uleft = file_get_contents($templets_path.$larr[1][$i].'/left.html');
				}
				$template = str_replace($larr[0][$i],$uleft,$template);
        	}
        }
        if(!empty($barr[1])){
        	for($i=0;$i<count($barr[1]);$i++){
        		$ubottom = '';
				if(file_exists($templets_path.$barr[1][$i].'/bottom.html')){
					$ubottom = file_get_contents($templets_path.$barr[1][$i].'/bottom.html');
				}
				$template = str_replace($barr[0][$i],$ubottom,$template);
        	}
        }
        if(!empty($parr[1])){
        	for($i=0;$i<count($parr[1]);$i++){
        		$upacks = '';
				if(file_exists($templets_path.$parr[1][$i].'/packs.html')){
					$upacks = file_get_contents($templets_path.$parr[1][$i].'/packs.html');
				}
				$template = str_replace($parr[0][$i],$upacks,$template);
        	}
        }
        unset($harr,$larr,$barr,$parr);
		//常用标签
	    $TempImg = str_replace('//','/',str_replace(FCPATH,Web_Path,$templets_path));
	    $end = strrpos(substr($TempImg,0,strlen($TempImg)-1),'/')+1;
    	$tpl_dir = substr(substr($TempImg,0,strlen($TempImg)-1),0,$end);
		$template = str_replace('[mccms_tpl]',$tpl_dir,$template);
		$template = str_replace('[mccms_url]',Web_Url,$template);
		$template = str_replace('[mccms_name]',Web_Name,$template);
		$template = str_replace('[mccms_icp]',Web_Icp,$template);
		$template = str_replace('[mccms_stat]',str_decode(Web_Stat),$template);
		$template = str_replace('[mccms_qq]',Web_QQ,$template);
		$template = str_replace('[mccms_tel]',Web_Tel,$template);
		$template = str_replace('[mccms_mail]',Web_Mail,$template);
		$template = str_replace('[mccms_basepath]',Web_Base_Path,$template);
		$template = str_replace('[mccms_path]',Web_Path,$template);
		$template = str_replace('[mccms_weburl]',(is_ssl()?'https://':'http://').Web_Url.Web_Path,$template);
		$template = str_replace('[mccms_date]',date('Y-m-d'),$template);
		$template = str_replace('[mccms_year]',date("y"),$template);
		$template = str_replace('[mccms_month]',date("m"),$template);
		$template = str_replace('[mccms_week]',date("w"),$template);
		$template = str_replace('[mccms_day]',date("d"),$template);
		$template = str_replace('[mccms_time]',strtotime(date("Y-m-d 0:0:0")),$template);
		$template = str_replace('[mccms_author]',Author_Mode,$template);
		$template = str_replace('[mccms_reg]',User_Reg,$template);
		$template = str_replace('[mccms_istel]',User_Reg_Tel,$template);
		//财务相关
		$template = str_replace('[mccms_cardurl]',Pay_Card_Url,$template);
		$template = str_replace('[mccms_cionname]',Pay_Cion_Name,$template);
		$template = str_replace('[mccms_rmbtocion]',Pay_Rmb_Cion,$template);
		$template = str_replace('[mccms_isqqpay]',Pay_QQ_Mode,$template);
		$template = str_replace('[mccms_iswxpay]',Pay_Wx_Mode,$template);
		$template = str_replace('[mccms_isalipay]',Pay_Ali_Mode,$template);
		$template = str_replace('[mccms_vip_rmb_1]',Pay_Vip_Rmb1,$template);
		$template = str_replace('[mccms_vip_rmb_2]',Pay_Vip_Rmb2,$template);
		$template = str_replace('[mccms_vip_rmb_3]',Pay_Vip_Rmb3,$template);
		$template = str_replace('[mccms_vip_rmb_4]',Pay_Vip_Rmb4,$template);
		$vip = isset($_COOKIE['vip']) && $_COOKIE['vip'] > 0 ? 1 : 0;
		$template = str_replace('[mccms_vip]',$vip,$template);

		//解析自带标签
		$replace = array();
		foreach ($data as $key => $val){
			$replace = array_merge(
				$replace,
				is_array($val)
					? $this->_parse_pair($key, $val, $template)
					: $this->_parse_single($key, (string) $val, $template)
			);
		}
		foreach($replace as $k=>$v){
            $template = str_replace($k,$v,$template);
		}
		//站点标题、关键字、描述
		$template = str_replace('[mccms_title]',Seo_Title,$template);
		$template = str_replace('[mccms_keywords]',Seo_Keywords,$template);
		$template = str_replace('[mccms_description]',Seo_Description,$template);
		//解析周标签
		preg_match_all('/{mccms:week}([\s\S]+?){\/mccms:week}/',$template,$tarr);
		if(!empty($tarr[0])){
			$aa = array('1'=>'周一','2'=>'周二','3'=>'周三','4'=>'周四','5'=>'周五','6'=>'周六','0'=>'周日');
			$time = strtotime(date('Y-m-d 0:0:0'))+86400;
			for($i=0;$i<count($tarr[0]);$i++){
				$strs = '';
				for($k=1;$k<8;$k++){
					$wt = $time-86400*$k;
					$w = date('w',$wt);
					$name = $k == 1 ? '今日' : ($k == 2 ? '昨天' : $aa[$w]);
					$weekstr = $tarr[1][$i];
			    	$weekstr = str_replace('[week:i]',$k,$weekstr);
			    	$weekstr = str_replace('[week:kstime]',($wt-1),$weekstr);
			    	$weekstr = str_replace('[week:jstime]',($wt+86401),$weekstr);
			    	$weekstr = str_replace('[week:num]',$w,$weekstr);
			    	$weekstr = str_replace('[week:name]',$name,$weekstr);
			    	$strs .= $weekstr;
				}
				$template = str_replace($tarr[0][$i],$strs,$template);
			}
		}
		unset($tarr);
		//广告js标签
		preg_match_all('/\[mccms_js_([0-9a-zA-Z\_\-]+)\]/',$template,$str_arr);
		if(!empty($str_arr[1])){
			for($i=0;$i<count($str_arr[0]);$i++){
                $js = '<script src="'.Web_Path.'advert/'.$str_arr[1][$i].'.js"></script>';
                $template = str_replace($str_arr[0][$i],$js,$template);
			}
		}
		unset($str_arr);
		//小说TAGS标签解析
		preg_match_all('/{book:tags len=\"([0-9]+)\"}([\s\S]+?){\/book:tags}/',$template,$tarr);
		if(!empty($tarr[0])){
			$aa = Web_Book_Tags != '' ? explode("|",Web_Book_Tags) : array();
			for($i=0;$i<count($tarr[0]);$i++){
				$strs = '';
				$nums = (int)$tarr[1][$i];
				if($nums == 0 || $nums > count($aa)) $nums = count($aa);
				for($k=0;$k<$nums;$k++){
					$tagsstr = $tarr[2][$i];
			    	$tagsstr = str_replace('[tags:i]',$k+1,$tagsstr);
			    	$tagsstr = str_replace('[tags:link]',get_category_url(array('tags',$k+1),'book'),$tagsstr);
			    	$tagsstr = str_replace('[tags:name]',$aa[$k],$tagsstr);
			    	$strs .= $tagsstr;
			    }
				$template = str_replace($tarr[0][$i],$strs,$template);
			}
		}
		unset($tarr);
		//sql解析
		preg_match_all('/{mccms:([\S]+)\s+(.*?)}([\s\S]+?){\/mccms:\1}/',$template,$str_arr);
		if(!empty($str_arr[3])){
			for($i=0;$i<count($str_arr[0]);$i++){
                $template = $this->mccms_skins($str_arr[1][$i],$str_arr[2][$i],$str_arr[0][$i],$str_arr[3][$i],$template);
			}
		}
		unset($str_arr);
		//统计
		preg_match_all('/\[mccms_count_([0-9a-zA-Z\_\-\>\<\=\|]+)\]/',$template,$str_arr);
		if(!empty($str_arr[1])){
			for($i=0;$i<count($str_arr[0]);$i++){
				$carr = explode("_",$str_arr[1][$i]);
                if(strpos($carr[0],'|') !== false){
                  	$table = current(explode('|',$carr[0]));
                }else{
					$table = $carr[0];
                }
                $table = str_replace('-','_',$table);
				if($this->CI->db->table_exists(Mc_SqlPrefix.$table)){  //数据表不存在
				   	$wh = array();
				   	if($table == 'comic') $wh['yid'] = 0;
				   	if(!empty($carr[1])){
					   	if(is_numeric($carr[1])){
						   	$day = $carr[1]>0 ? $carr[1]-1 : 0;
						   	$time = strtotime(date('Y-m-d 0:0:0'));
						   	$wh['addtime>'] = $time-$day*86400;
						}else{
						   	$carr2 = explode("|",$carr[1]);
						   	for ($k=0; $k < count($carr2); $k++) { 
						   	   $carr3 = explode("-",$carr2[$k]);
						   	   $wh[$carr3[0]] = $carr3[1];
						   	}
						}
				   }
				   $count = $this->CI->mcdb->get_nums($table,$wh);
				   $template = str_replace($str_arr[0][$i],$count,$template);
				}
			}
		}
		unset($str_arr);
		//自定义模版链接
		preg_match_all('/\[mccms_custom_([0-9a-zA-Z\_\-]+)\]/',$template,$u_arr);
		if(!empty($u_arr[1])){
			for($i=0;$i<count($u_arr[1]);$i++){
			   	$links = get_url('custom',array('file'=>$u_arr[1][$i]));
               	$template = str_replace($u_arr[0][$i],$links,$template);
			}
		}
		unset($u_arr);
		//自定义其他URL链接
		preg_match_all('/\[mccms_url_([\x80-\xff\_\-0-9a-zA-Z]+)\]/',$template,$u_arr);
		if(!empty($u_arr[1])){
			for($i=0;$i<count($u_arr[1]);$i++){
				$tarr = explode('_',$u_arr[1][$i]);
				if($tarr[0] == 'category'){
					unset($tarr[0]);
			   		$links = get_category_url($tarr,'comic');
				}elseif(isset($tarr[1]) && $tarr[1] == 'category'){
					$type = $tarr[0];
					unset($tarr[0],$tarr[1]);
			   		$links = get_category_url($tarr,$type);
				}else{
			   		$links = get_url(str_replace('_','/',$u_arr[1][$i]));
				}
               	$template = str_replace($u_arr[0][$i],$links,$template);
			}
		}
		unset($u_arr);
		//评论表情解析
		preg_match_all('/{mccms:face}([\s\S]+?){\/mccms:face}/',$template,$tarr);
		if(!empty($tarr[0])){
			$aa = array('1'=>'心碎','2'=>'睡觉','3'=>'吃瓜','4'=>'嘿嘿嘿','5'=>'心动','6'=>'泪奔','7'=>'闹了','8'=>'求抱抱','9'=>'开心','10'=>'小鱼干');
			for($i=0;$i<count($tarr[0]);$i++){
				$strs = '';
				for($k=1;$k<11;$k++){
					$facestr = $tarr[1][$i];
			    	$facestr = str_replace('[face:i]',$k,$facestr);
			    	$facestr = str_replace('[face:label]','[em:'.$k.']',$facestr);
			    	$facestr = str_replace('[face:pic]',Web_Base_Path.'face/'.$k.'.jpg',$facestr);
			    	$facestr = str_replace('[face:name]',$aa[$k],$facestr);
			    	$strs .= $facestr;
			    }
				$template = str_replace($tarr[0][$i],$strs,$template);
			}
		}
		unset($tarr);
		//数量转换标签
		preg_match_all('/\[mccms_zhuan_([0-9]+)_([0-9]+)_([0-9]+)\]/',$template,$zarr);
		if(!empty($zarr[0])){
			for($i=0;$i<count($zarr[1]);$i++){
				$num = 0;
				if(isset($zarr[3][$i]) && $zarr[3][$i] != ''){
					if($zarr[2][$i] == 1){ //加法
						$num = $zarr[1][$i]+$zarr[3][$i];
					}elseif($zarr[2][$i] == 2){ //减法
						$num = $zarr[1][$i]-$zarr[3][$i];
					}elseif($zarr[2][$i] == 3){ //乘法
						$num = $zarr[1][$i]*$zarr[3][$i];
					}else{ //除法
						$num = $zarr[1][$i]/$zarr[3][$i];
					}
				}
               	$template = str_replace($zarr[0][$i],number_format($num),$template);
			}
		}
		//IF解析
		if ($return === FALSE){
			$this->CI->output->append_output($template);
		}
		return $template;
	}

	//解析mccms大标签
	public function mccms_skins($fields, $para, $str_arr, $label, $str, $sql='') {
		if($sql=='') $sql = $this->mccms_sql($fields, $para, $str_arr, $label);
		$result_array = $this->CI->db->query($sql)->result_array();
		if(!$result_array){
			$Data_Content="";
			$str = str_replace($str_arr,$Data_Content,$str);
		}else{
			$Data_Content='';$sorti=1;
			foreach ($result_array as $row) {
		        $Data_Content.=$this->mccms_tpl($fields,$str,$label,$row,$sorti);
				$sorti++;
			}
		}
		$str = str_replace($str_arr,$Data_Content,$str);
		return $str;
	}
	//解析mccms嵌套标签
	public function mccms_type($field,$label,$row) {
		//判断是否嵌套二级
		preg_match_all('/where\=\"([\s\S]+?)\"/',$label,$s_arr);
		if(!empty($s_arr[0])){
			foreach ($s_arr[0] as $key => $value) {
				$ystr = $s_arr[0][$key];
				preg_match_all('/\['.$field.':\s*([0-9a-zA-Z\_\-]+)\]/',$value,$s_arr2);
				if(!empty($s_arr2[1])){
					foreach ($s_arr2[1] as $k => $v) {
						$val = isset($row[$v]) ? $row[$v] : '0';
						$s_arr[0][$key] = str_replace($s_arr2[0][$k],$val,$s_arr[0][$key]);
					}
				}
				$label = str_replace($ystr,$s_arr[0][$key],$label);
			}
			preg_match_all('/{mccms:([\S]+)\s+(.*?)}([\s\S]+?){\/mccms:\1}/',$label,$tarr);
			if(!empty($tarr[0])){
				for($i=0;$i<count($tarr[0]);$i++){
			    	$label = $this->mccms_skins($tarr[1][$i],$tarr[2][$i],$tarr[0][$i],$tarr[3][$i],$label);
				}
			}
		}
		return $label;
	}
	//解析mccms标签
	public function mccms_tpl($field, $str, $label, $row, $sorti=1) {
		//先解析嵌套标签
		$label = $this->mccms_type($field,$label,$row);
		preg_match_all('/\['.$field.':\s*([0-9a-zA-Z\_\-]+)([\s]*[wan|len|style|count|zdy|url|face]*)[=]??([\d0-9a-zA-Z\,\_\{\}\/\-\\\\:\s]*)\]/',$label,$field_arr);
		if(!empty($field_arr[0])){
			for($i=0;$i<count($field_arr[0]);$i++){
				$type=$field_arr[1][$i];
				if(array_key_exists($type,$row)){
					//判断自定义标签
				    if(!empty($field_arr[2][$i]) && !empty($field_arr[3][$i])){
						//格式化时间
						if(trim($field_arr[2][$i])=='style' && trim($field_arr[3][$i])=='time'){
							$label=str_replace($field_arr[0][$i],datetime($row[$type]),$label);
						//自定义时间
						}elseif(trim($field_arr[2][$i])=='style'){
							$label=str_replace($field_arr[0][$i],date(str_replace('f','i',$field_arr[3][$i]),$row[$type]),$label);
						}
						//数字转换
						if(trim($field_arr[2][$i])=='wan'){
							$label=str_replace($field_arr[0][$i],format_wan($row[$type]),$label);
						}
						//字符截取
						if(trim($field_arr[2][$i])=='len'){
							$label=str_replace($field_arr[0][$i],sub_str(str_checkhtml($row[$type]),$field_arr[3][$i]),$label);
						}
						//字符加链接
						if(trim($field_arr[2][$i]) == 'url' && !empty($field_arr[3][$i])){
							$label = str_replace($field_arr[0][$i],taglink($row[$type],$field_arr[3][$i]),$label);
						}
						//评论内容转换
						if(trim($field_arr[2][$i])=='face'){
							$label=str_replace($field_arr[0][$i],get_face($row[$type]),$label);
						}
					}
				    if($type=='pic' || $type=='picx' || $type=='img'){
				    	if($field == 'user' || $field == 'author'){
				        	$label=str_replace($field_arr[0][$i],getpic($row[$type],'user'),$label);
				    	}else{
				        	$label=str_replace($field_arr[0][$i],getpic($row[$type]),$label);
				    	}
				    }elseif($type=='addtime'){
				        $label=str_replace($field_arr[0][$i],date('Y-m-d H:i:s',$row[$type]),$label);
					}else{
				        $label=str_replace($field_arr[0][$i],$row[$type],$label);
					}

				}else{  //外部字段
				 	switch($type){
						//序
						case 'i'  :  
						    //判断从几开始
						    if(trim($field_arr[2][$i])=='len'){
						        $label = str_replace($field_arr[0][$i],($sorti+$field_arr[3][$i]),$label);
							}else{
						        $label = str_replace($field_arr[0][$i],$sorti,$label);
							}
						break;
						//分类名称
						case 'classname'  : 
							if(isset($row['mid'])){
						    	$cid = getzd('comic','cid',$row['mid']);
							}else{
						    	$cid = isset($row['cid'])? $row['cid'] : $row['id'];
							}
						    $label = str_replace($field_arr[0][$i],getzd('class','name',$cid),$label);
						break;
						//分类链接
						case 'classlink'  : 
							if(!isset($row['cid'])){
								$aa = array('id'=>$row['id'],'yname'=>$row['yname']);
							}else{
								if(isset($row['mid'])){
									$cid = getzd('comic','cid',$row['mid']);
								}else{
									$cid = $row['cid'];
								}
								$yname = getzd('class','yname',$cid);
								$aa = array('id'=>$cid,'yname'=>$yname);
							}
							$label = str_replace($field_arr[0][$i],get_url('lists',$aa),$label);
						break;
						//漫画链接
						case 'link'  : 
							if(isset($row['mid'])){
								$yname = getzd('comic','yname',$row['mid']);
								$aa = array('id'=>$row['mid'],'yname'=>$yname);
							}else{
								$aa = array('id'=>$row['id'],'yname'=>$row['yname']);
							}
							$label = str_replace($field_arr[0][$i],get_url('show',$aa),$label);
						break;
						//章节链接
						case 'piclink'  : 
							if(isset($row['mid'])){
								if(isset($row['cid'])){
									$aa = array('id'=>$row['cid'],'mid'=>$row['mid']);
								}else{
									$aa = array('id'=>$row['id'],'mid'=>$row['mid']);
								}
								$label = str_replace($field_arr[0][$i],get_url('pic',$aa),$label);
							}else{
								$cid = (int)getzd('comic_chapter','id',$row['id'],'mid','xid asc,id desc');
								$aa = array('id'=>$cid,'mid'=>$row['id']);
								if($cid > 0){
									$label = str_replace($field_arr[0][$i],get_url('pic',$aa),$label);
								}else{
									$label = str_replace($field_arr[0][$i],'',$label);
								}
							}
						break;
						//小说主页链接
						case 'booklink'  : 
							$label = str_replace($field_arr[0][$i],get_url('book'),$label);
						break;
						//小说分类链接
						case 'bclasslink'  : 
							if(isset($row['cid'])){
								if(isset($row['bid'])){
									$cid = getzd('book','cid',$row['bid']);
								}else{
									$cid = $row['cid'];
								}
								$yname = getzd('book_class','yname',$cid);
								$aa = array('id'=>$cid,'yname'=>$yname);
							}else{
								$aa = array('id'=>$row['id'],'yname'=>$row['yname']);
							}
							$label = str_replace($field_arr[0][$i],get_url('book_lists',$aa),$label);
						break;
						//小说详情链接
						case 'infolink'  : 
							if(isset($row['bid'])){
								$yname = getzd('book','yname',$row['bid']);
								$aa = array('id'=>$row['bid'],'yname'=>$yname);
							}else{
								$aa = array('id'=>$row['id'],'yname'=>$row['yname']);
							}
							$label = str_replace($field_arr[0][$i],get_url('book_info',$aa),$label);
						break;
						//小说阅读链接
						case 'readlink'  : 
							if(isset($row['bid'])){
								$aa = array('id'=>isset($row['cid'])?$row['cid']:$row['id'],'bid'=>$row['bid']);
								$label = str_replace($field_arr[0][$i],get_url('book_read',$aa),$label);
							}else{
								$zid = (int)getzd(get_chapter_table($row['id']),'id',$row['id'],'bid','xid asc,id desc');
								$aa = array('id'=>$zid,'bid'=>$row['id']);
								if($zid > 0){
									$label = str_replace($field_arr[0][$i],get_url('book_read',$aa),$label);
								}else{
									$label = str_replace($field_arr[0][$i],'',$label);
								}
							}
						break;
						//用户链接
						case 'authorlink'  : 
							if(!empty($row['author']) && $row['uid'] == 0){
								$ulink = get_url('search').'?key='.urlencode($row['author']);
								$label = str_replace($field_arr[0][$i],$ulink,$label);
							}else{
								$label = str_replace($field_arr[0][$i],get_url('author',$row),$label);
							}
						break;
						//作者主页链接
						case 'userlink'  : 
							$uid = isset($row['uid']) ? $row['uid'] : $row['id'];
							if(!empty($uid)){
								$label = str_replace($field_arr[0][$i],get_url('author/home/index/'.$uid),$label);
							}else{
								$label = str_replace($field_arr[0][$i],get_url('author',$row),$label);
							}
						break;
						//用户头像
						case 'authorpic'  : 
							$label = str_replace($field_arr[0][$i],getpic(getzd('user','pic',$row['uid'])),$label);
						break;
						//自定义字段
						case 'zdy'  :  
							if(trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
							    $arr = explode(',',$field_arr[3][$i]);
							    if(array_key_exists($arr[2],$row)){
							        $czd=empty($arr[3])?'id':$arr[3];
									$szd=$row[$arr[2]];
									if($arr[0] == 'book_chapter' && isset($row['bid'])) $arr[0] = get_chapter_table($row['bid']);
									$zdy = getzd($arr[0],$arr[1],$szd,$czd);
									if($arr[1] == 'pic') $zdy = getpic($zdy);
							        $label=str_replace($field_arr[0][$i],$zdy,$label);
								}
							}
						break;
						//自定义统计字段
						case 'count'  :  
							if(trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
							    $arr=explode(',',$field_arr[3][$i]);
							    if(array_key_exists($arr[1],$row)){
							        $czd = empty($arr[2])?'id':$arr[2];
									$szd = $row[$arr[1]];
									if($czd == 'uid' && empty($szd)){
										$czd = 'author';
										$szd = $row['author'];
									}
									$nums = $this->CI->mcdb->get_nums($arr[0],array($czd=>$szd));
							        $label=str_replace($field_arr[0][$i],$nums,$label);
								}
							}
						break;
						//自定义统计总和
						case 'sum'  :  
							if(trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
							    $arr=explode(',',$field_arr[3][$i]);
							    if(array_key_exists($arr[1],$row)){
							        $czd=empty($arr[2])?'id':$arr[2];
									$szd=$row[$arr[1]];
									$zd=$arr[3];
									$where[$czd] = $szd;
									if(isset($arr[4]) && isset($arr[5])){
										$where[$arr[4]] = $arr[5];
									}
									$nums = $this->CI->mcdb->get_sum($arr[0],$zd,$where);
							        $label=str_replace($field_arr[0][$i],$nums,$label);
								}
							}
						break;
						//类别
						case 'type'  :  
							if(trim($field_arr[2][$i]) == 'url'){
								$label = str_replace($field_arr[0][$i],taglink(get_type($row['id']),'tags'),$label);
							}else{
						    	$label = str_replace($field_arr[0][$i],get_type($row['id']),$label);
							}
						break;
						//tags
						case 'tags'  :  
							if(trim($field_arr[2][$i]) == 'url'){
								$label = str_replace($field_arr[0][$i],taglink(get_type($row['id']),'tags'),$label);
							}else{
						    	$label = str_replace($field_arr[0][$i],get_type($row['id']),$label);
							}
						break;
						//评分整数
						case 'scorenum'  : 
						    $label = str_replace($field_arr[0][$i],$row['score']*10,$label);
						break;
						//月票排名
						case 'ticket_rank'  : 
						    $label = str_replace($field_arr[0][$i],get_rank($row['id'],'ticket'),$label);
						break;
						//打赏排名
						case 'cion_rank'  : 
						    $label = str_replace($field_arr[0][$i],get_rank($row['id'],'cion'),$label);
						//小说月票排名
						case 'book_ticket_rank'  : 
						    $label = str_replace($field_arr[0][$i],get_rank($row['id'],'ticket','book'),$label);
						break;
						//小说打赏排名
						case 'book_cion_rank'  : 
						    $label = str_replace($field_arr[0][$i],get_rank($row['id'],'cion','book'),$label);
						break;
						//是否被赞
						case 'is_zan'  : 
						    $zan = 0;
							if($this->CI->cookie->get('user_id')){
								$uid = $this->CI->cookie->get('user_id');
								$fid = $field == 'comment' ? 0 :1;
								$rowz = $this->CI->mcdb->get_row('comment_zan','id',array('uid'=>$uid,'cid'=>$row['id'],'fid'=>$fid));
								if($rowz) $zan = 1;
							}
						    $label = str_replace($field_arr[0][$i],$zan,$label);
						break;
				 	}
				}
			}
		}
		unset($field_arr);
		return $label;
	}

	//组装SQL标签
	public function mccms_sql($fields, $para, $str_arr, $label, $sql='') {
		preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($para), $matches, PREG_SET_ORDER);
		$arr = array('field', 'table', 'page', 'where', 'limit', 'group', 'order');
		//获取数据表
		$table=$this->arr_val('table',$matches);
		if($table==''){  //模板标签错误，缺少table参数
			$strs=str_replace($label,".....",$str_arr);
			exit($strs.'标签中缺少 table ');
		}
		//获取要查询的字段
		$field=$this->arr_val('field',$matches);
		if(!$field) $field="*";
		$where = $this->arr_val('where',$matches);
		//组装SQL
		if($sql == ''){
			if($table == 'book_chapter'){
				preg_match('/bid=([0-9]+)/', $where,$match);
				$table = get_chapter_table($match[1]);
			}
			if(!$this->CI->db->table_exists(Mc_SqlPrefix.$table)){  //数据表不存在
			   $strs=str_replace($label,".....",$str_arr);
			   exit($strs.'标签中 数据库表《'.$table.'》不存在~');
			}
			$sql = "select ".$field." from ".Mc_SqlPrefix.$table;
		}else{
			$sql = str_replace("{field}",$field,$sql);
		}
		//获取要查询条件
		if(!empty($where)){
			$arr=explode("|",$where);
			$w=array();
			for($i=0;$i<count($arr);$i++){
				$arr1 = explode("=",$arr[$i]);
				if($table=='comic' && $arr1[0]=='cid' && is_numeric($arr1[1])){
					$cids = getcid($arr1[1]);
					if(is_numeric($cids)){
						$w[] = $arr[$i];
					}else{
						$w[] = "cid in(".$cids.")";
					}
				}else{
					$w[] = $arr[$i];
				}
			}
			$where = implode(' and ', $w);
			if(strpos(strtolower($sql),'where') === FALSE ){
				$sql.= ' where '.$where;
			}else{
				$sql.= ' and '.$where;
			}
		}
		//只显示审核通过的视频
		if(($table == 'comic' || $table == 'comic_chapter' || $table == 'book' || substr($table,0,12) == 'book_chapter') && 
			strpos(strtolower($sql),'uid=') === false){
			if(strpos(strtolower($sql),'where') === FALSE){
				$sql.= ' where yid=0';
			}else{
				$sql.= ' and yid=0';
			}
			if($table == 'comic' || $table == 'book'){
				$sql.= ' and sid=0';
			}
		}
		//去重复
		$group=$this->arr_val('group',$matches);
		if(!empty($group)) $sql.=' group by '.$group;
		//排序方式
		$order=$this->arr_val('order',$matches);
		if(!empty($order)) $sql.=' order by '.$order; 
		//显示数量
		$limit = $this->arr_val('limit',$matches);
		if($limit == 'all') $limit = '10000';
		if(!empty($limit)) $sql.=' limit '.$limit;
		unset($matches);
		return $sql;
	}

	//if标签处理
	public function labelif($Mark_Text){
		$Mark_Text = $this->labelif2($Mark_Text);
		$ifRule = "{if:(.*?)}(.*?){end if}";
		$ifRule2 = "{elseif";
		$ifRule3 = "{else}";
		$elseIfFlag = false;
		$ifFlag = false;
		preg_match_all('/'.$ifRule.'/is',$Mark_Text,$arr);
		if(!empty($arr[1][0])){
			for($i=0;$i<count($arr[1]);$i++){
		        $strIf = $arr[1][$i];
		        $strThen = $arr[2][$i];
				if(strpos($strThen, $ifRule2) !== FALSE) {
					$elseIfArr = explode($ifRule2, $strThen);
					$elseIfNum = count($elseIfArr);
					$elseIfSubArr = explode($ifRule3, $elseIfArr[$elseIfNum-1]);
					$resultStr = $elseIfSubArr[1];
					$elseIfstr = $elseIfArr[0];
					eval("if($strIf){\$resultStr=\"$elseIfstr\";}");
					for ($k = 1;$k < $elseIfNum;$k++){
						$temp = explode(":", $elseIfArr[$k], 2);
						$content = explode("}", $temp[1], 2);
						$strElseIf = $content[0];
						$temp1 = strpos($elseIfArr[$k],"}")+strlen("}");
						$temp2 = strlen($elseIfArr[$k])+1;
						$strElseIfThen = substr($elseIfArr[$k],$temp1,$temp2-$temp1);
						eval("if($strElseIf){\$resultStr=\"$strElseIfThen\";}");
						eval("if($strElseIf){\$elseIfFlag=true;}else{\$elseIfFlag=false;}");
						if ($elseIfFlag) {break;}
					}
					$temp = explode(":", $elseIfSubArr[0], 2);$content = explode("}", $temp[1], 2);
					$strElseIf0 = $content[0];
					$temp1 = strpos($elseIfSubArr[0],"}")+strlen("}");$temp2 = strlen($elseIfSubArr[0])+1;
					$strElseIfThen0 = substr($elseIfSubArr[0],$temp1,$temp2-$temp1);
					eval("if($strElseIf0){\$resultStr=\"$strElseIfThen0\";\$elseIfFlag=true;}");
					$Mark_Text=str_replace($arr[0][$i],$resultStr,$Mark_Text);
				}else{
                    if(strpos($strThen, "{else}") !== FALSE) {
						$elsearray = explode($ifRule3, $strThen);
						$strThen1 = $elsearray[0];
						$strElse1 = $elsearray[1];
						eval("if($strIf){\$ifFlag=true;}else{\$ifFlag=false;}");
						if ($ifFlag){
							$Mark_Text=str_replace($arr[0][$i],$strThen1,$Mark_Text);
						}else{
							$Mark_Text=str_replace($arr[0][$i],$strElse1,$Mark_Text);
						}
			        } else {
						eval("if($strIf){ \$ifFlag=true;} else{ \$ifFlag=false;}");
						if ($ifFlag){
							$Mark_Text=str_replace($arr[0][$i],$strThen,$Mark_Text);
						}else{
							$Mark_Text=str_replace($arr[0][$i],"",$Mark_Text);
						}
		            }
				}
			}
		}
		return $Mark_Text;
	}

	//if嵌套标签处理
	public function labelif2($Mark_Text){
		$ifRule = "{if2:(.*?)}(.*?){end if2}";
		$ifRule2 = "{elseif2";
		$ifRule3 = "{else2}";
		$elseIfFlag = false;
		$ifFlag = false;
		preg_match_all('/'.$ifRule.'/is',$Mark_Text,$arr);
		if(!empty($arr[1][0])){
			for($i=0;$i<count($arr[1]);$i++){
				$strIf = $arr[1][$i];
				$strThen = $arr[2][$i];
				if(strpos($strThen, $ifRule2) !== FALSE) {
					$elseIfArr = explode($ifRule2, $strThen);
					$elseIfNum = count($elseIfArr);
					$elseIfSubArr = explode($ifRule3, $elseIfArr[$elseIfNum-1]);
					$resultStr = $elseIfSubArr[1];
					$elseIfstr = $elseIfArr[0];
					eval("if($strIf){\$resultStr=\"$elseIfstr\";}");
					for ($k = 1;$k < $elseIfNum;$k++){
						$temp = explode(":", $elseIfArr[$k], 2);$content = explode("}", $temp[1], 2);
						$strElseIf = $content[0];
						$temp1 = strpos($elseIfArr[$k],"}")+strlen("}");$temp2 = strlen($elseIfArr[$k])+1;
						$strElseIfThen = substr($elseIfArr[$k],$temp1,$temp2-$temp1);
						eval("if($strElseIf){\$resultStr=\"$strElseIfThen\";}");
						eval("if($strElseIf){\$elseIfFlag=true;}else{\$elseIfFlag=false;}");
						if ($elseIfFlag) {break;}
					}
					$temp = explode(":", $elseIfSubArr[0], 2);$content = explode("}", $temp[1], 2);
					$strElseIf0 = $content[0];
					$temp1 = strpos($elseIfSubArr[0],"}")+strlen("}");$temp2 = strlen($elseIfSubArr[0])+1;
					$strElseIfThen0 = substr($elseIfSubArr[0],$temp1,$temp2-$temp1);
					eval("if($strElseIf0){\$resultStr=\"$strElseIfThen0\";\$elseIfFlag=true;}");
					$Mark_Text=str_replace($arr[0][$i],$resultStr,$Mark_Text);
				}else{
					if(strpos($strThen, $ifRule3) !== FALSE) {
						$elsearray = explode($ifRule3, $strThen);
						$strThen1 = $elsearray[0];
						$strElse1 = $elsearray[1];
						eval("if($strIf){\$ifFlag=true;}else{\$ifFlag=false;}");
						if ($ifFlag){
							$Mark_Text=str_replace($arr[0][$i],$strThen1,$Mark_Text);
						}else{
							$Mark_Text=str_replace($arr[0][$i],$strElse1,$Mark_Text);
						}
					} else {
						eval("if  ($strIf) { \$ifFlag=true;} else{ \$ifFlag=false;}");
						if ($ifFlag){
							$Mark_Text=str_replace($arr[0][$i],$strThen,$Mark_Text);
						}else{
							$Mark_Text=str_replace($arr[0][$i],"",$Mark_Text);
						}
					}
				}
			}
		}
		return $Mark_Text;
	}

	//查找数组指定元素
	protected function arr_val($key,$array){
		foreach ($array as $v) {
            if(strtolower($v[1])==$key){
                return $v[2];
			}
		}
		return NULL;
	}
}

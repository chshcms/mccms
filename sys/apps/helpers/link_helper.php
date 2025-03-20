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
//模版链接获取
function get_url($type = '',$arr = array(),$html = 0){
	$htmlarr = array('pic','show','lists','custom','index');
	//小说路由
	$type = str_replace('-','_',$type);
	$book_arr = array('book','book_lists','book_info','book_read','book_search','book_category');
	if(in_array($type,$book_arr)) return get_book_link($type,$arr,$html);
	//纯静态
	if(in_array($type,$htmlarr) && (Url_Mode == 1 || $html == 1)){
		if($type == 'pic'){
			$link = get_html_dir_file(Url_Html_Pic);
		}elseif($type == 'show'){
			$link = get_html_dir_file(Url_Html_Show);
		}elseif($type == 'lists'){
			$link = get_html_dir_file(Url_Html_List);
		}elseif($type == 'custom'){
			if(!empty($arr['file'])){
				$link = get_html_dir_file('custom/'.$arr['file'].'.html');
			}else{
				$link = Web_Path.'index.php/'.$type;
			}
		}else{
			$link = get_html_dir_file(Url_Html_Index);
		}
	}else{ //伪静态
		if($type == 'pic'){
			$link = Web_Path.'index.php/'.Url_Web_Pic;
		}elseif($type == 'show'){
			$link = Web_Path.'index.php/'.Url_Web_Show;
		}elseif($type == 'lists'){
			$link = Web_Path.'index.php/'.Url_Web_List;
		}elseif($type == 'category'){
			if(!empty($arr)){
				$ci = &get_instance();
				$uarr = $arr;unset($uarr['page']);
				$uri = safe_replace($ci->uri->assoc_to_uri($uarr));
				$link = Web_Path.'index.php/category/'.$uri.'/page/[page]';
			}else{
				$link = Web_Path.'index.php/category/page/'.$arr['page'];
			}
		}elseif($type == 'search'){
			if(!isset($arr['key'])){
				$link = Web_Path.'index.php/search';
			}else{
				$link = Web_Path.'index.php/search/'.$arr['key'].'/[page]';
			}
		}elseif($type == 'custom'){
			$link = Web_Path.'index.php/custom/'.$arr['file'];
		}elseif($type == 'home'){
			$link = Web_Path.'index.php/author/home/index/'.$arr['uid'];
		}else{
			if($type !== ''){
				$uarr = explode('/',$type);
				//漫画
				if($uarr[0] == 'comic' && isset($uarr[1])){
					$link = Url_Mode == 1 ? Web_Path.Url_Html_Show : Web_Path.'index.php/'.Url_Web_Show;
					$arr['id'] = $uarr[1] == 'index' ? (int)$uarr[2] : (int)$uarr[1];
					$arr['yname'] = getzd('comic','yname',$arr['id']);
				//章节
				}elseif($uarr[0] == 'chapter' && isset($uarr[2])){
					$link = Url_Mode == 1 ? Web_Path.Url_Html_Pic : Web_Path.'index.php/'.Url_Web_Pic;
					$arr['mid'] = $uarr[1] == 'index' ? (int)$uarr[2] : (int)$uarr[1];
					$arr['id'] = $uarr[1] == 'index' ? (int)$uarr[3] : (int)$uarr[2];
				}else{
					$link = Web_Path.'index.php/'.$type;
				}
			}else{
				$link = Web_Path;
			}
		}
	}
	//链接标签替换
	$link = get_link_replace($link,$arr);
	//判断小说独立域名
	if(Web_Book_Url != ''){
		$ssl = is_ssl() ? 'https://' : 'http://';
		if($link == Web_Path){
			$link = str_replace(Web_Path,$ssl.Web_Url.Web_Path,$link);
		}else{
			$link = str_replace(Web_Path.'index.php',$ssl.Web_Url.Web_Path.'index.php',$link);
		}
	}
	//是否去除index.php
	if(Url_Index_Mode == 1) $link = str_replace('/index.php','',$link);
	if(strpos($link,'://') === false) $link = str_replace('//','/',$link);
	return $link;
}
//小说链接
function get_book_link($type = '',$arr = array(),$html = 0){
	$htmlarr = array('book','book_lists','book_info','book_read');
	//纯静态
	if(in_array($type,$htmlarr) && (Url_Mode == 1 || $html == 1)){
		if($type == 'book_lists'){
			$link = Url_Book_Html_List;
		}elseif($type == 'book_info'){
			$link = Url_Book_Html_Info;
		}elseif($type == 'book_read'){
			$link = Url_Book_Html_Read;
		}else{
			$link = Url_Book_Html_Index;
		}
		$link = get_html_dir_file($link,'book');
	}else{ //伪静态
		if($type == 'book_lists'){
			$link = Web_Path.'index.php/'.Url_Book_Web_List;
		}elseif($type == 'book_info'){
			$link = Web_Path.'index.php/'.Url_Book_Web_Info;
		}elseif($type == 'book_read'){
			$link = Web_Path.'index.php/'.Url_Book_Web_Read;
		}elseif($type == 'book_category'){
			if(!empty($arr)){
				$ci = &get_instance();
				$uarr = $arr;unset($uarr['page']);
				$uri = str_replace("\\",'',safe_replace($ci->uri->assoc_to_uri($uarr)));
				$link = Web_Path.'index.php/book/category/'.$uri.'/page/[page]';
			}else{
				$link = Web_Path.'index.php/book/category/page/'.$arr['page'];
			}
		}elseif($type == 'book_search'){
			if(!isset($arr['key'])){
				$link = Web_Path.'index.php/book/search';
			}else{
				$link = Web_Path.'index.php/book/search/'.$arr['key'].'/[page]';
			}
		}else{
			if($type !== '' && $type !== 'book'){
				$link = Web_Path.'index.php/book/'.$type;
			}else{
				$link = Web_Path.'index.php/book';
			}
		}
	}
	//链接标签替换
	$link = get_link_replace($link,$arr);
	//判断独立域名
	if(Web_Book_Url != ''){
		$ssl = is_ssl() ? 'https://' : 'http://';
		$link = str_replace(Web_Path.'index.php/book',$ssl.Web_Book_Url,$link);
	}else{
		$link = str_replace('index.php/book/book','index.php/book',$link);
	}
	//是否去除index.php
	if(Url_Index_Mode == 1) $link = str_replace('/index.php','',$link);
	if(strpos($link,'://') === false) $link = str_replace('//','/',$link);
	return $link;
}
//分类类型检索链接
function get_category_url($arr=array(),$type='comic'){
	$i = 0;
	$lastval = '';
	$retval = array();
	foreach ($arr as $seg){
		if($i % 2){
			$retval[$lastval] = $seg;
		}else{
			$lastval = $seg;
		}
		$i++;
	}
	$ci = &get_instance();
    $uri = safe_replace($ci->uri->uri_string());
    $yarr = array();
    if(strpos($uri,'category') !== false){
	    $n = strpos($uri,'/index') !== false ? 3 : 2;
	    if(strpos($uri,'book/') !== false) $n++;
	    $yarr = $ci->uri->uri_to_assoc($n);
    }
    foreach ($retval as $key => $value) {
    	if(empty($value)){
    		unset($yarr[$key]);
    	}else{
    		$yarr[$key] = $value;
    	}
    }
    if(isset($yarr['page'])) unset($yarr['page']);
    //去除所有空值数组
    $uarr = array();
    foreach ($yarr as $key => $value) {
    	if(!empty($value)){
    		$uarr[$key] = $value;
    	}
    }
    $uri = safe_replace($ci->uri->assoc_to_uri($uarr));
    if($type == 'book'){
    	$link = Web_Path.'index.php/book/category/'.$uri;
		//判断独立域名
		if(Web_Book_Url != ''){
			$ssl = is_ssl() ? 'https://' : 'http://';
			$link = str_replace(Web_Path.'index.php/book',$ssl.Web_Book_Url.Web_Path.'index.php',$link);
		}
    }else{
    	$link = Web_Path.'index.php/category/'.$uri;
		//判断独立域名
		if(Web_Book_Url != ''){
			$ssl = is_ssl() ? 'https://' : 'http://';
			$link = str_replace(Web_Path.'index.php/',$ssl.Web_Url.Web_Path.'index.php/',$link);
		}
    }
	//是否去除index.php
	if(Url_Index_Mode == 1) $link = str_replace('/index.php','',$link);
	if(strpos($link,'://') === false) $link = str_replace('//','/',$link);
	return $link;
}
//静态URL标签替换
function get_link_replace($link,$arr = array()){
	if(!isset($arr['id'])) $arr['id'] = 0;
	if(!isset($arr['mid'])) $arr['mid'] = $arr['id'];
	if(!isset($arr['page'])) $arr['page'] = 1;
	if(!isset($arr['id'])) $arr['id'] = 0;
	if(!isset($arr['cid'])) $arr['cid'] = 0;
	if(!isset($arr['bid'])) $arr['bid'] = 0;
	if(!isset($arr['day'])) $arr['day'] = 0;
	if(!isset($arr['zt'])) $arr['zt'] = 0;
	if(!isset($arr['uid'])) $arr['uid'] = $arr['id'];
	if(!isset($arr['yname'])) $arr['yname'] = 'en';
	
	$a1 = array('[id]','[bid]','[mid]','[cid]','[uid]','[en]','[day]','[zt]');
	$a2 = array($arr['id'],$arr['bid'],$arr['mid'],$arr['cid'],$arr['uid'],$arr['yname'],$arr['day'],$arr['zt']);
	$link = str_replace($a1,$a2,$link);
	if($arr['page'] == 1){
		$link = str_replace('/page/[page]','',$link);
		$link = str_replace('/[page]','',$link);
	}
	$link = str_replace(array('[page]','//'),array($arr['page'],'/'),$link);
	return $link;
}
//静态生成路径补全
function get_html_file($path_file,$type='comic'){
	//目录结构
	if(substr($path_file,-1) == '/'){
		$path_file.='index.html';
	}else{
		$file_ext = strtolower(trim(substr(strrchr($path_file, '.'), 1)));
		$farr = array('html','htm','shtml','shtm');
		if(!in_array($file_ext,$farr)){
			$path_file.='/index.html';
		}
	}
	if(substr($path_file,0,1) !== '/'){
		$path_file = '/'.$path_file;
	}
	return $path_file;
}
//获取全站连接URL
function links($ac,$op='',$id=0,$where='',$html=0){ 
	if(!empty($op)) $ac.='/'.$op;
	if(empty($where)){
		if(empty($id)){
			$url=site_url($ac);
		}else{
			$url=site_url($ac.'/'.$id);
		}
	}else{
		if(empty($id)){
			$url=is_numeric($where) ? site_url($ac.'/'.$where) : site_url($ac).'?'.$where;
		}else{
			$url=is_numeric($where) ? site_url($ac.'/'.$id.'/'.$where) : site_url($ac.'/'.$id).'?'.$where;
		}
	}
   //是否去除index.php
	if(Url_Index_Mode == 1){
   		$url = str_replace("index.php/","",$url);
   	}
   	$url = str_replace("?&","?",$url);
   	return $url; 
}
//静态生成补全目录url
function get_html_dir_file($file,$type='comic'){
	$link = $file;
	if($type == 'comic'){
		if(defined('MOBILE')){
			$link = Wap_Html_Dir != '' ? Web_Path.Wap_Html_Dir.'/'.$link : Web_Path.$link;
		}
		if(defined('HTML_DIR')){
			if(HTML_DIR == 'wap'){
				$link = Wap_Html_Dir != '' ? Web_Path.Wap_Html_Dir.'/'.$link : Web_Path.$link;
			}else{
				$link = $file;
			}
		}
	}else{
		if(defined('MOBILE')){
			$link = Wap_Book_Html_Dir != '' ? Web_Path.Wap_Book_Html_Dir.'/'.$link : Web_Path.$link;
		}
		if(defined('HTML_DIR')){
			if(HTML_DIR == 'wap'){
				$link = Wap_Book_Html_Dir != '' ? Web_Path.Wap_Book_Html_Dir.'/'.$link : Web_Path.$link;
			}else{
				$link = $file;
			}
		}
	}
	if(substr($link,0,1) != '/') $link = Web_Path.$link;
	return $link;
}
//获取图片
function getpic($pic,$ac='empty'){ 
    if(empty($pic)){
		$pic = Web_Base_Path.'mccms/'.$ac.'.png';
    }else{
    	//域名前缀
		if(substr($pic,0,2) !== '//' && substr($pic,0,7) !== 'http://' && substr($pic,0,8) !== 'https://'){
			if(Annex_Mode == 1){ //FTP
				$pic = Annex_Ftp_Url.$pic;
			}elseif(Annex_Mode == 2){ //OSS
				$pic = Annex_Oss_Url.$pic;
			}elseif(Annex_Mode == 3){ //七牛
				$pic = Annex_Qniu_Url.$pic;
			}elseif(Annex_Mode == 4){ //又拍
				$pic = Annex_Up_Url.$pic;
			}else{
				if(substr($pic,0,1) !== '/') $pic = Web_Path.$pic;
			}
		}elseif(strpos($pic,'/manhua.qpic.cn/') !== false){
			$pic = Web_Path.'index.php/api/qpic/img?str='.sys_auth($pic);
		}
    }
    if(strpos($pic,'://') === false && substr($pic,0,2) !== '//') $pic = (is_ssl()?'https://': 'http://').Web_Url.$pic;
    return $pic; 
}

//后台分页
function admin_page($num,$pages,$page=1,$ac,$op='',$id=0,$where=''){
	if($pages < 2) return '';
	$phtml = '<span class="layui-laypage-count">共 '.$num.' 条</span>';
	$disabled =  $page == 1 ? ' layui-disabled' : '';
	$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page='.($page-1)).'" class="layui-laypage-prev'.$disabled.'">上一页</a>';
	if($pages<6 || $page<4){
		$len = $pages < 6 ? $pages : 5;
		for($i=1;$i<$len+1;$i++){
			if($page == $i){
				$phtml .= '<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'.$i.'</em></span>';
			}else{
				$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page='.$i).'">'.$i.'</a>';
			}
		}
		if($pages > 5){
			//尾页
			$phtml .= '<span class="layui-laypage-spr">…</span><a href="'.links($ac,$op,$id,$where.'&page='.$pages).'" class="layui-laypage-last">'.$pages.'</a>';
		}
	}else{//pages>$nums
		if($pages<$page+2){
			//尾页
			$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page=1').'" class="layui-laypage-last">1</a><span class="layui-laypage-spr">…</span>';
			for($i=$pages-4;$i<$pages+1;$i++){
				if($page == $i){
					$phtml .= '<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'.$i.'</em></span>';
				}else{
					$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page='.$i).'">'.$i.'</a>';
				}
			}
		}else{
			for($i=$page-2;$i<$page+3;$i++){
				if($page == $i){
					$phtml .= '<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'.$i.'</em></span>';
				}else{
					$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page='.$i).'">'.$i.'</a>';
				}
			}
			//尾页
			$phtml .= '<span class="layui-laypage-spr">…</span><a href="'.links($ac,$op,$id,$where.'&page='.$pages).'" class="layui-laypage-last">'.$pages.'</a>';
		}
	}
	//下一页
	if($page < $pages){
		$phtml .= '<a href="'.links($ac,$op,$id,$where.'&page='.($page+1)).'" class="layui-laypage-next">下一页</a>';
	}
	$phtml .= '<span class="layui-laypage-skip">到第<input type="number" min="1" onkeyup="this.value=this.value.replace(/\D/, \'\')" value="'.$page.'" id="laypage-skip" _url="'.links($ac,$op,$id,$where.'&page=').'" class="layui-input">页<button type="button" class="layui-laypage-btn">确定</button></span>';
	return $phtml;
}
//给字符加链接
function taglink($Key,$type=''){
	if(empty($Key)) return '';
     $List=$Key1="";
     $Str=" @,@，@|@/";
     $StrArr = explode('@',$Str);
     for($i=0;$i<5;$i++){
        if(stristr($Key,$StrArr[$i])){
            $Key1=explode($StrArr[$i],$Key);
        }
	 }
     if(is_array($Key1)){
        for($j=0;$j<count($Key1);$j++){
			$Key1[$j] = trimall($Key1[$j]);
			$tid = getzd('type','id',$Key1[$j],'name');
			$List.="<a target=\"_blank\" href=\"".get_category_url(array('tags',$tid))."\">".$Key1[$j]."</a> ";
		}
	 }else{
	    $Key = trimall($Key);
	    $tid = getzd('type','id',$Key,'name');
        $List="<a target=\"_blank\" href=\"".get_category_url(array('tags',$tid))."\">".$Key."</a> ";
	 }
     return $List;
}
//前台分页
function get_page($nums=1,$pagejs,$page=1,$size=10,$type='lists',$arr=array(),$parame=''){
   if($pagejs==0) $pagejs=1;
   if($page>$pagejs) $page=$pagejs;
   $arr['page'] = 1;$pagefirst = get_parame_url($type,$arr,$parame);
   $arr['page'] = $pagejs;$pagelast = get_parame_url($type,$arr,$parame);
   if($page > 1){
   		$arr['page'] = $page-1;$pageup= get_parame_url($type,$arr,$parame);
   }else{
	   $arr['page'] = 1;$pageup= get_parame_url($type,$arr,$parame);
   }
   if($pagejs > $page){
	   $arr['page'] = $page+1;$pagenext= get_parame_url($type,$arr,$parame);
   }else{
	   $arr['page'] = $pagejs;$pagenext= get_parame_url($type,$arr,$parame);
   }
   $str='';
   if($pagejs <= $size){
		for($i=1;$i<=$pagejs;$i++){
			$arr['page'] = $i;
			if($i == $page){
				$str.="<a class='on' href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
			}else{
				$str.="<a href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
			}
		}
   }else{
		if($page >= $size){
			for($i=$page-intval($size/2);$i<=$page+(intval($size/2));$i++){
				if($i<=$pagejs){
					$arr['page'] = $i;
					if($i==$page){
						$str.="<a class='on' href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
					}else{
						$str.="<a href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
					}
				}
			}
			if($i <= $pagejs){
				$arr['page'] = $pagejs;
				$str.="<a href='".get_parame_url($type,$arr,$parame)."'>".$pagejs."</a>";
			}
		}else{
			for($i=1;$i<=$size;$i++){
				$arr['page'] = $i;
				if($i == $page){
					$str.="<a class='on' href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
				}else{
					$str.="<a href='".get_parame_url($type,$arr,$parame)."'>".$i."</a>";
				}
			} 
			if($i <= $pagejs){ 
				$arr['page'] = $pagejs;
				$str.="<a href='".get_parame_url($type,$arr,$parame)."'>".$pagejs."</a>";
			}
		}
   }
   $pagelist="<select onchange=javascript:window.location=this.options[this.selectedIndex].value;>";
   for($k=1;$k<=$pagejs;$k++){
	   $cls = ($k==$page)?' selected':'';
	   $arr['page'] = $k;
	   $pagelist.="<option value='".get_parame_url($type,$arr,$parame)."'".$cls.">第".$k."页</option>";
   }
   $pagelist.="</select>";	
   return array($pagefirst,$pagelast,$pageup,$pagenext,$str,$pagelist);
}
function get_parame_url($type,$arr,$parame=''){
	$url = get_url($type,$arr);
	if(!empty($parame)) $url .= '?'.$parame;
	return $url;
}
//前台评论分页
function get_comment_page($nums=1,$pagejs,$page=1,$size=10,$mid=0,$bid=0){
	$zd = $mid == 0 ? 'bid' : 'mid';
	if($mid == 0) $mid = $bid;
    if($pagejs==0) $pagejs=1;
    if($page>$pagejs) $page=$pagejs;
    $pagefirst = 'javascript:mccms.comment({'.$zd.':'.$mid.',page:1});';
    $pagelast = 'javascript:mccms.comment({'.$zd.':'.$mid.',page:'.$pagejs.'});';
    if($page > 1){
   		$pageup= 'javascript:mccms.comment({'.$zd.':'.$mid.',page:'.($page-1).'});';
    }else{
	   $pageup= 'javascript:mccms.comment({'.$zd.':'.$mid.',page:'.$page.'});';
    }
    if($pagejs > $page){
	   $pagenext = 'javascript:mccms.comment({'.$zd.':'.$mid.',page:'.($page+1).'});';
    }else{
	   $pagenext= 'javascript:mccms.comment({'.$zd.':'.$mid.',page:'.$pagejs.'});';
    }
    $str='';
    if($pagejs <= $size){
		for($i=1;$i<=$pagejs;$i++){
			if($i == $page){
				$str.="<a class='on' href='javascript:mccms.comment({".$zd.":".$mid.",page:".$i."});'>".$i."</a>";
			}else{
				$str.="<a href='javascript:mccms.comment({".$zd.":".$mid.",page:".$i."});'>".$i."</a>";
			}
		}
	}else{
		if($page >= $size){
			for($i=$page-intval($size/2);$i<=$page+(intval($size/2));$i++){
				if($i<=$pagejs){
					if($i==$page){
						$str.="<a class='on' href='javascript:mccms.comment({".$zd.":".$mid.",page:".$i."});'>".$i."</a>";
					}else{
						$str.="<a href='javascript:mccms.comment({".$zd.":".$mid.",page:".$i."});'>".$i."</a>";
					}
				}
			}
			if($i <= $pagejs){
				$str.="<a href='javascript:mccms.comment({".$zd.":".$mid.",page:".$pagejs."});'>".$pagejs."</a>";
			}
		}else{
			for($i=1;$i<=$size;$i++){
				$arr['page'] = $i;
				if($i == $page){
					$str.="<a href='".get_url($type,$arr)."'>".$i."</a>";
				}else{
					$str.="<a href='".get_url($type,$arr)."'>".$i."</a>";
				}
			} 
			if($i <= $pagejs){ 
				$arr['page'] = $pagejs;
				$str.="<a href='javascript:mccms.comment({".$zd.":".$mid.",page:".$pagejs."});'>".$pagejs."</a>";
			}
		}
	}
	$pagelist="<select onchange=javascript:mccms.comment({".$zd.":".$mid.",page:this.options[this.selectedIndex].value});>";
	for($k=1;$k<=$pagejs;$k++){
	   $cls = ($k==$page)?' selected':'';
	   $pagelist.="<option value='".$k."'".$cls.">第".$k."页</option>";
	}
	$pagelist.="</select>";	
	return array($pagefirst,$pagelast,$pageup,$pagenext,$str,$pagelist);
}
//获取分页数目	
function getpagenum($str){
	preg_match('/\[mccms_pagenum_([\d]*)\]/',$str,$pagearr);
	if(!empty($pagearr)){
		if(isset($pagearr[1]) && (int)$pagearr[1] > 0){
			$pagenum = $pagearr[1];
		}else{
			$pagenum = 10;
		}	
	}else{
		$pagenum = 10;
	}
	unset($pagearr);
	return $pagenum;
}
//分页标签替换
function getpagetpl($str,$pagearr=array()){
	$str = preg_replace('/\[mccms_pagenum_([\d]*)\]/',$pagearr[4],$str);
	$str = str_replace('[mccms_pagefirst]',$pagearr[0],$str);  //首页
	$str = str_replace('[mccms_pagelast]',$pagearr[1],$str);  //尾页
	$str = str_replace('[mccms_pageup]',$pagearr[2],$str);  //上页
	$str = str_replace('[mccms_pagedown]',$pagearr[3],$str);  //下页
	$str = str_replace('[mccms_pagelist]',$pagearr[5],$str);  //翻页
	$str = str_replace('[mccms_pagesize]',$pagearr[6],$str); //每页数量
	$str = str_replace('[mccms_pagenum]',$pagearr[7],$str); //总数量
	$str = str_replace('[mccms_pagejs]',$pagearr[8],$str); //总页数
	$str = str_replace('[mccms_page]',$pagearr[9],$str);  //当前页
	return $str;
}
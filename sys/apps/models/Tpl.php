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

class Tpl extends CI_Model{

    function __construct (){
        parent:: __construct ();
    }

    //主页
    function index(){
        $str = load_file('index.html');
        $str = $this->parser->parse_string($str,array(),true);
        //IF判断解析
        return $this->parser->labelif($str);
    }

    //分类页
    function lists($id,$page,$mark=0){
        $data = array();
        if(!is_numeric($id)){
            $row = $this->mcdb->get_row_arr('class','*',array('yname'=>safe_replace($id)));
        }else{
            $row = $this->mcdb->get_row_arr('class','*',array('id'=>(int)$id));
        }
        //分类不存在
        if(!$row) get_err();
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val){
            $data['class_'.$key] = $val;
        }
        //当前ID
        $data['mccms_cid'] = $row['id'];
        $data['mccms_fid'] = $row['fid'] == 0 ? $row['id'] : $row['fid'];

        //获取模板
        $tpl = empty($row['tpl']) ? 'lists.html' : $row['tpl'];
        $str = load_file($tpl);
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
              //每页数量
              $per_page = (int)$arr[3][0];
              //获取分类下所有ID
              $cids = getcid($row['id']);
              //组装SQL数据
              if(strpos($cids,',') !== FALSE){
                  $sql = 'select {field} from '.Mc_SqlPrefix.'comic where cid in('.$cids.')';
              }else{
                  $sql = 'select {field} from '.Mc_SqlPrefix.'comic where cid='.$cids;
              }
              $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
              //总数量
              $total = $this->mcdb->get_sql_nums($sqlstr);
              //总页数
              $pagejs = ceil($total / $per_page);
              if($pagejs == 0) $pagejs = 1;
              if($total < $per_page) $per_page = $total;
              $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
              $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
              //解析分页
              $pagenum = getpagenum($str);
              $pagearr = get_page($total,$pagejs,$page,$pagenum,'lists',$row); 
              $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
              $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('class',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        if($mark == 1){
            return array($str,$pagejs);
        }else{
            return $str;
        }
    }

    //漫画内容页
    function comic($id){
        $data = array();
        if(!is_numeric($id)){
            $row = $this->mcdb->get_row_arr('comic','*',array('yname'=>safe_replace($id)));
        }else{
            $row = $this->mcdb->get_row_arr('comic','*',array('id'=>(int)$id));
        }
        //分类不存在或者被锁定
        if(!$row || $row['sid'] == 1 || $row['yid'] > 0) get_err('comic');
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //作者链接
        $data['comic_authorlink'] = get_url('author',array('uid'=>$row['uid']));
        //作者头像
        $data['comic_authorpic'] = getpic(getzd('user','pic',$row['uid']));
        //当前数据
        foreach ($row as $key => $val){
            if($key == 'pic' || $key =='picx'){
                $data['comic_'.$key] = getpic($val);
            }else{
                $data['comic_'.$key] = $val;
            }
        }
        //获取模板
        $str = load_file('comic.html');
        //判断章节入库
        $zjnum = $this->mcdb->get_nums('comic_chapter',array('mid'=>$row['id']));
        if($row['nums'] == 0 || $zjnum == 0 || $zjnum < $row['nums']){
            //解析章节标签
            if((Caiji_Tb_Chapter == 0 || $zjnum == 0) && $row['did'] > 0){
                $this->load->model('collect');
                $arr = require MCCMSPATH.'libs/collect.php';
                if(!empty($row['ly'])){
                    if(isset($arr['zyk'][$row['ly']])){
                        $this->collect->get_update_chapter($arr['zyk'][$row['ly']]['jxurl'].'/index/'.$row['did'],$row['id'],'comic','comic_chapter',$arr['zyk'][$row['ly']]['token']);
                    }
                }else{
                    $this->collect->get_update_chapter(Caiji_Tb_Url.'/index/'.$row['did'],$row['id'],'comic','comic_chapter',Caiji_Tb_Token);
                }
            }
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('comic',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        //增加人气
        $arr = explode('</body>',$str);
        $str = $arr[0].'<script src="'.links('api','hits/comic',$row['id']).'"></script></body>';
        if(isset($arr[1])) $str .= $arr[1];
        return $str;
    }

    //漫画章节
    function chapter($id){
        $data = array();
        $row = $this->mcdb->get_row_arr('comic_chapter','*',array('id'=>$id));
        //章节不存在
        if(!$row || $row['yid'] > 0) get_err();
        //判断漫画是否审核
        $rowm = $this->mcdb->get_row_arr('comic','yid,sid,ly,nums',array('id'=>$row['mid']));
        if($rowm['yid'] > 0 || $rowm['sid'] > 0) get_err();
        //同步远程图片到本地
        $picarr = $this->mcdb->get_select('comic_pic','id,img',array('cid'=>$id),'id ASC',10000);
        //如果章节图片为空时
        if(count($picarr) < $rowm['nums'] && !empty($row['jxurl'])){
            $this->load->model('collect');
            $collect = require MCCMSPATH.'libs/collect.php';
            $ly = $rowm['ly'];
            $token = isset($collect['zyk'][$ly]) ? $collect['zyk'][$ly]['token'] : Caiji_Tb_Token;
            $this->collect->get_update_pic($row['jxurl'],$row['id'],$row['mid'],$token);
        }
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //章节链接
        $rowm = $this->mcdb->get_row_arr('comic','id,cid,yname,name',array('id'=>$row['mid']));
        $data['comic_link'] = get_url('show',$rowm);
        $data['comic_name'] = $rowm['name'];
        //上一话，下一话
        $rowd = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('xid<'=>$row['xid'],'mid'=>$row['mid']),'xid desc');
        if($rowd){
            $data['chapter_slink'] = get_url('pic',$rowd);
            $data['chapter_sname'] = $rowd['name'];
            $data['chapter_sid'] = $rowd['id'];
        }else{
            $data['chapter_slink'] = '';
            $data['chapter_sname'] = '没有了';
            $data['chapter_sid'] = 0;
        }
        $rowd = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('xid>'=>$row['xid'],'mid'=>$row['mid']),'xid asc');
        if($rowd){
            $data['chapter_xlink'] = get_url('pic',$rowd);
            $data['chapter_xname'] = $rowd['name'];
            $data['chapter_xid'] = $rowd['id'];
        }else{
            $data['chapter_xlink'] = '';
            $data['chapter_xname'] = '没有了';
            $data['chapter_xid'] = 0;
        }
        //当前数据
        foreach ($row as $key => $val){
            $data['chapter_'.$key] = $val;
        }
        //获取模板
        $str = load_file('chapter.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('chapter',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        //增加人气
        $arr = explode('</body>',$str);
        $str = $arr[0].'<script src="'.links('api','hits/comic',$row['mid']).'"></script></body>';
        if(isset($arr[1])) $str .= $arr[1];
        return $str;
    }

    //搜索页
    function search($key='',$page=1){
        $data = array();
        //网站标题
        $data['mccms_title'] = $key.' - '.Web_Name;
        //当前搜索关键字
        $data['mccms_key'] = $key;

        //获取模板
        $str = load_file('search.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            if(!empty($key)){
				$myver = $this->db->version();
				preg_match_all("/./us",$key,$match);
				if(count($match[0]) > 3 && $myver > '5.6.9'){
					$sql1 = "SELECT * FROM information_schema.statistics WHERE table_schema=DATABASE() AND table_name = '".Mc_SqlPrefix."comic' AND index_name = 'name_text'";
					$res = $this->db->query($sql1)->row_array();
					if(!$res){
						$sql2 = "ALTER TABLE ".Mc_SqlPrefix."comic ADD FULLTEXT INDEX name_text (`name`,`author`) WITH PARSER ngram";
						$this->db->query($sql2);
					}
					$sql = "select {field} from ".Mc_SqlPrefix."comic where match(name,author) against ('".$key."*' IN BOOLEAN MODE)";
				}else{
					$sql1 = "SELECT * FROM information_schema.statistics WHERE table_schema=DATABASE() AND table_name = '".Mc_SqlPrefix."comic' AND index_name = 'name'";
					$res = $this->db->query($sql1)->row_array();
					if(!$res){
						$sql2 = "ALTER TABLE `".Mc_SqlPrefix."comic` ADD INDEX name( `name` )";
						$this->db->query($sql2);
					}
					$sql = "select {field} from ".Mc_SqlPrefix."comic where (name like '%".$key."%' or author like '%".$key."%')";
				}
            }else{
                $sql = 'select {field} from '.Mc_SqlPrefix.'comic where id=0';
            }
            $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
            //总数量
            $total = $this->mcdb->get_sql_nums($sqlstr);
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'search',array('key'=>$key));
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //IF判断解析
        $str = $this->parser->labelif($str);
        return $str;
    }

    //智能检索页
    function category($arr=''){
        $data = array();
        //网站标题
        $data['mccms_title'] = '漫画大全 - 漫画分类检索 - '.Web_Name;
        //先取出分页page
        $page = !isset($arr['page']) ? 1 : (int)$arr['page'];
        if($page == 0) $page = 1;
        unset($arr['page']);
        $data['title'] = '漫画大全';

        $sql="select a.* from ".Mc_SqlPrefix."comic a inner join ".Mc_SqlPrefix."comic_type b on a.id = b.mid where a.yid=0";
        $order = 'a.hits desc';
        $title = $tpl = $wh = array();$type = 0;
        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                $tpl[$k] = $v; //标签解析
                //首字母
                if($k == 'mark'){
                    $zimu_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                    $zimu_arr1 = array(-20319,-20283,-19775,-19218,-18710,-18526,-18239,-17922,-1,-17417,-16474,-16212,-15640,-15165,-14922,-14914,-14630,-14149,-14090,-13318,-1,-1,-12838,-12556,-11847,-11055);
                    $zimu_arr2 = array(-20284,-19776,-19219,-18711  ,-18527,-18240,-17923,-17418,-1,-16475,-16213,-15641,-15166,-14923,-14915,-14631,-14150,-14091,-13319,-12839,-1,-1,-12557,-11848,-11056,-2050);
                    if(!in_array(strtoupper($v),$zimu_arr)){
                        $sql .= $wh[]=" and substring(a.name,1,1) NOT REGEXP '^[a-zA-Z]' and substring(a.name,1,1) REGEXP '^[u4e00-u9fa5]'";
                    }else{
                        $posarr = array_keys($zimu_arr,strtoupper($v));
                        $pos=$posarr[0];
                        $sql .= $wh[]=" and (((ord(substring(convert(a.name USING gbk),1,1)) -65536>=".($zimu_arr1[$pos])." and  ord(substring(convert(a.name USING gbk),1,1)) -65536<=".($zimu_arr2[$pos]).")) or UPPER(substring(convert(a.name USING gbk),1,1))='".$zimu_arr[$pos]."')";
                    }
                    $title[] = $data['title'] = '首字母'.$v;
                }elseif($k == 'pay'){ //是否收费
                    if($v == 3){ //VIP
                        $sql .= $wh[]=" and a.pay=2";
                        $title[] = $data['title'] = 'VIP漫画';
                    }elseif($v == 2){ //收费
                        $sql .= $wh[]=" and a.pay=1";
                        $title[] = $data['title'] = '收费漫画';
                    }elseif($v == 1){ //免费
                        $sql .= $wh[]=" and a.pay=0";
                        $title[] = $data['title'] = '免费漫画';
                    }
                }elseif($k == 'list'){ //分类
                    $title[] = $data['title'] = getzd('class','name',$v);
                    if((int)$v > 0){
                        $cids = getcid($v);
                        if(!is_numeric($cids)){
                            $sql .= $wh[]=" and a.cid in(".$cids.")";
                        }else{
                            $sql .= $wh[]=" and a.cid=".$cids;
                        }
                    }
                }elseif($k == 'finish'){ //连载
                    if($v == 2){
                        $sql .= $wh[]=" and a.serialize='完结'";
                        $title[] = $data['title'] = '完结';
                    }elseif($v == 1){
                        $sql .= $wh[]=" and a.serialize='连载'";
                        $title[] = $data['title'] = '连载';
                    }
                }elseif($k == 'order'){ //排序
                    $oarr = array('id','addtime','hits','yhits','rhits','zhits','shits');
                    if(in_array($v,$oarr)) $order = 'a.'.$v.' desc';
                }else{
                    $title[] = $data['title'] = getzd('type','name',$v);
                    //判断type是否存在
                    $rt = $this->mcdb->get_row('type','id',array('zd'=>$k,'fid'=>0));
                    if($rt){
                        $type = 1;
                        $sql .= " and b.tid=".(int)$v;
                    }
                }
            }
            if(!empty($title)){
                $data['mccms_title'] = implode(' -  ',$title).' - '.$data['mccms_title'];
            }
        }
        $sql .= $wh[]=' order by '.$order;
        if($type == 0){
            $sql = "select * from ".Mc_SqlPrefix."comic where yid = 0".str_replace('a.','',implode('',$wh));
        }
        //获取模板
        $str = load_file('category.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$tplarr);
        if(!empty($tplarr[3])){
            //每页数量
            $per_page = (int)$tplarr[3][0];
            //组装SQL数据
            $sqlstr = $sql;
            //总数量
            $total = $this->mcdb->get_sql_nums($sqlstr);
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($tplarr[1][0],$tplarr[2][0],$tplarr[0][0],$tplarr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'category',$arr);
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //替换检索标签
        if(!isset($tpl['order'])) $tpl['order'] = 'hits';
        if(!isset($tpl['list'])) $tpl['list'] = 0;
        if(!isset($tpl['pay'])) $tpl['pay'] = 0;
        if(!isset($tpl['finish'])) $tpl['finish'] = 0;
        foreach ($tpl as $k=> $v) {
            $str = str_replace('['.$k.']',$v,$str);
        }
        $str = str_replace('[mccms_json]',json_encode($tpl),$str);
        //IF判断解析
        $str = $this->parser->labelif($str);
        return $str;
    }

    //自定义模版
    function custom($op){
        $op = safe_replace($op);
        $str = load_file('custom/'.$op.'.html');
        $str = $this->parser->parse_string($str,array(),true);
        //IF判断解析
        $str = $this->parser->labelif($str);
        return $str;
    }

    //小说主页
    function book_index(){//网站标题
        $data['mccms_title'] = '好看的小说 - '.Web_Name;
        $str = load_file('book/index.html');
        $str = $this->parser->parse_string($str,$data,true);
        //IF判断解析
        return $this->parser->labelif($str);
    }

    //小说分类页
    function book_lists($id,$page,$mark=0){
        $data = array();
        if(!is_numeric($id)){
            $row = $this->mcdb->get_row_arr('book_class','*',array('yname'=>safe_replace($id)));
        }else{
            $row = $this->mcdb->get_row_arr('book_class','*',array('id'=>(int)$id));
        }
        //分类不存在
        if(!$row) get_err();
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //当前数据
        foreach ($row as $key => $val){
            $data['class_'.$key] = $val;
        }
        //当前ID
        $data['mccms_cid'] = $row['id'];
        $data['mccms_fid'] = $row['fid'] == 0 ? $row['id'] : $row['fid'];

        //获取模板
        $tpl = 'book/'.(empty($row['tpl']) ? 'lists.html' : $row['tpl']);
        $str = load_file($tpl);
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
              //每页数量
              $per_page = (int)$arr[3][0];
              //获取分类下所有ID
              $cids = getcid($row['id']);
              //组装SQL数据
              if(strpos($cids,',') !== FALSE){
                  $sql = 'select {field} from '.Mc_SqlPrefix.'book where cid in('.$cids.')';
              }else{
                  $sql = 'select {field} from '.Mc_SqlPrefix.'book where cid='.$cids;
              }
              $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
              //总数量
              $total = $this->mcdb->get_sql_nums($sqlstr);
              //总页数
              $pagejs = ceil($total / $per_page);
              if($pagejs == 0) $pagejs = 1;
              if($total < $per_page) $per_page = $total;
              $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
              $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
              //解析分页
              $pagenum = getpagenum($str);
              $pagearr = get_page($total,$pagejs,$page,$pagenum,'book_lists',$row); 
              $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
              $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('class',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        if($mark == 1){
            return array($str,$pagejs);
        }else{
            return $str;
        }
    }

    //智能检索页
    function book_category($arr=''){
        $data = array();
        //网站标题
        $data['mccms_title'] = '书库 - '.Web_Name;
        //先取出分页page
        $page = !isset($arr['page']) ? 1 : (int)$arr['page'];
        if($page == 0) $page = 1;
        unset($arr['page']);

        $sql="select * from ".Mc_SqlPrefix."book where yid=0";
        $order = 'hits desc';
        $data['title'] = '小说大全';
        $title = $tpl = $wh = array();
        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                $v = urldecode(str_replace("\\","",$v));
                $tpl[$k] = $v; //标签解析
                //首字母
                if($k == 'mark'){
                    $zimu_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                    $zimu_arr1 = array(-20319,-20283,-19775,-19218,-18710,-18526,-18239,-17922,-1,-17417,-16474,-16212,-15640,-15165,-14922,-14914,-14630,-14149,-14090,-13318,-1,-1,-12838,-12556,-11847,-11055);
                    $zimu_arr2 = array(-20284,-19776,-19219,-18711  ,-18527,-18240,-17923,-17418,-1,-16475,-16213,-15641,-15166,-14923,-14915,-14631,-14150,-14091,-13319,-12839,-1,-1,-12557,-11848,-11056,-2050);
                    if(!in_array(strtoupper($v),$zimu_arr)){
                        $sql .= " and substring(name,1,1) NOT REGEXP '^[a-zA-Z]' and substring(name,1,1) REGEXP '^[u4e00-u9fa5]'";
                    }else{
                        $posarr = array_keys($zimu_arr,strtoupper($v));
                        $pos=$posarr[0];
                        $sql .= " and (((ord(substring(convert(name USING gbk),1,1)) -65536>=".($zimu_arr1[$pos])." and  ord(substring(convert(name USING gbk),1,1)) -65536<=".($zimu_arr2[$pos]).")) or UPPER(substring(convert(name USING gbk),1,1))='".$zimu_arr[$pos]."')";
                    }
                    $title[] = $data['title'] = '首字母'.$v;
                }elseif($k == 'pay'){ //是否收费
                    if($v == 3){ //VIP
                        $sql .= " and pay=2";
                        $title[] = $data['title'] = 'VIP小说';
                    }elseif($v == 2){ //收费
                        $sql .= " and pay=1";
                        $title[] = $data['title'] = '收费小说';
                    }elseif($v == 1){ //免费
                        $sql .= " and pay=0";
                        $title[] = $data['title'] = '免费小说';
                    }
                }elseif($k == 'list'){ //分类
                    $title[] = $data['title'] = getzd('book_class','name',$v);
                    if((int)$v > 0){
                        $cids = getcid($v);
                        if(!is_numeric($cids)){
                            $sql .= " and cid in(".$cids.")";
                        }else{
                            $sql .= " and cid=".$cids;
                        }
                    }
                }elseif($k == 'finish'){ //连载
                    if($v == 2){
                        $sql .= " and serialize='完结'";
                        $title[] = $data['title'] = '完结';
                    }elseif($v == 1){
                        $sql .= " and serialize='连载'";
                        $title[] = $data['title'] = '连载';
                    }
                }elseif($k == 'size'){ //字数
                    if($v == 5){
                        $sql .= " and text_num>2000000";
                    }elseif($v == 4){
                        $sql .= " and text_num>999999 and text_num<2000000";
                    }elseif($v == 3){
                        $sql .= " and text_num>499999 and text_num<1000000";
                    }elseif($v == 2){
                        $sql .= " and text_num>299999 and text_num<500000";
                    }elseif($v == 1){
                        $sql .= " and text_num>299999";
                    }
                }elseif($k == 'order'){ //排序
                    $oarr = array('id','addtime','hits','yhits','rhits','zhits','shits');
                    if(in_array($v,$oarr)) $order = $v.' desc';
                }elseif($k == 'tags'){ //主题标签
                    $tagarr = explode('|',Web_Book_Tags);
                    $tag_id = $v-1;
                    $tags = isset($tagarr[$tag_id]) ? $tagarr[$tag_id] : '';
                    if(!empty($tags)){
                        $title[] = $data['title'] = $tags;
                        $sql .= " and tags like '%".safe_replace($tags)."%'";
                    }
                }
            }
            if(!empty($title)){
                $data['mccms_title'] = implode(' -  ',$title).' - '.$data['mccms_title'];
            }
        }
        $sql .= ' order by '.$order;
        //获取模板
        $str = load_file('book/category.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$tplarr);
        if(!empty($tplarr[3])){
            //每页数量
            $per_page = (int)$tplarr[3][0];
            //组装SQL数据
            $sqlstr = $sql;
            //总数量
            $total = $this->mcdb->get_sql_nums($sqlstr);
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($tplarr[1][0],$tplarr[2][0],$tplarr[0][0],$tplarr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'book_category',$arr);
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //替换检索标签
        if(!isset($tpl['order'])) $tpl['order'] = 'hits';
        if(!isset($tpl['list'])) $tpl['list'] = 0;
        if(!isset($tpl['pay'])) $tpl['pay'] = 0;
        if(!isset($tpl['finish'])) $tpl['finish'] = 0;
        if(!isset($tpl['tags'])) $tpl['tags'] = 0;
        if(!isset($tpl['size'])) $tpl['size'] = 0;
        foreach ($tpl as $k=> $v) {
            $str = str_replace('['.$k.']',$v,$str);
        }
        $str = str_replace('[mccms_json]',json_encode($tpl),$str);
        //IF判断解析
        $str = $this->parser->labelif($str);
        return $str;
    }

    //搜索页
    function book_search($key='',$page=1){
        $data = array();
        //网站标题
        $data['mccms_title'] = $key.' - '.Web_Name;
        //当前搜索关键字
        $data['mccms_key'] = $key;

        //获取模板
        $str = load_file('book/search.html');
        //预先解析分页标签
        $pagejs = 1;
        preg_match_all('/{mccms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/mccms:\1}/',$str,$arr);
        if(!empty($arr[3])){
            //每页数量
            $per_page = (int)$arr[3][0];
            //组装SQL数据
            if(!empty($key)){
                //$sql = "select {field} from ".Mc_SqlPrefix."book where yid=0 and sid=0 and name like '%".$key."%' ESCAPE '!'";
                $myver = $this->db->version();
                preg_match_all("/./us",$key,$match);
                if(count($match[0]) > 3 && $myver > '5.6.9'){
                    $sql1 = "SELECT * FROM information_schema.statistics WHERE table_schema=DATABASE() AND table_name = '".Mc_SqlPrefix."book' AND index_name = 'name_text'";
                    $res = $this->db->query($sql1)->row_array();
                    if(!$res){
                        $sql2 = "ALTER TABLE ".Mc_SqlPrefix."book ADD FULLTEXT INDEX name_text (`name`,`author`) WITH PARSER ngram";
                        $this->db->query($sql2);
                    }
                    $sql = "select {field} from ".Mc_SqlPrefix."book where match(name,author) against ('".$key."*' IN BOOLEAN MODE)";
                }else{
                    $sql1 = "SELECT * FROM information_schema.statistics WHERE table_schema=DATABASE() AND table_name = '".Mc_SqlPrefix."book' AND index_name = 'name'";
                    $res = $this->db->query($sql1)->row_array();
                    if(!$res){
                        $sql2 = "ALTER TABLE `".Mc_SqlPrefix."book` ADD INDEX name( `name` )";
                        $this->db->query($sql2);
                    }
                    $sql = "select {field} from ".Mc_SqlPrefix."book where (name like '%".$key."%' or author like '%".$key."%')";
                }
            }else{
                $sql = 'select {field} from '.Mc_SqlPrefix.'book where yid=0 and sid=0';
            }
            $sqlstr = $this->parser->mccms_sql($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$sql);
            //总数量
            $total = $this->mcdb->get_sql_nums($sqlstr);
            //总页数
            $pagejs = ceil($total / $per_page);
            if($pagejs == 0) $pagejs = 1;
            if($total < $per_page) $per_page = $total;
            $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
            $str = $this->parser->mccms_skins($arr[1][0],$arr[2][0],$arr[0][0],$arr[4][0],$str, $sqlstr);
            //解析分页
            $pagenum = getpagenum($str);
            $pagearr = get_page($total,$pagejs,$page,$pagenum,'book_search',array('key'=>$key));
            $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
            $str = getpagetpl($str,$pagearr);
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //IF判断解析
        $str = $this->parser->labelif($str);
        return $str;
    }

    //小说详情页
    function book_info($id){
        $data = array();
        if(!is_numeric($id)){
            $row = $this->mcdb->get_row_arr('book','*',array('yname'=>safe_replace($id)));
        }else{
            $row = $this->mcdb->get_row_arr('book','*',array('id'=>(int)$id));
        }
        //分类不存在或者被锁定
        if(!$row || $row['sid'] == 1 || $row['yid'] > 0) get_err('book');
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //作者链接
        $data['comic_authorlink'] = get_url('author',array('uid'=>$row['uid']));
        //作者头像
        $data['comic_authorpic'] = getpic(getzd('user','pic',$row['uid']));
        //当前数据
        foreach ($row as $key => $val){
            if($key == 'pic' || $key =='picx'){
                $data['book_'.$key] = getpic($val);
            }else{
                $data['book_'.$key] = $val;
            }
        }
        //获取模板
        $str = load_file('book/info.html');
        //章节表
        $chapter_table = get_chapter_table($row['id']);
        //判断章节入库
        $zjnum = $this->mcdb->get_nums($chapter_table,array('bid'=>$row['id']));
        if($row['nums'] == 0 || $zjnum == 0 || $zjnum < $row['nums']){
            //解析章节标签
            if((Book_Caiji_Tb_Chapter == 0 || $zjnum == 0) && $row['did'] > 0){
                $this->load->model('collect');
                $arr = require MCCMSPATH.'libs/collect.php';
                if(!empty($row['ly'])){
                    if(isset($arr['book_zyk'][$row['ly']])){
                        $chapter = $this->collect->get_update_chapter($arr['book_zyk'][$row['ly']]['jxurl'].'/chapter/'.$row['did'],$row['id'],'book',$chapter_table,$arr['book_zyk'][$row['ly']]['token']);
                    }
                }else{
                    $chapter = $this->collect->get_update_chapter(Book_Caiji_Tb_Url.'/chapter/'.$row['did'],$row['id'],'book',$chapter_table,Book_Caiji_Tb_Token);
                }
                if(count($chapter) <= $row['nums']) $row['nums'] = count($chapter);
            }
        }
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('book',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        //增加人气
        $arr = explode('</body>',$str);
        $str = $arr[0].'<script src="//'.Web_Url.links('api','hits/book',$row['id']).'"></script></body>';
        if(isset($arr[1])) $str .= $arr[1];
        return $str;
    }

    //小说阅读页
    function book_read($bid,$id){
        $data = array();
        $table = get_chapter_table($bid);
        if($id == 0){
            $row = $this->mcdb->get_row_arr($table,'*',array('bid'=>$bid),'xid','asc');
        }else{
            $row = $this->mcdb->get_row_arr($table,'*',array('id'=>$id));
        }
        //章节不存在
        if(!$row || $row['yid'] > 0) get_err();
        //判断小说是否审核
        $rowm = $this->mcdb->get_row_arr('book','*',array('id'=>$row['bid']));
        if(!$rowm || $rowm['yid'] > 0 || $rowm['sid'] > 0) get_err('book');
        $row['text'] = get_book_txt($bid,$row['id']);
        //同步远程TXT文本到本地
        if($rowm['did'] > 0 && empty($row['text'])){
            $this->load->model('collect');
            if(!empty($rowm['ly'])){
                if(isset($arr['book_zyk'][$rowm['ly']])){
                    $row['text'] = $this->collect->get_update_txt($arr['book_zyk'][$rowm['ly']]['jxurl'].'/txt/'.$rowm['did'].'/'.$row['xid'],$row['id'],$bid,$table,$arr['book_zyk'][$rowm['ly']]['token']);
                }
            }else{
                $row['text'] = $this->collect->get_update_txt(Book_Caiji_Tb_Url.'/txt/'.$rowm['did'].'/'.$row['xid'],$row['id'],$bid,$table,Book_Caiji_Tb_Token);
            }
        }
        //判断收费
        if($row['vip'] > 0 || $row['cion'] > 0){
            $row['text'] = str_replace("[n]","\n",sub_str(str_replace("\n","[n]",$row['text']),250));
        }
        $row['text'] = '<p>'.str_replace("\n",'</p><p>',$row['text']).'</p>';
        //网站标题
        $data['mccms_title'] = $row['name'].' - '.Web_Name;
        //小说数据
        foreach ($rowm as $key => $val){
            $data['book_'.$key] = $val;
        }
        //小说链接
        $data['book_link'] = get_url('book_info',$rowm);
        //上一章，下一章
        $rowd = $this->mcdb->get_row_arr($table,'id,bid,name',array('xid<'=>$row['xid'],'bid'=>$row['bid']),'xid desc');
        if($rowd){
            $data['read_slink'] = get_url('book_read',$rowd);
            $data['read_sname'] = $rowd['name'];
            $data['read_sid'] = $rowd['id'];
        }else{
            $data['read_slink'] = '';
            $data['read_sname'] = '没有了';
            $data['read_sid'] = 0;
        }
        $rowd = $this->mcdb->get_row_arr($table,'id,bid,name',array('xid>'=>$row['xid'],'bid'=>$row['bid']),'xid asc');
        if($rowd){
            $data['read_xlink'] = get_url('book_read',$rowd);
            $data['read_xname'] = $rowd['name'];
            $data['read_xid'] = $rowd['id'];
        }else{
            $data['read_xlink'] = '';
            $data['read_xname'] = '没有了';
            $data['read_xid'] = 0;
        }
        //当前数据
        foreach ($row as $key => $val){
            $data['read_'.$key] = $val;
        }
        //获取模板
        $str = load_file('book/read.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //当前数据
        $str = $this->parser->mccms_tpl('read',$str,$str,$row);
        //IF判断解析
        $str = $this->parser->labelif($str);
        //增加人气
        $arr = explode('</body>',$str);
        $str = $arr[0].'<script src="//'.Web_Url.links('api','hits/book',$row['bid']).'"></script></body>';
        if(isset($arr[1])) $str .= $arr[1];
        return $str;
    }
}
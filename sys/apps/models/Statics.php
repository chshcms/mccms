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

//静态生成模型
class Statics extends CI_Model{
    
    function __construct (){
        parent:: __construct ();
    }

    //自定义模板生成
    function custom(){
        $tpl = $this->input->post('tpl',true);
        if(empty($tpl)) get_json('请选择要生成的页面!!!');
        foreach ($tpl as $k => $file) {
            $op = str_replace('.html','',$file);
            $str = $this->tpl->custom($op);
            $file_path = get_html_file(get_url('custom',array('file'=>$op)));
            write_file('.'.$file_path,$str);
        }
        get_json('全部生成完成....',1);
    }

    //漫画主页
    function comic_index(){
        $str = $this->tpl->index();
        $link = defined('HTML_DIR') && HTML_DIR == 'wap' && Wap_Html_Dir != '' ? Web_Path.Wap_Html_Dir.'/'.Url_Html_Index : Url_Html_Index;
        $file_path = get_html_file($link);
        $res = write_file('.'.$file_path,$str);
        if(!$res) get_json('根目录没有写入权限...');
        get_json('主页生成成功....',1);
    }

    //漫画分类
    function comic_list(){
        $ids = $this->input->get_post('id',true);
        $i = (int)$this->input->get_post('i');
        $p = (int)$this->input->get_post('p');
        if($p == 0) $p = 1;
        $idarr = array();
        if(empty($ids)){
            $class = $this->mcdb->get_select('class','id',array(),'xid ASC',10000);
            foreach ($class as $r) {
                $idarr[] = $r['id'];
            }
        }else{
            $idarr = explode(',',$ids);
        }
        if(!isset($idarr[$i]) || count($idarr) <= $i){
            $msg = '<tr><td><a href="">全部分类生成完毕!!!</a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>100),1);
        }
        $id = (int)$idarr[$i];
        $row = $this->mcdb->get_row_arr('class','*',array('id'=>$id));
        if(!$row) get_json('当前分类不存在!!!');
        $larr = $this->tpl->lists($id,$p,1);
        $pagejs = $larr[1];
        $str = $larr[0];
        $row['page'] = $p;
        $htmlink = get_url('lists',$row);
        $file_path = get_html_file($htmlink);
        write_file('.'.$file_path,$str);
        //返回前端
        $bi = round($p/$pagejs*100,2);
        $post['id'] = implode(',',$idarr);
        if($pagejs > $p){
            $post['p'] = $p+1;
            $post['i'] = $i;
            $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">分类《'.$row['name'].'》第'.$p.'页，'.$htmlink.' 生成完毕</a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
        }else{
            $post['i'] = $i+1;
            $post['p'] = 1;
            $msg = '<tr><td><a href="'.$htmlink.'" target="_blank"><b>分类《'.$row['name'].'》第'.$p.'页生成完毕，'.$htmlink.'，全部生成完毕!</b></a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>100,'post'=>$post),2);
        }
    }

    //漫画详情
    function comic_show(){
        $do = $this->input->get_post('do',true);
        $cid = $this->input->get_post('cid',true);
        $day = (int)$this->input->get_post('day');
        $okid = (int)$this->input->get_post('okid');
        $ksid = (int)$this->input->get_post('ksid');
        $jsid = (int)$this->input->get_post('jsid');
        $i = (int)$this->input->get_post('i');
        if($okid == 0) $okid = 1;
        $post['do'] = $do;
        $post['cid'] = $cid;
        $post['day'] = $day;
        $post['ksid'] = $ksid;
        $post['jsid'] = $jsid;
        //按ID生成
        if($do == 'id'){
            if($jsid > 0 && $ksid>$jsid)  get_json('开始ID不能大于结束ID!!!');
            if($i == 0) $i = $ksid;
            if($i == 0){
                $row = $this->mcdb->get_row_arr('comic','id,cid,name,yname',array('id<'=>$jsid,'yid'=>0),'id asc');
                if(!$row){
                    get_json('ID:'.$ksid.'-'.$jsid.'没有记录，全部生成完毕!',1);
                }
            }else{
                $row = $this->mcdb->get_row_arr('comic','id,cid,name,yname',array('id'=>$i,'yid'=>0));
                if(!$row) get_json('全部生成完毕!',1);
            }
            $rowx = $this->mcdb->get_row_arr('comic','id',array('id>'=>$row['id'],'yid'=>0),'id asc');
            if(!$rowx || $rowx['id'] >= $jsid+1){
                $bi = 100;
                $htmlink = get_url('show',$row);
                $file_path = get_html_file($htmlink);
                $str = $this->tpl->comic($row['id']);
                write_file('.'.$file_path,$str);
                $html = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('html'=>$html,'msg'=>'全部生成完毕'),1);
            }else{
                //下一个ID
                $post['i'] = $rowx ? $rowx['id'] : $row['id']+1;
                if($jsid == $ksid){
                    $bi = 100;
                }else{
                    $bi = round(($row['id']-$ksid)/($jsid-$ksid)*100,2);
                }
                //ID不存在跳过
                if(!$row){
                    $msg = '<tr><td style="color:red">ID['.$i.']记录不存在，跳过!</td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }else{
                    $htmlink = get_url('show',$row);
                    $file_path = get_html_file($htmlink);
                    $str = $this->tpl->comic($row['id']);
                    write_file('.'.$file_path,$str);
                    $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }
            }
        }else{ //按分类和时间
            $wh['yid'] = 0;
            //按分类
            if(!empty($cid) && preg_match('/^([0-9]+[,]?)+$/', $cid)){
                $wh['cid'] = $cid;
            }
            //按时间
            if($day > 0){
                $time = strtotime(date('Y-m-d 0:0:0'))-86401*($day-1);
                $wh['addtime<'] = $time;
            }
            //总数量
            $znum = $this->mcdb->get_nums('comic',$wh);
            if($znum == 0) get_json('没有记录，全部生成完毕');
            if($i == 0){
                $row = $this->mcdb->get_row_arr('comic','id,cid,name,yname',$wh,'id asc');
            }else{
                $row = $this->mcdb->get_row_arr('comic','id,cid,name,yname',array('id'=>$i,'yid'=>0));
            }
            if($jsid == 0){
                $row2 = $this->mcdb->get_row_arr('comic','id',$wh,'id desc');
                $post['jsid'] = $jsid = $row2['id'];
            }
            $bi = round($okid/$znum*100,2);
            $htmlink = get_url('show',$row);
            $file_path = get_html_file($htmlink);
            $str = $this->tpl->comic($row['id']);
            write_file('.'.$file_path,$str);
            //下一个ID
            $wh['id>'] = $row['id'];
            $post['okid'] = $okid+1;
            $rowx = $this->mcdb->get_row_arr('comic','id',$wh,'id asc');
            if(!$rowx){
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr><tr><td><b>全部生成完毕!</b></td></tr>';
                get_json(array('msg'=>'全部生成完毕!','html'=>$msg,'bi'=>$bi),1);
            }else{
                $post['i'] = $rowx['id'];
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
            }
        }
    }

    //漫画图片
    function comic_pic(){
        $do = $this->input->get_post('do',true);
        $cid = $this->input->get_post('cid',true);
        $day = (int)$this->input->get_post('day');
        $mid = (int)$this->input->get_post('mid');
        $okid = (int)$this->input->get_post('okid');
        $ksid = (int)$this->input->get_post('ksid');
        $jsid = (int)$this->input->get_post('jsid');
        $i = (int)$this->input->get_post('i');
        if($okid == 0) $okid = 1;
        $post['do'] = $do;
        $post['cid'] = $cid;
        $post['mid'] = $mid;
        $post['day'] = $day;
        $post['ksid'] = $ksid;
        $post['jsid'] = $jsid;
        //按ID生成
        if($do == 'id'){
            if($jsid > 0 && $ksid>$jsid)  get_json('开始ID不能大于结束ID!!!');
            if($i == 0) $i = $ksid;
            if($i == 0){
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('id<'=>$jsid),'id asc');
                if(!$row){
                    get_json('ID:'.$ksid.'-'.$jsid.'没有记录，全部生成完毕!',1);
                }
            }else{
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('id'=>$i));
            }
            if(!$row){
                $post['i'] = $i+1;
                $msg = '<tr><td>ID:'.$i.'记录不存在，跳过!</td></tr>';
                get_json(array('msg'=>$msg,'bi'=>0,'post'=>$post),2);
            }
            $rowx = $this->mcdb->get_row_arr('comic_chapter','id',array('id>'=>$row['id']),'id asc');
            if(!$rowx || $rowx['id'] > $jsid+1){
                $htmlink = get_url('pic',$row);
                $file_path = get_html_file($htmlink);
                $str = $this->tpl->chapter($row['id']);
                write_file('.'.$file_path,$str);
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('html'=>$msg,'msg'=>'全部生成完毕'),1);
            }else{
                //下一个ID
                $post['i'] = $rowx['id'];
                if($jsid == $ksid){
                    $bi = 100;
                }else{
                    $bi = round(($row['id']-$ksid)/($jsid-$ksid)*100,2);
                }
                //ID不存在跳过
                if(!$row){
                    $msg = '<tr><td style="color:red">ID['.$i.']记录不存在，跳过!</td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }else{
                    $htmlink = get_url('pic',$row);
                    $file_path = get_html_file($htmlink);
                    $str = $this->tpl->chapter($row['id']);
                    write_file('.'.$file_path,$str);
                    $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }
            }
        }elseif($do == 'mid'){
            if($i > 0){
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('id'=>$i));
            }else{
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('mid'=>$mid),'id asc');
            }
            if(!$row){
                get_json('该漫画没有章节记录，全部生成完毕!',1);
            }
            //总数量
            $znum = $this->mcdb->get_nums('comic_chapter',array('mid'=>$mid));
            $bi = round($okid/$znum*100,2);
            $htmlink = get_url('pic',$row);
            $file_path = get_html_file($htmlink);
            $str = $this->tpl->chapter($row['id']);
            write_file('.'.$file_path,$str);
            //下一个ID
            $post['okid'] = $okid+1;
            $rowx = $this->mcdb->get_row_arr('comic_chapter','id',array('id>'=>$row['id'],'mid'=>$mid),'id asc');
            if(!$rowx){
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr><tr><td><b>全部生成完毕!</b></td></tr>';
                get_json(array('msg'=>'全部生成完毕!','html'=>$msg,'bi'=>$bi),1);
            }else{
                $post['i'] = $rowx['id'];
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
            }
        }else{
            $wh = array();
            //按时间
            if($day > 0){
                $time = strtotime(date('Y-m-d 0:0:0'))-86401*($day-1);
                $wh['addtime<'] = $time;
            }
            //总数量
            $znum = $this->mcdb->get_nums('comic_chapter',$wh);
            if($znum == 0) get_json('没有记录，全部生成完毕');
            if($i == 0){
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',$wh,'id asc');
            }else{
                $row = $this->mcdb->get_row_arr('comic_chapter','id,mid,name',array('id'=>$i));
            }
            if($jsid == 0){
                $row2 = $this->mcdb->get_row_arr('comic_chapter','id',$wh,'id desc');
                $post['jsid'] = $jsid = $row2['id'];
            }
            $bi = round($okid/$znum*100,2);
            $htmlink = get_url('show',$row);
            $file_path = get_html_file($htmlink);
            $str = $this->tpl->chapter($row['id']);
            write_file('.'.$file_path,$str);
            //下一个ID
            $wh['id>'] = $row['id'];
            $post['okid'] = $okid+1;
            $rowx = $this->mcdb->get_row_arr('comic_chapter','id',$wh,'id asc');
            if(!$rowx){
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr><tr><td><b>全部生成完毕!</b></td></tr>';
                get_json(array('msg'=>'全部生成完毕!','html'=>$msg,'bi'=>$bi),1);
            }else{
                $post['i'] = $rowx['id'];
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
            }
        }
    }

    //小说主页
    function book_index(){
        $str = $this->tpl->book_index();
        $link = defined('HTML_DIR') && HTML_DIR == 'wap' && Wap_Html_Dir != '' ? Web_Path.Wap_Book_Html_Dir.'/'.Url_Book_Html_Index : Url_Book_Html_Index;
        $file_path = get_html_file($link);
        $res = write_file('.'.$file_path,$str);
        if(!$res) get_json('根目录没有写入权限...');
        get_json('主页生成成功....',1);
    }

    //小说分类
    function book_list(){
        $ids = $this->input->get_post('id',true);
        $i = (int)$this->input->get_post('i');
        $p = (int)$this->input->get_post('p');
        if($p == 0) $p = 1;
        $idarr = array();
        if(empty($ids)){
            $class = $this->mcdb->get_select('book_class','id',array(),'xid ASC',10000);
            foreach ($class as $r) {
                $idarr[] = $r['id'];
            }
        }else{
            $idarr = explode(',',$ids);
        }
        if(!isset($idarr[$i]) || count($idarr) <= $i){
            $msg = '<tr><td><a href="">全部分类生成完毕!!!</a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>100),1);
        }
        $id = (int)$idarr[$i];
        $row = $this->mcdb->get_row_arr('book_class','*',array('id'=>$id));
        if(!$row) get_json('当前分类不存在!!!');
        $larr = $this->tpl->book_lists($id,$p,1);
        $pagejs = $larr[1];
        $str = $larr[0];
        $row['page'] = $p;
        $htmlink = get_url('book_lists',$row);
        $file_path = get_html_file($htmlink);
        write_file('.'.$file_path,$str);
        //返回前端
        $bi = round($p/$pagejs*100,2);
        $post['id'] = implode(',',$idarr);
        if($pagejs > $p){
            $post['p'] = $p+1;
            $post['i'] = $i;
            $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">分类《'.$row['name'].'》第'.$p.'页，'.$htmlink.' 生成完毕</a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
        }else{
            $post['i'] = $i+1;
            $post['p'] = 1;
            $msg = '<tr><td><a href="'.$htmlink.'" target="_blank"><b>分类《'.$row['name'].'》第'.$p.'页生成完毕，'.$htmlink.'，全部生成完毕!</b></a></td></tr>';
            get_json(array('msg'=>$msg,'bi'=>100,'post'=>$post),2);
        }
    }

    //小说详情
    function book_info(){
        $do = $this->input->get_post('do',true);
        $cid = $this->input->get_post('cid',true);
        $day = (int)$this->input->get_post('day');
        $okid = (int)$this->input->get_post('okid');
        $ksid = (int)$this->input->get_post('ksid');
        $jsid = (int)$this->input->get_post('jsid');
        $i = (int)$this->input->get_post('i');
        if($okid == 0) $okid = 1;
        $post['do'] = $do;
        $post['cid'] = $cid;
        $post['day'] = $day;
        $post['ksid'] = $ksid;
        $post['jsid'] = $jsid;
        //按ID生成
        if($do == 'id'){
            if($jsid > 0 && $ksid>$jsid)  get_json('开始ID不能大于结束ID!!!');
            if($i == 0) $i = $ksid;
            if($i == 0){
                $row = $this->mcdb->get_row_arr('book','id,cid,name,yname',array('id<'=>$jsid,'yid'=>0),'id asc');
                if(!$row){
                    get_json('ID:'.$ksid.'-'.$jsid.'没有记录，全部生成完毕!',1);
                }
            }else{
                $row = $this->mcdb->get_row_arr('book','id,cid,name,yname',array('id'=>$i,'yid'=>0));
                if(!$row) get_json('全部生成完毕!',1);
            }
            $rowx = $this->mcdb->get_row_arr('book','id',array('id>'=>$row['id'],'yid'=>0),'id asc');
            if(!$rowx || $rowx['id'] >= $jsid+1){
                $bi = 100;
                $htmlink = get_url('book_info',$row);
                $file_path = get_html_file($htmlink);
                $str = $this->tpl->book_info($row['id']);
                write_file('.'.$file_path,$str);
                $html = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('html'=>$html,'msg'=>'全部生成完毕'),1);
            }else{
                //下一个ID
                $post['i'] = $rowx['id'];
                if($jsid == $ksid){
                    $bi = 100;
                }else{
                    $bi = round(($row['id']-$ksid)/($jsid-$ksid)*100,2);
                }
                //ID不存在跳过
                if(!$row){
                    $msg = '<tr><td style="color:red">ID['.$i.']记录不存在，跳过!</td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }else{
                    $htmlink = get_url('book_info',$row);
                    $file_path = get_html_file($htmlink);
                    $str = $this->tpl->book_info($row['id']);
                    write_file('.'.$file_path,$str);
                    $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                    get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
                }
            }
        }else{ //按分类和时间
            $wh['yid'] = 0;
            //按分类
            if(!empty($cid) && preg_match('/^([0-9]+[,]?)+$/', $cid)){
                $wh['cid'] = $cid;
            }
            //按时间
            if($day > 0){
                $time = strtotime(date('Y-m-d 0:0:0'))-86401*($day-1);
                $wh['addtime<'] = $time;
            }
            //总数量
            $znum = $this->mcdb->get_nums('book',$wh);
            if($znum == 0) get_json('没有记录，全部生成完毕');
            if($i == 0){
                $row = $this->mcdb->get_row_arr('book','id,cid,name,yname',$wh,'id asc');
            }else{
                $row = $this->mcdb->get_row_arr('book','id,cid,name,yname',array('id'=>$i,'yid'=>0));
            }
            if($jsid == 0){
                $row2 = $this->mcdb->get_row_arr('book','id',$wh,'id desc');
                $post['jsid'] = $jsid = $row2['id'];
            }
            $bi = round($okid/$znum*100,2);
            $htmlink = get_url('book_info',$row);
            $file_path = get_html_file($htmlink);
            $str = $this->tpl->book_info($row['id']);
            write_file('.'.$file_path,$str);
            //下一个ID
            $wh['id>'] = $row['id'];
            $post['okid'] = $okid+1;
            $rowx = $this->mcdb->get_row_arr('book','id',$wh,'id asc');
            if(!$rowx){
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr><tr><td><b>全部生成完毕!</b></td></tr>';
                get_json(array('msg'=>'全部生成完毕!','html'=>$msg,'bi'=>$bi),1);
            }else{
                $post['i'] = $rowx['id'];
                $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《'.$row['name'].'》,'.$htmlink.'生成完毕!</a></td></tr>';
                get_json(array('msg'=>$msg,'bi'=>$bi,'post'=>$post),2);
            }
        }
    }

    //小说阅读
    function book_read(){
        $do = $this->input->get_post('do',true);
        $cid = $this->input->get_post('cid',true);
        $xid = $this->input->get_post('xid',true);
        $day = (int)$this->input->get_post('day');
        $ksid = (int)$this->input->get_post('ksid');
        $jsid = (int)$this->input->get_post('jsid');
        $i = (int)$this->input->get_post('i');
        if($xid == 0) $xid = 1;
        $post['do'] = $do;
        $post['cid'] = $cid;
        $post['xid'] = $xid;
        $post['day'] = $day;
        $post['ksid'] = $ksid;
        $post['jsid'] = $jsid;
        //按ID生成
        if($do == 'id'){
            if($jsid > 0 && $ksid>$jsid)  get_json('开始ID不能大于结束ID!!!');
            if($i == 0) $i = $ksid;
            if($i == 0){
                $row = $this->mcdb->get_row_arr('book','id,name,nums',array('id<'=>$jsid),'id asc');
                if(!$row) get_json('ID:'.$ksid.'-'.$jsid.'没有记录，全部生成完毕!',1);
            }else{
                $row = $this->mcdb->get_row_arr('book','id,name,nums',array('id'=>$i));
            }
            $post['i'] = $row['id'];
            $rowx = $this->mcdb->get_row_arr('book','id',array('id>'=>$row['id']),'id asc');
            if(!$rowx || $rowx['id'] >= $jsid+1){
                $res = $this->book_chapter($row['id'],$xid);
                if($xid == 1){
                    $html = '<tr><td>--------------《'.$row['name'].'》生成开始--------------</td></tr>'.$res;
                }else{
                    $html = $res;
                }
                if($res){
                    $post['xid'] = $xid+1;
                    get_json(array('msg'=>$html,'bi'=>round($xid/$row['nums']*100,2),'post'=>$post),2);
                }else{
                    get_json(array('html'=>$html,'msg'=>'全部生成完毕'),1);
                }
            }else{
                //ID不存在跳过
                if(!$row){
                    $post['i'] = $rowx['id']; //下一个ID
                    $msg = '<tr><td style="color:red">ID['.$i.']记录不存在，跳过!</td></tr>';
                    get_json(array('msg'=>$msg,'post'=>$post),2);
                }else{
                    $res = $this->book_chapter($row['id'],$xid);
                    if($xid == 1){
                        $html = '<tr><td>--------------《'.$row['name'].'》生成开始--------------</td></tr>'.$res;
                    }else{
                        $html = $res;
                    }
                    if($res){
                        $post['xid'] = $xid+1;
                        get_json(array('msg'=>$html,'bi'=>round($xid/$row['nums']*100,2),'post'=>$post),2);
                    }else{
                        if($xid == 1) $html .= '<tr><td style="color:red">没有章节，跳过!</td></tr>';
                        $post['i'] = $rowx['id']; //下一个ID
                        $post['xid'] = 1;
                        $html .= '<tr><td>--------------《'.$row['name'].'》生成完毕--------------</td></tr>';
                        get_json(array('html'=>$html,'bi'=>100,'post'=>$post),2);
                    }
                }
            }
        }else{
            $wh = array();
            //按时间
            if($day > 0){
                $time = strtotime(date('Y-m-d 0:0:0'))-86401*($day-1);
                $wh['addtime<'] = $time;
            }
            //总数量
            $znum = $this->mcdb->get_nums('book',$wh);
            if($znum == 0) get_json('没有记录，全部生成完毕');
            if($i == 0){
                $row = $this->mcdb->get_row_arr('book','id,name,nums',$wh,'id asc');
            }else{
                $row = $this->mcdb->get_row_arr('book','id,name,nums',array('id'=>$i));
            }
            if($jsid == 0){
                $row2 = $this->mcdb->get_row_arr('book','id',$wh,'id desc');
                $post['jsid'] = $jsid = $row2['id'];
            }
            $post['i'] = $row['id'];
            $res = $this->book_chapter($row['id'],$xid);
            if($xid == 1){
                $html = '<tr><td>--------------《'.$row['name'].'》生成开始--------------</td></tr>'.$res;
            }else{
                $html = $res;
            }
            if($res){
                $post['xid'] = $xid+1;
                get_json(array('msg'=>$html,'bi'=>round($xid/$row['nums']*100,2),'post'=>$post),2);
            }else{
                $html .= '<tr><td>--------------《'.$row['name'].'》生成完毕--------------</td></tr>';
                //下一个ID
                $wh['id>'] = $row['id'];
                $rowx = $this->mcdb->get_row_arr('comic_chapter','id',$wh,'id asc');
                if(!$rowx){
                    $html .= '<tr><td><b>全部生成完毕!</b></td></tr>';
                    get_json(array('msg'=>'全部生成完毕!','html'=>$html),1);
                }else{
                    $post['i'] = $rowx['id'];
                    $post['xid'] = 1;
                    get_json(array('msg'=>$html,'bi'=>100,'post'=>$post),2);
                }
            }
        }
    }

    //生成小说章节
    function book_chapter($bid,$xid=1){
        $table = get_chapter_table($bid);
        $row = $this->mcdb->get_row_arr($table,'id',array('bid'=>$bid,'xid'=>$xid));
        if(!$row) return false;
        $htmlink = get_url('book_read',array('bid'=>$bid,'id'=>$row['id']));
        $file_path = get_html_file($htmlink);
        $str = $this->tpl->book_read($bid,$row['id']);
        write_file('.'.$file_path,$str);
        $msg = '<tr><td><a href="'.$htmlink.'" target="_blank">《第'.$xid.'章》,'.$htmlink.'生成完毕!</a></td></tr>';
        return $msg;
    }
}
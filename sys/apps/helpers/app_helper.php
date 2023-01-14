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
//数据转换
function get_app_data($arr){
    if(empty($arr)) return $arr;
    $harr = array('hits','rhits','zhits','yhits','shits','ticket','cion','text_num');
    foreach ($arr as $k=>$v){
        if(is_array($v)){
            $arr[$k] = get_app_data($v);
        }else{
            if(empty($arr['picx']) && !empty($arr['pic'])) $arr['picx'] = $arr['pic'];
            if(!empty($arr['picx'])) $arr['picx'] = getpic($arr['picx']);
            if(!empty($arr['pic'])) $arr['pic'] = getpic($arr['pic']);
            if(isset($arr['text']) && empty($arr['text'])) $arr['text'] = $arr['content'];
            if(!empty($arr['addtime']) && is_numeric($arr['addtime'])) $arr['addtime'] = date('Y-m-d H:i:s',$arr['addtime']);
            if(!empty($arr['viptime']) && is_numeric($arr['viptime'])) $arr['viptime'] = date('Y-m-d',$arr['viptime']);
            if(isset($arr['content'])) unset($arr['content']);
            if(in_array($k,$harr) && is_numeric($v)) $arr[$k] = get_app_wan($arr[$k]);
        }
    }
    return $arr;
}
//以万为单位格式化
function get_app_wan($hits = 0){
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
//判断请求签名
function get_app_sign(){
    $app = require FCPATH.'sys/libs/app.php';
    if(empty($app['apikey'])) get_json('APP接口已关闭',0);
    $ci = &get_instance();
    $post = $ci->input->get();
    if(empty($post)) $post = $ci->input->post();
    if(empty($post['facility']) || empty($post['deviceid']) || empty($post['sign']) || empty($post['timestamp'])) get_json('非法请求',0);
    if($post['timestamp']+1800000 < time()*1000) get_json('非法请求',0);
    $sign = $post['sign'];
    unset($post['sign']);
    $str = array();
    ksort($post);
    foreach ($post as $k => $v) {
        if($v != '') $str[] = $k.'='.$v;
    }
    $str = implode('&',$str).$app['apikey'];
    $mysign = strtoupper(md5($str));
    if($mysign != $sign) get_json('签名错误',0);
    $uid = (int)$post['user_id'];
    //记录设备统计
    $rh = $new = 0;$date = date('Ymd');
    $row = $ci->mcdb->get_row_arr('user_app','id,uid,uptime',array('deviceid'=>$post['deviceid'],'facility'=>$post['facility']));
    if($row){
        $edit = array();
        if($row['uid'] == 0 && $uid > 0) $edit['uid'] = $uid;
        if($row['uptime'] < strtotime(date('Y-m-d 0:0:0'))){
            $edit['uptime'] = time();
            $rh = 1;
        }
        if(!empty($edit)) $ci->mcdb->get_update('user_app',$row['id'],$edit);
    }else{
        $rh = 1;
        $ci->mcdb->get_insert('user_app',array(
            'deviceid'=>$post['deviceid'],
            'facility'=>$post['facility'],
            'uid'=>$uid,
            'addtime'=>time(),
            'uptime'=>time(),
        ));
    }
    //新增日活数量
    $facility = $post['facility'];
    $row2 = $ci->mcdb->get_row_arr('user_app_nums','*',array('date'=>$date));
    if(!$row2){
        $ci->mcdb->get_insert('user_app_nums',array($facility.'_nums'=>1,$facility.'_add'=>1,'date'=>$date));
    }elseif($rh == 1){
        if(!$row){
            $ci->mcdb->get_update('user_app_nums',$row2['id'],array(
                $facility.'_nums'=>$row2[$facility.'_nums']+1,
                $facility.'_add'=>$row2[$facility.'_add']+1
            ));
        }else{
            $ci->mcdb->get_update('user_app_nums',$row2['id'],array(
                $facility.'_nums'=>$row2[$facility.'_nums']+1
            ));
        }
    }
    return true;
}
//判断登录
function get_app_log($uid,$token,$db){
    if((int)$uid == 0) return false;
	$row = $db->get_row_arr('user','id,tel,pass,cion,ticket,vip,viptime',array('id'=>(int)$uid));
	if($row && md5('mccms_app'.$row['id'].$row['tel'].$row['pass'].Mc_Encryption_Key) == $token){
	    if($row['vip'] > 0 && $row['viptime'] < time()){
	        $db->get_update('user',$uid,array('vip'=>0,'viptime'=>0));
	        $row['vip'] = 0;
	        $row['viptime'] = 0;
	    }
	    unset($row['tel'],$row['pass']);
	    $row['log'] = 1;
		return $row;
	}else{
		return false;
	}
}
//判断漫画收费，返回说明：0可以浏览，-1需要登陆，1需要购买VIP，2需要金币购买
function app_comic_pay($db,$mid,$zid,$cion,$vip,$user){
    if($cion > 0 || $vip > 0){
        //需要登陆
        if($user['log'] == 0) return -1;
        //需要升级VIP
        if($vip > 0 && $user['vip'] == 0) return 1;
        //需要购买
        if($cion > 0){
            $row = $db->get_row_arr('comic_buy','id',array('mid'=>$mid,'cid'=>$zid,'uid'=>$user['id']));
            if(!$row){
                $row2 = $db->get_row_arr('comic_buy','auto',array('mid'=>$mid,'uid'=>$user['id']));
                //开启自动购买
                if($row2 && $row2['auto'] == 1){
                    if($user['cion'] < $cion) return 2;
                    $res = app_comic_buy($mid,$zid,$cion,1,$user);
                    if($res) return 3;
                }else{
                    return 2;
                }
            }
        }
    }
    return 0;
}
//购买漫画章节
function app_comic_buy($mid,$zid,$cion,$auto,$user){
    if($user['cion'] < $cion) return false;
    $ci = &get_instance();
    //作者ID
    $zzid = getzd('comic','uid',$mid);
    //漫画标题
    $mname = getzd('comic','name',$mid);
    //章节标题
    $zname = getzd('comic_chapter','name',$zid);
    //扣币
    $xcion = $user['cion']-$cion;
    $ci->mcdb->get_update('user',$user['id'],array('cion'=>$xcion));
    //写入消费记录
    $add['uid'] = $user['id'];
    $add['text'] = '购买漫画《'.$mname.'》章节《'.$zname.'》';
    $add['cion'] = $cion;
    $add['mid'] = $mid;
    $add['cid'] = $zid;
    $add['ip'] = getip();
    $add['addtime'] = time();
    $ci->mcdb->get_insert('buy',$add);
    //写入购买记录
    $add1['uid'] = $user['id'];
    $add1['mid'] = $mid;
    $add1['cid'] = $zid;
    $add1['auto'] = $auto;
    $ci->mcdb->get_insert('comic_buy',$add1);
	//改变所有购买模式
	$ci->mcdb->get_update('comic_buy',$mid,array('auto'=>$auto),'mid');
    //分成记录
    if($zzid != $user['id']){
        $add2['uid'] = $zzid;
        $add2['text'] = '收到漫画《'.$mname.'》章节购买分成';
        $add2['mid'] = $mid;
        $add2['cion'] = round($cion*Author_Fc_Comic/100);
        $add2['zcion'] = $cion;
        $add2['addtime'] = time();
        $ci->mcdb->get_insert('income',$add2);
        //增加收入
        $xrmb = round($cion/Pay_Rmb_Cion*Author_Fc_Comic/100,2);
        $ci->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$zzid);
    }
    return true;
}
//判断小说收费，返回说明：0可以浏览，-1需要登陆，1需要购买VIP，2需要金币购买
function app_book_pay($db,$bid,$zid,$cion,$vip,$user){
    if($cion > 0 || $vip > 0){
        //需要登陆
        if($user['log'] == 0) return -1;
        //需要升级VIP
        if($vip > 0 && $user['vip'] == 0) return 1;
        //需要购买
        if($cion > 0){
            $row = $db->get_row_arr('book_buy','id',array('bid'=>$bid,'cid'=>$zid,'uid'=>$user['id']));
            if(!$row){
                $row2 = $db->get_row_arr('book_buy','auto',array('bid'=>$bid,'uid'=>$user['id']));
                //开启自动购买
                if($row2 && $row2['auto'] == 1){
                    if($user['cion'] < $cion) return 2;
                    $res = app_book_buy($bid,$zid,$cion,1,$user);
                    if($res) return 3;
                }else{
                    return 2;
                }
            }
        }
    }
    return 0;
}
//购买小说章节
function app_book_buy($bid,$zid,$cion,$auto,$user){
    if($user['cion'] < $cion) return false;
    $ci = &get_instance();
    //作者ID
    $zzid = getzd('book','uid',$bid);
    //漫画标题
    $mname = getzd('book','name',$bid);
    //章节标题
    $zname = getzd(get_chapter_table($bid),'name',$zid);
    //扣币
    $xcion = $user['cion']-$cion;
    $ci->mcdb->get_update('user',$user['id'],array('cion'=>$xcion));
    //写入消费记录
    $add['uid'] = $user['id'];
    $add['text'] = '购买小说《'.$mname.'》章节《'.$zname.'》';
    $add['cion'] = $cion;
    $add['bid'] = $bid;
    $add['cid'] = $zid;
    $add['ip'] = getip();
    $add['addtime'] = time();
    $ci->mcdb->get_insert('buy',$add);
    //写入购买记录
    $add1['uid'] = $user['id'];
    $add1['bid'] = $bid;
    $add1['cid'] = $zid;
    $add1['auto'] = $auto;
    $ci->mcdb->get_insert('book_buy',$add1);
    //改变所有购买模式
    $ci->mcdb->get_update('book_buy',$bid,array('auto'=>$auto),'bid');
    //分成记录
    if($zzid != $user['id']){
        $add2['uid'] = $zzid;
        $add2['text'] = '收到小说《'.$mname.'》章节购买分成';
        $add2['bid'] = $bid;
        $add2['cion'] = round($cion*Author_Fc_Book/100);
        $add2['zcion'] = $cion;
        $add2['addtime'] = time();
        $ci->mcdb->get_insert('income',$add2);
        //增加收入
        $xrmb = round($cion/Pay_Rmb_Cion*Author_Fc_Book/100,2);
        $ci->db->query('update '.Mc_SqlPrefix.'user set rmb=rmb+'.$xrmb.' where id='.$zzid);
    }
    return true;
}
//任务奖励
function app_task_reward($db,$tid,$user) {
    //判断任务是否存在
    $row = $db->get_row_arr('task','*',array('id'=>$tid));
    if(!$row) return false;
    //判断每日任务上限
    if($row['daynum'] > 0){
        $jtime = strtotime(date('Y-m-d 0:0:0')) - 1;
        $daynums = $db->get_nums('task_list',array('tid'=>$tid,'uid'=>$user['id'],'addtime>'=>$jtime));
        if($daynums >= $row['daynum']) return false;
    }
    //记录任务记录
    $db->get_insert('task_list',array('uid'=>$user['id'],'tid'=>$tid,'cion'=>$row['cion'],'vip'=>$row['vip'],'addtime'=>time()));
    //增加用户金币，VIP
    $edit = array();
    if($row['cion'] > 0) $edit['cion'] = $user['cion']+$row['cion'];
    if($row['vip'] > 0){
        $edit['vip'] = 1;
        if($user['viptime'] > time()){
            $edit['viptime'] = $user['viptime']+86400*$row['vip'];
        }else{
            $edit['viptime'] = time()+86400*$row['vip'];
        }
    }
    if(!empty($edit)) $db->get_update('user',$user['id'],$edit);
    return true;
}
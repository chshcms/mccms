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

class Pay extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//支付信息
    public function index() {
        $arr = array();
        $arr['cion_name'] = Pay_Cion_Name;
        $arr['rmb_cion'] = Pay_Rmb_Cion;
        //判断登陆
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = $this->cookie->get('user_id');
        $user = $this->mcdb->get_row_arr('user','id,name,nichen,cion,ticket',array('id'=>$uid));
        $arr['unichen'] = empty($user['nichen']) ? $user['name'] : $user['nichen'];
        $arr['ucion'] = $user['cion'];
        $arr['ticket'] = $user['ticket'];
		//卡密后买地址
        $arr['cardurl'] = Pay_Card_Url;
        //金币
        $arr['pay']['cion'] = array(
            array('rmb'=>10,'cion'=>Pay_Rmb_Cion*10),
            array('rmb'=>20,'cion'=>Pay_Rmb_Cion*20),
            array('rmb'=>30,'cion'=>Pay_Rmb_Cion*30),
            array('rmb'=>50,'cion'=>Pay_Rmb_Cion*50),
        );
        //VIP
        $arr['pay']['vip'] = array(
            array('day'=>30,'rmb'=>Pay_Vip_Rmb1,'name'=>'月度VIP','txt'=>'有效期30天'),
            array('day'=>90,'rmb'=>Pay_Vip_Rmb2,'name'=>'季度VIP','txt'=>'有效期90天'),
            array('day'=>180,'rmb'=>Pay_Vip_Rmb3,'name'=>'半年VIP','txt'=>'有效期180天'),
            array('day'=>365,'rmb'=>Pay_Vip_Rmb4,'name'=>'年度VIP','txt'=>'有效期365天'),
        );
        //月票
        $arr['pay']['ticket'] = array(
            array('num'=>1,'rmb'=>1,'cion'=>Pay_Rmb_Cion*1),
            array('num'=>5,'rmb'=>5,'cion'=>Pay_Rmb_Cion*5),
            array('num'=>10,'rmb'=>10,'cion'=>Pay_Rmb_Cion*10),
        );
        //支付方式
        $arr['pay']['is_wxpay'] = Pay_Wx_Mode;
        $arr['pay']['is_alipay'] = Pay_Ali_Mode;
        $arr['pay']['is_qqpay'] = Pay_QQ_Mode;
        //输出
        $d['code'] = 1;
        $d['data'] = $arr;
        get_json($d);
	}

    //充值订单入库
    public function save() {
        $type = $this->input->get_post('type',true);
        $pay = $this->input->get_post('pay',true);
        $rmb = (int)$this->input->get_post('rmb');
        $num = (int)$this->input->get_post('num');
        $day = (int)$this->input->get_post('day');
		$card = $this->input->get_post('card',true);
        $dayarr = array(30,90,180,365);
        if($day == 0) $day = 30;
        if($type == 'cion' && ($rmb < 1 || $rmb >9999)) get_json('金额错误!!!');
        if($type == 'cion' && $rmb < Pay_Rmb_Min) get_json('最低充值金额'.Pay_Rmb_Min.'元');
        if($type == 'ticket' && $num < 1) get_json('月票数量错误!!!');
		if($type == 'card' && empty($card)) get_json('卡密不能为空!!!');
        if($type == 'vip' && !in_array($day, $dayarr)) get_json('Vip时间错误!!!');
        $parr = array('cion','wxpay','alipay','qqpay');
        if(!in_array($pay, $parr)) $pay = 'cion';

        //判断登陆
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = $this->cookie->get('user_id');
        $user = $this->mcdb->get_row_arr('user','id,cion,ticket,vip,viptime',array('id'=>$uid));

        //充值类型
		if($type == 'card'){
            if(!ctype_alnum($card)) get_json('卡密不不能为空');
			$row = $this->mcdb->get_row_arr('card','*',array('pass'=>$card));
			if(!$row) get_json('卡密不存在!!!');
			if($row['uid'] > 0) get_json('卡密不能重复使用!!!');
			if($row['sid'] == 1){ //VIP卡
				$name = '卡密充值VIP会员成功';
                $text = '您使用卡密成功购买了'.$row['day'].'天Vip会员';
                $edit['vip'] = 1;
                if($user['viptime'] > time()){
                    $edit['viptime'] = $user['viptime']+86400*$row['day'];
                }else{
                    $edit['viptime'] = time()+86400*$row['day'];
                }
			}else{
				$name = '卡密充值金币成功';
                $text = '您使用卡密成功充值了'.$row['cion'].'个金币';
                $edit['cion'] = $user['cion']+$row['cion'];
			}
            $this->mcdb->get_update('user',$uid,$edit);
            $this->mcdb->get_update('card',$row['id'],array('uid'=>$uid,'usetime'=>time()));
            //写入消息记录
            $add1['uid'] = $uid;
            $add1['name'] = $name;
            $add1['text'] = $text;
            $add1['addtime'] = time();
            $this->mcdb->get_insert('message',$add1);
            //记录订单
            $add2['dd'] = date('YmdHis').rand(1111,9999);
            $add2['uid'] = $uid;
            $add2['rmb'] = 0;
            $add2['text'] = $text;
            $add2['type'] = '卡密充值';
            $add2['pid'] = 1;
            $add2['addtime'] = time();
            $did = $this->mcdb->get_insert('order',$add2);

            //输出
            $d['code'] = 1;
            $d['pay'] = 0;
            $d['msg'] = '充值成功，请稍后...';
            get_json($d);
        }elseif($type == 'ticket'){
            $rmb = $num;
            $txt = '购买'.$num.'张月票';
            $zd = array('zd'=>'ticket','num'=>$num);
        }elseif($type == 'vip'){
            $rarr = array('30'=>Pay_Vip_Rmb1,'90'=>Pay_Vip_Rmb2,'180'=>Pay_Vip_Rmb3,'365'=>Pay_Vip_Rmb4);
            $rmb = $rarr[$day];
            $txt = '购买'.$day.'天Vip会员';
            $zd = array('zd'=>'vip','day'=>$day);
        }else{
            $txt = '购买'.$rmb*Pay_Rmb_Cion.'个'.Pay_Cion_Name;
            $zd = array('zd'=>'cion','cion'=>$rmb*Pay_Rmb_Cion);
        }
        //金币支付
        if($pay == 'cion'){
            //判断金币是否不足
            $cion = $rmb * Pay_Rmb_Cion;
            if($cion > $user['cion']) get_json(Pay_Cion_Name.'不足，请先充值!!!');
            //购买月票
            if($type == 'ticket'){
                $edit['ticket'] = $user['ticket']+$num;
                $name = '购买月票成功';
                $text = '您花费'.$cion.Pay_Cion_Name.'，成功购买了'.$num.'张月票';
            }else{
                $name = '购买VIP会员成功';
                $text = '您花费'.$cion.Pay_Cion_Name.'，成功购买了'.$day.'天Vip会员';
                $edit['vip'] = 1;
                if($user['viptime'] > time()){
                    $edit['viptime'] = $user['viptime']+86400*$day;
                }else{
                    $edit['viptime'] = time()+86400*$day;
                }
                //判断赠送天数
                if(($day/30) > Pay_Vip_Month){
                	$sday = (int)(($day/30) - Pay_Vip_Month);
                	if($sday > 0){
                		$edit['viptime'] = $edit['viptime']+86400*$sday;
                		$text .= '，系统赠送您'.$sday.'天';
                	}
                }
            }
            $edit['cion'] = $user['cion'] - $cion;
            $this->mcdb->get_update('user',$uid,$edit);
            //写入消费记录
            $add['uid'] = $uid;
            $add['text'] = $txt;
            $add['cion'] = $cion;
            $add['ip'] = getip();
            $add['addtime'] = time();
            $this->mcdb->get_insert('buy',$add);
            //写入消息记录
            $add1['uid'] = $uid;
            $add1['name'] = $name;
            $add1['text'] = $text;
            $add1['addtime'] = time();
            $this->mcdb->get_insert('message',$add1);
            //输出
            $d['code'] = 1;
            $d['pay'] = 0;
            $d['msg'] = '购买成功，请稍后...';
            get_json($d);
        }
        //记录订单
        $add2['dd'] = date('YmdHis').rand(1111,9999);
        $add2['uid'] = $uid;
        $add2['rmb'] = $rmb;
        $add2['text'] = $txt;
        $add2['type'] = $pay;
        $add2['zd'] = json_encode($zd);
        $add2['addtime'] = time();
        $did = $this->mcdb->get_insert('order',$add2);

        //输出
        $d['code'] = 1;
        $d['pay'] = 1;
        $d['did'] = $did;
        if(defined('MOBILE')){
            $d['payurl'] = links('api','pay/wap',$did);
            $d['msg'] = '支付地址获取成功';
        }else{
            //二维码地址
            if($pay == 'alipay'){
                $codeurl = 'http://'.Web_Url.links('api','pay/qrcode',$did);
            }else{
                $this->load->library($pay);
                $codeurl = $this->$pay->qrcode($add2['dd'],$add2['rmb'],$add2['text']);
            }
            $d['payurl'] = 'http://'.Web_Url.links('api','code','qr').'?txt='.sys_auth($codeurl).'&size=3';
            $d['msg'] = '二维码获取成功';
        }
        get_json($d);
    }

    //二维码支付请求
    public function qrcode($id = 0) {
        $id = (int)$id;
        if($id == 0) exit('ID不能为空');
        $row = $this->mcdb->get_row_arr('order','*',array('id'=>$id));
        if(!$row) exit('记录不存在!');
        if($row['pid'] > 0) exit('订单已处理!');
        //获取支付二维码
        $pay = $row['type'];
        $this->load->library($pay);
        $payurl = $this->$pay->qrcode($row['dd'],$row['rmb'],$row['text']);
        header("location:".$payurl);
        exit;
    }

    //H5支付请求
    public function wap($id = 0) {
        echo '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"></head><body style="font-size:18px;color:#f00;padding:50px 0;text-align: center;">';
        $id = (int)$id;
        if($id == 0) exit('ID不能为空');
        $row = $this->mcdb->get_row_arr('order','*',array('id'=>$id));
        if(!$row) exit('记录不存在!');
        if($row['pid'] > 0) exit('订单已处理!');
        //获取支付二维码
        $pay = $row['type'];
        $this->load->library($pay);
        $payurl = $this->$pay->h5($row['dd'],$row['rmb'],$row['text']);
        if($pay == 'qqpay'){
        	$codeurl = $this->$pay->qrcode($row['dd'],$row['rmb'],$row['text']);
        	$code_img = links('api','code','qr').'?txt='.sys_auth($codeurl).'&size=8';
        	echo '<br><br><br><font style="color:#080">正在唤醒支付，请稍后...</font><br><br>如果无法唤醒，请截屏二维码，用QQ扫码支付<br><br><img src="'.$code_img.'"><script type="application/javascript" src="https://open.mobile.qq.com/sdk/qqapi.js?_bid=152"></script><script type="application/javascript">function pay(){mqq.device.isMobileQQ(function(result){if(result) {this.callPay();}});}function callPay(){mqq.tenpay.pay({tokenId: "'.$payurl.'",}, function(result, resultCode){alert(resultCode);if(result == "Permission denied") {alert("无权限 ");} else {if(result.resultCode == 0){document.location.href = "'.links('user').'";}}});}pay();</script>';
        }else{
        	header("location:".$payurl);
        }
        exit('</body></html>');
    }

    //支付状态
    public function init() {
        $id = (int)$this->input->get_post('id');
        if($id == 0) get_json('ID不能为空');
        $row = $this->mcdb->get_row_arr('order','*',array('id'=>$id));
        if(!$row) get_json('记录不存在!');
        if($row['pid'] == 1){
            get_json('付款成功，3秒后刷新',1);
        }else{
            get_json('未付款!');
        }
    }

    //支付同步返回
    public function return_url($dd=''){
        if(!ctype_alnum($dd)) exit('缺少订单号');
        if($dd){
            $row = $this->mcdb->get_row_arr('order','*',array('dd'=>$dd));
            if($row && $row['pid']==1){
                echo '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"></head><body><center><br><br><br><font style="font-size:18px;color:#080;">订单支付成功~!</font><br><br><a href="'.links('user').'">立即返回</a></center><script type="text/javascript">window.setTimeout(function (){   location.href="'.links('user').'";},3000);</script></body></html>';
                exit;
            }
        }
        echo '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"></head><body><center><br><br><br><font style="font-size:18px;color:red;">订单处理中，请稍后...</font><br><br><a href="'.links('user').'">立即返回</a></center>
            <script src="'.Web_Base_Path.'jquery/jquery.min.js"></script>
            <script type="text/javascript">
            window.setInterval(function (){
                $.post("'.links('api','pay/init').'", {id:'.$row['id'].'}, function(res) {
                    if(res.code == 1){
                        location.href="'.links('user').'";
                    }
                },\'json\');
            },3000);
            </script></body></html>';
    }

    //支付异步返回处理
    public function notify_url($mode='alipay'){
        $this->load->library($mode);
        $dd = $this->$mode->is_sign();
        if($dd){
            $row = $this->mcdb->get_row_arr('order','*',array('dd'=>$dd));
            if($row && $row['pid'] == 0){
              	//改变支付状态
                $this->mcdb->get_update('order',$row['id'],array('pid'=>1));
                $arr = json_decode($row['zd'],1);
                if($arr['zd'] == 'vip'){
                    $viptime = getzd('user','viptime',$row['uid']);
                    $edit['vip'] = 1;
                    if($viptime > time()){
                        $edit['viptime'] = $viptime+86400*$arr['day'];
                    }else{
                        $edit['viptime'] = time()+86400*$arr['day'];
                    }
	                //判断赠送天数
	                if(($arr['day']/30) > Pay_Vip_Month){
	                	$sday = (int)(($arr['day']/30) - Pay_Vip_Month);
	                	if($sday > 0){
	                		$edit['viptime'] = $edit['viptime']+86400*$sday;
	                		$row['text'] .= '，系统赠送您'.$sday.'天，';
	                	}
	                }
                }elseif($arr['zd'] == 'ticket'){
                    $ticket = getzd('user','ticket',$row['uid']);
                    $edit['ticket'] = $ticket+$arr['num'];
                }else{
                    $cion = getzd('user','cion',$row['uid']);
                    $edit['cion'] = $cion+$arr['cion'];
                }
                $this->mcdb->get_update('user',$row['uid'],$edit);
                //发送消息通知
                $add['uid'] = $row['uid'];
                $add['name'] = '在线购买成功';
                $add['text'] = '恭喜您，'.$row['text'].'已完成';
                $add['addtime'] = time();
                $this->mcdb->get_insert('message',$add);
            }
            if($mode == 'qqpay'){
                echo '<xml><return_code>SUCCESS</return_code></xml>';
            }else{
            	echo 'success';
            }
        }else{
            echo 'fail';
        }
    }
}
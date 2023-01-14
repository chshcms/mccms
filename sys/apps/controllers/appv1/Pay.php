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
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
		//充值套餐
		$app = require FCPATH.'sys/libs/app.php';
		$this->pay = $app['pay'];
	}

	//充值套餐
    public function init() {
		//判断签名
		get_app_sign();
		//判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$this->user) get_json('未登录',-1);
		$user['vipday'] = $this->user['viptime'] > time() ? ceil(($this->user['viptime'] - time()) / 86400) : 0;
        $user['cion_name'] = Pay_Cion_Name;
        $user['cion'] = $this->user['cion'];
        $user['ticket'] = $this->user['ticket'];
        //金币套餐
        $cionlist = $this->pay['cion'];
        foreach ($cionlist as $k => $v) {
            $cionlist[$k]['text'] = $v['cion'].Pay_Cion_Name;
        }
        //输出
        $d['code'] = 1;
        $d['user'] = get_app_data($user);
        $d['cionlist'] = $cionlist;
        $d['ticketlist'] = $this->pay['ticket'];
        $d['viplist'] = $this->pay['vip'];
        //支付方式
        $d['is_wxpay'] = Pay_Wx_Mode == 0 ? 1 : 0;
        $d['is_alipay'] = Pay_Ali_Mode == 0 ? 1 : 0;
        $d['is_qqpay'] = Pay_QQ_Mode == 0 ? 1 : 0;
		get_json($d);
	}
	
	//获取充值地址
    public function save() {
		//判断签名
		get_app_sign();
		//判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
		if(!$this->user) get_json('未登录',-1);
		
		$type = $this->input->get_post('type',true);
        $pay = $this->input->get_post('pay',true);
        $rmb = (int)$this->input->get_post('rmb');
        $num = (int)$this->input->get_post('num');
        $day = (int)$this->input->get_post('day');
        $parr = array('wxpay','alipay','qqpay');
        if(!in_array($pay, $parr)) get_json('付款方式有误',0);
        if($type == 'vip'){
            $rmb = 0;
            foreach ($this->pay['vip'] as $v){
                if($v['day'] == $day) $rmb = $v['rmb'];
            }
            if($rmb == 0) get_json('Vip元套餐不存在',0);
        }
        if($type == 'ticket'){
            $rmb = 0;
            foreach ($this->pay['ticket'] as $v){
                if($v['nums'] == $num) $rmb = $v['rmb'];
            }
            if($rmb == 0) get_json('月票数量错误',0);
        }
        if($type == 'cion' && ($rmb == 0 || $rmb >9999)) get_json('金额错误',0);
        //记录订单
        if($type == 'ticket'){
            $rmb = $num;
            $txt = '购买'.$num.'张月票';
            $zd = array('zd'=>'ticket','num'=>$num);
        }elseif($type == 'vip'){
            $txt = '购买'.$day.'天Vip会员';
            $zd = array('zd'=>'vip','day'=>$day);
        }else{
            $txt = '购买'.$rmb*Pay_Rmb_Cion.'个'.Pay_Cion_Name;
            $zd = array('zd'=>'cion','cion'=>$rmb*Pay_Rmb_Cion);
        }
        
        //记录订单
        $add2['dd'] = date('YmdHis').rand(1111,9999);
        $add2['uid'] = $this->uid;
        $add2['rmb'] = $rmb;
        $add2['text'] = $txt;
        $add2['type'] = $pay;
        $add2['zd'] = json_encode($zd);
        $add2['addtime'] = time();
        $did = $this->mcdb->get_insert('order',$add2);

        //输出
        $d['code'] = 1;
        $d['payurl'] = 'http://'.Web_Url.links('appv1','pay/wap',$did);
        get_json($d);
    }

    //H5支付请求
    public function wap($id = 0) {
        echo '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"><title>在线支付中...</title></head><body style="font-size:18px;color:#f00;padding:50px 0;text-align: center;">';
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
        }elseif($pay == 'wxpay'){
            echo '<br><br><br><font style="color:#080">正在唤醒微信支付，请稍后...</font><script>setTimeout(function(){ window.location.href = "'.$payurl.'";},1000);</script>';
        }else{
        	header("location:".$payurl);
        }
        exit('</body></html>');
    }
}
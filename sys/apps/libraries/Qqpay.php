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
/**
 * QQ钱包在线支付类
 */
class Qqpay {

    public function __construct ()
	{
		//商户密钥
		$this->app_id = Pay_QQ_ID;
		$this->mch_id = Pay_QQ_User;
		$this->mch_key = Pay_QQ_Key;
		//同步地址
		$this->return_url = 'http://'.Web_Url.Web_Path.'index.php/api/pay/return_url';
		//异步地址
		$this->notify_url = 'http://'.Web_Url.Web_Path.'index.php/api/pay/notify_url/qqpay';
	}

	//微信扫码支付
    public function qrcode($dingdan,$total_fee,$body='vip'){
		//$arr['appid'] = $this->app_id;
		$arr['mch_id'] = $this->mch_id;
		$arr['nonce_str'] = $this->getNonceStr();
		$arr['body'] = $body;
		$arr['out_trade_no'] = $dingdan;
		$arr['fee_type'] = 'CNY';
		$arr['total_fee'] = $total_fee*100;
		$arr['spbill_create_ip'] = $this->get_ip();
		$arr['notify_url'] = $this->notify_url;
		$arr['trade_type'] = 'NATIVE';
		$arr['sign'] = $this->getsign($arr);

		$url = 'https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi';
		$post_xml = $this->arrtoxml($arr);
		$xml = $this->geturl($url,$post_xml);
		$arr2 = $this->xmltoarr($xml);
		if($arr2['return_code'] != 'SUCCESS' || $arr2['result_code'] != 'SUCCESS'){
			if(empty($arr2['return_msg']) || $arr2['return_msg'] == 'SUCCESS') $arr2['return_msg'] = $arr2['err_code_des'];
			get_json($arr2['return_msg']);
		}else{
			return $arr2['code_url'];
		}
    }

    //微信h5支付
    public function h5($dingdan,$total_fee,$body='vip'){
    	//$arr['appid'] = $this->app_id;
		$arr['mch_id'] = $this->mch_id;
		$arr['nonce_str'] = $this->getNonceStr();
		$arr['body'] = $body;
		$arr['out_trade_no'] = $dingdan;
		$arr['fee_type'] = 'CNY';
		$arr['total_fee'] = $total_fee*100;
		$arr['spbill_create_ip'] = $this->get_ip();
		$arr['notify_url'] = $this->notify_url;
		$arr['trade_type'] = 'JSAPI';
		$arr['sign'] = $this->getsign($arr);

		$url = 'https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi';
		$post_xml = $this->arrtoxml($arr);
		$xml = $this->geturl($url,$post_xml);
		$arr2 = $this->xmltoarr($xml);
		if($arr2['return_code'] != 'SUCCESS' || $arr2['result_code'] != 'SUCCESS'){
			if(empty($arr2['return_msg'])) $arr2['return_msg'] = $arr2['err_code_des'];
			get_json($arr2['return_msg']);
		}else{
			return $arr2['prepay_id'];
		}
    }

    //验证签名
	public function is_sign(){
		$xml = file_get_contents("php://input");
		//file_put_contents('./1.txt',$xml);
        $arr = $this->xmltoarr($xml);
		if($arr['trade_state'] == 'SUCCESS') {
			$sign = $arr['sign'];
			$md5 =  $this->getsign($arr);
			if($sign == $md5){
				return $arr['out_trade_no'];
			}
		}
		return false;
	}

	//生成签名，$arr为请求数组，$key为私钥
	public function getsign($arr){
		if(isset($arr['sign'])) unset($arr['sign']);
        ksort($arr);
		$arr['key'] = $this->mch_key;
        $requestString = $this->arrtouri($arr);
        $newSign = md5($requestString);
        return strtoupper($newSign);
    }

	//数组转URI
	function arrtouri($param){
		$str = '';
		foreach($param as $key => $value) {
			$str .= $key .'=' . $value . '&';
		}
		$str = substr($str,0,-1);
		return $str;
	}

	//获取IP
	public function get_ip(){    
		$ip = '';    
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){        
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];    
		}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){        
			$ip = $_SERVER['HTTP_CLIENT_IP'];    
		}else{        
			$ip = $_SERVER['REMOTE_ADDR'];    
		}
		$ip_arr = explode(',', $ip);
		return $ip_arr[0];
	}

	//数组转XML
	public function arrtoxml($arr){
		$xml = '<xml>';
		foreach($arr as $k=>$v){
			$xml .= '<'.$k.'>'.$v.'</'.$k.'>';
		}
		$xml .= '</xml>';
        return $xml;
    }

	//XML转数组
	public function xmltoarr($xml){ 
		//禁止引用外部xml实体 
		libxml_disable_entity_loader(true); 
		$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
		$val = json_decode(json_encode($xmlstring),true); 
		return $val; 
	}

	//产生随机字符串，不长于32位
	public function getNonceStr($length = 32) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}
	
	public function geturl($url,$post=''){
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
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//获取跳转后的
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

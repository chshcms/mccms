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

class Sms{

    function __construct (){
        log_message('debug', "Native Sms Class Initialized");
		$this->appid   = Sms_Appid;  //商户ID
		$this->appkey  = Sms_Appkey;  //商户KEY
		$this->signname = Sms_Name;  //商户签名
	}

    //发送
	public function add($tel,$code,$type='reg'){
		//模版ID
		if($type == 'bind'){
			$tplid = Sms_Tpl_Bind;
		}elseif($type == 'pass'){
			$tplid = Sms_Tpl_Pass;
		}else{
			$tplid = Sms_Tpl_Log;
		}
		if(Sms_Mode == 3){ //聚合数据
			return $this->juhe($tel,$code,$tplid);
		}elseif(Sms_Mode == 2){ //腾讯云
			return $this->tencent($tel,$code,$tplid);
		}else{ //阿里云
			return $this->alyun($tel,$code,$tplid);
		}
	}

	//聚合支付发送
	public function juhe($tel,$code,$tplid){
		$sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
		$smsConf = array(
		    'key'   => $this->appkey, //您申请的APPKEY
		    'mobile'    => $tel, //接受短信的用户手机号码
		    'tpl_id'    => $tplid, //您申请的短信模板ID，根据实际情况修改
		    'tpl_value' =>'#code#='.$code //您设置的模板变量，根据实际情况修改
		);
		$content = $this->juhecurl($sendUrl,$smsConf,1); //请求发送短信
		if($content){
		    $result = json_decode($content,true);
		    $error_code = $result['error_code'];
		    if($error_code == 0){
		        //状态为0，说明短信发送成功
		        //echo "短信发送成功,短信ID：".$result['result']['sid'];
		        return true;
		    }else{
		        //状态非0，说明失败
		        //echo $result['reason'];
		        return false;
		    }
		}else{
		    return false;
		}
	}

	//腾讯云发送
	public function tencent($tel,$code,$tplid){
		// 发送短信 单发指定模板
        $random = rand(1111,9999);
        $time = time();
        //请求地址
        $apiurl = 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid='.$this->appid.'&random='.$random;
        //请求参数
        $params = array();
        $params["params"] = array($code);
        $params["sig"] = hash("sha256", "appkey=".$this->appkey."&random=".$random."&time=".$time."&mobile=".$tel);
        $params["sign"] = $this->signname;
        $params['tel'] = array('mobile'=>$tel,'nationcode'=>'86');
        $params['time'] = $time;
        $params['tpl_id'] = $tplid;
        $json = $this->sendCurlPost($apiurl,$params);
        $arr = json_decode($json,1);
        if(isset($arr['result']) && $arr['result'] == 0){
        	return true;
        }else{
            if(!isset($arr['errmsg'])) $arr['errmsg'] = $arr['ErrorInfo'];
        	//echo $arr['errmsg'];
        	return false;
        }
	}

	//阿里云发送
	public function alyun($tel,$code,$tplid){
		$params = array ();
	    $params["PhoneNumbers"] = $tel;
	    $params["SignName"] = $this->signname;
	    $params["TemplateCode"] = $code;
	    $params['TemplateParam'] = $tplid;
	    $content = $this->request($this->appid,$this->appkey,'dysmsapi.aliyuncs.com',array_merge($params, array("RegionId" => "cn-hangzhou","Action" => "SendSms","Version" => "2017-05-25")));
        if($content->Code == 'OK'){
            return true;
        }
	    return false;
    }

    public function request($accessKeyId, $accessKeySecret, $domain, $params, $security=false) {
        $apiParams = array_merge(array (
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => uniqid(mt_rand(0,0xffff), true),
            "SignatureVersion" => "1.0",
            "AccessKeyId" => $accessKeyId,
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "Format" => "JSON",
        ), $params);
        ksort($apiParams);
        $sortedQueryStringTmp = "";
        foreach ($apiParams as $key => $value) {
            $sortedQueryStringTmp .= "&" . $this->encode($key) . "=" . $this->encode($value);
        }
        $stringToSign = "GET&%2F&" . $this->encode(substr($sortedQueryStringTmp, 1));
        $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&",true));
        $signature = $this->encode($sign);
        $url = ($security ? 'https' : 'http')."://{$domain}/?Signature={$signature}{$sortedQueryStringTmp}";
        try {
            $content = $this->fetchContent($url);
            return json_decode($content);
        } catch( \Exception $e) {
            return false;
        }
    }

    private function encode($str){
        $res = urlencode($str);
        $res = preg_replace("/\+/", "%20", $res);
        $res = preg_replace("/\*/", "%2A", $res);
        $res = preg_replace("/%7E/", "~", $res);
        return $res;
    }

    private function fetchContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "x-sdk-client" => "php/2.0.0"
        ));
        if(substr($url, 0,5) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $rtn = curl_exec($ch);
        if($rtn === false) {
            trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
        }
        curl_close($ch);
        return $rtn;
    }

    //腾讯云发送
    public function sendCurlPost($url, $dataObj)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
	/**
	 * 请求接口返回内容
	 * @param  string $url [请求的URL地址]
	 * @param  string $params [请求的参数]
	 * @param  int $ipost [是否采用POST形式]
	 * @return  string
	 */
	function juhecurl($url,$params=false,$ispost=0){
	    $httpInfo = array();
	    $ch = curl_init();
	    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
	    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
	    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
	    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	    if( $ispost )
	    {
	        curl_setopt( $ch , CURLOPT_POST , true );
	        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
	        curl_setopt( $ch , CURLOPT_URL , $url );
	    }
	    else
	    {
	        if($params){
	            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
	        }else{
	            curl_setopt( $ch , CURLOPT_URL , $url);
	        }
	    }
	    $response = curl_exec( $ch );
	    if ($response === FALSE) {
	        return false;
	    }
	    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
	    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
	    curl_close( $ch );
	    return $response;
	}
}
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

class Wxapp extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		//微信token
		$this->token     =  Wx_Token;
		$this->echostr   =  $this->input->get_post('echostr');
		$this->signature =  $this->input->get_post('signature');
		$this->timestamp =  $this->input->get_post('timestamp');
		$this->nonce     =  $this->input->get_post('nonce');
	}

    public function index() {
    	$echostr = $this->input->get_post('echostr');
    	$neir = file_get_contents('php://input');
    	//验证微信过来的
    	$this->checkSignature();

        $MsgType = 'event';
        $msg = $event ='';
        if(!empty($neir)){
            $xml = @simplexml_load_string($neir);
	        $ToUserName = (string) $xml->ToUserName; //开发者微信号ID
            $OpenID = (string) $xml->FromUserName; //发送者账号ID
            $MsgType = (string) $xml->MsgType; //消息类型
            $msg = (string) $xml->Content; //消息内容
            $event = (string) $xml->Event; //关注状态
        }
        //关注状态消息,Wx_Gz_Msg
		if(!empty($event)){
			echo "<xml><ToUserName><![CDATA[".$OpenID."]]></ToUserName><FromUserName><![CDATA[".$ToUserName."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".Wx_Gz_Msg."]]></Content></xml>";
		}
		//用户输入消息
		if(!empty($msg)){
			$text = '';$ok = 0;
			$marr = explode("\n", Wx_Key_Msg);
			foreach ($marr as $v) {
				$arr1 = explode("|", $v);
				if(strpos($arr1[0],$msg) !== false){
					$text = $arr2[1];
					$ok = 1;
					break;
				}
			}
			if($ok == 0){
				$neir = "我们找到以下漫画；\r\n\r\n";
				$msg = str_replace("@","",safe_replace($msg));
				$result = $this->mcdb->get_select('comic','*',array('yid'=>0,'sid'=>0),'id DESC',5,array('name'=>$msg));
				$i = 1;
				foreach ($result as $row) {
					$neir .= "➢<a href=\"http://".Web_Url.get_url('show',$row)."\">".$row['name']."</a>\r\n\r\n";
					$i++;
					$ok++;
				}
				if($ok > 4){
					$neir .= "➢<a href=\"http://".Web_Url.links('search','?key='.rawurlencode($msg))."\">查看更多</a>\r\n";
				}
			}
			if($ok == 0){
			    $neir = "未找到匹配的内容\r\n\r\n请回复小说/漫画书名\r\n\r\n我们帮您查找其他内容哦......";
			}
			echo "<xml><ToUserName><![CDATA[".$OpenID."]]></ToUserName><FromUserName><![CDATA[".$ToUserName."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$neir."]]></Content></xml>";
		}
		echo $echostr;
	}

    //判断是否为微信过来
    private function checkSignature()
    {
    	if(empty($this->token)) exit();
		$tmpArr = array($this->token, $this->timestamp, $this->nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $this->signature ){
			return true;
		}else{
			exit();
		}
	}
}
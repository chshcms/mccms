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

class Code extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//输出验证码
    public function index() {
        $w = (int)$this->input->get_post('w'); //宽度
        $h = (int)$this->input->get_post('h'); //高度
        $s = (int)$this->input->get_post('s'); //文字大小
        $n = (int)$this->input->get_post('n'); //验证码位数
        $config =   array();
        if($w > 0) $config['imageW'] = $w;
        if($h > 0) $config['imageH'] = $h;
        if($s > 0) $config['fontSize'] = $s;
        if($n > 0) $config['length'] = $n;
        $this->load->library('verify',$config);
        $this->verify->entry();
	}

    //发送手机验证码
    public function tel_send($op='') {
        $this->load->library('verify');
        $tel = $this->input->get_post('tel',true);
        $code = $this->input->get_post('code',true);
        if(!is_tel($tel)) get_json('手机号码格式错误');
        if(!$this->verify->check($code)) get_json('图形验证码错误');

        //判断手机是否注册
        if($op == 'reg'){
            $reg = $this->mcdb->get_row_arr('user','id',array('tel'=>$tel));
            if($reg) get_json('手机号码已存在');
        }

        //判断手机是否注册
        if($op == 'edit'){
            if(!$this->users->login(1)) get_json('登陆超时');
            $uid = (int)$this->cookie->get('user_id');
            $ytel = getzd('user','tel',$uid);
            if($ytel != $tel) get_json('绑定的手机号码不匹配');
        }

        //判断发送时间
        $row = $this->mcdb->get_row_arr('telcode','*',array('tel'=>$tel));
        if($row){
            if($row['addtime']+60 > time()) get_json('操作太频繁');
        }

        //验证码
        $tcode = rand(111111,999999);
        //发送手机验证码
        $this->load->library('sms');
        $res = $this->sms->add($tel,$tcode);
        if($res){
            //操作数据库
            if($row){
                $this->mcdb->get_update('telcode',$row['id'],array('code'=>$tcode,'addtime'=>time()));
            }else{
                $this->mcdb->get_insert('telcode',array('tel'=>$tel,'code'=>$tcode,'addtime'=>time()));
            }
            get_json('验证码发送成功~',1);
        }else{
            get_json('发送失败，稍后再试~');
        }
    }

    //二维码图片
    public function qr() {
        require_once MCCMSPATH.'class/phpqrcode/phpqrcode.php';
        $txt = sys_auth($this->input->get('txt'),1);   //二维码内容
        $size = (int)$this->input->get('size');
        if($size == 0) $size = 10;
        if(empty($txt)) exit('二维码内容错误');
        //生成二维码图片
        QRcode::png($txt,false,'L', $size, 1);
    }
}
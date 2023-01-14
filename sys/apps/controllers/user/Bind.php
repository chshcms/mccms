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

class Bind extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//绑定
    public function index() {
        $this->users->login();//判断登陆
        $data = array();
        $data['mccms_title'] = '安全绑定 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        if(!empty($row['tel'])) $row['tel'] = substr($row['tel'],0,3).'****'.substr($row['tel'],-4);
        //判断绑定QQ
        $row1 = $this->mcdb->get_row_arr('user_oauth','*',array('uid'=>$uid,'type'=>'qq'));
        $row['is_qq'] = $row1 ? 1 : 0;
        //判断绑定微信
        $row2 = $this->mcdb->get_row_arr('user_oauth','*',array('uid'=>$uid,'type'=>'weixin'));
        $row['is_weixin'] = $row2 ? 1 : 0;
        //判断绑定微博
        $row3 = $this->mcdb->get_row_arr('user_oauth','*',array('uid'=>$uid,'type'=>'weibo'));
        $row['is_weibo'] = $row3 ? 1 : 0;
        //会员用户名是否能够修改
        $row['is_name'] = 0;
        if($row['name'] == 'T-'.substr(md5($row['addtime']),8,-8)) $row['is_name'] = 1;
        //会员密码
        $row['is_pass'] = 0;
        if($row['pass'] == '123456') $row['is_pass'] = 1;
        //获取模版
        $str = load_file('user/bind.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //修改用户名和密码
    public function save(){
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = (int)$this->cookie->get('user_id');
        $name = safe_replace($this->input->post('name',true));
        $pass = $this->input->post('pass',true);
        $pass2 = $this->input->post('pass2',true);
        if(!empty($pass) && $pass != $pass2) get_json('两次密码不一致');
        $edit = array();
        if(!empty($name)){
            if(!ctype_alnum($name)) exit('用户名格式不正确');
            $yname = getzd('user','name',$uid);
            $ytime = getzd('user','addtime',$uid);
            if($yname != $name && $yname == 'T-'.substr(md5($ytime),8,-8)){
                $row = $this->mcdb->get_row('user','id',array('name'=>$name));
                if($row) get_json('用户已经存在!!!');
                $edit['name'] = $name;
            }
        }
        if(!empty($pass)) $edit['pass'] = md5($pass);
        if(!empty($edit)){
           $this->mcdb->get_update('user',$uid,$edit); 
        }
        get_json('信息修改成功',1);
    }

    //更换手机号码
    public function tel_edit(){
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = (int)$this->cookie->get('user_id');

        $tel = $this->input->post('tel',true);
        $code = $this->input->post('code',true);
        $sign = $this->input->post('sign',true);
        if(!is_tel($tel)) get_json('手机号码格式不正确');
        //判断验证码是否正确
        $row = $this->mcdb->get_row_arr('telcode','*',array('tel'=>$tel));
        if(!$row || $row['code'] != $code) get_json('手机验证码错误');
        //判断是否验证
        if(!empty($sign)){
            $ytel = sys_auth($sign,1);
            if(!is_tel($ytel)) get_json('sign密钥错误');
            $this->mcdb->get_update('user',$uid,array('tel'=>$tel));
            $this->mcdb->get_del('telcode',$row['id']);
            get_json('手机号码修改成功',1);
        }else{
            $this->mcdb->get_del('telcode',$row['id']);
            get_json(array('msg'=>'验证成功','sign'=>sys_auth($tel)),1);
        }
    }

    //绑定手机号码
    public function tel_save(){
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = (int)$this->cookie->get('user_id');

        $tel = $this->input->post('tel',true);
        $code = $this->input->post('code',true);
        if(!is_tel($tel)) get_json('手机号码格式不正确');
        //判断验证码是否正确
        $row = $this->mcdb->get_row_arr('telcode','*',array('tel'=>$tel));
        if(!$row || $row['code'] != $code) get_json('手机验证码错误');

        $this->mcdb->get_del('telcode',$row['id']);
        $this->mcdb->get_update('user',$uid,array('tel'=>$tel));
        get_json('手机绑定成功',1);
    }

    //解除第三方绑定
    public function unbind(){
        if(!$this->users->login(1)) get_json('登陆超时');
        $uid = (int)$this->cookie->get('user_id');
        $type = $this->input->post('type',true);
        if($type != 'weixin' && $type != 'weibo') $type = 'qq';

        $this->db->where(array('type'=>$type,'uid'=>$uid))->delete('user_oauth');
        get_json('解除绑定成功',1);
    }
}
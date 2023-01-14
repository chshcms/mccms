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

class Users extends CI_Model{
	
    function __construct (){
		parent:: __construct ();
    }

    //判断是否登入
    function login($sid=0,$key=''){
        if(empty($key)){
            $id = !$this->cookie->get('user_id') ? 0 : $this->cookie->get('user_id');
            $login =  !$this->cookie->get('user_login') ? '' :  $this->cookie->get('user_login');
        }else{
            $str  = sys_auth($key,1);
            $id   = isset($str['id']) ? intval($str['id']) : 0;
            $login = isset($str['login']) ? $str['login'] : '';
        }
        $islog = false;
        if(!empty($id) && !empty($login)){
            $user = $this->mcdb->get_row('user','sid,pass,vip,viptime',array('id'=>$id));
            if($user && md5($id.$user->sid.$user->pass.Mc_Encryption_Key) == $login){
                //判断VIP到期
                if($user->vip == 1 && $user->viptime < time()){
                    //改变VIP状态
                    $this->mcdb->get_update('user',$id,array('vip'=>0));
                }
                $islog = true;
            }
        }
        if($sid > 0){
            return $islog;
        }else{
            //未登录
            if(!$islog){
                $this->cookie->set('user_id');
                $this->cookie->set('user_login');
                //判断直接打开还是ajax
                if(strpos($_SERVER['HTTP_ACCEPT'],'application/json') === false){
                    die("<script>top.location='".links('user','login')."';</script>");
                }else{
                    get_json('您已登陆超时!!!');
                }
            }
        } 
    }

    //作者判断
    function author($sid=0){
        //判断作者开关
        if(Author_Mode == 1){
            if($sid == 0){
                exit('作者中心已关闭');
            }else{
                get_json('作者中心已关闭');
            }
        }
        //判断作者登陆
        if($sid == 0){
            $this->login();
        }else{
            return $this->login(1);
        }
        //判断作者认证
        if(Author_Rz == 0){ //需要认证才能成为作者
            $uid = $this->cookie->get('user_id');
            $cid = getzd('user','cid',$uid);
            if($cid < 2){
                if($sid == 0){
                    header("location:".links('author','renzheng'));
                    exit;
                }else{
                    get_json('您还没有认证，请先认证!!!');
                }
            }
        }
    }
}
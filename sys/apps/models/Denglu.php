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

class Denglu extends CI_Model{
	
    function __construct (){
		parent:: __construct ();
    }

    //返回
    function callback($type='qq'){
        $arr = $this->$type();
        if(empty($arr['oid'])){
            exit('获取登陆信息失败');
        }
        //判断数据是否存在
        $row = $this->mcdb->get_row_arr('user_oauth','*',array('oid'=>$arr['oid'],'type'=>$type));
        if($row){
            return array('id'=>$row['id'],'uid'=>$row['uid']);
        }else{
            $arr['type'] = $type;
            $did = $this->mcdb->get_insert('user_oauth',$arr);
            return array('id'=>$did,'uid'=>0,'nichen'=>$arr['nichen'],'pic'=>$arr['pic']);
        }
    }

    //QQ登陆
    function qq($state=''){
        $Land_QQ_Url = Land_QQ_Url == '' ? (is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/qq' : Land_QQ_Url;
        $code = $this->input->get_post('code');
        if(empty($code)){
            $scope= "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
            $login_url='https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.Land_QQ_Appid.'&redirect_uri='.urlencode($Land_QQ_Url).'&state='.$state.'&scope='.$scope;
            header("Location:$login_url");
        }else{
            //获取ACCSEE_TOTEN
            $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                      . "client_id=".Land_QQ_Appid."&redirect_uri=".urlencode($Land_QQ_Url)
                      . "&client_secret=".Land_QQ_Appkey."&code=".$code;
            $json = getcurl($token_url);
            parse_str($json,$arr);
            //得到 access_token
            if(empty($arr['access_token'])){
                exit('QQ登陆，获取信息失败!!!');
            }
            //获取OPENID
            $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$arr['access_token'];
            $json = str_replace(array('callback( ',' );'),'',getcurl($graph_url));
            $arr2 = json_decode($json,1);
            //得到openid
            if(empty($arr2['openid'])){
                error('错误提示','QQ登陆，获取openid失败!!!');
            }
            $openid = $arr2['openid'];
            //获取用户信息
            $get_user_info = "https://graph.qq.com/user/get_user_info?"
                   . "access_token=" . $arr['access_token']
                   . "&oauth_consumer_key=".Land_QQ_Appid
                   . "&openid=".$arr2['openid']
                   . "&format=json";
            $info = getcurl($get_user_info);
            $arr3 = json_decode($info, 1);

            $array = array();
            $array['oid'] = empty($arr3['unionid']) ? $arr2['openid'] : $arr3['unionid'];
            $array['nichen'] = $arr3['nickname'];
            $array['pic'] = $arr3['figureurl_2'];
            return $array;
        }
    }

    //微信登陆
    function weixin($state=''){
        $Land_Wx_Url = Land_Wx_Url == '' ? (is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/weixin' : Land_Wx_Url;
        $code = $this->input->get_post('code');
        if(empty($code)){
            $wxurl = "https://open.weixin.qq.com/connect/qrconnect?appid="
            .Land_Wx_Appid."&redirect_uri="
            .urlencode($Land_Wx_Url)."&response_type=code&scope=snsapi_login&state="
            .$state."#wechat_redirect";
            header("Location: $wxurl");exit;
        }else{
            $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.Land_Wx_Appid.'&secret='.Land_Wx_Appkey.'&code='.$code.'&grant_type=authorization_code';
            $json = getcurl($url);
            $arr = json_decode($json,1);
            //得到 access_token 与 openid
            if(empty($arr['openid']) || empty($arr['access_token'])){
                exit('微信登陆，获取信息失败!!!');
            }
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
            $ujson = getcurl($url);
            $uarr = json_decode($ujson,1);

            $array = array();
            $array['oid'] = empty($uarr['unionid']) ? $arr['openid'] : $uarr['unionid'];
            $array['nichen'] = $uarr['nickname'];
            $array['pic'] = $uarr['headimgurl'];
            return $array;
        }
    }

    //微博登陆
    function weibo($state=''){
        $Land_Wb_Url = Land_Wb_Url == '' ? (is_ssl()?'https://':'http://').Web_Url.Web_Path.'index.php/user/open/callback/weixin' : Land_Wb_Url;
        $code = $this->input->get_post('code');
        if(empty($code)){
            $wb_url = "https://api.weibo.com/oauth2/authorize?client_id=".Land_Wb_Appid."&response_type=code&redirect_uri=".urlencode($Land_Wb_Url);
            header("Location: $wb_url");exit;
        }else{
            $url = "https://api.weibo.com/oauth2/access_token?client_id=".Land_Wb_Appid."&client_secret=".Land_Wb_Appkey."&grant_type=authorization_code&redirect_uri=".urlencode($Land_Wb_Url)."&code=".$code;
            $json = getcurl($url);
            $arr = json_decode($json, 1);
            if(empty($arr['access_token'])){
                exit('微博登陆，获取信息失败!!!');
            }

            //获取用户信息 : get方法，替换参数： access_token， uid
            $url = "https://api.weibo.com/2/users/show.json?access_token={$arr['access_token']}&uid={$arr['uid']}";
            $uinfo = getcurl($url);
            $uarr = json_decode($ujson,1);

            $array = array();
            $array['oid'] = $arr['uid'];
            $array['nichen'] = $uarr['screen_name'];
            $array['pic'] = $uarr['profile_image_url'];
            return $array;
        }
    }

    // 字符串截取函数
    function str_substr($start, $end, $str){ 
        $temp = explode($start, $str, 2);      
        $content = explode($end, $temp[1], 2);      
        return $content[0];      
    }
}
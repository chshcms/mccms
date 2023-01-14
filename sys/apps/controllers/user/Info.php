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

class Info extends Mccms_Controller {
	public function __construct(){
		parent::__construct();
	}

	//资料修改
    public function index() {
        $this->users->login();//判断登陆
        $data = array();
        $data['mccms_title'] = '修改资料 - '.Web_Name;
    	$uid = (int)$this->cookie->get('user_id');
    	$row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //会员用户名是否能够修改
        $row['nameedit'] = 0;
        if($row['name'] == 'T-'.substr(md5($row['addtime']),8,-8)){
            $row['nameedit'] = 1;
        }
        //城市分割
        $carr = explode('-',$row['city']);
        $row['province'] = $row['city'] = $row['area'] = '';
        if(isset($carr[0])) $row['province'] = $carr[0];
        if(isset($carr[1])) $row['city'] = $carr[1];
        if(isset($carr[2])) $row['area'] = $carr[2];
        //获取模版
        $str = load_file('user/info.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
	}

    //密码修改
    public function pass() {
        $this->users->login();//判断登陆
        $data = array();
        $data['mccms_title'] = '修改密码 - '.Web_Name;
        $uid = (int)$this->cookie->get('user_id');
        $row = $this->mcdb->get_row_arr('user','*',array('id'=>$uid));
        //获取模版
        $str = load_file('user/info_pass.html');
        //全局解析
        $str = $this->parser->parse_string($str,$data,true);
        //会员数据
        $str = $this->parser->mccms_tpl('user',$str,$str,$row);
        //IF判断解析
        echo $this->parser->labelif($str);
    }

    //资料入库
    public function save() {
        if(!$this->users->login(1)) get_json('登陆超时!!!');

        $uid = (int)$this->cookie->get('user_id');
        $name = safe_replace(urldecode($this->input->get_post('name',true)));
        $province = $this->input->get_post('province',true);
        $city = $this->input->get_post('city',true);
        $area = $this->input->get_post('area',true);

        $edit['nichen'] = $this->input->get_post('nichen',true);
        $edit['qq'] = $this->input->get_post('qq',true);
        $edit['email'] = $this->input->get_post('email',true);
        $edit['sex'] = $this->input->get_post('sex',true);
        $edit['text'] = $this->input->get_post('text',true);
        if(empty($edit['nichen'])) get_json('昵称不能为空');
        if(empty($name)) get_json('用户名不能为空');
        $edit['city'] = $province;
        if(!empty($city)) $edit['city'] = $edit['city'].'-'.$city;
        if(!empty($area)) $edit['city'] = $edit['city'].'-'.$area;

        $yname = getzd('user','name',$uid);
        $ytime = getzd('user','addtime',$uid);
        if($yname != $name && $yname == 'T-'.substr(md5($ytime),8,-8)){
            $row = $this->mcdb->get_row('user','id',array('name'=>$name));
            if($row) get_json('用户已经存在!!!');
            $edit['name'] = $name;
        }
        //原来笔名
        $bname = getzd('user','nichen',$uid);
        //不一样则更改所有漫画的笔名
        if($bname !== $edit['nichen']){
            $this->db->where(array('uid'=>$uid))->update('comic',array('author'=>$edit['nichen']));
        }
        $res = $this->mcdb->get_update('user',$uid,$edit);
        if($res){
            get_json('资料更新成功',1);
        }else{
            get_json('资料更新失败');
        }
    }

    //新密码入库
    public function pass_save() {
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');
        $ypass = getzd('user','pass',$uid);

        $pass = $this->input->get_post('pass',true);
        $pass1 = $this->input->get_post('pass1',true);
        $pass2 = $this->input->get_post('pass2',true);
        if($ypass != '123456' && empty($pass)) get_json('原密码不能为空');
        if(empty($pass1)) get_json('新密码不能为空');
        if($pass1 != $pass2) get_json('两次新密码不一直');
        if($ypass != '123456' && $ypass != md5($pass)) get_json('原密码不正确');
        $res = $this->mcdb->get_update('user',$uid,array('pass'=>md5($pass1)));
        if($res){
            get_json('密码更新成功',1);
        }else{
            get_json('密码更新失败');
        }
    }

    //上传头像
    public function pic() {
        $x = (int)$this->input->get_post('x');
        $y = (int)$this->input->get_post('y');
        $w = (int)$this->input->get_post('w');
        $h = (int)$this->input->get_post('h');
        if(!$this->users->login(1)) get_json('登陆超时!!!');
        $uid = (int)$this->cookie->get('user_id');

        $cof['upload_path'] = FCPATH.Annex_Dir.'/user/'.get_str_date(Annex_Path).'/';
        mkdirss($cof['upload_path']); //创建文件夹
        $cof['allowed_types'] = Annex_Ext;
        $cof['file_name'] = date('YmdHis').rand(1111,9999);
        $cof['max_size'] = Annex_Size;
        $this->load->library('upload',$cof);

        if(!$this->upload->do_upload('image')){
            $msg = $this->upload->display_errors();
            get_json($msg);
        }else{
            $arr = $this->upload->data();
            $img_path_file = $arr['full_path'];
            $res = checkPicHex($img_path_file);
            if($res == 1) get_json('非法图片');
            //裁剪
            $config['image_library'] = 'gd2';
            $config['source_image'] = $img_path_file;
            $config['maintain_ratio'] = false;
            $config['width']  = $w;
            $config['height'] = $h;
            $config['x_axis'] = $x;
            $config['y_axis'] = $y;
            $this->load->library('image_lib', $config);
            $this->image_lib->crop();
            //缩放
            $config2['maintain_ratio'] = TRUE;
            $config2['width']  = 200;
            $config2['height'] = 200;
            $this->image_lib->initialize($config2);
            $this->image_lib->resize();
            //同步
            $img_path_file = get_tongbu($img_path_file);
            if(!$img_path_file) get_json('图片上传失败');
            //替换绝对路径
            $img_file = str_replace(FCPATH,Web_Path,$img_path_file);
            //获取原头像文件
            $rowu = $this->mcdb->get_row_arr('user','pic',array('id'=>$uid));
            //更新数据库
            $this->mcdb->get_update('user',$uid,array('pic'=>$img_file));
            //删除原头像文件
            get_tongbu($rowu['pic'],'del');
            //返回
            get_json(array('url'=>$img_file,'img'=>getpic($img_file),'msg'=>'图片上传完成'),1);
        }
    }
}
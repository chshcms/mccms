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

class User extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
		header("Access-Control-Allow-Origin: *");
		//加载函数
		$this->load->helper('app_helper');
		//判断签名
		get_app_sign();
		//用户ID
		$this->uid = (int)$this->input->get_post('user_id');
		//用户token
		$this->token = $this->input->get_post('user_token');
		//判断登录
		$this->user = get_app_log($this->uid,$this->token,$this->mcdb);
	}

	//会员主页
    public function index() {
        if($this->user){
            $row = $this->mcdb->get_row_arr('user','id,name,nichen,email,tel,pic,vip,viptime,cion,ticket,rmb',array('id'=>$this->uid));
            $row['vipday'] = $row['viptime'] > time() ? ceil(($row['viptime'] - time()) / 86400) : 0;
            $row['pic'] = getpic($row['pic'],'user');
            $row['viptime'] = $row['vip'] == 0 ? '未开通' : date('Y-m-d',$row['viptime']);
        }else{
            $row = array('id'=>0,'nichen'=>'立即登录','pic'=>getpic('','user'),'tel'=>'','vip'=>0,'viptime'=>'未开通','cion'=>0,'ticket'=>0,'rmb'=>0,'vipday'=>0);
        }
        $row['cion_name'] = Pay_Cion_Name;
        $row['tel_code'] = User_Reg_Tel == 0 ? 1 : 0;
        if(!empty($row['tel'])) $row['tel'] = substr($row['tel'],0,3).'****'.substr($row['tel'],-4);
        if(!empty($row['email'])){
            $arr = explode('@',$row['email']);
            $row['email'] = substr($arr[0],0,3).'****@'.$arr[1];
        }
        //邀请码
        $row2 = $this->mcdb->get_row_arr('user_invite','inviteid',array('uid'=>$this->uid));
        $row['inviteid'] = $row2 ? $row2['inviteid']+10000 : '';
        $d['code'] = 1;
        $d['user'] = get_app_data($row);
		get_json($d);
	}
	
	//修改资料
    public function edit() {
        if(!$this->user) get_json('未登录',-1);
        $type = $this->input->get_post('type',true);
        $codeinit = (int)$this->input->get_post('codeinit',true);
        $tel = $this->input->get_post('tel',true);
        $code = (int)$this->input->get_post('code',true);
        $email = $this->input->get_post('email',true);
        $ypass = $this->input->get_post('ypass',true);
        $pass = $this->input->get_post('pass',true);
        $nichen = $this->input->get_post('nichen',true);
        $ckey = $this->input->get_post('ckey',true);
        $edit = $d = array();
        if($type == 'tel'){
            if($codeinit == 1){
                $tel = getzd('user','tel',$this->uid);
            }else{
                if(!is_tel($tel)) get_json('手机号码格式不正确',0);
                if(sys_auth($ckey,1) != $this->uid) get_json('非法请求',0);
                $row = $this->mcdb->get_row_arr('user','id',array('tel'=>$tel));
                if($row) get_json('该手机已注册',0);
            }
            //判断手机验证码是否正确
            $row = $this->mcdb->get_row_arr('telcode','*',array('tel'=>$tel));
            if(!$row || $row['code'] != $code) get_json('手机验证码错误',0);
            //删除手机验证码记录
            $this->mcdb->get_del('telcode',$tel,'tel');
            if($codeinit == 1){
                get_json(array('ckey'=>sys_auth($this->uid)),1);
            }
            $edit['tel'] = $tel;
            $pass = getzd('user','pass',$this->uid);
            $d['token'] = md5('mccms_app'.$this->uid.$tel.$pass.Mc_Encryption_Key);
        }elseif($type == 'email'){
            if(!is_email($email)) get_json('邮箱格式不正确',0);
            $row = $this->mcdb->get_row_arr('user','id',array('email'=>$email));
            if($row) get_json('该邮箱已注册',0);
            $edit['email'] = $email;
        }elseif($type == 'nichen'){
            if(empty($nichen)) get_json('昵称不能为空',0);
            $edit['nichen'] = $nichen;
        }elseif($type == 'pass'){
            if(empty($ypass)) get_json('原密码不能为空',0);
            if(empty($pass)) get_json('新密码不能为空',0);
            $edit['pass'] = md5($pass);
            $d['token'] = md5('mccms_app'.$this->uid.$this->user['tel'].$edit['pass'].Mc_Encryption_Key);
        }elseif($type == 'invite'){
            $deviceid = $this->input->get_post('deviceid',true);
            $inviteid = (int)$this->input->get_post('inviteid')-10000;
            if($inviteid < 1) get_json('邀请不存在',0);
            if($inviteid == $this->uid) get_json('不能关联自己',0);
            $row = $this->mcdb->get_row_arr('user','id',array('id'=>$inviteid));
            if(!$row) get_json('邀请码不存在',0);
            //判断设备ID是否存在
            $row2 = $this->mcdb->get_row_arr('user_invite','id',array('deviceid'=>$deviceid));
            if(!$row2){
                $this->mcdb->get_insert('user_invite',array('uid'=>$this->uid,'inviteid'=>$inviteid,'deviceid'=>$deviceid,'addtime'=>time()));
                //领取奖励
                $user = $this->mcdb->get_row_arr('user','id,vip,viptime',array('id'=>$inviteid));
                app_task_reward($this->mcdb,2,$this->user);
                get_json('填写成功',1);
            }else{
                get_json('当前设备已关联',0);
            }
        }
        if(empty($edit)) get_json('非法请求',0);
        //修改
        $this->mcdb->get_update('user',$this->uid,$edit);
        //输出
        $d['code'] = 1;
        $d['msg'] = '修改成功';
        get_json($d);
    }
	
	//上传头像
    public function uppic() {
        if(!$this->user) get_json('未登录',-1);
        $cof['upload_path'] = FCPATH.Annex_Dir.'/user/'.get_str_date(Annex_Path).'/';
        mkdirss($cof['upload_path']); //创建文件夹
        $cof['allowed_types'] = Annex_Ext;
        $cof['file_name'] = date('YmdHis').rand(1111,9999);
        $cof['max_size'] = Annex_Size;
        $this->load->library('upload',$cof);
        //上传
        if(!$this->upload->do_upload('file')){
            $msg = $this->upload->display_errors();
            get_json($msg,0);
        }else{
            $arr = $this->upload->data();
            $img_path_file = $arr['full_path'];
            //同步
            $img_path_file = get_tongbu($img_path_file);
            if(!$img_path_file) get_json('图片上传失败',0);
            //替换绝对路径
            $img_file = str_replace(FCPATH,Web_Path,$img_path_file);
            //获取原头像文件
            $rowu = $this->mcdb->get_row_arr('user','pic',array('id'=>$this->uid));
            //更新数据库
            $this->mcdb->get_update('user',$this->uid,array('pic'=>$img_file));
            //删除原头像文件
            get_tongbu($rowu['pic'],'del');
            //返回
            $d['code'] = 1;
            $d['pic'] = getpic($img_file,'user');
            get_json($d);
        }
    }
    
	//购买记录
    public function buy($type='comic') {
        if($type != 'book') $type = 'comic';
        $table = $type == 'book' ? 'book_buy' : 'comic_buy';
        $zd = $type == 'book' ? 'bid' : 'mid';
        if(!$this->user) get_json('未登录',-1);
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size == 0 || $size > 100) $size = 15;
		if($page == 0) $page = 1;
		
		$sql = 'select count('.$zd.') num from '.Mc_SqlPrefix.$table.' where uid='.$this->uid.' GROUP BY '.$zd;
        $row2 = $this->db->query($sql)->row_array();
        //总数量
		$nums = $row2 ? $row2['num'] : 0;
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
        $sql = 'select max(a.id) id,a.'.$zd.',max(a.cid) zid from '.Mc_SqlPrefix.$table.' a left join '.Mc_SqlPrefix.'book b on a.mid=b.id where a.uid='.$this->uid.' GROUP BY a.'.$zd.' order by id desc';
		$read = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		$i = 0;
		foreach ($read as $k => $v) {
			//详情
			$row = get_app_data($this->mcdb->get_row_arr($type,'name,pic,picx,author,score,serialize state',array('id'=>$v[$zd])));
			if($row){
                $table_chapter = $type == 'book' ? get_chapter_table($v['bid']) : 'comic_chapter';
    			$v['name'] = $row['name'];
    			$v['pic'] = $row['pic'];
    			$v['author'] = $row['author'];
    			$v['state'] = $row['state'];
    			$v['nums'] = $this->mcdb->get_nums($table,array($zd=>$v[$zd],'uid'=>$this->uid));
    			$v['zcion'] = $this->sum_cion($v[$zd],$type,$table_chapter);
    			$v['news_name'] = getzd($table_chapter,'name',$v[$zd],$zd);
			    $read[$i] = $v;
			    $i++;
			}
		}
		//输出
		$data['nums'] = $nums;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['list'] = get_app_data($read);
		get_json($data,1);
	}
    
	//消费明细列表
    public function consume() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;

        //总数量
		$nums = $this->mcdb->get_nums('buy',array('uid'=>$this->uid));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select text,cion,addtime from '.Mc_SqlPrefix.'buy where uid='.$this->uid.' order by id desc';
		$list = $this->mcdb->get_sql($sql.' limit '.$limit,1);
		foreach ($list as $k=>$row){
		    $list[$k]['cion'] = $list[$k]['cion'].Pay_Cion_Name;
		}
        //输出
        $d['code'] = 1;
        $d['nums'] = $nums;
        $d['size'] = $size;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['list'] = get_app_data($list);
        get_json($d);
    }
    
	//充值成功订单
    public function order() {
        $page = (int)$this->input->get_post('page');
        if($page == 0) $page = 1;
        $size = (int)$this->input->get_post('size');
        if($size == 0 || $size > 300) $size = 15;

        //总数量
		$nums = $this->mcdb->get_nums('order',array('uid'=>$this->uid,'pid'=>1));
		//总页数
		$pagejs = ceil($nums / $size);
		if($pagejs == 0) $pagejs = 1;
		//偏移量
		$limit = $size*($page-1).','.$size;
		$sql = 'select dd,text,rmb,addtime from '.Mc_SqlPrefix.'order where uid='.$this->uid.' and pid=1 order by id desc';
		$list = $this->mcdb->get_sql($sql.' limit '.$limit,1);
        //输出
        $d['code'] = 1;
        $d['nums'] = $nums;
        $d['size'] = $size;
        $d['page'] = $page;
        $d['pagejs'] = ceil($nums / $size);
        $d['list'] = get_app_data($list);
        get_json($d);
    }
	
    //统计消耗金币
    private function sum_cion($id,$type='comic',$table_chapter='comic_chapter') {
        $zd = $type == 'comic' ? 'mid' : 'bid';
        $sql = 'select sum(b.cion) as cion from '.Mc_SqlPrefix.$type.'_buy a left join '.Mc_SqlPrefix.$table_chapter.' b on a.cid=b.id where a.'.$zd.'='.$id.' and a.uid='.$this->uid;
        $row = $this->db->query($sql)->row_array();
        return ((int)$row['cion']).Pay_Cion_Name;
    }
}
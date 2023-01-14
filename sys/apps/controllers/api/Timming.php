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

class Timming extends Mccms_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->get_templates('admin');
    }

    //win访问
    public function win($type='comic',$ly='') {
        $pass = $this->input->get('pass',true);
        if(empty($pass)) exit('访问密码不能为空');
        $zyk = require MCCMSPATH.'libs/collect.php';
        if(substr($ly,0,6) == 'mccms_'){
            $gzyk = json_decode(getcurl(sys_auth(Zykurl,1,'mccms_zyk')),1);
            $arr = $gzyk[$ly];
        }else{
            $arr = $zyk['zyk'][$ly];
        }
        $tim = $type == 'book' ? $zyk['timming_book'][$ly] : $zyk['timming'][$ly];
        $tim['ly'] = $ly;
        $tim['type'] = $type;
        if($tim['pass'] != $pass) exit('密码不正确');
        if($tim['zt'] == 1) exit('该任务已经停止');
        $data['i'] = $tim['i'];
        $data['cjurl'] = links('api','timming','send').'?token='.urlencode(sys_auth($tim));
        $this->load->view('caiji/api_win.tpl',$data);
    }

    //os访问
    public function os($type='comic',$ly='') {
        set_time_limit(0); //不超时
        $pass = $this->input->get('pass',true);
        if(empty($pass)) exit('访问密码不能为空');
        $zyk = require MCCMSPATH.'libs/collect.php';
        $timming = $type == 'book' ? $zyk['timming_book'] : $zyk['timming'];
        if(!isset($timming[$ly])) exit('采集来源不存在!');
        $tim = $timming[$ly];
        if($tim['pass'] != $pass) exit('密码不正确');
        if($tim['zt'] == 1) exit('该任务已经停止');
        $page = 1;
        $tim['url'] = strpos($tim['url'],'://') === false ? sys_auth($tim['url'],1,'mccms_zyk') : $tim['url'];
        $cjurl = $tim['url'].'?ac=data&day='.$tim['day'];
        do {
            $res = $this->ruku($cjurl,$page,$ly,$type);
            $page++;
            sleep(3);
        } while ($res==1);
        echo '全部采集完毕，等待下个时间点继续!!!';
    }

    //WIN采集
    public function send() {
        set_time_limit(0); //不超时
        $token = $this->input->get('token');
        $page = (int)$this->input->get('page');
        if($page == 0) $page = 1;
        $tim = sys_auth($token,1);
        if(!isset($tim['url'])) exit('非法访问!!');
        $tim['url'] = sys_auth($tim['url'],1,'mccms_zyk');
        $cjurl = $tim['url'].'?ac=data&day='.$tim['day'];
        $res = $this->ruku($cjurl,$page,$tim['ly'],$tim['type']);
        if($res == 1){
            echo '第'.$page.'页采集完毕，3秒后继续下一页......';
            echo "<script>setTimeout(function() {
                window.location.href = '?token=".$token."&page=".($page+1)."';
            }, 3000);</script>";
        }else{
            exit('<script>parent.n = 0;</script>全部采集完成，等待下个时间点继续!!!');
        }
    }

    //采集入库开始
    private function ruku($url='',$page=1,$ly,$type){
        $this->load->model('collect');
        $json = getcurl($url.'&page='.$page);
        $apiarr = json_decode($json,1);
        $zykarr = require MCCMSPATH.'libs/collect.php';
        $bind = $zykarr['bind'];
        $msg = array();
        //循环入库
        foreach ($apiarr['data'][$type] as $k => $v) {
            $v = str_checkhtml($v,1);
            //标题替换
            $v['name'] = $this->collect->get_name_replace($v['name'],$type);
            $zycid = $v['cid'];
            $v['cid'] = isset($bind[$ly][$zycid]) ? (int)$bind[$ly][$zycid] : 0;
            //判断绑定分类
            if($v['cid'] > 0){ //未绑定
                $row = $this->collect->get_query($v,$type);
                //数据存在
                if($row){
                    $this->collect->get_update($v,$row['id'],$type,$ly);//更新入库
                }else{
                    $this->collect->get_insert($v,$type,$ly);//新增入库
                }
            }
        }
        //更新最后执行时间
        if($type == 'book'){
            $zykarr['timming_book'][$ly]['time'] = date('Y-m-d H:i:s');
        }else{
            $zykarr['timming'][$ly]['time'] = date('Y-m-d H:i:s');
        }
        arr_file_edit($zykarr,MCCMSPATH.'libs/collect.php');
        //判断采集完成
        if($page >= $apiarr['data']['pagejs']){
            return 2;
        }
        //还有下一页
        return 1;
    }
}
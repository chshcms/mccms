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
//邮件发送模型
class Mail extends CI_Model{
    function __construct (){
        parent:: __construct ();
    }

    //发送邮件
    function send($arr = array()){

        if(!isset($arr['to_mail']) || !isset($arr['title']) || !isset($arr['html']) ||
            empty($arr['to_mail']) || empty($arr['title']) || empty($arr['html'])){
            return FALSE;
        }

        $mail['crlf']          = "\r\n";
        $mail['newline']       = "\r\n";
        $mail['charset']       = 'utf-8';
        $mail['mailtype']      = 'html';
        $mail['protocol']      = isset($arr['type']) ? $arr['type'] : Mail_Type;
        $mail['smtp_host']     = isset($arr['host']) ? $arr['host'] : Mail_Host; 
        $mail['smtp_port']     = isset($arr['port']) ? $arr['port'] : Mail_Port;
        $mail['smtp_user']     = isset($arr['user']) ? $arr['user'] : Mail_Name;
        $mail['smtp_pass']     = isset($arr['pass']) ? $arr['pass'] : Mail_Pass;
        $mail['smtp_crypto']   = isset($arr['crypto']) ? $arr['crypto'] : Mail_Crypto;
        $this->load->library('email', $mail);

        $from_mail = isset($arr['form_mail']) ? $arr['form_mail'] : Mail_Email;
        $from_name = isset($arr['form_name']) ? $arr['form_name'] : Mail_Name;

        $this->email->from($from_mail, $from_name);
        $this->email->to($arr['to_mail']);
        $this->email->subject($arr['title']);
        $this->email->message($arr['html']);
        if(!$this->email->send()){
            //echo $this->email->print_debugger();  //返回信息
            return FALSE;
        }else{
            return TRUE;
        }
    }
}
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
class Err extends Mccms_Controller {

	public function __construct(){
		parent::__construct();
	}

	//404
    public function index() {
    	echo '<!DOCTYPE html><html lang="en"><head>	<meta charset="utf-8">	<title>404 Page Not Found</title>	<style type="text/css">	::selection { background-color: #f07746; color: #fff; }	::-moz-selection { background-color: #f07746; color: #fff; }	body {		background-color: #fff;		margin: 40px auto;		max-width: 1024px;		font: 16px/24px normal "Helvetica Neue", Helvetica, Arial, sans-serif;		color: #808080;	}	a {		color: #dd4814;		background-color: transparent;		font-weight: normal;		text-decoration: none;	}	a:hover {		color: #97310e;	}	h1 {		color: #fff;		background-color: #dd4814;		border-bottom: 1px solid #d0d0d0;		font-size: 22px;		font-weight: bold;		margin: 0 0 14px 0;		padding: 5px 15px;		line-height: 40px;	}	h2 {		color:#404040;		margin:0;		padding:0 0 10px 0;	}	code {		font-family: Consolas, Monaco, Courier New, Courier, monospace;		font-size: 13px;		background-color: #f5f5f5;		border: 1px solid #e3e3e3;		border-radius: 4px;		color: #002166;		display: block;		margin: 14px 0 14px 0;		padding: 12px 10px 12px 10px;	}	#container {		margin: 10px;		border: 1px solid #d0d0d0;		box-shadow: 0 0 8px #d0d0d0;		border-radius: 4px;	}	p {		margin: 0 0 10px;		padding:0;	}	#body {		margin: 0 15px 0 15px;		min-height: 96px;	}	</style></head><body>	<div id="container">		<h1>找不到404页</h1>		<div id="body">			<p>sorry,页面去旅游了，请先休息一下吧^_^!</p></div>	</div><script type="text/javascript">setTimeout(\'window.location="'.Web_Path.'"\', 3000);</script></body></html>';
	}

	public function comic(){
	    //获取模板
        $str = load_file('error.html');
		$this->parser->parse_string($str);
	}
}
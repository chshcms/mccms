<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mc_Loader extends CI_Loader {

	public function __construct()
	{
		parent::__construct();
		log_message('debug', "MY_Loader Class Initialized");
	}

	//网站模版
    public function get_templates($dir='')
    {
    	if(!empty($dir)){
    		if(substr($dir,-1) != '/') $dir .= DIRECTORY_SEPARATOR;
            $this->_ci_view_paths = array(VIEWPATH.str_replace('/',DIRECTORY_SEPARATOR,$dir) => TRUE);
		}elseif(defined('IS_ADMIN')){
            $this->_ci_view_paths = array(VIEWPATH.'admin'.DIRECTORY_SEPARATOR => TRUE);
		}else{
            $this->_ci_view_paths = array(VIEWPATH.str_replace('/',DIRECTORY_SEPARATOR,Skin_Pc_Path) => TRUE);
		}
    }
}
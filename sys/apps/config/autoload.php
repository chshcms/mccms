<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();
$autoload['libraries'] = array('cookie','parser','caches');
$autoload['drivers'] = array();
$autoload['helper'] = array('url','common','link');
$autoload['config'] = array();
$autoload['language'] = array();
$marr = array('tpl');
if(!defined('IS_INSTALL')) $marr[] = 'mcdb';
if(defined('IS_ADMIN')){
	$marr[] = 'admin';
}else{
	$marr[] = 'users';
}
$autoload['model'] = $marr;
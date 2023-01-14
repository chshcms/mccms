<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['error/404'] = 'error/index/404';
//类别智能检索
$route['category/(.+)'] = 'category/index/$1';
//自定义页面
$route['custom/(.+)'] = 'custom/index/$1';
//搜索
$route['search/(.+)'] = 'search/index/$1';

//加载自定义路由
require MCCMSPATH.'libs/rewrite.php';
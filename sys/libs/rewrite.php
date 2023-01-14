<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$route['lists/([a-zA-Z0-9\-\_]+)/(\d+)'] = 'lists/index/$1/$2';
$route['lists/([a-zA-Z0-9\-\_]+)'] = 'lists/index/$1';
$route['comic/([a-zA-Z0-9\-\_]+)'] = 'comic/index/$1';
$route['chapter/(\d+)'] = 'chapter/index/$1/$2';
$route['book/read/(\d+)/(\d+)'] = 'book/read/$1/$2';
$route['book/lists/([a-zA-Z0-9\-\_]+)/(\d+)'] = 'book/lists/$1/$2';
$route['book/lists/([a-zA-Z0-9\-\_]+)'] = 'book/lists/$1';
$route['book/info/([a-zA-Z0-9\-\_]+)'] = 'book/info/$1';
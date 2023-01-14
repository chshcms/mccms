<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
	'socket_type' => 'tcp',
	'host' => Cache_Redis_Ip,
	'password' => Cache_Redis_Pass === '' ? NULL : Cache_Redis_Pass,
	'port' => Cache_Redis_Port,
	'timeout' => 0
);
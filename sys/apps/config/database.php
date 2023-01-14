<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => Mc_Sqlserver,
	'port' => Mc_Sqlport,
	'username' => Mc_Sqluid,
	'password' => Mc_Sqlpwd,
	'database' => Mc_Sqlname,
	'dbdriver' => Mc_Dbdriver,
	'dbprefix' => Mc_SqlPrefix,
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => Mc_Sqlcharset,
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
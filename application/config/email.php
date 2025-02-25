<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
	'protocol'=>'sendmail',
	
	
	// 'smtp_host'=>'smtp.falconide.com',
	// 'smtp_port'=> 25,
	// 'smtp_pass'=>'77085$ee3d113',
	// 'smtp_user'=>'chasebid',
	'mailtype' => 'html',
	'charset' => 'utf-8',
	'wordwrap'=>TRUE,
	'newline'=> "\r\n",
	//'crlf' => "\r\n",
); 

// $config = array(
// 	'protocol'=>'sendmail',
	
// 	'smtp_host'=>'ssl://smtp.googlemail.com',
// 	'smtp_port'=> 465,
// 	'smtp_user'=>'emts.testers@gmail.com',
// 	'smtp_pass'=>'emts5526001',
// 	//'smtp_crypto' => 'ssl',
// 	'mailtype' => 'html',
// 	'charset' => 'utf-8',
// 	'wordwrap'=>TRUE,
// 	'newline'=> "\r\n",
// 	//'crlf' => "\r\n",
// ); 
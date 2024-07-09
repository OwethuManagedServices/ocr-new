<?php
// Global variables
$oV = [
	'sDirectoryBin' => '/usr/bin/',
	'sDirectoryUpload' => getcwd() . '/upload/',
	'sDirectoryWork' => getcwd() . '/work/',
	'sApiUrl' =>  (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/v1/',
	'bUseAuth' => 0,
	'iPageDpi' => 600,
	'bReconBalanceFixed' => 0,
];

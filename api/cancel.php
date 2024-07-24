<?php
// Cancel the process
require('functions.php');

$sId = $oRouteVars['id'];
$oMeta = [];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	$oMeta['message'] = 'Canceled';
	$oMeta['error'] = 999;
	$oMeta['result']['job'] = '';
	if ((isset($oMeta['pid'])) && ($oMeta['pid'])){
		$oProcess = new Process();
		$oProcess->setPid($oMeta['pid']);
		$oProcess->stop();
		$oMeta['pid'] = 0;
	}
	$sCmd = getcwd() . '/killall.sh';
	shell_exec($sCmd);
	$sCmd = 'rm -rf ' . getcwd() . '/work/' . $oMeta['result']['id'] . '/';
	shell_exec($sCmd);
//	error_log($sCmd);
}
response_json($oMeta);



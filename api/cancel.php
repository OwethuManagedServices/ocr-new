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
	if ((isset($oMeta['pid'])) && ($oMeta['pid'])){
		$oProcess = new Process();
		$oProcess->setPid($oMeta['pid']);
		$oProcess->stop();
		$oMeta['pid'] = 0;
	}
	file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
}
response_json($oMeta);



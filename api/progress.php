<?php
// Ping the process for progress information
require('functions.php');

$sId = $oRouteVars['id'];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';

if (file_exists($sMetaFile)){
    $oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if (file_exists($oV['sDirectoryWork'] . $sId . '/pid')){
		$oMeta['pid'] = file_get_contents($oV['sDirectoryWork'] . $sId . '/pid');
		file_put_contents($oV['sDirectoryWork'] . $sId . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
	}

	if ((isset($oMeta['result']['job'])) && ($oMeta['result']['job'] == 'ocr-to-data')){
		$iP = count(glob($oV['sDirectoryWork'] . $sId . "/out-col-*.hocr"));
		$iC = sizeof($oMeta['result']['template']['columns']);
		$iP = intval($iP / $iC);
		$oMeta['ocr_page'] = $iP;
		file_put_contents($oV['sDirectoryWork'] . $sId . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
	}

} else {
	$oMeta = [
		'error' => 1,
		'message' => 'The record could not be found',
		'result' => [
			'id' => $sId,
		],
	];
}
$sCmd = '/usr/bin/top -n 1';
exec($sCmd, $oOutput, $iResult);	
$oMeta['top'] = $oOutput;
response_json($oMeta);




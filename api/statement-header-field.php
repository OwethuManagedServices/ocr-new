<?php
// Return the statement information
require('functions.php');

$sId = $oRouteVars['id'];
$sField = $oRouteVars['field'];

$oMetaRet = [
	'error' => 9,
	'message' => 'Invalid field name',
];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['header'])) && (isset($oMeta['result']['header'][$sField]))){
		$oMetaRet = [
			'error' => 0,
			'message' => 'Header Field',
			'result' => [
				'field' => $sField,
				'value' => $oMeta['result']['header'][$sField],
			],
		];
	}
}
response_json($oMetaRet);



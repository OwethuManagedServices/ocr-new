<?php
// Find the salaries
require('functions.php');



$sId = $oRouteVars['id'];
$oMeta = [
	'error' => 10,
	'message' => 'Could not find the record',
];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
		$aGrid = $oMeta['result']['grid'];
		if (isset($oMeta['result']['edit'])){
			$aEdit = $oMeta['result']['edit'];
		} else {
			$aEdit = array_fill(0, sizeof($aGrid), []);
		}
		// Do the job
		$aRes = salary($oMeta);
		$oMeta['result']['salaries'] = $aRes[0];
		$oMeta['result']['incomes'] = $aRes[1];
		$oMeta['result']['job'] = 'display';
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	}
}



response_json($oMeta);




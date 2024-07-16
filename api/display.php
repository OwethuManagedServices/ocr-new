<?php
// Find the monthly opening and closing balances
require('functions.php');




function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$oMeta = [
		'error' => 10,
		'message' => 'Could not find the record',
	];
    $sWork = $oV['sDirectoryWork'] . $sId . '/';
	$sMetaFile = $sWork . '/meta.json';
	if (file_exists($sMetaFile)){
        copy ($sWork . '../../data/templates/display.json', $sWork . 'display.json');
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
			$oMeta['result']['display'] = json_decode(file_get_contents($sWork . 'display.json'));
			file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
		}
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);

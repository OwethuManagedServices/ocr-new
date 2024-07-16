<?php
// Rename the folder and change the ID
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$oMeta = [
		'error' => 12,
		'message' => 'Could not find the record',
	];
    $sWork = $oV['sDirectoryWork'] . $sId;
	$sMetaFile = $sWork . '/meta.json';
error_log($sMetaFile);
	if (file_exists($sMetaFile)){
        error_log(123);
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		if ((isset($oMeta)) && (isset($oMeta['result']))){
            $oMeta['result']["job"] = 'load';
		}
	}
    error_log(json_encode($_SERVER['REQUEST_METHOD']));
	response_json($oMeta);
}



job($oRouteVars, $oV);

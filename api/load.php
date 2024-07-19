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
	if (file_exists($sMetaFile)){
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		if ((isset($oMeta)) && (isset($oMeta['result']))){
            $oMeta['result']["job"] = 'load';
		}
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);

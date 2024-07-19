<?php
// Return the recon statement
require('functions.php');



function job($oRouteVars, $oV){
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
			$aData = recon_balance($oMeta, $oV);
			$oMeta['result']['grid'] = $aData[1];
			$oMeta['result']['recon'] = $aData[0];
			$oMeta['result']['job'] = 'balances';
			file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
		}
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);



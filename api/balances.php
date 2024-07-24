<?php
// Find the monthly opening and closing balances
require('functions.php');

//$oRouteVars['id'] = '1721399736570339';

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
			$aResult = balances_monthly($oMeta);
			$oMeta['result']['balances'] = $aResult[0];
			$oMeta['result']['header']['date_from'] = $aResult[1];
			$oMeta['result']['header']['date_to'] = $aResult[2];
			$oMeta['result']['job'] = 'salary';
			file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
		}
	}
	response_json($oMeta);
}




job($oRouteVars, $oV);



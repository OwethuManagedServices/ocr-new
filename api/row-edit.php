<?php
// Return the statement information
require('functions.php');

/*
$oRouteVars['data'] = 'WzE2LCIyMDIzLTEyLTEzIiwiIiwiRk5CIEFwcCBQYXltZW50IFRvIEtpcHBlIE5ldyBEb3R0aWUiLCIiLCIyMDAiLCI1MTExLjEwIiwiMTE5OS41OSIsIjAuMDAiLCI1MTExLjEwIl0_';
$oRouteVars['id'] = 'fnb';
*/

$sId = $oRouteVars['id'];
$oData = $oRouteVars['data'];
$oData = str_replace('_', '=', $oData);
$oData = str_replace('-', '/', $oData);
$oData = json_decode(base64_decode($oData), 1);
$iRow = $oData[0];
$oMeta = [
	'error' => 10,
	'message' => 'Could not save the record',
];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
//		$aG = [];
		$aGrid = $oMeta['result']['grid'];
		if (isset($oMeta['result']['edit'])){
			$aEdit = $oMeta['result']['edit'];
		} else {
			$aEdit = array_fill(0, sizeof($aGrid), []);
		}
		// Ignore the OPTIONS method, only respond to GET method
		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			if ($aGrid[$iRow][6]){
				$iBalance = $aGrid[$iRow][6];
			} else {
				$iBalance = $aGrid[$iRow - 1][6] + floatval($oData[4]) + floatval($oData[5]);
			}
			$oData[6] =  number_format($iBalance, 2, '.', '');
			$aEdit[$iRow] = $oData;
			$oMeta['result']['edit'] = $aEdit;

			$oTemplate = $oMeta['result']['template'];
			$aRecon = recon_balance($aGrid, $oTemplate, $aEdit);
			$aGrid = $aRecon[1];
			$aRecon = $aRecon[0];
			$oMeta['result']['recon'] = $aRecon;
			$oMeta['result']['grid'] = $aGrid;

			file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
		}
	}
}
response_json($oMeta);



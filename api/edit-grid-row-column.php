<?php
// Return the statement information
require('functions.php');

$sId = $oRouteVars['id'];
$sPageRow = $oRouteVars['page_row'];
$iColumn = intval($oRouteVars['column']);
$sValue = base64_decode($oRouteVars['id']);

$oMetaRet = [
	'error' => 10,
	'message' => 'Could not save the record',
];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
		$aValue = [];
		$aRecon = [];
		$iIndex = -1;
		$iRow = 0;
		$bFound = 0;
		foreach ($oMeta['result']['grid'] as $aRow){
			if ($aRow[0] == $sPageRow){
				$bFound = 1;
				$aValue = $aRow;
				$aValue[$iColumn] = $sValue;
				$iIndex = $iRow;
				$aRecon = $oMeta['result']['recon'][$iRow];
				break;
			}
			$iRow++;
		}
		if ($bFound){
			file_put_contents(json_encode($sMetaFile, JSON_PRETTY_PRINT));
		}
		$oMetaRet = [
			'error' => 0,
			'message' => 'Grid Row Edit',
			'result' => [
				'row' => $sPageRow,
				'value' => $aValue,
				'index' => $iRow,
				'recon' => $aRecon,
			],
		];
	}
}
response_json($oMetaRet);



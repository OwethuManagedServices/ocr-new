<?php
// Return the statement information
require('functions.php');

$sId = $oRouteVars['id'];
$sPageRow = $oRouteVars['page_row'];

$oMetaRet = [
	'error' => 9,
	'message' => 'Invalid grid row',
];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
		$aValue = [];
		$aRecon = [];
		$iIndex = -1;
		$iRow = 0;
		foreach ($oMeta['result']['grid'] as $aRow){
			if ($aRow[0] == $sPageRow){
				$aValue = $aRow;
				$iIndex = $iRow;
				$aRecon = $oMeta['result']['recon'][$iRow];
				break;
			}
			$iRow++;
		}
		$oMetaRet = [
			'error' => 0,
			'message' => 'Grid Row',
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



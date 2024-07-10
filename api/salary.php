<?php
// Find the salaries
require('functions.php');


 //$oRouteVars['id'] = '1720447509820712';


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
		$aRes = salary($oMeta);
		$oMeta['result']['salaries'] = $aRes[0];
		$oMeta['result']['incomes'] = $aRes[1];
		$oMeta['result']['job'] = 'salary';
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	}
}
response_json($oMeta);



function salary($oMeta){
	$aKeywords = ['salar', 'stipend', 'learnership', 'commission', 'wage', 'internship', 'remuneration'];
	$aGrid = $oMeta['result']['grid'];
	$aSalaries = [];
	$aIn = [];
	// Loop through each transaction
	for ($iI = 1; $iI < sizeof($aGrid); $iI++){
		$aR = $aGrid[$iI];
		if ($aR[4]){
			$bFound = 0;
			// Find the keyword in the description
			foreach ($aKeywords as $sK){
				if (strpos(strtolower($aR[3]), $sK) !== false){
					$bFound = 1;
					break;
				}
			}
			if ($bFound){
				// Write to Salaries grid
				$aSalaries[] = $aR;
			} else {
				// Write to income grid
				$aIn[] = $aR;
			}
		}
	}
	// Sort other income to largest first In amount
	usort($aIn, function ($a, $b) { 
		return ( floatval($a[4]) < floatval($b[4]) ? 1 : -1 ); 
	});
	return [$aSalaries, $aIn];
}

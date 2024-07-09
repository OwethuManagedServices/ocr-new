<?php

// Find the monthly opening and closing balances
require('functions.php');


 //$oRouteVars['id'] = '1720185656257098';


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
		$oMeta['result']['balances'] = balances_monthly($oMeta);
		$oMeta['result']['job'] = 'salary';
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	}
}
response_json($oMeta);



function statement_date_format($sV, $oTemplateBalance){
	$sX = $oTemplateBalance['field_format'];
	$bMonthString = 0;
	if (strpos($sX, 'MMM')){
		$aM = explode(', ', ', Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec');
		$bMonthString = 1;
	}
	if (strpos($sX, 'MMMM')){
		$aM = explode(', ', ', January, February, March, April, May, June, July, Augustus, September, October, November, December');
		$bMonthString = 1;
	}
	if ($bMonthString){
		$aD = explode(' ', $sV);
		$sM = array_search($aD[1], $aM);
		if ($sM < 10){
			$sM = '0' . $sM;
		}
		$sM .= '';
		$sV = str_replace(' ' . $aD[1]. ' ', '-' . $sM . '-', $sV);
		$aV = explode('-', $sV);
		if (strlen($aV[0]) == 1){
			$sV = $aV[2] . '-' . $aV[1] . '-0' . $aV[0]; 
		} else {
			$sV = $aV[2] . '-' . $aV[1] . '-' . $aV[0]; 
		}
	}
	if (!$bMonthString){
		$iP = strpos($sX, 'yyyy');
		$sW = substr($sV, $iP, 4) . '-';
		$iP = strpos($sX, 'mm');
		$sW .= substr($sV, $iP, 2) . '-';
		$iP = strpos($sX, 'dd');
		$sW .= substr($sV, $iP, 2);
		$sV = $sW;
	}
	return $sV;
}



function balances_monthly($oMeta){
	$aGrid = $oMeta['result']['grid'];
	$oTemplateBalance = $oMeta['result']['template']['statement_from_to_dates'];
	$oHeader = $oMeta['result']['header'];
	$sDateFrom = $oTemplateBalance['header_field_from'];
	if ($sDateFrom){
		$sDateFrom = $oHeader[$oTemplateBalance['header_field_from']];
		if (isset($oTemplateBalance['word_from_split'])){
			$sDateFrom = explode($oTemplateBalance['word_from_split'], $sDateFrom)[0];
			if (isset($oTemplateBalance['word_from_start'])){
				$sDateFrom = str_replace($oTemplateBalance['word_from_start'], '', $sDateFrom);
			}
		}
		/*
		$sDateTo = $oHeader[$oTemplateBalance['header_field_to']];
		if (isset($oTemplateBalance['word_to_split'])){
			$sDateTo = explode($oTemplateBalance['word_from_split'], $sDateTo)[0];
			if (isset($oTemplateBalance['word_to_start'])){
				$sDateTo = str_replace($oTemplateBalance['word_to_start'], '', $sDateTo);
			}
		}
		*/
		$sDateTo = $aGrid[sizeof($aGrid) - 1][1];
		$sDateFrom = statement_date_format($sDateFrom, $oTemplateBalance);
//		$sDateTo = statement_date_format($sDateTo, $oTemplateBalance);
	}
	$sDateFromTo = $oTemplateBalance['header_field_from_to'];
	if ($sDateFromTo){
		$sD = $oHeader[$sDateFromTo];
		$aD = explode($oTemplateBalance['word_split'], $sD);
		$aD[0] = str_replace($oTemplateBalance['word_start'], '', $aD[0]);
		$sDateFrom = statement_date_format(trim($aD[0]), $oTemplateBalance);
//		$sDateTo = statement_date_format(trim($aD[1]), $oTemplateBalance);
		$aR = $aGrid[sizeof($aGrid) - 1];
		$sDateTo = $aR[1];

	}
	$iTimeFrom = strtotime($sDateFrom);
	$iTimeTo = strtotime($sDateTo);
	$iDaysPeriod = ($iTimeTo - $iTimeFrom) / 60 / 60 / 24;
	$sDayStart = substr($sDateFrom, 8, 2);
	$sMonthStart = substr($sDateFrom, 5, 2);
	$aR = $aGrid[0];
	$aBalances = [['Dates From/To', $sDateFrom, $sDateTo, $iDaysPeriod . ' days'], ['Starting', $aR[1], $aR[6]]];
	for ($iI = 1; $iI < sizeof($aGrid); $iI++){
		$aROld = $aR;
		$aR = $aGrid[$iI];
		$sDayNow = substr($aR[1], 8, 2);
		$sMonthNow = substr($aR[1], 5, 2);
		if (($sDayNow == $sDayStart) && ($sMonthNow != $sMonthStart)){
			$aBalances[] = ['Closing', $aROld[1], $aROld[6]];
			$aBalances[] = ['Opening', $aR[1], $aR[6]];
			$sMonthStart = $sMonthNow;
		}
	}
	$aBalances[] = ['Current', $aR[1], $aR[6]];
	return $aBalances;
}

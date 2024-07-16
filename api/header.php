<?php
// Extract header information from the first page
require('functions.php');

function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sBank = $oRouteVars['bank'];
	$iPages = $oRouteVars['pages'];

	$sWork = $oV['sDirectoryWork'] . $sId;
	$sMetaFile = metamessage('Finding Header Information', $sId, $oV);
	$bMeta = 1;
	$sHocr = $sWork . '/out-page-1.hocr';
	if (file_exists($sHocr)){
		$oTemplate = getcwd() . '/data/templates/banks/' . $sBank . '.json';
		if (file_exists($oTemplate)){
			$oTemplate = json_decode(file_get_contents($oTemplate), 1);
			$sHtml = str_get_html(file_get_contents($sHocr));
			$oHeader = header_info($sHtml, $oTemplate);
			$oMeta = json_decode(file_get_contents($sMetaFile), 1);
			$oMeta = [
				'error' => 0,
				'message' => 'Dividing pages into columns',
				'result' => [
					'id' => $sId,
					'pages' => $iPages,
					'bank' => $sBank,
					'header' => $oHeader,
					'thumbs' => $oMeta['result']['thumbs'],
					'job' => 'pages-columns',
				],
			];
			file_put_contents($oV['sDirectoryWork'] . $sId . '/header.json', json_encode($oHeader, JSON_PRETTY_PRINT));
		} else {
			$oMeta = [
				'error' => 6,
				'message' => 'Could not find a bank template',
				'id' => $sId,
			];
		}
		file_put_contents($sWork . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
		$bMeta = 0;
	}
	if ($bMeta){
		$oMeta = [
			'error' => 1,
			'message' => 'The record could not be found',
			'id' => $sId,
		];
	}
	response_json($oMeta);
}



function header_info($sHtml, $oTemplate){
	$oHeader = [];
	// Loop through the template header fields
	foreach ($oTemplate['header'] as $oT){
		// Get all the words at the field position
		$aWords = words_at_position($sHtml, $oT['position']);
		// Concatenate with ' '
		$sText = '';
		foreach ($aWords as $oW){
			$sText .= $oW['text'] . ' ';
		}
		// Remove unwanted words
		if (isset($oT['replace'])){
			$sText = str_replace($oT['replace'], '', $sText);
		}
		$oHeader[$oT['name']] = trim($sText);
	}
	// Add the bank name to the header info
	$oHeader['bank_name'] = $oTemplate['name'];
	return $oHeader;
}



job($oRouteVars, $oV);



<?php
// Find the bank template by identifying text in certain positions
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sMetaFile = metamessage('Searching bank template', $sId, $oV);
	$bMeta = 1;
	if (file_exists($oV['sDirectoryWork'] . $sId . '/0.pdf')){
		$sWork = $oV['sDirectoryWork'] . $sId;
		$sZ = '';
		$sPng = $sWork . '/0-' . $sZ . '1.jpg';
		if (file_exists($sPng)){
			if (!file_exists($sWork . '/out-page-1.hocr')){
				// Run OCR on first page to identify bank and retrieve header info
				$sCmd = $oV['sDirectoryBin'] . 'tesseract ' . $sPng . ' ' . $sWork . '/out-page-1 hocr';
				runcommand($sCmd, $sMetaFile);
			}
			$oData = [
				'id' => $sId,
				'work' => $oV['sDirectoryWork']
			];

			$oData = bank_template($oData);
			if ($oData['error']){
				$oMeta = [
					'error' => 5,
					'message' => 'Could not find a bank template',
					'id' => $sId,
				];
			} else {
				$oMeta = json_decode(file_get_contents($sMetaFile), 1);
				$oMeta = [
					'error' => 0,
					'message' => $oData['message'] . '. Finding header information',
					'result' => [
						'id' => $sId,
						'pages' => $oRouteVars['pages'],
						'bank' => $oData['bank'],
						'thumbs' => $oMeta['result']['thumbs'],
						'job' => 'header',
					],
				];
			}
		} else {
			$oMeta = [
				'error' => 4,
				'message' => 'Could not find the first page',
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


// Find the bank by looking for words at certain positions on the first page
function bank_template($oData){
	$sId = $oData['id'];
	$sWork = $oData['work'] . $sId . '/';
	$sHocr = $sWork . 'out-page-1.hocr';
	$sHtml = str_get_html(file_get_contents($sHocr));
	$sBank = '';
	// Load the detection JSON
	$sF = getcwd() . '/data/templates/bank-detect.json';
	$aTemplate = json_decode(file_get_contents($sF), 1);
	foreach ($aTemplate as $oT){
		if (!$sBank){
			switch ($oT['method']){
				// Detect in 2 ways
				case 'words_by_class_id':
					$aBank = [];
					foreach ($oT['test'] as $oTT){
						$aBank[] = words_by_class_id($sHtml, 'span', $oTT['parameter']);
						if (($aBank[0] == $oTT['value'])){
							$sBank = $oT['name'];
							break;
						}
						if ($sBank){
							break;
						}
					}
				break;
				
				case 'words_at_position':
					$aBank = [];
					foreach ($oT['test'] as $oTT){
						$aBank[] = words_at_position($sHtml, $oTT['parameter']);
						if ((isset($aBank[0][0]['text']) && ($aBank[0][0]['text'] == $oTT['value']))){
							$sBank = $oT['name'];
							break;
						}
						if ($sBank){
							break;
						}
					}
				break;
				
			}
		}
	
	}
	if ($sBank){
		$oData['bank'] = $sBank;
		$oData['error'] = 0;
		$oData['message'] = 'Found template: ' . $sBank;
	} else {
		$oData['bank'] = '';
		$oData['message'] = 'Could not find a bank template';
		$oData['error'] = 1;
	}
	return $oData;
}



job($oRouteVars, $oV);




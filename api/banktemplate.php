<?php
// Find the bank template by identifying certain text in certain positions
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
	$sWork = $oData['work'] . $sId;
	$sHocr = $sWork . '/out-page-1.hocr';
	$sHtml = str_get_html(file_get_contents($sHocr));
	$sBank = '';

	// Check for Capitec Bank Limited
	$aBank = [];
	$aBank[] = words_by_class_id($sHtml, 'span', 'word_1_1');
	if (($aBank[0] == 'One')){
		$sBank = 'capitec';
	}
		
	// Check for ABSA
	$aBank = [];
	$aBank[] = words_by_class_id($sHtml, 'span', 'word_1_1');
	if (($aBank[0] == 'eStamp')){
		$sBank = 'absa';
	}
		
	// Check for ABSA2
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[4227, 146, 4680, 322]);
	$aBank[] = words_at_position($sHtml,[4190, 310, 4686, 500]);
	if ((($aBank[0]) && ($aBank[0][0]['text'] == '(absa)')) || (($aBank[1]) && ($aBank[1][0]['text'] == '(absa)'))){
		$sBank = 'absa2';
	}
	
	// Check for Nedbank
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[830, 5915, 1385, 5970]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'www.nedbank.co.za')){
		$sBank = 'nedbank';
	}
	
	// Check for Nedbank 2
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[666, 170, 940, 210]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'NEDBANK')){
		$sBank = 'nedbank2';
	}

	// Check for Nedbank 3
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[777, 642, 1052, 691]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'NEDBANK')){
		$sBank = 'nedbank3';
	}
	
	// Check for FNB
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[1341, 2315, 1696, 2375]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == '4210102051')){
		$sBank = 'fnb';
	}

	// Check for STDBANK
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[500, 260, 1130, 400]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'Standard')){
		$sBank = 'standard';
	}

	// Check for STDBANK 2
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[1690, 770, 2190, 860]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'STANDARD')){
		$sBank = 'standard2';
	}

	// Check for STDBANK 3
	$aBank = [];
	$aBank[] = words_at_position($sHtml,[1768, 468, 2524, 576]);
	if (($aBank[0]) && ($aBank[0][0]['text'] == 'STANDARD')){
		$sBank = 'standard3';
	}

	if ($sBank){
		$oData['bank'] = $sBank;
		$oData['error'] = 0;
		$oData['message'] = 'Found template: ' . $sBank;
	} else {
		$oData['bank'] = '';
		$oData['message'] = 'Could not find a suitable template. Aborting';
		$oData['error'] = 1;
	}
	return $oData;
}



job($oRouteVars, $oV);


<?php
// Split each page into seperate images per column
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sBank = $oRouteVars['bank'];
	$aPages = json_decode($oRouteVars['pages']);
	$sWork = $oV['sDirectoryWork'] . $sId;
	// Create shell scripts to run the OCR jobs
	file_put_contents($sWork . '/ocrjob', "#!/bin/bash\ndate\n");
	chmod($sWork . '/ocrjob', 0777);
	file_put_contents($sWork . '/ocrjob1', "#!/bin/bash\ndate\nexport HOME=/tmp\nexport OMP_THREAD_LIMIT=1\n");
	chmod($sWork . '/ocrjob1', 0777);
	$sMetaFile = metamessage('OCR columns', $sId, $oV);
	$bMeta = 1;

	$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
	$sImg = $sWork . '/0-';
	if (file_exists($sImg . '1.jpg')){
		$oTemplate = getcwd() . '/data/templates/' . $sBank . '.json';
		if (file_exists($oTemplate)){
			$oTemplate = json_decode(file_get_contents($oTemplate), 1);
			$iPg = 0;
			foreach ($aPages as $iPages){
				for ($iPage = 1; $iPage <= $iPages; $iPage++){
					$sMetaFile = metamessage('Splitting into columns, page ' . ($iPg + 1) . '-' . $iPage , $sId, $oV);
					$sImg = $sWork . '/' . $iPg . '-';
					// Create JPG files for each columnn of this page
					pages_columnns($oTemplate, $sWork, $iPg, $iPage, $sImg . $iPage . '.jpg', $oV, $sMetaFile);
				}
				$iPg++;
			}
			$oMeta = [
				'error' => 0,
				'message' => 'Process OCR page ' . $iPage,
				'result' => [
					'id' => $sId,
					'pages' => json_encode($aPages),
					'bank' => $sBank,
					'page' => $iPage,
					'job' => 'ocr-to-data',
				],
			];
		} else {
			$oMeta = [
				'error' => 7,
				'message' => 'Could not find first page',
				'id' => $sId,
			];
		}
		$oMeta['result']['template'] = $oTemplate;
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
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


function pages_columnns($oTemplate, $sWork, $iPg, $iPage, $sImg, $oV, $sMetaFile){
	$iCol = 0;
	$iDpi = $oV['iPageDpi'];
	$sCmd = '';
	$sCmd1 = '';
	$sExecFile = file_get_contents($sWork . '/ocrjob');
	$sExecFile1 = file_get_contents($sWork . '/ocrjob1');
	foreach ($oTemplate['columns'] as $oCol){
		$iX = $oCol['position_x'][0];
		$iW = $oCol['position_x'][1] - $oCol['position_x'][0];
		if ((isset($oTemplate['grid']['skip_page_1'])) && ($oTemplate['grid']['skip_page_1'])){
			$iStart = 2;
		} else {
			$iStart = 1;
		}
		// Crop the page according to page number
		if ($iPage == $iStart){
			$iY = $oTemplate['grid']['page_1_start_y'];
			$iH = $oTemplate['grid']['page_1_end_y'] - $iY;
		// After first page
		} else {
			$iY = $oTemplate['grid']['start_y'];
			$iH = $oTemplate['grid']['end_y'] - $iY;
		}
		// Sometimes, the first page has different column coordinates
		if (isset($oCol['position_x'][2]) && ($iPage > 1)){
			$iX = $oCol['position_x'][2];
			$iW = $oCol['position_x'][3] - $oCol['position_x'][2];
		}
		$iX = intval($iX / ($oTemplate['dpi'] / $iDpi));
		$iY = intval($iY / ($oTemplate['dpi'] / $iDpi));
		$iW = intval($iW / ($oTemplate['dpi'] / $iDpi));
		$iH = intval($iH / ($oTemplate['dpi'] / $iDpi));
		// Use imagemagick to crop the column
		$sIm = $oV['sDirectoryBin'] . 'convert -crop ' . $iW . 'x' . $iH . '+' . $iX . '+' . $iY . ' ' . $sImg . ' ' . $sWork . '/col-' . $iPg . '-' . $iPage . '-' . $iCol . '.jpg';
		shell_exec($sIm);

		$sImgCol = $sWork . '/col-' . $iPg . '-' . $iPage . '-' . $iCol . '.jpg';
		$sCmd = '' . $oV['sDirectoryBin'] . 'tesseract ' . $sImgCol . ' ' . $sWork . '/out-col-' . $iPg . '-' . $iPage . '-' . $iCol . ' hocr';
		$sCmd1 .= ' "' . $oV['sDirectoryBin'] . 'tesseract ' . $sImgCol . ' ' . $sWork . '/out-col-' . $iPg . '-' . $iPage . '-' . $iCol . ' hocr"';
		$sExecFile .= $sCmd . "\n";
		file_put_contents($sWork . '/ocrjob', $sExecFile);
		$iCol++;
	}
	// Finalise the shell scripts
	$sCmd1 = $oV['sDirectoryBin'] . 'parallel -X :::' . $sCmd1;
	$sCmd1 .= "\necho $$ > " . $sWork . '/pid' . "\n";
	$sExecFile1 .= $sCmd1 . "\ndate\n";
	file_put_contents($sWork . '/ocrjob1', $sExecFile1);
}



job($oRouteVars, $oV);



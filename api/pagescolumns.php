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
		$oTemplate = getcwd() . '/data/templates/banks/' . $sBank . '.json';
		if (file_exists($oTemplate)){
			$oTemplate = json_decode(file_get_contents($oTemplate), 1);
			$iPg = 0;
			$iPgNow = 1;
			foreach ($aPages as $iPages){
				for ($iPage = 1; $iPage <= $iPages; $iPage++){
					$sMetaFile = metamessage('Splitting into columns, page ' . $iPgNow, $sId, $oV);
					$sImg = $sWork . '/' . $iPg . '-';
					// Create JPG files for each columnn of this page
					pages_columnns($oTemplate, $sWork, $iPg, $iPage, $sImg . $iPage . '.jpg', $oV, $sMetaFile);
					$iPgNow++;
				}
				$iPg++;
			}
			$oMeta = json_decode(file_get_contents($sMetaFile), 1);
			$oMeta = [
				'error' => 0,
				'message' => 'Process OCR page ' . $iPage,
				'result' => [
					'id' => $sId,
					'pages' => json_encode($aPages),
					'bank' => $sBank,
					'page' => $iPage,
					'thumbs' => $oMeta['result']['thumbs'],
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



job($oRouteVars, $oV);



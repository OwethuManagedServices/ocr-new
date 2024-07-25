<?php
// Split the PDFs file into single page image files
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	metamessage('Separate PDF pages', $sId, $oV);
	$bMeta = 1;
	$iPdfNumber = 0;
	$aFiles= [];
	$aThumbs = [];

	$sWork = $oV['sDirectoryWork'] . $sId;
	$sMetaFile = $sWork . '/meta.json';
	if (file_exists($sMetaFile)){
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		$aFiles = json_decode($oMeta['result']['pages']);
		

		// Create shell script to run the splitting pages jobs
		file_put_contents($sWork . '/extjob', "#!/bin/bash\ndate\n");
		chmod($sWork . '/extjob', 0777);
		// Loop through all the uploaded PDFs
		while (file_exists($oV['sDirectoryWork'] . $sId . '/' . $iPdfNumber . '.pdf')){
			$iNumPages = $aFiles[$iPdfNumber];
			// Loop through the pages of this PDF
			for ($iI = 0; $iI < $iNumPages; $iI++){
				$sExecFile = file_get_contents($sWork . '/extjob');

				// Use vips to extract 600dpi JPG file
				$sCmd = $oV['sDirectoryBin'] . 'vips copy ' . $sWork . '/' . $iPdfNumber . '.pdf[dpi=600,page=' . $iI . '] ' . $oV['sDirectoryWork'] . $sId .'/' . $iPdfNumber . '-' . ($iI + 1) . '.jpg[Q=100]' . "\n";

				// Use vips to create a thumbnail
				$sThumb = 'thumb-' . $iPdfNumber . '-' . ($iI + 1) . '.jpg';
				$sCmd1 = $oV['sDirectoryBin'] . 'vips copy ' . $sWork . '/' . $iPdfNumber . '.pdf[dpi=25,page=' . $iI . '] ' . $oV['sDirectoryWork'] . $sId .'/' . $sThumb . '[Q=100]' . "\n";

				$aThumbs[] = [$iPdfNumber, $iI, $sThumb];
				file_put_contents($sWork . '/extjob', $sExecFile . $sCmd . $sCmd1);
			}
			$iPdfNumber++;
		}
		// Execute the script
		shell_exec($sWork . '/extjob');

		//Success
		if (file_exists($oV['sDirectoryWork'] . $sId . '/0.pdf')){
			if (file_exists($sWork . '/0-1.jpg')){
				$oMeta['message'] = 'Find the bank template';
				$oMeta['result']['pages'] = json_encode($aFiles);
				$oMeta['result']['job'] = 'bank-template';
			} else {
				$oMeta = [
					'error' => 3,
					'message' => 'Could not convert the PDF to image files',
					'result' => [
						'id' => $sId,
						'directory' => $oV['sDirectoryWork'],
					],
				];
			}
			file_put_contents($sWork . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
			$bMeta = 0;
		}
		if ($bMeta){
			$oMeta = [
				'error' => 1,
				'message' => 'The record could not be found',
				'result' => [
					'id' => $sId,
					'directory' => $oV['sDirectoryWork'],
				],
			];
		}
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);




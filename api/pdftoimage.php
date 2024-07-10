<?php
// Split the PDFs file into single page image files
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];

	$sMetaFile = metamessage('Separate PDF pages', $sId, $oV);
	$bMeta = 1;
	$iPdfNumber = 0;
	$aFiles= [];
	$aThumbs = [];
	// Loop through all the PDFs
	while (file_exists($oV['sDirectoryWork'] . $sId . '/' . $iPdfNumber . '.pdf')){
		$sWork = $oV['sDirectoryWork'] . $sId;
		// Find the number of pages in the PDF
		$image = new Imagick();
		$image->pingImage($oV['sDirectoryWork'] . $sId . '/' . $iPdfNumber . '.pdf');
		$iNumPages = $image->getNumberImages();
		$image->clear(); 
		$image->destroy();
		// Loop through the pages
		for ($iI = 0; $iI < $iNumPages; $iI++){
			// Use vips to extract 600dpi JPG file
			$sCmd = $oV['sDirectoryBin'] . 'vips copy ' . $sWork . '/' . $iPdfNumber . '.pdf[dpi=600,page=' . $iI . '] ' . $oV['sDirectoryWork'] . $sId .'/' . $iPdfNumber . '-' . ($iI + 1) . '.jpg[Q=100]';
			shell_exec($sCmd);
			// Use vips to create a thumbnail
			$sThumb = 'thumb-' . $iPdfNumber . '-' . ($iI + 1) . '.jpg';
			$sCmd = $oV['sDirectoryBin'] . 'vips copy ' . $sWork . '/' . $iPdfNumber . '.pdf[dpi=25,page=' . $iI . '] ' . $oV['sDirectoryWork'] . $sId .'/' . $sThumb . '[Q=100]';
			shell_exec($sCmd);
			$aThumbs[] = [$iPdfNumber, $iI, $sThumb];
		}
		$aFiles[] = count(glob($sWork . '/' . $iPdfNumber . '*.jpg'));
		$iPdfNumber++;
	}
	if (file_exists($oV['sDirectoryWork'] . $sId . '/0.pdf')){
		if (file_exists($sWork . '/0-1.jpg')){
			$oMeta = [
				'error' => 0,
				'message' => 'Find the bank template',
				'result' => [
					'id' => $sId,
					'pages' => json_encode($aFiles),
					'thumbs' => $aThumbs,
					'job' => 'bank-template',
				],
			];
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
	response_json($oMeta);
}



job($oRouteVars, $oV);


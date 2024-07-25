<?php
// Find the number of pages in the pdf
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sWork = $oV['sDirectoryWork'] . $sId;
	$sMetaFile = $sWork . '/meta.json';
	if (file_exists($sMetaFile)){
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);

		$iPdfNumber = 0;
		$aFiles= [];
		$aDim = [];
		// Loop through all the uploaded PDFs
		while (file_exists($oV['sDirectoryWork'] . $sId . '/' . $iPdfNumber . '.pdf')){
			// Find the number of pages in the PDF
			$image = new Imagick();
			$image->pingImage($oV['sDirectoryWork'] . $sId . '/' . $iPdfNumber . '.pdf');
			$iNumPages = $image->getNumberImages();
			$image->clear(); 
			$image->destroy();	
			$aFiles[] = $iNumPages;
			$iPdfNumber++;
		}
		$oMeta['result']['pages'] = json_encode($aFiles);
		$oMeta['result']['job'] ='pdf-to-image';
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	} else {
		$oMeta = [
			'error' => 10,
			'message' => 'Could not find the record',
		];	
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);



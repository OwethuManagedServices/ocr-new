<?php
// Start off the job
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sWork = $oV['sDirectoryWork'] . $sId;
	if (file_exists($sWork . '/0.pdf')){
		$oMeta = [
			'error' => 0,
			'message' => 'Find total pages',
			'result' => [
				'id' => $sId,
				'pid' => 0,
				'job' => 'number-pages'
			],
		];
	} else {
		$oMeta = [
			'error' => 1,
			'message' => 'File not found',
			'result' => [
				'directory' => $oV['sDirectoryUpload']
			],
		];
	}
	file_put_contents($sWork . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
	response_json($oMeta);
}



job($oRouteVars, $oV);




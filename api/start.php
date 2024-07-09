<?php

require('functions.php');
$sId = $oRouteVars['id'];
$sWork = $oV['sDirectoryWork'] . $sId;
if (file_exists($sWork . '/out-page-1.hocr')){
	$oMeta = json_decode(file_get_contents($sWork . '/meta.json'), 1);
	unset($oMeta['result']['job']);
} else {
	if (file_exists($sWork . '/0.pdf')){
		$oMeta = [
			'error' => 0,
			'message' => 'Separate PDF pages',
			'result' => [
				'id' => $sId,
                'pid' => 0,
				'job' => 'pdf-to-image'
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
}
response_json($oMeta);




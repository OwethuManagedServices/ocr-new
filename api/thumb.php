<?php

// Return the thumbnail
require('functions.php');


 //$oRouteVars['id'] = '1720447509820712';


$sId = $oRouteVars['id'];
$iPdf = $oRouteVars['pdf'];
$iPage = $oRouteVars['page'];

$oMeta = [
	'error' => 14,
	'message' => 'Could not find the image',
];
$sThumb = $oV['sDirectoryWork'] . $sId . '/thumb-' . $iPdf . '-' . $iPage . '.jpg';
if (file_exists($sThumb)){
	$oMeta = [
		'error' => 0,
		'msg' => '',
		'result' => [
			'b64' => base64_encode(file_get_contents($sThumb)),
		],
	];
}
response_json($oMeta);
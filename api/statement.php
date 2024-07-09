<?php
// Return the statement information
require('functions.php');

$sId = $oRouteVars['id'];
$oMeta = [];
$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
if (file_exists($sMetaFile)){
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
}
response_json($oMeta);



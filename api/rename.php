<?php
// Rename the folder and change the ID
require('functions.php');

/*
				$oRet = [
					'error' => 20,
					'message' => 'Record not found'
				];
				$sId = $oData['id'];
				$sValue = $oData['value'];
				$sFile = getcwd() . '/../api/work/' . $sId . '/meta.json';
				if (file_exists($sFile)){
					$oMeta = json_decode(file_get_contents($sFile), 1);
					$oMeta['result']["id"] = $sValue;
					file_put_contents($sFile, json_encode($oMeta, JSON_PRETTY_PRINT));
					$sFolder = getcwd() . '/../api/work/';
					$oRet = [
						'error' => 0,
						'message' => '',
						'result' => [
							'id' => $sValue,
						],
					];
					rename($sFolder . $sId, $sFolder . $sValue);
				}

*/

function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sValue = $oRouteVars['value'];
	$oMeta = [
		'error' => 10,
		'message' => 'Could not find the record',
	];
    $sWork = $oV['sDirectoryWork'] . $sId;
	$sMetaFile = $sWork . '/meta.json';
error_log($sMetaFile);
	if (file_exists($sMetaFile)){
        error_log(123);
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		if ((isset($oMeta)) && (isset($oMeta['result']))){
            $oMeta['result']["id"] = $sValue;
            $oMeta['result']["job"] = 'rename';
            
            file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
            $sFolder = getcwd() . '/../api/work/';
            rename($sFolder . $sId, $sFolder . $sValue);
		}
	}
    error_log(json_encode($_SERVER['REQUEST_METHOD']));
	response_json($oMeta);
}



job($oRouteVars, $oV);

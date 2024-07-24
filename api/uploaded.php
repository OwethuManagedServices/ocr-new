<?php
// A file has been uploaded, prepare the environment
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

			break;

			case 'pageload':
				$sId = $oData['id'];
				$sFile = getcwd() . '/../api/work/' . $sId . '/' . $oData['pdf'] . '-' . ($oData['page'] + 1) . '.jpg';
				if (file_exists($sFile)){
					$oRet = [
						'data' => $oData,
						'url' => url('/statement-page/' .$sId . '/' . $oData['pdf'] . '/' . $oData['page']),
					];
				}

			break;

			case 'transaction_edit':
				$oData['bUseAuth'] = 0;
				$oResponse = $this->curlpost(env('OCR_API_URL') . 'row-edit/', $oData);
				$oRet = [
					'data' => $oData,
					'edit' => env('OCR_API_URL'),
					'resp' => $oResponse
				];
			break;

			case 'statement-load':
				$sId = $oData['id'];
				$oMeta = [];
				$sWork = getcwd() . '/../api/work/' . $sId;
				if (is_dir($sWork)){
					if (file_exists($sWork . '/meta.json')){
						$oMeta = json_decode(file_get_contents($sWork . '/meta.json'), 1);
					} 
				}
				$oRet = $oMeta;
			break;

			case 'upload-done':
				$sCat = explode('&cat=', $oData['getp'])[1];
				$sCat = explode('&', $sCat)[0];
				$iStep = explode('&step=', $oData['getp'])[1];
				$iStep = intval(explode('&', $iStep)[0]);
				$oData['step'] = $iStep;
				$sDir = public_path() . '/upload/'. $oData['folder']. '/';
				$sFN = $oData['filename'] . '.' . $oData['ext'];
				$sFN1 = base64_decode($oData['orig']);
				$oData['fn1'] =  $sFN1;
				switch ($sCat){
					case 'statement':
						$oData['fullpath'] =  base_path() . '/resources/docs/statements/' . $sFN1;
						$iFileNumber = $oData['number'];
						$sDir2 = public_path() . '/../api/work/';
						if (!$iFileNumber){
							$sFN2 = intval(floatval(microtime(1)) * 1000000);
							mkdir($sDir2 . $sFN2);
						} else {
							$sFN2 = $oData['id'];
						}
						rename($sDir . $sFN, $sDir2 . $sFN2 . '/' . $iFileNumber . '.pdf');
						$oMeta = [
							'id' => $sFN2,
							'b64_original_filename' => $oData['orig'],
						];
						file_put_contents($sDir2 . $sFN2 . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
						$oRet['errorcode'] = 0;
						$oRet['message'] = 'Success';
						$oRet['step'] = 1;
						$oRet['data'] = $oData;
						$oRet['b64_original_filename'] = $oData['orig'];
						$oRet['folder'] = $sFN2;
//						$oData = app('App\Http\Controllers\LeadsController')
//							->importspreadsheet($oData);
//						$oRet['records'] = $oData['records'];	
					break;

				}
*/
function job($oRouteVars, $oV){
	error_log(json_encode($oRouteVars));
	$sId = $oRouteVars['id'];
	$oMeta = [
		'error' => 10,
		'message' => 'Could not find the record',
	];
    $sWork = $oV['sDirectoryWork'] . $sId . '/';
	$sMetaFile = $sWork . '/meta.json';
	if (file_exists($sMetaFile)){
        copy ($sWork . '../../data/templates/display.json', $sWork . 'display.json');
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		if ((isset($oMeta)) && (isset($oMeta['result'])) && (isset($oMeta['result']['grid']))){
			$oMeta['result']['display'] = json_decode(file_get_contents($sWork . 'display.json'));
			file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
		}
	}
	response_json($oMeta);
}



job($oRouteVars, $oV);



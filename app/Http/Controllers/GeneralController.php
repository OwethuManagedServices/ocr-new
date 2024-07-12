<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use File;
use Response;

use function Laravel\Prompts\error;

class GeneralController extends Controller
{
	/*
	Handle AJAX requests, input and output base64 and json encoded
*/
public function ajax(Request $oRequest){
	$oRet = [
		'errorcode' => 1,
		'message' => 'Ajax error',
		'req' => $oRequest->action,
		'records' => [],
	];
	// Reject everything not using this specific action param
	if ((isset($oRequest->action)) && ($oRequest->action == 'acc-ajax')){
		$oData = json_decode(base64_decode($oRequest->data), 1);
		$oRet = [];
		// Only respond on 'job' requests
		switch ($oData['job']){

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
			break;

		}

	}

	return base64_encode(json_encode($oRet));
}



public function statement_page(Request $oRequest){
	$sFile = getcwd() . '/../api/work/' . $oRequest->id . '/' . $oRequest->pdf . '-' . ($oRequest->page + 1) . '.jpg';
error_log('xxxxxx '.$sFile);
	if(!file_exists($sFile)) {
		return response()->json(['message' => 'Image not found.'], 404);
	}

	$file = File::get($sFile);
	$type = File::mimeType($sFile);

	$response = Response::make($file, 200);
	$response->header("Content-Type", $type);

	return $response;

}



public function curlpost($sUrl, $oV){
	try {
		$oCh = curl_init();
		// Check if initialization had gone wrong
		if ($oCh === false){
			throw new Exception('failed to initialize');
            return '';
		}
		curl_setopt($oCh, CURLOPT_URL, $sUrl);
		curl_setopt($oCh, CURLOPT_POST, 1);
		curl_setopt($oCh, CURLOPT_POSTFIELDS, $oV);
		curl_setopt($oCh, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCh, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($oCh, CURLOPT_FOLLOWLOCATION, 1);
		if ($oV['bUseAuth']){
			curl_setopt($oCh, CURLOPT_HTTPHEADER, [
				'Authorization: X-API-KEY ' . $_SESSION['X-API-KEY'],
			]);
		}
		$sContent = curl_exec($oCh);
		// Check the return value of curl_exec(), too
		if ($sContent === false){
			throw new Exception(curl_error($oCh), curl_errno($oCh));
		}
	} catch(Exception $oError) {
		trigger_error(sprintf(
			'Curl failed with error #%d: %s',
			$oError->getCode(), $oError->getMessage()),
			E_USER_ERROR);
	} finally {
		curl_close($oCh);
	}
	return $sContent;	
}



public function avatar(Request $oRequest){
	$sInitials = str_replace(' ', '', $oRequest->name);
	if (strlen($sInitials) >= 3){
		$sInitials = substr($sInitials, 0, 3);
		$iX = 2.6;
		$iY = 11.1;
		$iF = 8;
	} else {
		$iX = 4.0;
		$iY = 11.2;
		$iF = 9;
	}
	$sColor = $oRequest->color;
	$sBackground = $oRequest->background;
	$sSrc = '<?xml version="1.0" encoding="UTF-8" ?>
	<svg width="64" height="64" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
		<g>
		<circle
			style="fill:#' . $sBackground . 
			';stroke:#' . $sColor . ';stroke-width:1;stroke-dasharray:none"
			cx="8.5" cy="8.5" r="8.5" />
		<text
			style="font-family:monospace;font-weight:900;font-size:' . $iF . 'px;fill:#' . $sColor . ';"
			x="' . $iX . '" y="' . $iY . '">' . $sInitials . '</text>
		</g>
	</svg>';
	return 'data:image/svg+xml;base64,' . base64_encode($sSrc);
}
	
	

public function home(){
	return view('home.home');
}
	


}

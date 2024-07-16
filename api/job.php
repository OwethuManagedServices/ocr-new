<?php
// Run the API calls in sequence
require('functions.php');



function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];

	// Start
	$sUrl = $oV['sApiUrl'] . 'start/'  . $sId;
	$oResponse = curlget($sUrl, $oV);
	$oResponse = json_decode($oResponse, 1);

	if (isset($oResponse['result']['job'])){

		// PDF to IMAGE
		if ((isset($oResponse)) && (isset($oResponse['error'])) && (!$oResponse['error'])){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);    
		}

		// Bank Template
		if ((isset($oResponse)) && (isset($oResponse['error'])) && (!$oResponse['error'])){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'] . '/' . $oResponse['result']['pages'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);    
		}

		// Header Information
		if ((isset($oResponse)) && (isset($oResponse['error'])) && (!$oResponse['error'])){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'] . '/' . $oResponse['result']['bank'] . '/' . $oResponse['result']['pages'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);    
		}

		// Pages to Columns
		if ((isset($oResponse)) && (isset($oResponse['error'])) && (!$oResponse['error'])){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'] . '/' . $oResponse['result']['bank'] . '/' . $oResponse['result']['pages'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}

		// OCR to Data
		if ((isset($oResponse['result'])) && (isset($oResponse['result']['job']))){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'] . '/' . $oResponse['result']['bank'] . '/' . $oResponse['result']['pages'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}

		// Recon
		if ((isset($oResponse['result'])) && (isset($oResponse['result']['job']))){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}

		// Opening/Closing Balances
		if ((isset($oResponse['result'])) && (isset($oResponse['result']['job']))){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}

		// Detect Salary
		if ((isset($oResponse['result'])) && (isset($oResponse['result']['job']))){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}
		
		// Info to display the result
		if ((isset($oResponse['result'])) && (isset($oResponse['result']['job']))){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);
		}
		
	}
	response_json($oResponse);
}



function curlget($sUrl, $oV){
	try {
		$oCh = curl_init();
		// Check if initialization had gone wrong
		if ($oCh === false){
			throw new Exception('failed to initialize');
            return '';
		}
		curl_setopt($oCh, CURLOPT_URL, $sUrl);
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



job($oRouteVars, $oV);


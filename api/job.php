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

		// NUMBER OF PAGES
		if ((isset($oResponse)) && (isset($oResponse['error'])) && (!$oResponse['error'])){
			$sUrl = $oV['sApiUrl'] . $oResponse['result']['job'] . '/' . $oResponse['result']['id'];
			$oResponse = curlget($sUrl, $oV);
			$oResponse = json_decode($oResponse, 1);    
		}

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



job($oRouteVars, $oV);



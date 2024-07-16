<?php
// The API hits this page, which will route the request
include(getcwd() . '/config.php');


class Route{

	private function routeplain($sFile, $sRoute){
		if(!empty($_REQUEST['uri'])){
			$sRoute = preg_replace("/(^\/)|(\/$)/", "", $sRoute);
			$sReqUri = preg_replace("/(^\/)|(\/$)/", "", $_REQUEST['uri']);
		}else{
			$sReqUri = "/";
		}
		if($sReqUri == $sRoute){
			$oRouteVars = [];
			include ($sFile);
			exit();
		}
	}
	
	
	
	function add($sRoute, $sFile){
		$oRouteVars = [];
		$aParamKey = [];
		preg_match_all("/(?<={).+?(?=})/", $sRoute, $aParamMatches);
		if (empty($aParamMatches[0])){
			$this->routeplain($sFile, $sRoute);
			return;
		}
		foreach($aParamMatches[0] as $sKey){
			$aParamKey[] = $sKey;
		}
	
		if (!empty($_REQUEST['uri'])){
			$sRoute = preg_replace("/(^\/)|(\/$)/", "", $sRoute);
			$sReqUri =  preg_replace("/(^\/)|(\/$)/", "", $_REQUEST['uri']);
		} else {
			$sReqUri = "/";
		}
		$aUri = explode("/", $sRoute);
		$aIndexNum = []; 
		foreach ($aUri as $iIndex => $sParam){
			if (preg_match("/{.*}/", $sParam)){
				$aIndexNum[] = $iIndex;
			}
		}
		$sReqUri = explode("/", $sReqUri);
		foreach ($aIndexNum as $sKey => $iIndex){
			if (!isset($sReqUri[$iIndex])){
				return;
			}
			$oRouteVars[$aParamKey[$sKey]] = $sReqUri[$iIndex];
			$sReqUri[$iIndex] = "{.*}";
		}
		$sReqUri = implode("/",$sReqUri);
		$sReqUri = str_replace("/", '\\/', $sReqUri);
		if (preg_match("/$sReqUri/", $sRoute)){
			include($sFile);
			exit();
		}
	}
	
	
	
	function notfound($sFile){
		http_response_code(404);
		include($sFile);
		exit();
	}
	
	
	// Send the Authorization header with X-API-KEY
	function authheader($bUseAuth = 0){
		if (!$bUseAuth){
			return 1;
		}
		$bRet = 0;
		$oHdr = apache_request_headers();
		if (isset($oHdr['authorization'])){
			$aHdr = explode(' ', $oHdr['authorization']);
		}
		if (isset($oHdr['Authorization'])){
			$aHdr = explode(' ', $oHdr['Authorization']);
		}
		if (isset($aHdr)){
			if ($aHdr[0] == 'X-API-KEY'){
				$aApiKeys = json_decode(file_get_contents(getcwd() . '/data/api-keys.json'), 1);
				$sFound = '';
				foreach ($aApiKeys as $oKey){
					if ($oKey['api_key'] == $aHdr[1]){
						$sFound = $aHdr[1];
					}
				}
				if ($sFound){
					$bRet = 1;
					$_SESSION['X-API-KEY'] = $aHdr[1];
				}	
			}
		}
		return $bRet;
	}



	}
	


	$oRoute = new Route();
	if ($oRoute->authheader($oV['bUseAuth'])){

		// Define the routes
		
		// Job
		$oRoute->add('v1/job/{id}',			 					'job.php');
		$oRoute->add('v1/cancel/{id}', 							'cancel.php');
		$oRoute->add('v1/progress/{id}', 						'progress.php');
		$oRoute->add('v1/start/{id}', 							'start.php');
		$oRoute->add('v1/pdf-to-image/{id}', 					'pdftoimage.php');
		$oRoute->add('v1/bank-template/{id}/{pages}', 			'banktemplate.php');
		$oRoute->add('v1/header/{id}/{bank}/{pages}',		 	'header.php');
		$oRoute->add('v1/pages-columns/{id}/{bank}/{pages}', 	'pagescolumns.php');
		$oRoute->add('v1/ocr-to-data/{id}/{bank}/{pages}', 		'ocrtodata.php');
		$oRoute->add('v1/recon/{id}',					 		'recon.php');
		$oRoute->add('v1/balances/{id}',						'balances.php');
		$oRoute->add('v1/salary/{id}',							'salary.php');
		$oRoute->add('v1/display/{id}',							'display.php');
		// Queries
		$oRoute->add('v1/thumb/{id}/{pdf}/{page}',				'thumb.php');
		$oRoute->add('v1/statement/{id}', 						'statement.php');
		$oRoute->add('v1/statement/header-field/{field}/{id}', 	'statement-header-field.php');
		$oRoute->add('v1/statement/grid-row/{page_row}/{id}', 	'statement-grid-row.php');
		$oRoute->add('v1/statement/columns/{page_row}/{id}', 	'statement-page-row-columns.php');
		// Update
		$oRoute->add('v1/row-edit/{id}/{data}',			 		'row-edit.php');

		$oRoute->notfound('404.php');

	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode([
			'error' => 1,
			'message' => 'Invalid authorization header',
		], JSON_PRETTY_PRINT);
	}
	
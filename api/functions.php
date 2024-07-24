<?php
$sDir = getcwd();
include($sDir . '/libs/simple_html_dom/simple_html_dom.php');
include($sDir . '/config.php');


// Launch and keep track of processes
class Process{
	private $pid;
	private $command;

	public function __construct($cl = false){
		if ($cl != false) {
			$this->command = $cl;
			$this->runCom();
		}
	}
	private function runCom(){
		$command = 'nohup ' . $this->command . ' > /dev/null 2>&1 & echo $!';
		exec($command, $op);
		$this->pid = (int)$op[0];
	}

	public function setPid($pid){
		$this->pid = $pid;
	}

	public function getPid(){
		return $this->pid;
	}

	public function status(){
		$command = 'ps -p ' . $this->pid;
		exec($command, $op);
		if (!isset($op[1])) return false;
		else return true;
	}

	public function start(){
		if ($this->command != '') $this->runCom();
		else return true;
	}

	public function stop(){
		$command = 'kill ' . $this->pid;
		exec($command);
		if ($this->status() == false) return true;
		else return false;
	}
}



function all_columns($oTemplate){
	$aC = [];
	$aD = $oTemplate['columns'];
	$aE = $oTemplate['grid_columns'];
	for ($iI = 0; $iI < sizeof($aD); $iI++){
		foreach ($aE as $oGC){
			if ($oGC['column_number'] == $iI){
				$aC[] = $oGC;
			}
		}
		$aC[] =$aD[$iI];
	}
	return $aC;
}



function amount($sValue){
	$sValue .= "";
	if ($sValue == "0") {
		return [0, '', true];
	}
	$sD = $sValue;
	$iL = strlen($sValue);
	$iI = strpos($sValue, '.');
	if ($iI == -1) {
		$sD = number_format(floatval($sValue), 2, '.', '');
	} else {
		if ($iL - $iI != 3) {
			number_format(floatval($sValue) / 100, 2, '.', '');
		}
	}
	return [floatval($sD), $sValue, ($sValue == $sD)];
}



function amount_or_empty($iAmount){
	if (!$iAmount){
		$iAmount = 0;
	}
	$sAmount = number_format($iAmount, 2, '.', '');
	if ($sAmount == '0.00'){
		$sAmount = '';
	}
	return $sAmount;
}



function balances_monthly($oMeta){
	$aGrid = $oMeta['result']['grid'];
	$oTemplateBalance = $oMeta['result']['template']['statement_from_to_dates'];
	$oHeader = $oMeta['result']['header'];
	/*
	// Template has From and To fields
	$sDateFrom = $oTemplateBalance['header_field_from'];
	if ($sDateFrom){
		$sDateFrom = $oHeader[$oTemplateBalance['header_field_from']];
		if (isset($oTemplateBalance['word_from_split'])){
			$sDateFrom = explode($oTemplateBalance['word_from_split'], $sDateFrom)[0];
			if (isset($oTemplateBalance['word_from_start'])){
				$sDateFrom = str_replace($oTemplateBalance['word_from_start'], '', $sDateFrom);
			}
		}
		$sDateFrom = statement_date_format($sDateFrom, $oTemplateBalance);
	}
	// Template has one field for From/To
	$sDateFromTo = $oTemplateBalance['header_field_from_to'];
	if ($sDateFromTo){
		$sD = $oHeader[$sDateFromTo];
		$aD = explode($oTemplateBalance['word_split'], $sD);
		$aD[0] = str_replace($oTemplateBalance['word_start'], '', $aD[0]);
		$sDateFrom = statement_date_format(trim($aD[0]), $oTemplateBalance);
	}
	*/
	$iV = 1;
	$bFound = 0;
	while ((!$bFound) && ($iV < 10)){
		$aR = $aGrid[sizeof($aGrid) - $iV];
		if (is_valid_date($aR[1])){
			$bFound = 1;
		} else {
			$iV++;
		}
	}
	$aR = $aGrid[sizeof($aGrid) - $iV];
	$sDateTo = $aR[1];

	$iV = 0;
	$bFound = 0;
	while ((!$bFound) && ($iV < 10)){
		$aR = $aGrid[$iV];
		if (is_valid_date($aR[1])){
			$bFound = 1;
		} else {
			$iV++;
		}
	}
	$aR = $aGrid[$iV];
	$sDateFrom = $aR[1];

	// Use Unix time to determine number of days from start to end
	$iTimeFrom = strtotime($sDateFrom);
	$iTimeTo = strtotime($sDateTo);
	$iDaysPeriod = ($iTimeTo - $iTimeFrom) / 60 / 60 / 24;
	$sDayStart = substr($sDateFrom, 8, 2);
	$sMonthStart = substr($sDateFrom, 5, 2);
	// The grid row [0=row_num, 1=date, 2=descr, 3=amt_in, 4=amt_out, 5=balance]
	for ($iStart = 0; $iStart < sizeof($aGrid); $iStart++){
		$aR = $aGrid[$iStart];
		if ($aR[1] >= $sDateFrom){
			break;
		}
	}
	// Create array with header as first element and start balance 2nd
	$aBalances = [
		['Statement from', $sDateFrom, "to", $sDateTo . ', period ', $iDaysPeriod . ' days'], 
		[$aR[1], $aR[5], 0]
	];
	$iIndex = 1;
	for ($iI = $iStart; $iI < sizeof($aGrid); $iI++){
		$aROld = $aR;
		$aR = $aGrid[$iI];
		$sDayNow = substr($aR[1], 8, 2);
		$sMonthNow = substr($aR[1], 5, 2);
		// We have the same day and a new month, save the balance
		$iDiff = intval($sDayNow) - intval($sDayStart);
		if (($iDiff >= 0) && ($sMonthNow != $sMonthStart)){
			
			$aBalances[$iIndex][0] .= (' to ' . $aROld[1]);
			$aBalances[$iIndex][2] = $aROld[5];
			$aBalances[] = [$aR[1], $aR[5], 0];
			$sMonthStart = $sMonthNow;
			$iIndex++;
		}
	}
	// Add the last balance
	$aBalances[$iIndex][2] = $aBalances[$iIndex][1];
	$aBalances[$iIndex][1] = '';
	return [$aBalances, $sDateFrom, $sDateTo];
}



function columns_blank($aGridData, $oTemplate, $aRow, $bEnd){
	// Count the blank columns
	$iM = $oTemplate['grid']['blank_column_trigger'];
	$iB = 0;
	foreach( $aRow as $sC){
		if ($sC === ''){
			$iB++;
		}
	}
	if (!$bEnd){
		if ($iB > ($iM + 2)){
			// Too many blank columns, concatenate to previous row
			$iSz = sizeof($aGridData) - 1;
			for ($iB = 1; $iB < sizeof($aRow); $iB++){
				if ($aRow[$iB]){
					if ($iSz >= 0){
						$aGridData[$iSz][$iB] .= ' ' . $aRow[$iB];
					}
				}
			}
			if ($iSz >= 0){
				$aGridData[$iSz][9]++;
			} else {
				$aGridData[] = $aRow;	
			}		
		} else {
			$aGridData[] = $aRow;
		}
	}
	return $aGridData;
}



function column_for_debit_and_credit($sBank, $aR){
	switch ($sBank){
		case 'standard':
		case 'fnb':
		case 'absa':
			if (isset($aR[4][0])){
				if ($aR[4][0] != '-'){
					$aR[4] = '';
				}
				if ($aR[3][0] == '-'){
					$aR[3] = '';
				}
			}
		break;
	}
	return $aR;
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



function dates_year_fix($aGridData, $oHeader, $sBank){
	switch ($sBank){
		case 'fnb':
			$aFromTo = explode(' ', $oHeader['statement_period']);
			$sFrom = '';
			for ($iI = 0; $iI < sizeof($aFromTo); $iI++){
				if ((intval($aFromTo[$iI])) && (intval($aFromTo[$iI]) > 2000)){
					if (!$sFrom){
						$sFrom = $aFromTo[$iI];
					}
				}
			}
			$sYear = $sFrom;
			$aR = [0, '-00'];
			for ( $iI = 0; $iI < sizeof($aGridData); $iI++){
				$sMonthNow = explode('-', $aR[1])[1];
				$aR = $aGridData[$iI];
				$sYear = $sFrom;
				$aD = explode('-', $aR[1]);
				// Increase the year when month changes from 12 to 01
				if ((isset($aD[1])) && ($aD[1] == '01') && ($sMonthNow == '12')){
					$sFrom = (intval($sYear) + 1) . '';
					$sYear = $sFrom;
				}
				$aGridData[$iI][1] = str_replace('0000', $sYear, $aGridData[$iI][1]);
			}
		break;
	}
	return $aGridData;
}



function field_value_format($iPage, $oTemplate, $oT, $iI, $iJ, $sE, $sV){
	$aGridError = [];
	if (!isset($oT['field_type'])){
		return $sV;
	}
	switch ($oT['field_type']){

		case 'amount':
			// OCR mistakes 5 for S
			$sW = str_replace('S', '5', $sV);
			// OCR mistakes 7 for /
			$sW = str_replace('/', '7', $sW);
			if ($sV != $sW){
				$sE = "amount_invalid_characters";
				$aGridError = [
					$iPage . '_' . ($iI - 0),
					$iPage,
					$iI,
					$iJ + 1,
					$sE,
					$sV,
				];
				$sV = $sW;
			}
			$aV = explode(',', '0,1,2,3,4,5,6,7,8,9,.,-');
			if (isset($oTemplate['grid']['has_cr_for_credit'])){
				if (substr($sV, strlen($sV) -2, 1) != 'C'){
					$sV = '-' . $sV;
				}
			}
			$sW = '';
			for ($iM = 0; $iM < strlen($sV); $iM++){
				if (in_array($sV[$iM], $aV)){
					$sW .= $sV[$iM];
				}
			}
			// Place '-' in front
			if (substr($sW, strlen($sW) - 1, 1) == '-'){
				$sW = '-' . substr($sW, 0, strlen($sW) - 1);
			}
			$sV = $sW;
			if ((isset($oTemplate['grid']['skip_page_1'])) && ($iPage == 1)){
				// nop
			} else {
				if ((strpos($sV, '.') == false) && (floatval($sV))){
					$sE = "amount_no_decimal";
					$aGridError = [
						$iPage . '_' . ($iI - 0),
						$iPage,
						$iI,
						$iJ + 1,
						$sE,
						$sV,
					];
					$sV = floatval($sV) / 100 . '';
				}			
				if (strpos($sV, '.') != strlen($sV) - 3){
					$sE = "amount_invalid_decimal";
					$aGridError = [
						$iPage . '_' . ($iI - 0),
						$iPage,
						$iI,
						$iJ + 1,
						$sE,
						$sV,
					];
					$sV = floatval($sV);
				}
			}
		break;

		case 'date':
			$sX = $oT['field_format'];
			if (strpos($sX, 'MMM')){
				$aM = explode(', ', ', Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec');
				// StdBank 2
				if (strpos($sX, 'yy')){
					$iM = strpos($sX, 'MMM');
					$sM = substr($sV, $iM, 3);
					$sY = '20' . substr($sV, $iM + 4, 2);
					$iM = array_search($sM, ($aM));
					if ($iM < 10){
						$sM1 = '0' . $iM;
					} else {
						$sM1 = $iM;
					}
					$sV = $sY . '-' . $sM1 . '-' . substr($sV, 0, 2);
					$sX = 'yyyy-mm-dd';
				} else {				
					// FNB without year
					$sX = substr(strtolower($sX), 0, strlen($sX) - 1);
					$sW = str_replace(']J', 'J', $sV);
					$sW = str_replace(']', 'J', $sW);
					$sW = str_replace(' ', '', $sW);
					$sW = substr($sW, 0, 2) . '-' . substr($sW, 2, 3);
					$sW .= '-0000';
					$sX .= ' yyyy';
					$iM = strpos($sX, 'mm');
					$sM = substr($sW, $iM, 3);
					$iM = array_search($sM, ($aM));
					if ($iM < 10){
						$sM1 = '0' . $iM;
					} else {
						$sM1 = $iM;
					}
					$sV = str_replace($sM, $sM1, $sW);
				}
			}
			if (strpos($sX, ' ') === false){
				$sV = str_replace(' ', '', $sV);
			}
			if ((strpos($sX, '/') == 2) && (strpos($sV, '/') == 1)){
				$sV = '0' . $sV;
			}
			$iM = strpos($sX, 'y');
			$sW = substr($sV, $iM, $iM + 4) . '-';
			$iM = strpos($sX, 'm');
			$sW .= substr($sV, $iM, 2) . '-';
			$iM = strpos($sX, 'd');
			$sW .= substr($sV, $iM, 2);
			if ($sW == '--'){
				$sW = '';
				$sE = 'date_empty';
				$aGridError = [
					$iPage . '_' . ($iI - 1),
					$iPage,
					$iI,
					$iJ + 1,
					$sE,
					$sV,
				];
			}
			$sV = $sW;
		break;

		case 'text':
			$sW = preg_replace('/[^\x00-\x7F]/', '', $sV);
			if (($sW != $sV) && ($sV) && (substr($sV, 0, 9) != 'USSD Cash')){
				$sE = 'invalid_characters';
				$aGridError = [
					$iPage . '_' . ($iI - 1),
					$iPage,
					$iI,
					$iJ + 1,
					$sE,
					$sV,
				];
				$sV = $sW;
			}			
		break;
	}
	return [$sV, $aGridError];
}	


// Add a column for in/out amounts 
function grid_columns_per_bank($sBank, $aGrid){
	switch ($sBank){
		case 'absa':
		case 'fnb':
		case 'standard':
			$aGrid = [
				$aGrid[0],
				$aGrid[1],
				$aGrid[2],
				$aGrid[2],
				$aGrid[3],
			];
		break;
	}
	return $aGrid;
}



function header_info($sHtml, $oTemplate){
	$oHeader = [];
	// Loop through the template header fields
	foreach ($oTemplate['header'] as $oT){
		// Get all the words at the field position
		$aWords = words_at_position($sHtml, $oT['position']);
		// Concatenate with ' '
		$sText = '';
		foreach ($aWords as $oW){
			$sText .= $oW['text'] . ' ';
		}
		// Remove unwanted words
		if (isset($oT['replace'])){
			$sText = str_replace($oT['replace'], '', $sText);
		}
		$oHeader[$oT['name']] = trim($sText);
	}
	// Add the bank name to the header info
	$oHeader['bank_name'] = $oTemplate['name'];
	return $oHeader;
}



function is_valid_date($sDate, $sFormat = 'Y-m-d'){
	$oDate = DateTime::createFromFormat($sFormat, $sDate);
	return $oDate && $oDate->format($sFormat) == $sDate;
}



function metamessage($sMessage, $sId, $oV){
	$sMetaFile = $oV['sDirectoryWork'] . $sId . '/meta.json';
	if (file_exists($sMetaFile)) {
		$oMeta = json_decode(file_get_contents($sMetaFile), 1);
		$oMeta['message'] = $sMessage;
		file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	}
	return $sMetaFile;
}



function ocr_to_data_columns($oTemplate, $sWork, $iPg, $iPage){
	$sHocr = $sWork . '/out-col-' . $iPg . '-' . $iPage . '-';
	$aGrid = [];
	$oData = [];
	$iYWordGridStart = 0;
	for ($iColumn = 0; $iColumn < count($oTemplate['columns']); $iColumn++){
		$oC = $oTemplate['columns'][$iColumn];
		$sHocrColumnFilename = $sHocr . $iColumn . '.hocr';
		$oData['result'] = [
			'template' => $oTemplate,
			'file' => $sHocrColumnFilename,
		];
		$sHtml = str_get_html(file_get_contents($sHocrColumnFilename));
		$aRows = [];
		$iLastY = 0;
		$aP = [0, 0];
		foreach ($sHtml->find('span') as $oElement){
			$aCP = $oC['position_x'];
			if (($oElement->class === 'ocr_line') || ($oElement->class === 'ocr_header') || ($oElement->class === 'ocr_textfloat')){
				$aWP = explode(' ', $oElement->title);
				$aPOld = $aP;
				$aP = [0, intval($aWP[2] - 5), ($aCP[1] - $aCP[0] + 2), intval(str_replace(';', '', $aWP)[4] + 5)];
				if (($aP[1] + 5) < $iLastY){
					break;
				}
				// Tesseract may give the bottom values first, stop that
				if (abs($aP[1] - $iLastY) < 200){
					$iLastY = $aP[1];
				}
				$iHY = $aP[1] - $aPOld[1];
				if (($iHY < 0) && (sizeof($aRows))){
					break;
				}
				if (!$iColumn){
					$aText = words_at_position($sHtml, $aP);
					$aRows[] = [$aP[1], $aText];
				}
				if (($iColumn) && ($aP[1] > $iYWordGridStart)){
					$aText = words_at_position($sHtml, $aP);
					$aRows[] = [$aP[1], $aText];
				}
				if (isset($oTemplate['grid']['end_of_document_trigger_words'])){
					$oTW = $oTemplate['grid']['end_of_document_trigger_words'];
					if ($iColumn == $oTW['column']){
						$sText = '';
						foreach ($aText as $oT){
							$sText .= $oT['text'] . ' ';
						}
						if (strpos($sText, $oTW['words']) !== false){
							break;
						}
					}
				}

				if ((isset($oTemplate['grid']['page_y_word_grid_start']))){
					if ((!$iColumn) && (isset($aText[0])) && ($aText[0]['text'] == $oTemplate['grid']['page_y_word_grid_start'])){
						$aRows = [];
						$iYWordGridStart = $aP[3] + $oTemplate['grid']['page_y_grid_start_space'];
					}
				}
			}
		}
		$aGrid[] = $aRows;
	}
	return [$aGrid, $iYWordGridStart];
}



function pages_columnns($oTemplate, $sWork, $iPg, $iPage, $sImg, $oV, $sMetaFile){
	$iCol = 0;
	$iDpi = $oV['iPageDpi'];
	$sCmd = '';
	$sCmd1 = '';
	$sExecFile = file_get_contents($sWork . '/ocrjob');
	$sExecFile1 = file_get_contents($sWork . '/ocrjob1');
	foreach ($oTemplate['columns'] as $oCol){
		$iX = $oCol['position_x'][0];
		$iW = $oCol['position_x'][1] - $oCol['position_x'][0];
		if ((isset($oTemplate['grid']['skip_page_1'])) && ($oTemplate['grid']['skip_page_1'])){
			$iStart = 2;
		} else {
			$iStart = 1;
		}
		// Crop the page according to page number
		if ($iPage == $iStart){
			$iY = $oTemplate['grid']['page_1_start_y'];
			$iH = $oTemplate['grid']['page_1_end_y'] - $iY;
		// After first page
		} else {
			$iY = $oTemplate['grid']['start_y'];
			$iH = $oTemplate['grid']['end_y'] - $iY;
		}
		// Sometimes the first page has different column coordinates
		if (isset($oCol['position_x'][2]) && ($iPage > 1)){
			$iX = $oCol['position_x'][2];
			$iW = $oCol['position_x'][3] - $oCol['position_x'][2];
		}
		$iX = intval($iX / ($oTemplate['dpi'] / $iDpi));
		$iY = intval($iY / ($oTemplate['dpi'] / $iDpi));
		$iW = intval($iW / ($oTemplate['dpi'] / $iDpi));
		$iH = intval($iH / ($oTemplate['dpi'] / $iDpi));
		// Use imagemagick to crop the column
		$sIm = $oV['sDirectoryBin'] . 'convert -crop ' . $iW . 'x' . $iH . '+' . $iX . '+' . $iY . ' ' . $sImg . ' ' . $sWork . '/col-' . $iPg . '-' . $iPage . '-' . $iCol . '.jpg';
		shell_exec($sIm);

		$sImgCol = $sWork . '/col-' . $iPg . '-' . $iPage . '-' . $iCol . '.jpg';
		$sCmd = '' . $oV['sDirectoryBin'] . 'tesseract ' . $sImgCol . ' ' . $sWork . '/out-col-' . $iPg . '-' . $iPage . '-' . $iCol . ' hocr';
		$sCmd1 .= ' "' . $oV['sDirectoryBin'] . 'tesseract ' . $sImgCol . ' ' . $sWork . '/out-col-' . $iPg . '-' . $iPage . '-' . $iCol . ' hocr"';
		$sExecFile .= $sCmd . "\n";
		file_put_contents($sWork . '/ocrjob', $sExecFile);
		
		$iCol++;
	}
	// Finalise the shell scripts
	$sCmd1 = $oV['sDirectoryBin'] . 'parallel -X :::' . $sCmd1;
	$sCmd1 .= "\necho $$ > " . $sWork . '/pid' . "\n";
	$sExecFile1 .= $sCmd1 . "\ndate\n";
	file_put_contents($sWork . '/ocrjob1', $sExecFile1);
}



function response_json($oJson){
	if (!isset($oJson['pid'])) {
		$oJson['pid'] = 0;
	}
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(($oJson));
}



function recon_balance($oMeta){
	$aGrid = $oMeta['result']['grid'];
	$aAnomalies = $oMeta['result']['anomalies'];
	$oTemplate = $oMeta['result']['template'];
	$aEdit = $oMeta['result']['edit'];
	// In/Out/Bal, Ocr/Now/Prev
	$aA = [[0, 0, 0], [0, 0, 0],[0, 0, 0]];
	$aRecon = [[]];
	$aGridNew = [];

	$aA[2][2] = recon_column_amount($aGrid, $aEdit, 0, 5);
	$aR = $aGrid[0];
	$aGridNew[] = [$aR[0], $aR[1], $aR[2], $aR[3], $aR[4], $aR[5], '', '', ''];
	$iBalanceFixedRow = 0;
	for ($iI = 1; $iI < sizeof($aGrid); $iI++){
		$aA[0][0] = recon_column_amount($aGrid, $aEdit, $iI, 3);
		$aA[1][0] = recon_column_amount($aGrid, $aEdit, $iI, 4);
		$aA[2][0] = recon_column_amount($aGrid, $aEdit, $iI, 5);
		$aA[0][1] = $aA[0][0];
		$aA[1][1] = $aA[1][0];
		$aA[2][1] = $aA[2][0];
		$aR = $aGrid[$iI];
		$iOcrBal = floatval($aR[8]);
		if (isset($oTemplate['grid']['add_negative_to_debit'])){
			$aA[1][1] = floatval($aA[1][1]) * -1;
		}
		if (isset($oTemplate['grid']['has_cr_for_credit'])) {
			$aA[1][1] = floatval($aA[1][1]) * -1;
		}
		$aA[2][1] = $aA[2][2] + ($aA[0][1] + $aA[1][1]);
		$aARe = [0, 0, 0, 0];
		// Recon the row if the calculated balance is not equal to the OCR balance
		if ((abs($aA[2][0] - $aA[2][1]) > 0.05) || ($iOcrBal)){
			$aARe = recon_row_adjust($aGrid, $aEdit, $aAnomalies, $iI, $aA, $iBalanceFixedRow);
			$aRe1 = $aARe;
			$iBalanceFixedRow = $aARe[3];
			$aARe[0] = $aA[0][0];
			$aARe[1] = $aA[1][0];
			$aARe[2] = $aA[2][0];
			$aA[0][1] = $aRe1[0];
			$aA[1][1] = $aRe1[1];
			$aA[2][1] = $aRe1[2];
		}
		// Rebuild the grid row with the recon data
		$aR = $aGrid[$iI];
		$aR1 = [
			$aR[0], $aR[1], $aR[2], 
			amount_or_empty($aA[0][1]),
			amount_or_empty($aA[1][1]),
			amount_or_empty($aA[2][1]),
			amount_or_empty($aARe[0]),
			amount_or_empty($aARe[1]),
			amount_or_empty($aARe[2]),
		];
		if ($aARe[3]){
			$aRecon[] = $aR1;
		}
		$aGridNew[] = $aR1;
		// Update the Previous values
		$aA[0][2] = $aA[0][1];
		$aA[1][2] = $aA[1][1];
		$aA[2][2] = $aA[2][1]; 
	}
	return [$aRecon, $aGridNew];
}



function recon_row_adjust($aGrid, $aEdit, $aAnomalies, $iRow, $aA, $iBalanceFixedRow){
	$aR = $aGrid[$iRow];
	$iProblemCol = 0;
	$iIn = 0;
	$iOut = 0;
	$iBal = $aA[2][0];
	$aFound = 0;
	foreach ($aAnomalies as $aAn){
		if ($aR[0] == $aAn[0]){
			$aFound = $aAn;
			break;
		}
	}
	if ($aFound){
		$iProblemCol = $aFound[3];
		if ($iProblemCol == 3){
			$aA[0][1] = 0;
			$iIn = 0;
			$iOut = $aA[1][1];
		}
		if ($iProblemCol == 4){
			$aA[1][1] = 0;
			$iIn = $aA[0][1];
			$iOut = 0;
		}
		if ($iProblemCol == 5){
			$aA[2][1] = 0;
		}
	}
	$bFixed = 0;
	// Out column is not negative
	if ($aA[1][1] > 0){
		$aA[1][1] *= -1;
		if (abs($iBal - $aA[1][1] - $aA[2][2]) < 0.05){
			$iOut = $aA[1][1];
			$bFixed = 1;
		}
	}
	$iTr = $aA[2][0] - $aA[2][2];

	// Fix the most obvious first. Don't fix after fixed

	// In and out columns have values
	if (($aA[0][1]) && ($aA[1][1])){
		$iB2 = $aA[2][2] + $aA[0][1];
		$iB3 = $aA[2][2] + $aA[1][1];
		if (abs($iB2 - $aA[2][1]) < 0.05){
			$iIn = $iTr;
		}
		if (abs($iB3 - $aA[2][1]) > 0.05){
			$iOut = $iTr;
		}
		$bFixed = 1;
	}

	if (!$bFixed){
		// Both in and out are zero
		if ((!$aA[0][1]) && (!$aA[1][1])){
			if ($iTr >= 0){
				$iIn = $iTr;
			} else {
				$iOut = $iTr;
			}
			$bFixed = 1;
		}
	}

	if (!$bFixed){
		// OCR Missed a negative sign
		$iB1 = $aA[2][0] + $aA[0][1] + $aA[1][1];
		$aR = $aGrid[$iRow];
		if (abs($aA[2][2] - $iB1) < 0.05){
			$iIn = floatval(number_format($aA[1][1], 2, '.', '')) * -1;
			$iOut = floatval(number_format($aA[0][1], 2, '.', '')) * -1;
			$iBal = floatval(number_format($iBal, 2, '.', ''));
			$bFixed = 1;
			$iBalanceFixedRow = $iRow;
		}
	}

	// Still not fixed, sigh and just fix
	if (!$bFixed){
		// The OCR Balance column has to be incorrect
		$iB1 = $aA[2][2] + $aA[0][1] + $aA[1][1];
		if (abs($iB1 - $aA[2][1]) < 0.05){
			$iIn = floatval(number_format($aA[0][1], 2, '.', ''));
			$iOut = floatval(number_format($aA[1][1], 2, '.', ''));
			$bFixed = 1;
			if ($iBalanceFixedRow == $iRow - 1){
				$iBal = floatval(number_format($aA[2][0], 2, '.', ''));
				$iBalanceFixedRow = 0;
			} else {
				$iBal = floatval(number_format($aA[2][1], 2, '.', ''));
				$iBalanceFixedRow = $iRow;
			}
		}
	}
	return [$iIn, $iOut, $iBal, $iBalanceFixedRow];
}



function recon_column_amount($aGrid, $aEdit, $iRow, $iCol){
	if (isset($aEdit[$iRow][$iCol])){
		$iAmount = $aEdit[$iRow][$iCol];
	} else {
		$iAmount = $aGrid[$iRow][$iCol];
	}
	$aAm = amount($iAmount);
	if (!$aAm[2]) {
		$bError = 1;
	}
	$iAmount = $aAm[0];
	return $iAmount;
}



function runcommand($sCmd, $sMetaFile){
	$oProcess = new Process($sCmd);
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	$oMeta['pid'] = $oProcess->getPid();
	file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
	while ($oProcess->status()) {
		usleep(1000);
	}
	$oMeta = json_decode(file_get_contents($sMetaFile), 1);
	$oMeta['pid'] = 0;
	file_put_contents($sMetaFile, json_encode($oMeta, JSON_PRETTY_PRINT));
}



function salary($oMeta){
	$aKeywords = ['salar', 'stipend', 'learnership', 'commission', 'wage', 'internship', 'remuneration'];
	$aGrid = $oMeta['result']['grid'];
	$aSalaries = [];
	$aIn = [];
	// Loop through each transaction
	for ($iI = 1; $iI < sizeof($aGrid); $iI++){
		$aR = $aGrid[$iI];
		if ($aR[3]){
			$bFound = 0;
			// Find the keyword in the description
			foreach ($aKeywords as $sK){
				if (strpos(strtolower($aR[2]), $sK) !== false){
					$bFound = 1;
					break;
				}
			}
			if ($bFound){
				// Write to Salaries grid
				$aSalaries[] = $aR;
			} else {
				// Write to income grid
				$aIn[] = $aR;
			}
		}
	}
	// Sort other income to largest first In amount
	usort($aIn, function ($a, $b) { 
		return ( floatval($a[3]) < floatval($b[3]) ? 1 : -1 ); 
	});
	return [$aSalaries, $aIn];
}



// Ensure consistent date format across the banks
function statement_date_format($sV, $oTemplateBalance){
	$sX = $oTemplateBalance['field_format'];
	$bMonthString = 0;
	if (strpos($sX, 'MMM')){
		$aM = explode(', ', ', Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec');
		$bMonthString = 1;
	}
	if (strpos($sX, 'MMMM')){
		$aM = explode(', ', ', January, February, March, April, May, June, July, Augustus, September, October, November, December');
		$bMonthString = 1;
	}
	if ($bMonthString){
		$aD = explode(' ', $sV);
		$sM = array_search($aD[1], $aM);
		if ($sM < 10){
			$sM = '0' . $sM;
		}
		$sM .= '';
		$sV = str_replace(' ' . $aD[1]. ' ', '-' . $sM . '-', $sV);
		$aV = explode('-', $sV);
		if (strlen($aV[0]) == 1){
			$sV = $aV[2] . '-' . $aV[1] . '-0' . $aV[0]; 
		} else {
			$sV = $aV[2] . '-' . $aV[1] . '-' . $aV[0]; 
		}
	}
	if (!$bMonthString){
		$iP = strpos($sX, 'yyyy');
		$sW = substr($sV, $iP, 4) . '-';
		$iP = strpos($sX, 'mm');
		$sW .= substr($sV, $iP, 2) . '-';
		$iP = strpos($sX, 'dd');
		$sW .= substr($sV, $iP, 2);
		$sV = $sW;
	}
	return $sV;
}



// Search all words in the position coordinates
function words_at_position($sHtml, $aPos){
	global $oV;
	$iDpi = $oV['iPageDpi'];
	$aP = [];
	foreach ($aPos as $iP) {
		$aP[] = intval($iP / (600 / $iDpi));
	}
	$aPos = $aP;
	$aWords = [];
	if ($sHtml){
		foreach ($sHtml->find('span') as $oElement) {
			// Cycle through all words
			if ($oElement->class === 'ocrx_word') {
				$oW = [
					'text' => $oElement->innertext,
					'id' => $oElement->id,
					'title'	=> $oElement->title,
				];
				$aWP = explode(' ', $oElement->title);
				$aWP1 = [];
				// Find the position coordinates
				foreach ($aWP as $sP) {
					$aWP1[] = intval($sP);
				}
				$oW['pos'] = $aWP1;
				// Test if word is inside provided position range
				if (($oW['pos'][2] >= $aPos[1]) && ($oW['pos'][2] <= $aPos[3])
					&& ($oW['pos'][1] >= $aPos[0]) && ($oW['pos'][1] <= $aPos[2])
				) {
					$aWords[] = $oW;
				}
			}
		}
	}
	return $aWords;
}



function words_by_class_id($sHtml, $sTagName, $sId = '', $sClass = ''){
	$sRet = '';
	if ($sHtml){
		foreach ($sHtml->find($sTagName) as $oElement) {
			if ($sId) {
				if ($oElement->id === $sId) {
					$sRet = $oElement->innertext;
					break;
				}
			}
			if ($sClass) {
				if ($oElement->class === $sClass) {
					$sRet = $oElement->innertext;
					break;
				}
			}
		}
	}
	return $sRet;
}

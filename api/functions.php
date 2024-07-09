<?php
$sDir = getcwd();
include($sDir . '/libs/simple_html_dom/simple_html_dom.php');
include($sDir . '/config.php');



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



function response_json($oJson){
	if (!isset($oJson['pid'])) {
		$oJson['pid'] = 0;
	}
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(($oJson));
}



function recon_balance1($oMeta){
	$aGrid = $oMeta['result']['grid'];
	$aAnomalies = $oMeta['result']['anomalies'];
	$oTemplate = $oMeta['result']['template'];
	$aEdit = $oMeta['result']['edit'];
	// In/Out/Bal, Ocr/Now/Prev
	$aA = [[0, 0, 0], [0, 0, 0],[0, 0, 0]];
	$aRecon = [[]];
	$aGridNew = [];
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

	$aA[2][2] = recon_column_amount($aGrid, $aEdit, 0, 6);
	$aR = $aGrid[0];
	$aGridNew[] = [$aR[0], $aR[1], $aR[2], $aR[3], $aR[4], $aR[5], $aR[6], '', '', ''];
	$iBalanceFixedRow = 0;
	for ($iI = 1; $iI < sizeof($aGrid); $iI++){
		$aA[0][0] = recon_column_amount($aGrid, $aEdit, $iI, 4);
		$aA[1][0] = recon_column_amount($aGrid, $aEdit, $iI, 5);
		$aA[2][0] = recon_column_amount($aGrid, $aEdit, $iI, 6);
		$aA[0][1] = $aA[0][0];
		$aA[1][1] = $aA[1][0];
		$aA[2][1] = $aA[2][0];
		$aR = $aGrid[$iI];
		$iOcrBal = floatval($aR[9]);
		if (isset($oTemplate['grid']['add_negative_to_debit'])) {
			$aA[1][1] = floatval($aA[1][1]) * -1;
		}
		if (isset($oTemplate['grid']['has_cr_for_credit'])) {
			$aA[1][1] = floatval($aA[1][1]) * -1;
		}
		$aA[2][1] = $aA[2][2] + ($aA[0][1] + $aA[1][1]);
		$aARe = [0, 0, 0, 0];

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
		$aR = $aGrid[$iI];
		$aR1 = [
			$aR[0], $aR[1], $aR[2], $aR[3], 
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
		if ($iProblemCol == 4){
			$aA[0][1] = 0;
			$iIn = 0;
			$iOut = $aA[1][1];
		}
		if ($iProblemCol == 5){
			$aA[1][1] = 0;
			$iIn = $aA[0][1];
			$iOut = 0;
		}
		if ($iProblemCol == 6){
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
	// In and out have values
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

	if (!$bFixed){
		// The OCR Balance column is incorrect
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
	return $aWords;
}



function words_by_class_id($sHtml, $sTagName, $sId = '', $sClass = ''){
	$sRet = '';
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
	return $sRet;
}

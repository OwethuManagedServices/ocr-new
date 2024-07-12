<?php
// Create the OCR files for each column of each page, build grid, recon
require('functions.php');

/*
$oRouteVars['id'] = '1720797350638209';
$oRouteVars['bank'] = 'absa2';
$oRouteVars['pages'] = json_encode([2]);
*/

function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sBank = $oRouteVars['bank'];
	$aPages = json_decode($oRouteVars['pages']);
	$sWork = $oV['sDirectoryWork'] . $sId;

	$sMetaFile = metamessage('Extracting OCR', $sId, $oV);
	$bMeta = 0;
	$sHocr = $sWork . '/out-page-1.hocr';
	if (file_exists($sHocr)){
		$oTemplate = getcwd() . '/data/templates/' . $sBank . '.json';
		$oHeader = json_decode(file_get_contents($oV['sDirectoryWork'] . $sId . '/header.json'), 1);
		if (file_exists($oTemplate)){
			$aGridData = [];
			$aGridErrors = [];
			$oTemplate = json_decode(file_get_contents($oTemplate), 1);
			shell_exec($sWork . '/' . $oTemplate['ocr_cmd']);

			if (isset($oTemplate['grid']['skip_whole_page'])){
				$oSkip = $oTemplate['grid']['skip_whole_page'];
			} else {
				$oSkip = 0;
			}
			$iPg = 0;
			foreach ($aPages as $iPages){
				for ($iPage = 1; $iPage <= $iPages; $iPage++){
					// Test if the page is a header page without transactions, then skip if it is
					if ($oSkip){
						$sHocr = $sWork . '/out-col-' . $iPg . '-' . $iPage . '-' . $oSkip['column'] . '.hocr';
						$sHtml = str_get_html(file_get_contents($sHocr));
						foreach ($oSkip['positions'] as $aPos){
							$aText = words_at_position($sHtml, $aPos);
							$sText = '';
							foreach ($aText as $oW){
								$sText .= $oW['text'] . ' ';
							}
							$sText = trim($sText);
							if ($sText == $oSkip['words']){
								$iPage++;
								break;
							}
						}
					}
					$aRet = ocr_to_data_columns($oTemplate, $sWork, $iPg, $iPage);
					$aGrid = $aRet[0];
					$iYWordGridStart = $aRet[1];
					// Adjust the number of columns to be consistent for each bank
					$aGrid = grid_columns_per_bank($sBank, $aGrid);
					// Find the column with most records
					$iColLength = 0;
					$iLongestColumn = 0;
					for ($iI =0; $iI < sizeof($aGrid); $iI++){
						if (!isset($aGrid[$iI][0])){
							$aGrid[$iI][] = [0, []];
						}
						if (sizeof($aGrid[$iI]) > $iColLength){
							$iColLength = sizeof($aGrid[$iI]);
							$iLongestColumn = $iI;
						}
						$aGrid[$iI][] = [99999, []];
					}
					$aColIndexes = array_fill(0, sizeof($aGrid), 0);
					$bEnd = 0;
					$iY = -60;
					$iH = 0;
					$iHAvg = 80;
					// Add columns, all banks results has to use same columns
					$aAllColumns = all_columns($oTemplate);
					// Loop through longest column
					for ($iI =0; $iI < sizeof($aGrid[$iLongestColumn]); $iI++){
						if ($bEnd){
							break;
						}
						$aRow = array_fill(0, sizeof($aGrid) + 4, '');
						$aRow[] = 0;
						$aRow[0] = $iPg . '_' . $iPage . '_' . $iI;
						$iL = $iY;
						$iY = $aGrid[$iLongestColumn][$iI][0];
						$iL = ($iY - $iL - $iYWordGridStart);
						$iYWordGridStart = 0;
						if ((($iL / $iHAvg) > 1.8) && ($iL != sizeof($aGrid[$iLongestColumn]) - 1)){
							if ((isset($oTemplate['grid']['end_of_document_space_y'])) && ($iL >= $oTemplate['grid']['end_of_document_space_y'])){
								$bEnd = 1;
							} else {
								$iL = intval($iHAvg);
								$iH += $iL;
								$iY -= $iL;
								$iI--;
							}
						} else {
							$iH += $iL;
							$iHAvg = $iH / ($iI + 1);
						}
			
						for ($iJ = 0; $iJ < sizeof($aGrid); $iJ++){
							if ($bEnd){
								break;
							}
							if (isset($aGrid[$iJ][$aColIndexes[$iJ]][0])){
								$iK = $aGrid[$iJ][$aColIndexes[$iJ]][0];
							} else {
								$iK = 99999;
							}
							if ((abs($iY - $iK) >= 20) && ($iK != 99999)){
								for ($iZ = $aColIndexes[$iJ]; $iZ < sizeof($aGrid[$iJ]); $iZ++){
									$iK = $aGrid[$iJ][$iZ][0];
									if (abs($iY - $iK) < 20){
										$aColIndexes[$iJ] = $iZ;
										break;
									}
								}
							}
							if ((abs($iY - $iK) < 20) && ($iK != 99999)){
								foreach ($aGrid[$iJ][$aColIndexes[$iJ]][1] as $oR){
									if (isset($oR['text'])){
										$aRow[$iJ + 1] .= $oR['text'] . ' ';
									}
								}
								$oT = $aAllColumns[$iJ];
								$sV = trim($aRow[$iJ + 1]);
								$oEnd = $oTemplate['grid'];
								if (isset($oEnd['end_of_document_trigger_word'])){
									$oEnd = $oEnd['end_of_document_trigger_word'];
									$iSPos = strpos(' ' . $sV, $oEnd['word']);
									if (($iJ == $oEnd['column']) && ($iSPos == 1)){
										$bEnd = 1;
									}
								}
								if ($sV){
									$sE = '';
									$sV = field_value_format($iPage, $oTemplate, $oT, $iI, $iJ, $sE, $sV);
									if ($sV[1]){
										$aGridErrors[] = $sV[1];
									}
									$sV = $sV[0];
								}
								$aRow[$iJ + 1] = $sV;
								$aColIndexes[$iJ]++;
							}
						}
						// Insert extra amount column if necessary
						$aRow = column_for_debit_and_credit($sBank, $aRow);
						// Fix multi-row lines
						$aGridData = columns_blank($aGridData, $oTemplate, $aRow, $bEnd);
					}
				}
				$iPg++;
			}
			// All pages done
			
			//loop anomalies to fix multi-line entries
			foreach ($aGridData as $aR){
				if ($aR[9]){
					$aPR = explode('_', $aR[0]);
					$iR = intval($aPR[1]) + $aR[9];
					$sPR = $aPR[0] . '_' . $iR;
					$iI = 0;
					foreach ($aGridErrors as $aE){
						if ($aE[0] == $sPR){
							$aGridErrors[$iI][0] = $aR[0];
							$aGridErrors[$iI][2] = $iR;
						}
						$iI++;
					}
				}
			}

			// Fix dates without years
			if (substr($aGridData[0][1], 0, 4) == '0000'){
				$aGridData = dates_year_fix($aGridData, $oHeader, $sBank);
			}
			$oMeta = json_decode(file_get_contents($sMetaFile), 1);
			$oMeta = [
				'error' => 0,
				'message' => 'Done',
				'result' => [
					'id' => $sId,
					'pages' => json_encode($aPages),
					'bank' => $sBank,
					'grid' => $aGridData,
					'anomalies' => $aGridErrors,
					'thumbs' => $oMeta['result']['thumbs'],
					'edit' => array_fill(0, sizeof($aGridData), []),
					'job' => 'recon',
				],
			];
		
		} else {
			$oMeta = [
				'error' => 8,
				'message' => 'Could not find a bank template',
				'id' => $sId,
			];
		}
		$oMeta['result']['header'] = $oHeader;
		$oMeta['result']['template'] = $oTemplate;
		file_put_contents($sWork . '/meta.json', json_encode($oMeta, JSON_PRETTY_PRINT));
		$bMeta = 1;
	}
	if (!$bMeta){
		$oMeta = [
			'error' => 1,
			'message' => 'The record could not be found',
			'id' => $sId,
		];
	}
	response_json($oMeta);
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



job($oRouteVars, $oV);




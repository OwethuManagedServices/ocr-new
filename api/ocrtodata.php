<?php
// Create the OCR files for each column of each page, build grid, recon

use function Laravel\Prompts\error;

require('functions.php');

/*
$oRouteVars['id'] = '1721400130088732';
$oRouteVars['bank'] = 'fnb';
$oRouteVars['pages'] = json_encode([3]);
*/

function job($oRouteVars, $oV){
	$sId = $oRouteVars['id'];
	$sBank = $oRouteVars['bank'];
	$aPages = json_decode($oRouteVars['pages']);
	$sWork = $oV['sDirectoryWork'] . $sId;

	$sMetaFile = metamessage('Extracting OCR', $sId, $oV);
	$bMeta = 0;
	$sHocr = $sWork . '/out-page-1.hocr';
	$iPageCount = 0;
	if (file_exists($sHocr)){
		$oTemplate = getcwd() . '/data/templates/banks/' . $sBank . '.json';
		$oHeader = json_decode(file_get_contents($oV['sDirectoryWork'] . $sId . '/header.json'), 1);
		if (file_exists($oTemplate)){
			$aGridData = [];
			$aGridErrors = [];
			$oTemplate = json_decode(file_get_contents($oTemplate), 1);
			// OCR each column via shell script
			shell_exec($sWork . '/' . $oTemplate['ocr_cmd']);

			if (isset($oTemplate['grid']['skip_whole_page'])){
				$oSkip = $oTemplate['grid']['skip_whole_page'];
			} else {
				$oSkip = 0;
			}
			$iPg = 0;
			$aPageDates = [];
			// Every PDF
			foreach ($aPages as $iPages){
				// Every page of the PDF
				for ($iPage = 1; $iPage <= $iPages; $iPage++){
					$aGridData1 = [];
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
					for ($iI = 0; $iI < sizeof($aGrid[$iLongestColumn]); $iI++){
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
						$aGridData1 = columns_blank($aGridData1, $oTemplate, $aRow, $bEnd);
					}
					// Save the last date to be able to order the pages by date
					$aGridData[] = $aGridData1;
					$sLastDate = $aGridData1[sizeof($aGridData1) - 1][1];
					if (is_valid_date($sLastDate)){
						$aPageDates[] = [$iPageCount, $sLastDate];
					}
		
					$iPageCount++;

				}
				$iPg++;
			}
			// All pages done yay
			// Fix dates without years
			if (substr($aPageDates[0][1], 0, 4) == '0000'){
				$aPageDates = dates_year_fix($aPageDates, $oHeader, $sBank);
			}
//			error_log(json_encode($aPageDates));

			// Sort by date
			usort($aPageDates, function($a, $b){
				return ($a[1] >= $b[1] ? 1 : -1);
			});
//error_log(json_encode($aPageDates));
			$aGridData1 = [];
			// Assemble the statement in the correct date order
			foreach ($aPageDates as $aP){
				$aGridData1 = array_merge($aGridData1, $aGridData[$aP[0]]);
			}
			// Clear the double-stored data
			$aGridData = 0;
			// Loop anomalies to fix multi-line entries
			foreach ($aGridData1 as $aR){
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
			if (substr($aGridData1[0][1], 0, 4) == '0000'){
				$aGridData1 = dates_year_fix($aGridData1, $oHeader, $sBank);
			}
			$oMeta = json_decode(file_get_contents($sMetaFile), 1);
			$oMeta = [
				'error' => 0,
				'message' => 'Done',
				'result' => [
					'id' => $sId,
					'pages' => json_encode($aPages),
					'bank' => $sBank,
					'grid' => $aGridData1,
					'anomalies' => $aGridErrors,
					'thumbs' => $oMeta['result']['thumbs'],
					'edit' => array_fill(0, sizeof($aGridData1), []),
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



job($oRouteVars, $oV);




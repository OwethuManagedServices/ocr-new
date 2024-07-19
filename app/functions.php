<?php
function bookedcheck($oSrv, $iDay, $iTime){
	$sA = '&nbsp;';
	$iUnixtime = $oSrv['sevendays'][$iDay][0] 
		+ $oSrv['timedaystartminutes'] * 60
		+ $iTime * $oSrv['minutesperbooking'] * 60
		- $oSrv['timezoneoffsetminutes'] * 60;
	for ($iB = 0; $iB < sizeof($oSrv['bookings']); $iB++){
		if ($oSrv['bookings'][$iB] == $iUnixtime){
			$sA = 'x';
		}
	}
	$sRet = '<div class="acc_gridborder" unixtime="' . 
		$iUnixtime . '">' . $sA . '</div>';
	return $sRet;
}



function formfieldshow($oFld, $sVal, $oUser = 0){
	if ((isset($oFld['no_label'])) && ($oFld['no_label'] == 1)){
		$sH = '';
	} else {
		
		$sH = '<label class="text-sm" for="' . $oFld['dbfield'] .'"><span>' . $oFld['name'] . '<br>';
	}
	switch ($oFld['type']){

		case 'image':
			$sH .= '<div class="w-' . $oFld['size'][0] . ' h-' . $oFld['size'][1] . '">';
			$sH .= '<img id="' . $oFld['dbfield'] . '" src="' . $sVal . '" class="w-full h-full" /></div>';
		break;

		case 'color':
			$sH .= '<div>';
			$sH .= '<input id="' . $oFld['dbfield'] . '" name="' . $oFld['dbfield'] . '"';
			if ($sVal){
				$sH .= ' value="' . $sVal . '"';
			} else {
				$sH .= ' value="' . "{{ old('" . $oFld['dbfield'] ."') }}";
			}
			$sH .= ' style="display:none" /><input class="rounded-md pb-1 font-bold bg-light" type="text"';
			$sH .= ' oninput="APP.color(event)" data-coloris value="' . $sVal . '"';
			$sH .= ' /></div>';
		break;

		case 'upload':
			$sH .= '
<div>
<table border=1><tr><td width="50%">
	<label style="--hoverbg:' . $oUser->theme_color_secondary . ';background:' . $oUser->theme_color_primary . '" class="hovering mx-1 text-light text-sm py-2 px-6 rounded-md" for="acc_fileupload_' . $oFld['job'] . '">
			<span class="text-center">Select File(s)</span>
		</label>
		<input type="file" multiple="multiple" id="acc_fileupload_' . $oFld['job'] . '" style="opacity:0;width:1px;margin:0 30px 0 -30px;" onchange="MBF.selected(event)" />
	</td>
	<td width="50%">
		<div class="mt-4 h-8" id="acc_fileupload_' . $oFld['job'] . '_selected"></div>
		<div class="float-right" id="accwaitspinupload"></div>
	</td>
	</tr>
	<tr>
		
	</tr>
	<tr>
		<td>
			<button id="acc_upload_' . $oFld['job'] . '" style="--hoverbg:' . $oUser->theme_color_secondary . ';background:' . $oUser->theme_color_primary . '" class="hovering mx-1 text-light text-sm py-2 px-6 rounded-md w-full" type="button" onclick="MBF.uploadstart(event)">Upload ' . $oFld['description'] . '</button>
		</td>
		<td>
			<div id="acc_uploadprogress_' . $oFld['job'] . '" class="accprogress">
				<div id="acc_uploadprogressbar_' . $oFld['job'] . '" class="accprogressbar"></div>
			</div>
		</td>
	</tr>
</table>
</div>';
			if ($oFld['status']){
				$sH .= '<div class="col-span-6 accuploadstatus" id="acc_uploadstatus_' . $oFld['job'] . '"></div>';
			}
		break;

		case 'button':
			$sH .= '<button id="' . $oFld['id'] . '" style="--hoverbg:' . $oUser->theme_color_secondary . ';background:' . $oUser->theme_color_primary . '" class="hovering mt-2 mx-1 text-light font-medium py-2 px-6 rounded-md text-sm"	type="button" onclick="' . $oFld['onclick'] . '">' . $oFld['description'] . '</button>';
		break;

		case 'text':
		case 'number':
		case 'email':
		case 'date':
		case 'time':
			$sH .= '<input class="w-full font-light text-lg text-dark bg-greylight rounded-md pb-1';
			if (isset($oFld['css'])){
				$sH .= ' ' . $oFld['css'];
			}
			$sH .= '" type="' . $oFld['type'] . '"';
			$sH .= ' id="' . $oFld['dbfield'] 
			. '" name="' . $oFld['dbfield'] . '"';
			if ((isset($oFld['required'])) && ($oFld['required'])){
				$sH .= ' required';
			}
			if ((isset($oFld['disabled'])) && ($oFld['disabled'])){
				$sH .= ' disabled="disabled"';
			}
			if (isset($oFld['oninput'])){
				$sH .= ' oninput="' . $oFld['oninput'] . '"';
			}
			if (isset($oFld['display_none'])){
				$sH .= ' style="display:none;"';
			}
			if ($sVal){
				$sH .= ' value="' . $sVal . '"';
			}
			$sH .= ' />';
		break;

		case 'datetime':
			$aDate = explode(' ', $sVal);
			$sVal = $aDate[0];
			$sH .= '<div class="grid grid-cols-3"><div class="w-40">';
			$sH .= '<input class="font-light font-mono text-lg text-dark bg-greylight rounded-md mr-8" type="date" placeholder="YYYY-MM-DD" id="' . $oFld['dbfield'] 
			. '_date" name="' . $oFld['dbfield'] . '_date"';
			if (isset($oFld['css'])){
				$sH .= ' class="' . $oFld['css'] . '"';
			}
			if ((isset($oFld['required'])) && ($oFld['required'])){
				$sH .= ' required';
			}
			if ((isset($oFld['disabled'])) && ($oFld['disabled'])){
				$sH .= ' disabled="disabled"';
			}
			if ($sVal){
				$sH .= ' value="' . $sVal . '"';
			}
			$sH .= ' /></div><div class="w-4 h-4"></div><div class="float-right">';
			$sH .= '<input class="w-40 font-light font-mono text-lg text-dark bg-greylight rounded-md" type="time" id="' . $oFld['dbfield'] 
			. '_date" name="' . $oFld['dbfield'] . '_date"';
			if (isset($oFld['css'])){
				$sH .= ' class="' . $oFld['css'] . '"';
			}
			if ((isset($oFld['required'])) && ($oFld['required'])){
				$sH .= ' required';
			}
			if ((isset($oFld['disabled'])) && ($oFld['disabled'])){
				$sH .= ' disabled="disabled"';
			}
			if ($sVal){
				$sH .= ' value="' . $aDate[1] . '"';
			}
			$sH .= ' /></div></div>';

		break;

		case 'checkbox':
			$sH .= '<input type="' . $oFld['type'] . '" id="' . $oFld['dbfield'] 
			. '" name="' . $oFld['dbfield'] . '"';
			if (isset($oFld['css'])){
				$sH .= ' class="' . $oFld['css'] . '"';
			}
			if ($sVal){
				$sH .= ' checked="checked"';
			}
			if (isset($oFld['onchange'])){
				$sH .= ' onchange="' . $oFld['onchange'] . '"';
			}
			$sH .= ' />';
		break;

		case 'select':
			if ($oFld['dbfield']){
				$sId = $oFld['dbfield'];
			} else {
				$sId = strtolower($oFld['name']);
			}
			$sH .= '<select class="font-light text-lg w-full bg-greylight color-dark rounded" id="' . $sId 
			. '" name="' . $sId . '"';
			if (isset($oFld['css'])){
				$sH .= ' class="' . $oFld['css'] . '"';
			}
			$sH .= '>';
			$iI = 0;
			if ($sVal){
				$sSelected = $sVal[0];
				foreach ($sVal as $aO){
					if ($iI){
						if ($aO[0] == $sSelected){
							$sV = 'selected="selected"';
						} else {
							$sV = '';
						}
						$sH .= '<option ' . $sV . ' value="' . $aO[0] . '">' . $aO[1] . '</option>';
					}
					$iI++;
				}
			}
			$sH .= '</select>';
		break;

		case 'textarea':
			$sH .= '<textarea id="' . $oFld['dbfield'] . '" name="' . $oFld['dbfield'] . '" rows="6" class="w-full text-lg font-mono font-light text-dark bg-greylight rounded-md"';
			if (isset($oFld['css'])){
				$sH .= ' class="' . $oFld['css'] . '"';
			}
			if ((isset($oFld['disabled'])) && ($oFld['disabled'])){
				$sH .= ' disabled="disabled"';
			}
			$sH .= '>'
			. ($sVal) . '
			</textarea>';
		
		break;

	}
	if ((isset($oFld['no_label'])) && ($oFld['no_label'] == 1)){
	} else {
		$sH .= '</label>';
	}
	return $sH;
}



function gridsortlink($sField, $sDesc, $sDir, $aGet = ['', '']){
	$aDir = explode(' ', $sDir);
	if ($aDir[1] == 'asc'){
		$sDir = 'd';
	} else {
		$sDir = 'a';
	}
	if ($aGet[0] == $sField){
		if ($sDir == 'a'){
			$sDesc = ' &#9650;&nbsp;&nbsp;' . $sDesc ;
		} else {
			$sDesc = '  &#9660;&nbsp;&nbsp;' . $sDesc;
		}
	}
	$sH = 'onclick="GRD.sort(\''. $sField . '-' . $sDir . '\')">' . $sDesc . '';
	return $sH;
}


?>
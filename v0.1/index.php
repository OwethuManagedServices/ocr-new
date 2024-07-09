<?php

$oOTS = new AAA_OCR_TO_BANK_STATEMENT();



class AJAX_JOBS{

function bank_template($oData){
	global $oOTS;
	$oData = $oOTS->bank_template($oData);
	$oData = $oOTS->png_pages($oData);
	$oTemplate = json_decode(file_get_contents('template-' . $oData['bank'] . '.json'), 1);
	$oData['template'] = $oTemplate;
	return $oData;
}



}



class AAA_OCR_TO_BANK_STATEMENT{

// Global variables
public $_V = [
	'dir_bin' => '/usr/local/bin/',
	'dir_work' => '',
	'hocr' => '',
	'template' => 0,
	'header' => 0,
	'columns' => 0,
	'grid' => [],
];



public function __construct(){	
	// Set up the global variables	
	$this->_V['dir_work'] = getcwd() . '/';
	// Include this library to search and retrieve the HTML DOMoutput from Tesseract
	include($this->_V['dir_work'] . '../dom/simple_html_dom.php');
	// Handle AJAX requests from frontend, while doing the action check
	if ((isset($_POST['action'])) && ($_POST['action'] == 'pdfocr')){
		$this->ajax();
	}
	echo $this->html_page();
}



public function ajax(){
	// The encoded data arrives, ensure validity and react only on the job paramater
	// TODO: nonce
	$oData = $_POST['data'];
	$iError = 0;
	if (!$oData){
		$iError = 1;
	} else {
		if (!is_string($oData)){
			$iError = 1;
		} else {
			$oData = base64_decode($oData);
			if (!$oData){
				$iError = 1;
			} else {
				$oData = base64_decode($oData);
				if (!$oData){
					$iError = 1;
				} else {
					$oData = json_decode($oData, 1);
					if (!$oData){
						$iError = 1;
					}
				}
			}
		}
	}
	if ($iError){
		$oData = ['error' => $iError];
		echo base64_encode(json_encode($oData));
		die;
	}

	// We have a valid request, continue according to the job name
	$oAjax = new AJAX_JOBS();
	switch ($oData['job']){

		case 'bank-template':
			$oData = $oAjax->bank_template($oData);
		break;


	}
	// Encode the data and return to frontend
	echo base64_encode(json_encode($oData));
	die;

}



public function bank_template($oData){

	return $oData;
}


// Return the HTML output body section
public function html_page_body(){
	ob_start();
	// THe body HTML goes here in plain HMTL
?>

<div style="width:100%;text-align:center">
	<h3>Bank Statement OCR Multiple Template Recognition</h3>
</div>
<div style="padding:20px;width:calc(100% - 40px);max-width:1280px;margin:0 auto;text-align:center;background:bisque;">

	<div>
		<div style="font-size:small;text-align:left;">Legend</div>
		<div class="bklegend">
<?php
$aBox = explode(', ', 'amount_no_decimal, amount_invalid_decimal, empty_required_column, invalid_characters, amount_changed_in, amount_changed_out, amount_changed_balance');
foreach ($aBox as $sClass) {
	$sDesc = ucwords(str_replace('_', ' ', $sClass));
	?>
			<div class="bklegendbox err_<?= $sClass ?>"><?= $sDesc ?></div>
<?php
}

?>
		</div>
	</div>
	<div style="width:70%;float:left;">
		<div style="font-size:small;;text-align:left;">Folder</div>
		<input type="text" id="bk_folder" style="width:100%;" 




		value="" />
	</div>
	<div style="float:right;margin-right:20px;">
		<div style="font-size:small;margin:5px 0 -10px 0">Time</div>
		<div id="bk_time">0.0</div>
	</div>
	<div style="clear:both;"></div>
	<div style="float:left;">
		<input type="file" id="bk_fileupload_statement">
	</div>
	<div style="float:right;">
		<button id="bk_upload_statement" type="button" onclick="MBF.uploadstart(event)">Upload PDF</button>
		<br>
		<div style="float:right;margin-top:20px;">
			<div id="bk_uploadprogress_statement" class="bkprogress">
				<div id="bk_uploadprogressbar_statement" class="bkprogressbar"></div>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
	<div style="float:left;">
		<div class="bkuploadstatus" id="bk_uploadstatus_statement"></div>
	</div>
	<div style="margin-bottom:30px;">
		<div style="font-size:small;float:left;margin:-14px -20px 18px 0;">Log</div>
		<div style="padding:0 10px;calc(width:100% - 20px);height:400px;background:white;text-align:left;overflow-y:scroll;">
			<table id="bk_log"></table>
		</div>
	</div>
	<div style="width:100%;background:white;" id="bk_textarea"></div>
	<div style="font-size:small;float:left;margin:-14px -20px 18px 0;">Header</div>
	<div style="width:100%;background:white;margin-bottom:20px;padding:10px;" id="bk_grid_1"></div>
	<div style="width:100%;background:white;" id="bk_grid_2"></div>
	<div style="width:100%;background:white;" id="bk_pages_png"></div>
</div>

<?php
	// End of html body
	return ob_get_clean();
}



// Return the HTML output head section first and then the body via a separate function
public function html_page(){
	$sRootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/v0.1/';
	ob_start();
	$sNonce = md5(time());
	$iR = rand(5, 9);
	$sNonce = 'X' . strtoupper(substr($sNonce, $iR, 10)) . $iR;
?>
<html>
	<head>
		<title>v0.1</title>
		<link rel="stylesheet" href="index.css"></link>
		<script>
			var OCRsrv = {
				url: "<?= $sRootUrl ?>",
				action: 'pdfocr',
				nonce: '<?= $sNonce ?>',
				ajaxurl: "<?= $sRootUrl ?>",
			};
		</script>
		<script src="ocrbank.js"></script>
	</head>
	<body>
	<?= $this->html_page_body() ?>
	</body>
</html>
<?php
	return ob_get_clean();
}



public function png_pages($oData){

	return $oData;
}



// Search all words inside the specified position coordinates
function words_at_position($sHtml, $aPos){
	$aWords = [];
	foreach($sHtml->find('span') as $oElement){
		// Cycle through all words
		if ($oElement->class === 'ocrx_word'){
			$oW = [
				'text' => $oElement->innertext,
				'id' => $oElement->id,
				'title'	=> $oElement->title,
			];
			$aWP = explode(' ', $oElement->title);
			$aWP1 = [];
			// Find the position coordinates
			foreach($aWP as $sP){
				$aWP1[] = intval($sP);
			}
			$oW['pos'] = $aWP1;
			// Test if word is inside provided position range
			if (($oW['pos'][2] >= $aPos[1]) && ($oW['pos'][2] <= $aPos[3]) 
			&& ($oW['pos'][1] >= $aPos[0]) && ($oW['pos'][1] <= $aPos[2])){
				$aWords[] = $oW;
			}
		}
	}
	return $aWords;
}
	


// Search word by tagname, id or class
public function words_by_class_id($sHtml, $sTagName, $sId = '', $sClass = ''){
	$sRet = '';
	foreach($sHtml->find($sTagName) as $oElement) {
		if ($sId){
			if ($oElement->id === $sId){
				$sRet = $oElement->innertext;
				break;
			}    
		}
		if ($sClass){
			if ($oElement->class === $sClass){
				$sRet = $oElement->innertext;
				break;
			}    
		}
	}
	return $sRet;
}


}




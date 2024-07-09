<?php
$sDr = __DIR__ ;
$sFN = $_GET['file'];
$iStep = $_GET['step'];
$sCat = $_GET['cat'];
$sDr .= '/upload/';
$sExt = 'img';
switch ($iStep){
	case 1:
		$sFNN = $sDr . $sCat . '/' . $sFN . '.pdf';
		$sExt = 'pdf';
	break;
	case 2:
		$sFNN = $sDr . $sCat . '/' . $sFN . '.img';
		$sExt = 'img';
	break;
}
$sFN = $_GET['file'];
$oF = fopen($sFNN, 'a');
flock ($oF, LOCK_EX);
fwrite($oF, file_get_contents('php://input'));
flock ($oF, LOCK_UN);
fclose($oF);
echo filesize($sFNN) . '|' . $sExt . '|' . 
$_SERVER['SERVER_NAME'] . '/upload/' . '|' . explode('upload/', $sFNN)[1];
?>

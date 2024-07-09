<?php
$sDr = getcwd() . '/';
$sFN = $_GET['file'];
$iStep = $_GET['step'];
$sExt = 'pdf';
switch ($iStep){
	case 1:
		$sFNN = $sDr . 'upload/' . $sFN . '.pdf';
		$sExt = 'pdf';
	break;
}
$sFN = $_GET['file'];
$oF = fopen($sFNN, 'a');
flock ($oF, LOCK_EX);
fwrite($oF, file_get_contents('php://input'));
flock ($oF, LOCK_UN);
fclose($oF);
echo filesize($sFNN) . '|' . $sExt . '||' . $sFNN;
?>

<?php
if (!isset($three)){
	$three = '';
}
echo '<script>APP.initheader("' . base64_encode($one) . 
	'", "' . base64_encode($two) . '", "' . base64_encode($three) . '");</script>';
?>

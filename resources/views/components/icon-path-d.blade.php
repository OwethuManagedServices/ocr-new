<?php
$sPathD = file_get_contents(base_path() . '/resources/views/components/icons/' . $data . '.blade.php');
$sPathD = explode('<path', $sPathD)[1];
$sPathD = explode('d="', $sPathD)[1];
$sPathD = explode('"', $sPathD)[0];
echo $sPathD;
?>

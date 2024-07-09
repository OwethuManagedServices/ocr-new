<?php
$oU =Auth::user();
if ($oU->theme_color_primary){
    $sStyle = 'style=background:' . $oU->theme_color_primary . ';--hoverbg:' . $oU->theme_color_secondary . ';';
} else {
    $sStyle = '';
}
$sHref = '';
$sHEnd = '';
if (isset($data['href'])){
	$sHref = '<a href="' . $data['href'] . '">';
	$sHEnd = '</a>';
}
if (isset($data['onclick'])){
	$sHref = '<a onclick="' . $data['onclick'] . '">';
}
if (((isset($data['post'])) || (isset($data['form']))) && (!isset($data['onclick']))){
	$sType = 'type="submit"';
} else {
	$sType = 'type="button"';
}
if (isset($data['onclick'])){
	$sType .= 'onclick="' . $data['onclick'] . '" ';
}
$sText = explode('|', $data['text']);
if (isset($sText[1])){
	$sText2 = $sText[1];
	$sWidth = ' style=width:'. $sText[2] . 'rem';
} else {
	$sText2 = '';
	$sWidth = '';
}

$sText = $sText[0];
if (isset($data['form'])){
	$aF = explode('|', $data['form']);
	$sHref = '<form method="POST" action="' . $aF[1] . '">';
	$sHref .= '<input type="hidden" name="_token" value="' . csrf_token() . '" />';
	if ($aF[1] != "POST"){
		$sHref .= '<input type="hidden" name="_method" value="' . $aF[0] . '" />';
	}
	$sHEnd = '</form>';
}
$sClass = 'hovering inline-flex items-center px-6 py-2 bg-blue border border-greydark rounded-md font-semibold text-sm text-dark tracking-widest shadow-sm text-light hover:bg-bluemedium focus:outline-none focus:ring-2 focus:ring-bluemedium focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 tracking-widest';
if (isset($data['style'])){
	$aStyle = $data['style'];
	foreach ($aStyle as $aS){
		$sClass = str_replace($aS[0], $aS[1], $sClass);
	}
}
?>

{!!$sHref!!}
<button {!!$sType!!} {{$sWidth}} class="{{ $sClass }}" {{ $sStyle }}>
	<div>{!!$sText!!}</div>
</button>
{!!$sHEnd!!}
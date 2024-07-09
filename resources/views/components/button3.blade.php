<?php
$oU =Auth::user();
if ($oU->theme_color_primary){
    $sStyle = 'style=background:' . $oU->theme_color_primary . ';--hoverbg:' . $oU->theme_color_secondary . ';';
} else {
    $sStyle = '';
}
?>
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'hovering bg-blue hover:bg-bluemedium inline-flex items-center px-6 py-1 border border-greydark rounded-md font-semibold text-sm text-dark tracking-widest shadow-sm text-light focus:outline-none focus:ring-2 focus:ring-bluemedium focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 tracking-widest']) }}{{ $sStyle }}>{{ $slot }}</button>

<?php
if (isset($type)){
    $sT = 'type="' . $type . '" ';
} else {
    $sT = '';
}
if (isset($width)){
    $sC = ' ' . $width;
} else {
    $sC = '';
}
?>
<button {{ $attributes->merge(['class' => 'min-w-24 text-sm sm:text-lg rounded-full px-4 sm:px-8 py-2 bg-blue text-light sm:font-medium hover:bg-hoverlight border-greydark border-2' . $sC]) }} {!!$sT!!}>
    {{ $text }}
</button>

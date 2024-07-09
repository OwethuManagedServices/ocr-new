<?php
$sSvg = file_get_contents(resource_path() . '/views/components/iconscolor/'. $name . '.svg');
$sSvg = str_replace('width="500"', 'width="100%"', $sSvg);
$sSvg = str_replace('height="500"', 'height="100%"', $sSvg);
$sSvg = str_replace('fill="#c3cad7"', '', $sSvg);

?>
<div class="w-{{ $size }} h-{{ $size }}">
    {!! $sSvg !!}
</div>
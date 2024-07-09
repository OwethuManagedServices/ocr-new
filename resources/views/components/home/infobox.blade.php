<?php
if (isset($data['num'])){
    $sNum = '<span class="px-4 py-2 font-semibold text-2xl w-14 rounded-full bg-light text-dark border-solid border-2 border-greydark -mb-6">' . $data['num'] . '</span><br>';
} else {
    $sNum = '';
}
if (isset($data['col'])){
    $sCol = $data['col'];
} else {
    $sCol = '';
}
?>

<div class="rounded-lg mb-10 {{$data['class']}} py-8 px-6 mb-4">
@if ($sNum)
{!!$sNum!!}
@endif
                        <div
                            class="text-light float-right w-10 h-10 -mt-8">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                            viewBox="{{$data['icon']['viewbox']}}">
                            <path class="{{$data['icon']['class']}}" d="<x-icon-path-d :data="$data['icon']['svg']" />">
                            </svg>
                        </div>
                        <br>
                        <span class="{!! $sCol !!} mt-12 text-2xl font-semibold">{{$data['heading']}}</span>
                        <br>
                        <br>
                        <p class="{!! $sCol !!} pb-6 text-lg">
                            <span>{{$data['text']}}</span>
                        </p>
                    </div>

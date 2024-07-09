<?php
$oU =Auth::user();
if ($oU->theme_color_primary){
    $sStyle = 'style=background:' . $oU->theme_color_primary . ';--hoverbg:' . $oU->theme_color_secondary . ';';
} else {
    $sStyle = '';
}
$sRIndex = route($route . '.index');
if (!isset($buttons)){
	$buttons = [];
} else {
	$buttons = json_decode($buttons, 1);
}
if (!isset($columns)){
	$columns = [];
} else {
	$columns = json_decode($columns, 1);
}
if (!isset($filter)){
	$filter = [];
} else {
	$filter = json_decode($filter, 1);
	$sFilterField = $columns[$filter[0]]['field'];
	$sFilterFieldDisplay = $columns[$filter[0]]['display'];
}
if (!isset($tabs)){
	$tabs = [];
} else {
	$tabs = json_decode($tabs, 1);
}
?>
<div class="h-12 fixed w-full max-w-7xl mx-auto border-b border-greylight bg-light pt-2">
	<div class="float-left mr-8">
		<a href="{{ $sRIndex }}">
			<h2 class="mx-4 font-medium text-xl text-dark">{{$title}}</h2>
		</a>
	</div>
@for ($iI = 0; $iI < sizeof($tabs); $iI++)
	<a href="?tab=<?= $iI + 1 ?>">
		<div id="tabbutton_<?= $iI + 1 ?>" class="bg-greylight text-dark mr-[1px] mt-[10px] text-sm float-left px-8 py-1 rounded-t border border-greylight">{{ $tabs[$iI] }}</div>
	</a>
@endfor
@for ($iI = 0; $iI < sizeof($buttons); $iI++)
	<div class="ml-8 float-left">
		<x-button2 :data="$buttons[$iI]" />
	</div>
@endfor
@if (isset($search))
	<div class="float-right mr-6 mt-1">
		<input id="search" class="bg-greylight text-dark h-6 w-32 rounded px-1"
			oninput="GRD.searchtype(event)" onkeypress="GRD.searchenter(event)"
			value="{!!$search!!}" placeholder="Search" />
		<button class="hovering hover:bg-bluemedium text-light bg-blue rounded h-6 w-6 leading-tight" {{ $sStyle }} onclick="GRD.searchgo(event)">></button>
		<button class="hovering hover:bg-bluemedium text-light bg-blue rounded h-6 w-6 leading-tight" {{ $sStyle }} onclick="GRD.searchclear(event)">X</button>
		<button class="hovering hover:bg-bluemedium text-light bg-blue rounded h-6 w-6 leading-tight" {{ $sStyle }} onclick="GRD.filtershow(event)">Y</button>
	</div>
	<div id="acc_filter_box" class="absolute overflow-hidden right-4 -top-1 w-96 bg-light rounded-md border border-bluemedium" style="height:0;transition: height 0.1s linear;">
		<div class="w-full h-12 bg-blue text-light">
			<div class="float-left pl-4 pt-3 font-bold tracking-widest">Filter</div>
			<div class="float-right mr-2 mt-2">
				<button class="hovering hover:bg-bluemedium text-light bg-blue rounded h-6 w-6 leading-tight mt-1" {{ $sStyle }} onclick="GRD.filtershow(event)">X</button>
			</div>
		</div>
		<div class="w-full h-full p-2">
			<?= formfieldshow([
				'name' => 'ID',
				'dbtable' => 'grids',
				'dbfield' => 'id',
				'type' => 'number',
				'css' => 'w-16'
			], '')?>
		</div>
	</div>
@endif
<!--
@if (isset($filter[0]))
	<div class="float-right mr-6 mt-1"><div class="float-left">{{ $sFilterFieldDisplay }}&nbsp;</div>
		<select id="filter" field="{{ $sFilterField }}" class="text-xs leading-none bg-greylight text-dark h-6 w-32 rounded py-0"
			onchange="GRD.filter(event)">
			@for ( $iI = 1; $iI < sizeof($filter); $iI++)
				<option class="-mt-2" value="{{ $iI }}"
				<?php 
				$sS = '';
				if ($filter[0] == $filter[$iI]){
					$sS = ' selected';
				}
				echo $sS .'>' . $filter[$iI];
				?></option>
			@endfor
		</select>
	</div>
@endif
			-->
</div>
<div class="h-10"></div>
<!-- PAGE-HEADER END -->

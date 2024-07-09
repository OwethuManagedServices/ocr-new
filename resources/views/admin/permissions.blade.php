			
<?php
$iRow = 0;
if (isset($_GET['search'])){
	$sSearch = $_GET['search'];
} else {
	$sSearch = '';
}
if (isset($_GET['sort'])){
	$aSort = explode('-', $_GET['sort']);
} else {
	$aSort = ['email', 'a'];
}
$sSort = json_encode($aSort);
$aButtons = [
	[
		'text' => 'Add Permission',
		'href' => route('admin-permission.create'),
		'style' => [
			['py-2', 'py-1']
		]
	],
/*	[
		'text' => 'Routes',
		'href' => route('admin-permission.routes.update'),
		'style' => [
			['py-2', 'py-1']
		]
	]
*/
];
$sButtons = json_encode($aButtons);
ob_start();?>
<x-grid.header 
search="{{$sSearch}}" 
title="Access Permissions" 
route="admin-permission"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();

?>
<x-app-layout>
<div class="max-w-7xl w-full min-h-screen shadow-xl sm:rounded-md px-4 pb-8">
	<x-form-messages />
		<?php ob_start();?>
		<div class="pt-4 px-4 bg-light border-b border-greylight max-w-7xl w-full">
			<table class="w-full table table-bordered table-hover">
				<thead style="cursor:pointer">
				@foreach ($aSrvGrid as $oColumn)
<th style="width:{{ $oColumn['width'] }}%" 
class="text-left" <?= gridsortlink($oColumn['field'], $oColumn['display'], $sSrvSort, $aSort);?></th>
				@endforeach
				</thead>
			</table>
		</div>
		<?php $sHeaderColumns = ob_get_clean();?>
	<table class="w-full table table-bordered table-hover">
		<thead style="cursor:pointer">
		@foreach ($aSrvGrid as $oColumn)
			<th style="width:{{ $oColumn['width'] }}%" class="text-left"></th>
		@endforeach
		</thead>
		<tbody>
			@if ($aSrvData->count() == 0)
			<tr>
				<td colspan="5">No records to display.</td>
			</tr>
			@endif
			@foreach ($aSrvData as $oRec)
				<?php if ($iRow % 2) $sClass = ''; else $sClass = ' class="bg-greylight"';?>
				<tr {!!$sClass!!}>
				@for ($iI = 0; $iI < sizeof($aSrvGrid); $iI++)
					@if (!$iI)
					<td><a class="text-blue hover:text-bluemedium block" 
					href="{{ route('admin-permission.edit', $oRec['id']) }}">{{ $oRec['id'] }}</a></td>
					@else
					<td>{{ $oRec[$aSrvGrid[$iI]['field']] }}</td>
					@endif
				@endfor
				</tr>
				<?php $iRow++; ?>
			@endforeach
		</tbody>
	</table>
	<br>
	{{ $aSrvData->onEachSide(0)->links() }}
</div>
@stack('modals')
@livewireScripts
<x-section-border />
<x-app.header-height one="{!! $sHeaderHTML !!}" two="{!! $sHeaderColumns !!}" />
</x-app-layout>

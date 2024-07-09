<?php
$aVals = [
	'',
	'',
	'',
	'',
	'',
];
$aButtons = [
	[
		'href' => route('admin-users.import'),
		'text' => 'Import Spreadsheet',
		'style' => [
			['py-2', 'py-1']
		]
	],
];
$sButtons = json_encode(($aButtons));
ob_start();?>
<x-grid.header 
title="Add User" 
route="admin-users"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();
$iField = 0;
?>
<x-app-layout>
<div class="max-w-7xl w-full shadow-xl rounded-md px-4 py-8">
	<x-form-messages />
	<form method="POST" action="{{ route('admin-users.store') }}">
		@csrf
<div class="grid grid-cols-6 max-w-lg">
	@for ($iField = 0; $iField < sizeof($formfields); $iField++)
		<div class="col-span-5 pb-4">
			{!! formfieldshow($formfields[$iField], $aVals[$iField]) !!}
		</label></div><div></div>
	@endfor
	<div class="col-span-6 text-right py-6">
		<x-button>{{ __('Submit') }}</x-button>
	</div>
</div>	
	</form>
</div>
<x-app.header-height one="{!! $sHeaderHTML !!}" two="" />
</x-app-layout>

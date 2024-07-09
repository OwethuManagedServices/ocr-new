<?php

use Illuminate\Support\Facades\Auth;
$aVals = [];
foreach ($aSrvFormFields as $oF){
    $aVals[] = $aSrvData[$oF['dbfield']];
}
$aVals[2] = '';
$iRecordId = $aSrvData['id'];
$aButtons = [
	[
		'form' => 'DELETE|' . route('admin-users.destroy', $iRecordId),
		'style' => [
			['py-2', 'py-1'],
			['float-left', 'float-right']
		],
		'text' => 'Delete User',
		'onclick' => 'APP.deleteconfirm(event);',
	],
	[
		'style' => [
			['py-2', 'py-1'],
			['float-left', 'float-right']
		],
		'text' => 'Roles',
		'href' => route('admin-users.roles', $iRecordId)
	],
];
$sButtons = json_encode($aButtons);
ob_start();?>
<x-grid.header
title="Edit User" 
route="admin-users"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();

$iField = 0;
?>
<x-app-layout>
<div class="max-w-7xl w-full shadow-xl rounded-md px-4 py-8">
	<x-form-messages />
	<form method="POST" action="{{ route('admin-users.update', $iRecordId) }}">
		@csrf
		@method('PUT')
		<input style="display:none" name="id" id="id" value="{{$iRecordId}}" />
<div class="grid grid-cols-6 max-w-lg">
@for ($iField = 0; $iField < sizeof($aSrvFormFields); $iField++)
	<div class="col-span-5 pb-4">
		<?= formfieldshow($aSrvFormFields[$iField], $aVals[$iField])?>
	</label></div><div></div>
@endfor
	<div class="col-span-6 text-right py-6">
		<x-button>{{ __('Update') }}</x-button>
	</div>
</div>
	</form>
</div>
<x-app.header-height one="{!! $sHeaderHTML !!}" two="" />
</x-app-layout>

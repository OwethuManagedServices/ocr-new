<?php
/* echo json_encode($aSrvFormFields, JSON_PRETTY_PRINT).'<br><br>';
echo json_encode($aSrvData, JSON_PRETTY_PRINT).'<br><br>';
die;
*/
$aVals = [];
foreach ($aSrvFormFields as $oF){
	$aVals[] = $aSrvData[$oF['dbfield']];
}

$aPM = [];
$aPN = [];
foreach ($aSrvData['permissions'] as $oR){
	$bF = 0;
	foreach ($aSrvData['rolepermissions'] as $oRP){
		if ($oRP->permission_id == $oR->id){
			$bF = 1;
		}
	}
	if ($bF){
		$aPM[] = [$oR->id, $oR->name];
	} else {
		$aPN[] = [$oR->id, $oR->name];
	}
}

$iRecordId = $aSrvData['id'];
$aButtons = [
];
$sButtons = json_encode($aButtons);
ob_start();?>
<x-grid.header
title="Edit Role: {!! $aVals[0] !!}, {!! $aVals[1] !!}"  
route="admin-role"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();

$iField = 0;
?>
<x-app-layout>
<div class="max-w-7xl w-full shadow-xl rounded-md px-4 py-8">
	<x-form-messages />
<div class="grid grid-cols-7 max-w-2xl">

	<div class="col-span-3">
		<div>Member Of</div>
		<select id="selmember" multiple class="w-full h-96">
		@foreach ($aPM as $aR)
			<option value="{{ $aR[0] }}">{!! $aR[1] !!}</option>
		@endforeach
		</select>
	</div>
	<div class="text-center">
		<div class="my-8">
			<button type="button" onclick="ACL.member(0);" class="border-greydark rounded-md border px-2">>></button>
		</div>
		<br>
		<div class="">
			<button type="button" onclick="ACL.member(1);" class="border-greydark rounded-md border px-2"><<</button>
		</div>
	</div>
	<div class="col-span-3">
		<div>Not Member Of</div>
		<select id="selnotmember" multiple class="w-full h-96">
		@foreach ($aPN as $aR)
			<option value="{{ $aR[0] }}">{!! $aR[1] !!}</option>
		@endforeach
		</select>
	</div>
	<form method="POST" action="{{ route('admin-role.membersupdate', $iRecordId) }}">
	@csrf
	@method('PUT')
	<input style="display:none" name="id" id="id" value="{{$iRecordId}}" />
	<input style="display:none" name="memberof" id="memberof" />
	<input style="display:none" name="notmemberof" id="notmemberof" />
	<div class="col-span-7 text-right py-6">
		<x-button>{{ __('Update') }}</x-button>
	</div>

</form>


</div>
</div>
<x-app.header-height one="{!! $sHeaderHTML !!}" two="" />
</x-app-layout>

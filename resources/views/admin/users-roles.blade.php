<?php
$aVals = [];
foreach ($aSrvFormFields as $oF){
	$aVals[] = $aSrvData[$oF['dbfield']];
}
$aRM = [];
$aRN = [];
foreach ($aSrvData['roles'] as $oR){
	$bF = 0;
	foreach ($aSrvData['userroles'] as $oUR){
		if ($oUR->role_id == $oR->id){
			$bF = 1;
		}
	}
	if ($bF){
		$aRM[] = [$oR->id, $oR->name];
	} else {
		$aRN[] = [$oR->id, $oR->name];
	}
}
$iRecordId = $aSrvData['id'];
$aButtons = [
];
$sButtons = json_encode($aButtons);
ob_start();?>
<x-grid.header
title="Access Roles: {!! $aVals[0] !!}, {!! $aVals[1] !!}"  
route="admin-users"
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
		<select id="selmember" multiple class="w-full h-64">
		@foreach ($aRM as $aR)
			<option value="{{ $aR[0] }}">{!! $aR[1] !!}</option>
		@endforeach
		</select>
	</div>
	<div class="text-center">
		<div class="my-8">
			<button type="button" onclick="ROL.member(0);" class="border-greydark rounded-md border px-2">>></button>
		</div>
		<br>
		<div class="">
			<button type="button" onclick="ROL.member(1);" class="border-greydark rounded-md border px-2"><<</button>
		</div>
	</div>
	<div class="col-span-3">
		<div>Not Member Of</div>
		<select id="selnotmember" multiple class="w-full h-64">
		@foreach ($aRN as $aR)
			<option value="{{ $aR[0] }}">{!! $aR[1] !!}</option>
		@endforeach
		</select>
	</div>
	<form method="POST" action="{{ route('admin-users.rolesupdate', $iRecordId) }}">
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
<script>ROL.init();</script>

<?php
$aVals = [];
foreach ($aSrvFormFields as $oF){
	$aVals[] = $aSrvData[$oF['dbfield']];
}
$aRM = [];
$aRN = [];
foreach ($aSrvData['users'] as $oR){
	$bF = 0;
	foreach ($aSrvData['userspermissions'] as $oUR){
		if ($oUR->user_id == $oR->id){
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
title="Access Permission: {!! $aVals[0] !!}, {!! $aVals[1] !!}"  
route="users"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();

$iField = 0;
?>
<x-app-layout>
<script>
	console.log(123)
var ROL = {
V: {
	aMemberOf: [],
	aNotMemberOf: [],
},



member: function(bMember){
	var aM, aN, eA, eB, eO, eP, iI, iSM, iSN;
	iSM = 0;
	iSN = 0;
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	if (bMember){
		if (iSN){
			eO = eB.options[iSN - 1];
			eP = APP.ele(eA, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	} else {
		if (iSM){
			eO = eA.options[iSM - 1];
			eP = APP.ele(eB, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	}
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	ROL.V.aMemberOf = aM;
	ROL.V.aNotMemberOf = aN;
	APP.dg("memberof").value = JSON.stringify(aM);
	APP.dg("notmemberof").value = JSON.stringify(aN);
},


};
</script>

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
	<form method="POST" action="{{ route('permission.usersupdate', $iRecordId) }}">
	@csrf
	@method('PUT')
	<input style="display:none" name="id" id="id" value="{{$iRecordId}}" />
	<input style="display:none" name="memberof" id="memberof" />
	<input style="display:none" name="notmemberof" id="notmemberof" />
	<div class="col-span-6 text-right py-6">
		<x-button>{{ __('Update') }}</x-button>
	</div>

</form>


</div>
</div>
<x-app.header-height one="{!! $sHeaderHTML !!}" two="" />
</x-app-layout>

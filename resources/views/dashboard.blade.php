<?php
$aTabs = [
	'processing',
	'header',
	'anomalies',
	'recon',
	'transactions',
	'balances',
	'income',
];
$oUploadField = [
	'name' => 'Upload a PDF Bank Statement',
	'dbtable' => 'statements',
	'dbfield' => 'statement',
	'type' => 'upload',
	'description' => 'Statement',
	'job' => 'statement',
	'status' => 0,
	'no_label' => 1,
	'tab' => 3,
];
$oLoadButton = [
	'name' => 'load',
	'no_label' => 1,
	'description' => 'Load',
	'type' => 'button',
	'dbfield' => 'statement',
	'id' => 'statement_load',
	'onclick' => 'OCR.statementload()',
];
$oSaveButton = [
	'name' => 'save',
	'no_label' => 1,
	'description' => 'Save',
	'type' => 'button',
	'dbfield' => 'statement',
	'id' => 'statement_save',
	'onclick' => 'OCR.statementsave()',
];
$aFieldsEdit = [
	[
		'name' => 'Date',
		'dbfield' => 'edit_date',
		'type' => 'text',
	],
	[
		'name' => 'Description',
		'dbfield' => 'edit_description',
		'type' => 'text',
	],
	[
		'name' => 'Amount In',
		'dbfield' => 'edit_amount_in',
		'type' => 'number',
	],
	[
		'name' => 'Amount Out',
		'dbfield' => 'edit_amount_out',
		'type' => 'number',
	],
	[
		'name' => 'Balance',
		'dbfield' => 'edit_balance',
		'type' => 'number',
	],
	[
		'name' => 'OCR In',
		'dbfield' => 'edit_ocr_in',
		'type' => 'number',
		'display_none' => 1,
		'no_label' => 1,
	],
	[
		'name' => 'OCR Out',
		'dbfield' => 'edit_ocr_out',
		'type' => 'number',
		'display_none' => 1,
		'no_label' => 1,
	],
	[
		'name' => 'OCR Balance',
		'dbfield' => 'edit_ocr_balance',
		'type' => 'number',
		'display_none' => 1,
		'no_label' => 1,
	],
	[
		'name' => 'load',
		'no_label' => 1,
		'description' => 'Save',
		'type' => 'button',
		'dbfield' => 'edit_save',
		'id' => 'statement_load',
		'onclick' => 'OCR.edit_save()',
	],
	[
		'name' => 'load',
		'no_label' => 1,
		'description' => 'Cancel',
		'type' => 'button',
		'dbfield' => 'edit_cancel',
		'id' => 'statement_load',
		'onclick' => 'OCR.edit_cancel()',
	],
];


?>
<x-app-layout>


	<div class="pb-12">
		<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
			<div class="overflow-hidden shadow-xl sm:rounded-lg">
				</div>
				<div class="p-6 lg:p-8">
	<div><div class="float-left block w-20 h-12"><x-icons.logo-acc-name size="8" color="#0508a2, #fff, #076ad0, #faa51f, #a01929, #000" /></div>
	<div class=" mt-4 float-left text-xl font-medium text-dark">{{ config('app.name') }}</div></div>
	<div style="clear:both"></div>

				</div>

<div class="border-b border-greydark h-[30px]">

@for ($iI = 0; $iI < sizeof($aTabs); $iI++)
<div id="tabbutton_{{ $iI + 1 }}" class="text-sm float-left cursor-pointer border border-greydark py-1 px-5 rounded-t-md bg-greylight text-dark" onclick="OCR.tabpage(event)">{{ ucfirst($aTabs[$iI]) }}
</div>
@endfor
<div class="clear-both"></div>
@for ($iI = 0; $iI < sizeof($aTabs); $iI++)
<div id="tabpage_{{ $iI + 1 }}" class="tabpage w-full my-4" style="display:none;">

@if (!$iI)
<div class="bg-greylight bg-opacity-25 grid grid-cols-[80%_20%] p-6">
	<div class="col-span-2">
		<div class="float-left mr-4">
			<input type="text" id="folder" 
				
				
				
				value="" />
			
			
			
			
		</div>
		<div class="float-left">
			<?= formfieldshow($oLoadButton, '', Auth::user())?>
		</div>
		<div class="float-left">
			<?= formfieldshow($oSaveButton, '', Auth::user())?>
		</div>
		<br>
	</div>
	<div id="uploadbox" class="my-4">
		<?= formfieldshow($oUploadField, '', Auth::user())?>
	</div>
	<div id="processingbox" style="display:none" class="col-span-2 mb-4">
		<div id="processprogress" class="mt-4">
		<div>
			<div class="float-left">
				<?= formfieldshow([
					'name' => 'process',
					'no_label' => 1,
					'type' => 'button',
					'id' => 'btn_action',
					'description' => 'Process',
					'dbfield' => 'statement',
					'onclick' => 'OCR.start();',
				], 'button', Auth::user()) ?></div></div><div id="process_progress" class="float-left mb-2">&nbsp;&nbsp;Processing</div><div style="clear:both;height:20px"></div>
		</div><div id="log"></div>
	</div>
	<div class="col-span-2">
		<div id="preview"></div>
	</div>
	<div></div>
</div>
@endif

@if ($iI == 1)
<div id="grid_header"></div>
@endif

@if ($iI == 2)
<div id="grid_anomalies"></div>
@endif

@if ($iI == 3)
<div id="grid_recon"></div>
@endif

@if ($iI == 4)
<div id="grid_data"></div>
@endif

@if ($iI == 5)
<div id="grid_balances"></div>
@endif

@if ($iI == 6)
<div id="grid_salaries"></div>
@endif

</div>
@endfor

<x-app.header-height one="" two="" />
</x-app-layout>

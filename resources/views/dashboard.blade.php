<?php
$oUploadField = [
	'name' => 'Upload a PDF Bank Statement',
	'dbtable' => 'statements',
	'dbfield' => 'statement',
	'type' => 'upload',
	'description' => 'Bank Statement',
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
	'onclick' => 'OCR.load()',
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
				<div class="p-6 lg:p-8 border-b border-greylight">
	<div><div class="float-left block w-24"><x-icons.logo-acc-name :size="16" color="#0508a2, #fff, #076ad0, #faa51f, #a01929, #000" /></div>
	<div class="ml-12 mt-12 float-left text-6xl md:text-4xl sm:text-3xl font-medium text-dark">{{ config('app.name') }}</div></div>
	<div style="clear:both"></div>

				</div>
<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="processing_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg h-48">Processing</div>
	</div>
	<div class="bg-greylight bg-opacity-25 grid grid-cols-1 md:grid-cols-2  p-6 lg:p-8">
		<div class="col-span-2">
			<div class="float-left mr-4">
				<input type="text" id="folder" 
				
				
				
				value="0fnb" />
			
			
			
			
			</div>
			<div class="float-left">
				<?= formfieldshow($oLoadButton, '', Auth::user())?>
			</div>
			<br>
		</div>
		<div id="uploadbox" class="mt-4">
			<?= formfieldshow($oUploadField, '', Auth::user())?>
		</div>
		<div id="processingbox" style="display:none" class="col-span-2 h-48">
			<div>Processing</div>
			<div>
				<?= formfieldshow([
					'name' => 'process',
					'no_label' => 1,
					'type' => 'button',
					'id' => 'btn_action',
					'description' => 'Process',
					'dbfield' => 'statement',
					'onclick' => 'OCR.start();',
					], 'button', Auth::user()) ?>
				
			</div>
			<div id="log"></div>
		</div>
		<div class="col-span-2">
			<div id="preview"></div>
		</div>
	<div>
	</div>
	</div>
</div>
	
<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="header_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Header</div>
	</div>
	<div id="grid_header"></div>
</div>

<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="anomalies_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Anomalies</div>
	</div>
	<div id="grid_anomalies"></div>
</div>
<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="recon_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Recon</div>
	</div>
	<div id="grid_recon"></div>
</div>
<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="data_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Transactions</div>
	</div>
	<div id="grid_data"></div>
</div>

<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="data_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Opening and Closing Balances</div>
	</div>
	<div id="grid_balances"></div>
</div>

<div class="mt-4 border border-greydark rounded-md overflow-hidden" id="data_section">
	<div onclick="OCR.section(event)" class="cursor-pointer bg-red h-10 py-2 px-4">
		<div class="text-lg">Salaries</div>
	</div>
	<div id="grid_salaries"></div>
</div>

</div>

<div class="absolute w-96  border border-greydark rounded-md bg-light mx-auto" style="display:none" id="transaction_edit">
<div class="w-full h-12 px-4 py-2 bg-yellow">Edit Row</div>
<div class="p-8 pt-2">
<?php
foreach ($aFieldsEdit as $oF){
	echo formfieldshow($oF, '', Auth::user());
}
?>

</div>
</div>

	</div>
	

<x-app.header-height one="" two="" />
</x-app-layout>

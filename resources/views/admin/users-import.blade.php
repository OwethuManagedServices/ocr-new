<?php
$aVals = [
	'',
	'',
	'',
	'',
	'',
];
$aButtons = [];
$sButtons = json_encode(($aButtons));
ob_start();?>
<x-grid.header 
title="Import Users Spreadsheet" 
route="users"
buttons="{!! $sButtons !!}"
/>
<?php $sHeaderHTML = ob_get_clean();
?>
<x-app-layout>
<div class="max-w-7xl w-full shadow-xl rounded-md px-4 py-8">
	<div class="bg-light grid grid-cols-6 max-w-lg">
		<div class="text-sm font-semibold col-span-5">Users Spreadsheet File</div>
		<div class="float-right" id="accwaitspinupload"></div>
	
		<div class="mt-2 col-span-3 pb-6">
			<label class="wd-16 mx-1 bg-blue hover:bg-bluemedium text-light font-medium py-[6px] px-6 rounded-md" for="acc_fileupload_users">
			<span>Select File</span>
			</label>
			<input type="file" id="acc_fileupload_users" 
				style="opacity:0;width:1px;margin:0 30px 0 -30px;"
				onchange="MBF.selected(event)" />
			<span id="acc_fileupload_users_selected"></span>
		</div><div colspan="3"></div>

		<div class="col-span-3">
			<button id="acc_upload_users" class="mx-1 mt-2 bg-blue hover:bg-bluemedium text-light font-medium py-1 px-6 rounded-md"	type="button" onclick="MBF.uploadstart(event)">Upload Spreadsheet</button>
		</div><div colspan="3"></div>
		<div class="col-span-6">
			<div style="margin-top:20px;">
				<div id="acc_uploadprogress_users" class="accprogress">
					<div id="acc_uploadprogressbar_users" class="accprogressbar"></div>
				</div>
			</div>
		</div>
		<div class="col-span-6 accuploadstatus" id="acc_uploadstatus_users"></div>
	</div>
<x-app.header-height one="{!! $sHeaderHTML !!}" two="" />
</x-app-layout>

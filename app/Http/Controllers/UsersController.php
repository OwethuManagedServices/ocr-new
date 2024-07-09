<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Role;
use App\Models\DeletedRecords;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;

use function Laravel\Prompts\error;

class UsersController extends Controller
{
	public function import(){
		return view('admin.users-import', [
			'users' => 'import',
		]);	
	}
	
	
	
	public function importspreadsheet($oData){
	//public function importer(){
		$sFN = $oData['fullpath'];
	//	$sFN = '/usr/local/www/Code/accom/resources/docs/spreadsheets/companies/'.
	//		'companylist-7.xlsx';
		$spreadsheet = IOFactory::load($sFN);
		$sheet        = $spreadsheet->getActiveSheet();
		$row_limit    = $sheet->getHighestDataRow();
		$row_range    = range( 1, $row_limit );
		$startcount = 1;
		$data = array();
		$iUTime = microtime(1) * 10000;
		$aCols = [];
		foreach ( $row_range as $row ) {
			if ($startcount == 1){
				$bF = 0;
				$iChar = 65;
				while (!$bF){
					$aCols[] = $sheet->getCell( chr($iChar) . $row )->getValue();
					$iChar++;
					if ($iChar > ord('J')){
						$bF = 1;
					}
				}		
			} else {
				$aRow = [];
				$iChar = 65;
				foreach ($aCols as $sCol){
					$aRow[$sCol] = $sheet->getCell( chr($iChar) . $row )->getValue();
					$iChar++;
				}
				$data[] = $aRow;
				$oVal = [
					'category' => 'companies',
					'run_time' => $iUTime,
					'key' => $startcount,
					'value' => json_encode($aRow),
				];
				DB::table('temp_key_value')->insert($oVal);
			}
			$startcount++;
		}
		$oData['records'] = DB
			::table('temp_key_value')
			->where('run_time', '=', $iUTime)
			->get();
		return $oData;
	}
	
	
/*
	Show grid page
*/
public function index(Request $oRequest){		
	if ($oRequest->sort){
		$aSort = explode('-', $oRequest->sort);
		// Choose the default sorting if empty or not recognized
		if (!in_array($aSort[0], Users::$sortable)){
			$sSort = Users::$sortable[0];
			$sDir = ' asc';
		} else {
			$sSort = $aSort[0];
			if ((isset($aSort[1])) && (in_array($aSort[1], ['a', 'd']))){
				if ($aSort[1] == 'a'){
					$sDir = ' asc';
				} else {
					$sDir = ' desc';
				}
			}
		}
		$sSort .= $sDir;
	} else {
		$sSort = Users::$sortable[0] . ' asc';
	}
	$iPaginate = Auth::user()->grid_items_per_page;
	if (isset($oRequest->search)){
		$sV = '%' . strtolower($oRequest->search) . '%';
		$oData = Users
			::select('*')
			->where('id', 'LIKE', $sV)
			->orWhere('name', 'LIKE', $sV)
			->orWhere('email', 'LIKE', $sV)
			->orWhere('created_at', 'LIKE', $sV)
			->orderByRaw($sSort)
			->paginate($iPaginate, '*', 'pg')
			->appends(['sort' => urldecode($oRequest->sort), 
				'search' => urldecode($oRequest->search)]);
	} else {
		$oData = Users
			::select('*')
			->orderByRaw($sSort)
			->paginate($iPaginate, '*', 'pg')
			->appends(['sort' => urldecode($oRequest->sort), 
			'search' => urldecode($oRequest->search)]);
	}
	return view('admin.users', [
		'aSrvData' => $oData,
		'aSrvGrid' => Users::$grid,
		'aSrvFormFields' => Users::$formfields,
		'sSrvSort' => $sSort,
	]);
}




	/**
	 * Show the form for creating a new resource.
	 */
	public function create(){
		return view('admin.users-create', [
			'formfields' => Users::$formfields,
		]);
	}



	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $oRequest)
	{
		$oRec = [
			'name' => $oRequest->name,
			'email' => $oRequest->email,
			'password' => Hash::make($oRequest->password),

		];
		if ($oRequest->email_verified_at){
			$oRec['email_verified_at'] = date('Y-m-d h:i:s');
		}
		$oRec = Users::create($oRec);
		return redirect(route('admin-users.index'));

	}

	/**
	 * Display the specified resource.
	 */
	public function show(Users $users)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Request $oRequest){
		$iId = intval($oRequest->admin_user);
		// SQL query
		$oData = Users
			::where('id', '=', $iId)
			->get();
		if (!isset($oData[0])){
			$oRet = [
				'error' => 1,
				'message' => 'Record not found',
			];
		} else {
			$oRet = $oData[0];
			$aC = explode(' ', $oRet['created_at']);
			$oRet['date'] = $aC[0];
			$oRet['time'] = $aC[1];
		}
		// Send to view
		return view('admin.users-edit', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Users::$formfields,
		]);
	}
	
	/**
	 * Show the roles form for editing the user's roles.
	 */
	public function roles(Request $oRequest){
		$iId = intval($oRequest->admin_user);
		// SQL query
		$oData = Users
			::where('id', '=', $iId)
			->get();
		if (!isset($oData[0])){
			$oRet = [
				'error' => 1,
				'message' => 'Record not found',
			];
		} else {
			$oRet = $oData[0];
			$aC = explode(' ', $oRet['created_at']);
			$oRet['date'] = $aC[0];
			$oRet['time'] = $aC[1];
			$oRet['roles'] = Role::get();
			$oRet['userroles'] = UserRole::where('user_id', '=', $iId)->get();
 		}
		// Send to view
		return view('admin.users-roles', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Users::$formfields,
		]);
	}
	


	public function rolesupdate(Request $oRequest){
		$aM = json_decode($oRequest->memberof);
//		echo json_encode($aM).'<br><br>';
		$aN = json_decode($oRequest->notmemberof);
//		echo json_encode($aN);die;
		$iUserId = $oRequest->admin_user;
		foreach ($aM as $aR){
			$aUR = UserRole::where('user_id', '=', $iUserId)
				->where('role_id', '=', $aR[0])->get();
			if (!isset($aUR[0])){
				$oUR = new UserRole;
				$oUR['user_id'] = $iUserId;
				$oUR['role_id'] = $aR[0];
				$oUR->save();
			}
		}
		foreach ($aN as $aR){
			$aUR = UserRole::where('user_id', '=', $iUserId)
				->where('role_id', '=', $aR[0])->get();
			if (isset($aUR[0])){
				UserRole::where('user_id', '=', $iUserId)
					->where('role_id', '=', $aUR[0]->role_id)->delete();
			}
		}



		return redirect(route('admin-users.roles', $iUserId))->with('message', 'Updated');
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $oRequest){
		$iId = intval($oRequest->id);

		if ($iId){
			$aRules = [
			];
			$oRequest->validate($aRules);
			$oData = Users
				::where('id', '=', $iId)
				->get();
			$oRecord = Users::find($iId);
			$oRecord->email = $oRequest['email'];
			$oRecord->name = $oRequest['name'];
			if ($oRequest->password){
				$oRecord->password = Hash::make($oRequest->password);
			}
//			$oRecord->usergroup = $oRequest['usergroup'];
			$oRecord->save();
			$sMsg = 'Successful edit';
		} else {
			$sMsg = 'Failed edit';
		}
		return redirect(route('admin-users.edit', $iId))->with('message', $sMsg);
	}
	

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Request $oRequest)
	{
		$iId = intval($oRequest->admin_user);
		$aC = Users::where('id', '=', $iId)->get();
		if (isset($aC[0])){
			$oDR = new DeletedRecords();
			$oDR->user_id = Auth::user()->id;
			$oDR->table_name = 'users';
			$oDR->contents = json_encode($aC[0]);
			$oDR->save();
			Users::where('id', '=', $iId)->delete();
		}
		return redirect(route('admin-users.index'));
	}



}

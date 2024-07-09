<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $oRequest){		
		if ($oRequest->sort){
			$aSort = explode('-', $oRequest->sort);
			// Choose the default sorting if empty or not recognized
			if (!in_array($aSort[0], Role::$sortable)){
				$sSort = Role::$sortable[0];
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
			$sSort = Role::$sortable[0] . ' asc';
		}
		$iPaginate = Auth::user()->grid_items_per_page;
		if (isset($oRequest->search)){
			$sV = '%' . strtolower($oRequest->search) . '%';
			$oData = Role
				::select('*')
				->where('id', 'LIKE', $sV)
				->orWhere('name', 'LIKE', $sV)
				->orWhere('slug', 'LIKE', $sV)
				->orderByRaw($sSort)
				->paginate($iPaginate, '*', 'pg')
				->appends(['sort' => urldecode($oRequest->sort), 
					'search' => urldecode($oRequest->search)]);
		} else {
			$oData = Role
				::select('*')
				->orderByRaw($sSort)
				->paginate($iPaginate, '*', 'pg')
				->appends(['sort' => urldecode($oRequest->sort), 
				'search' => urldecode($oRequest->search)]);
		}
		return view('admin.roles', [
			'aSrvData' => $oData,
			'aSrvGrid' => Role::$grid,
			'aSrvFormFields' => Role::$formfields,
			'sSrvSort' => $sSort,
		]);
	}
	
	
	
	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('admin.role-create', [
			'formfields' => Role::$formfields,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $oRequest)
	{
		$oRL = new Role;
		$oRL['name'] = $oRequest->name;
		$oRL['slug'] = $oRequest->slug;
		$oRL->save();
		return redirect(route('admin-role.index'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Request $oRequest){
	$iId = intval($oRequest->admin_role);
	// SQL query
	$oData = Role
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
	return view('admin.role-edit', [
		'aSrvData' => $oRet,
		'aSrvFormFields' => Role::$formfields,
	]);
}




	
	/**
	 * Show the form for editing the specified resource.
	 */
	public function editmembers(Request $oRequest)
	{
		$iId = intval($oRequest->admin_role);
		// SQL query
		$oData = Role
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
			$oRet['permissions'] = Permission::get();
			$oRet['rolepermissions'] = RolePermission::where('role_id', '=', $iId)->get();
//			echo json_encode($oRet);die;
		}
		// Send to view
		return view('admin.roles-edit', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Role::$formfields,
		]);

	}

	
	public function update(Request $oRequest){
		$iId = intval($oRequest->admin_role);
	
		if ($iId){
			$oRecord = Role::find($iId);
			$oRecord->name = $oRequest['name'];
			$oRecord->slug = $oRequest['slug'];
			$oRecord->save();
			$sMsg = 'Successful edit';
		} else {
			$sMsg = 'Failed edit';
		}
		return redirect(route('admin-role.edit', $iId))->with('message', $sMsg);
	}
		
	/**
	 * Update the specified resource in storage.
	 */
	public function updatemember(Request $oRequest)
	{
		$aM = json_decode($oRequest->memberof);
		$iId = $oRequest->admin_role;
		$aRP = RolePermission::where('role_id', '=', $iId)->get();
//		RolePermission::where('role_id', '=', $iId)->delete();
		foreach ($aM as $aR){
			$iF = 0;
			$iI = 0;
			foreach ($aRP as $oRP){
				if ($oRP->role_id == $aR[0]){
					$iF = $iI + 1;
				} else {
					$iI++;
				}
			}
			if ($iF){
				$aR = RolePermission::where('role_id', '=', $iId)->update(['permission_id' => $aR[0]]);
			} else {
				$oUR = new RolePermission();
				$oUR['role_id'] = $iId;
				$oUR['permission_id'] = $aR[0];
				$oUR->save();	
			}
		}
		return redirect(route('admin-role.members', $iId))->with('message', 'Updated');

	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}

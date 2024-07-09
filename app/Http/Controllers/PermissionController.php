<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserPermission;
use App\Models\Role;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $oRequest){		
		if ($oRequest->sort){
			$aSort = explode('-', $oRequest->sort);
			// Choose the default sorting if empty or not recognized
			if (!in_array($aSort[0], Permission::$sortable)){
				$sSort = Permission::$sortable[0];
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
			$sSort = Permission::$sortable[0] . ' asc';
		}
		$iPaginate = Auth::user()->grid_items_per_page;
		if (isset($oRequest->search)){
			$sV = '%' . strtolower($oRequest->search) . '%';
			$oData = Permission
				::select('*')
				->where('id', 'LIKE', $sV)
				->orWhere('name', 'LIKE', $sV)
				->orWhere('slug', 'LIKE', $sV)
				->orderByRaw($sSort)
				->paginate($iPaginate, '*', 'pg')
				->appends(['sort' => urldecode($oRequest->sort), 
					'search' => urldecode($oRequest->search)]);
		} else {
			$oData = Permission
				::select('*')
				->orderByRaw($sSort)
				->paginate($iPaginate, '*', 'pg')
				->appends(['sort' => urldecode($oRequest->sort), 
				'search' => urldecode($oRequest->search)]);
		}
		return view('admin.permissions', [
			'aSrvData' => $oData,
			'aSrvGrid' => Permission::$grid,
			'aSrvFormFields' => Permission::$formfields,
			'sSrvSort' => $sSort,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		return view('admin.permission-create', [
			'formfields' => Permission::$formfields,
		]);

	}


	public function routesupdate(){
		$routes = Route::getRoutes()->getRoutes();

        foreach ($routes as $route) {
            if ($route->getName() != '' && $route->getAction()['middleware']['0'] == 'web') {
                $permission = Permission::where('name', $route->getName())->first();

                if (is_null($permission)) {
					$aLO = [
'login', 'verification.notice', 'verification.verify', 'verification.send', 'password.request', 'password.reset', 'password.email', 'password.update', 'register', 'home', 'home-about-us', 'home-contact-us', 'home-contact-us-store', 'home-cyber-awareness-training', 
'home-cyber-security-training', 'home-dark-web-recognisance', 'home-free-human-risk-report', 'home-free-human-risk-report-store', 'home-free-human-risk-report-verify', 'home-free-humanrisk-report-verifylink', 'home-free-trial', 'home-free-trial-store', 'home-free-trial-verify', 'home-free-trial-verifylink', 'home-human-risk-management', 'home-human-risk-reporting', 'home-our-products', 'home-our-services', 'home-policy-management-suite', 'home-simulated-phishing-attacks', 'payment-gateway', 'payment-cancel', 'payment-failure', 'payment-success', 'payment-webhook', 'policy.show', 'terms.show', 'policy-show', 'terms-show', 'register', 
];
					$sRN = $route->getName();
					$bF = 0;
					foreach ($aLO as $sLO){
						if ($sRN == $sLO){
							$bF = 1;
						}
					}
					if (!$bF){
                    	permission::create(['name' => $sRN, 'slug' => 'route-web']);
					}
                }
            }
        }
		return redirect(route('admin-permission.index'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $oRequest)
	{
		$oRec = [
			'name' => $oRequest->name,
			'slug' => $oRequest->slug,
		];
		$oRec = Permission::create($oRec);
		return redirect(route('admin-permission.index'));


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
		$iId = intval($oRequest->admin_permission);
		// SQL query
		$oData = Permission
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
		return view('admin.permission-edit', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Permission::$formfields,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
public function update(Request $oRequest){
	$iId = intval($oRequest->admin_permission);

	if ($iId){
		$oRecord = Permission::find($iId);
		$oRecord->name = $oRequest['name'];
		$oRecord->slug = $oRequest['slug'];
		$oRecord->save();
		$sMsg = 'Successful edit';
	} else {
		$sMsg = 'Failed edit';
	}
	return redirect(route('admin-permission.edit', $iId))->with('message', $sMsg);
}
	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Request $oRequest)
	{
		$iId = intval($oRequest->admin_permission);
		if ($iId){
			$oRecord = Permission::find($iId);


			$aRP = RolePermission::where('permission_id', '=', $iId)->get();
			foreach ($aRP as $oRP){
				$oRP->delete();
			}


			$oRecord->delete();
			$sMsg = 'Successful deletion';
		} else {
			$sMsg = 'Failed deletion';
		}
		return redirect(route('admin-permission.index'));
	}


	public function roles(Request $oRequest){
		$iId = intval($oRequest->admin_permission);
		// SQL query
		$oData = Permission
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
			$oRet['rolespermissions'] = RolePermission::where('permission_id', '=', $iId)->get();
 		}
		// Send to view
		return view('admin.permission-roles', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Permission::$formfields,
		]);
	}
	


	public function users(Request $oRequest){
		$iId = intval($oRequest->admin_permission);
		// SQL query
		$oData = Permission
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
			$oRet['users'] = Users::get();
			$oRet['userspermissions'] = UserPermission::where('permission_id', '=', $iId)->get();
 		}
//	echo json_encode($oRet);die;
		// Send to view
		return view('admin.permission-users', [
			'aSrvData' => $oRet,
			'aSrvFormFields' => Permission::$formfields,
		]);
	}
	


	public function rolesupdate(Request $oRequest){
		$aM = json_decode($oRequest->memberof);
		$aN = json_decode($oRequest->notmemberof);
		$iId = $oRequest->admin_permission;
		$aUR = RolePermission::where('permission_id', '=', $iId)->get();
		return redirect(route('permission.roles', $iId))->with('message', 'Updated');
	}



}

<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{

/**
 * Display a listing of the resource.
 */
public function index(Request $oRequest){
	if ($oRequest->sort){
		$aSort = explode('-', $oRequest->sort);
		// Choose the default sorting if empty or not recognized
		if (!in_array($aSort[0], Website::$sortable)){
			$sSort = Website::$sortable[0];
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
		$sSort = Website::$sortable[0] . ' asc';
	}
	$iPaginate = Auth::user()->grid_items_per_page;
	if (isset($oRequest->search)){
		$sV = '%' . strtolower($oRequest->search) . '%';
		$oData = Website
			::select('*')
			->where('id', 'LIKE', $sV)
			->orWhere('language', 'LIKE', $sV)
			->orWhere('category', 'LIKE', $sV)
			->orWhere('key', 'LIKE', $sV)
			->orWhere('value', 'LIKE', $sV)
			->orderByRaw($sSort)
			->paginate($iPaginate, '*', 'pg')
			->appends(['sort' => urldecode($oRequest->sort), 
				'search' => urldecode($oRequest->search)]);
	} else {
		$oData = Website
			::select('*')
			->orderByRaw($sSort)
			->paginate($iPaginate, '*', 'pg')
			->appends(['sort' => urldecode($oRequest->sort), 
			'search' => urldecode($oRequest->search)]);
	}
	return view('frontend.website', [
		'aSrvData' => $oData,
		'aSrvGrid' => Website::$grid,
		'aSrvFormFields' => Website::$formfields,
		'sSrvSort' => $sSort,
	]);
}



	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Website $website)
	{
		//
	}

/*
	Show Edit page
*/
public function edit(Request $oRequest){
	$iId = intval($oRequest->frontend_website);
	// SQL query
	$oData = website
		::select('*')
		->where('id', '=', $iId)
		->get();
	if (!isset($oData[0])){
		$oRet = [
			'error' => 1,
			'message' => 'Record not found',
		];
	} else {
		$oRet = $oData[0];
	}
	// Send to view
	return view('frontend.website-edit', [
		'aSrvData' => $oRet,
		'aSrvFormFields' => website::$formfields,
	]);
}



/*
	Update record
*/
public function update(Request $oRequest){
	$iId = intval($oRequest->id);
	$oVals = [];
	if ($iId){
		$aRules = [
		];
		$oRequest->validate($aRules);
		$oWebsite = Website::find($iId);
		$oVals = json_decode($oWebsite->value, 1);
		$sKeys = explode('||', $oRequest->keys)[1];
		$sRVal = $oRequest->realvalue;
		$oVals[$sKeys] = $sRVal;
		$oWebsite->value = json_encode($oVals, JSON_PRETTY_PRINT);
		$oWebsite->save();
	}
	$oData = website
	::select('*')
	->where('id', '=', $iId)
	->get();

	// Send to view
	return view('frontend.website-edit', [
		'iKey' => explode('||', $oRequest->keys)[0],
		'aSrvData' => $oData[0],
		'aSrvFormFields' => website::$formfields,
	]);
	
//	return redirect(route('website.edit', $iId))->with('data', $oData);
}



	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Website $website)
	{
		//
	}
}

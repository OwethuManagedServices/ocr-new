<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\ClientEmployees;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\User;
use App\Models\HumanRiskReports;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{

public function index(){

	$sHtmlClass = Auth::user()->html_theme;
	if ($sHtmlClass == 'dark'){
		$aBGAll = [
			'#991b1b',
			'#d97706',
			'#facc15',
			'#3f6212',
			'#14532d',
			'#115e59',
			'#0e7490',
			'#1d4ed8',
			'#5b21b6',
			'#831843',
		];
		$aBorsAll = [
			"#CDA776",
			"#989898",
			"#CB252B",
			"#E39371",
			"#1D7A46",
			"#F4A460",
			"#CDA776",
		  ];
		  $aText = [
			'#e5e7eb',
		];

	} else {
		$aBGAll = [
			"#0EB887",
			"#09A9A9",
			"#0C143C",
			"#04A460",
			"#0E8B57",
			"#0D7A46",
			"#0DA776",
		];
		$aBorsAll = [
			"#C0A776",
			"#980898",
			"#0B252B",
			"#039371",
			"#FD7A46",
			"#04A460",
			"#0DA776",
		  ];
		$aText = [
			'#172554',
		];
	}

	$oData = [];
	if (Auth::user()->hasRole('admin-dashboard')){
		$oChart1 = [
			'label' => [],
			'data' => [],
			'title' => 'New Leads',
			'canvas_id' => 'chart-1',
			'chart_type' => 'line',
			'backgrounds' => $aBGAll,
			'borders' => $aBorsAll,
			'color' => $aText,
			'icon' => 'arrow-up',
		];
		$aRecs = Leads
			::where('id', '>', '11400')
			->get();
		foreach ($aRecs as $oRec){
			$oChart1['label'][] = strtoupper(substr(
				str_replace(' ', '', $oRec['company_name']), 0, 4));
			$oChart1['data'][] = intval($oRec['id']) - 10000 + rand(1, 10);
		}
		
		$oChart2 = [
			'label' => [],
			'data' => [],
			'legend' => 1,
			'title' => 'Assigned Agents',
			'chart_type' => 'doughnut',
			'canvas_id' => 'chart-2',
			'backgrounds' => $aBGAll,
			'borders' => $aBorsAll,
			'color' => $aText,
			'icon' => 'annotation',
		];
		$aRecs = HumanRiskReports
			::where('id', '>', '10000')
			->get();
		foreach ($aRecs as $oRec){
			$oChart2['label'][] = strtoupper(substr(
				str_replace(' ', '', $oRec['domain']), 0, 4));
			$oChart2['data'][] = intval($oRec['id']);
		}
		
		$oChart3 = [
			'label' => [],
			'data' => [],
			'title' => 'Lead Progression',
			'canvas_id' => 'chart-3',
			'chart_type' => 'bar',
			'backgrounds' => $aBGAll,
			'borders' => $aBorsAll,
			'color' => $aText,
			'icon' => 'arrow-left',
		];
		$aRecs = Leads
	
			::where('company_name', '>', 'X')
			->get();
		foreach ($aRecs as $oRec){
			$oChart3['label'][] = strtoupper(substr(
				str_replace(' ', '', $oRec['company_name']), 0, 4));
			$oChart3['data'][] = rand(40,100);
		}
	
		$oChart4 = [
			'label' => [],
			'data' => [],
			'title' => 'Assigned Domains',
			'canvas_id' => 'chart-4',
			'chart_type' => 'pie',
			'backgrounds' => $aBGAll,
			'borders' => $aBorsAll,
			'color' => $aText,
			'icon' => 'arrow-down',
		];
		$aRecs = Leads
			::where('company_name', '>', 'X')
			->get();
		foreach ($aRecs as $oRec){
			$oChart4['label'][] = strtoupper(substr(
				str_replace(' ', '', $oRec['company_name']), 0, 4));
			$oChart4['data'][] = rand(40,100);
		}	
		$oData['chart1'] = json_encode($oChart1);
		$oData['chart2'] = json_encode($oChart2);
		$oData['chart3'] = json_encode($oChart3);
		$oData['chart4'] = json_encode($oChart4);
		return view('dashboard')->with('data', $oData);
	}


	if (Auth::user()->hasRole('tenant-admin-dashboard')){
		$oClient = Clients::find(Auth::user()->client_id);
		$aEmployees = ClientEmployees
		::where('client_id', '=', $oClient->id)
		->get();
		$iQty = sizeof($aEmployees);
		$aTransactions = Transactions
		::where('client_id', '=', $oClient->id)
		->get();
		$iTotal = $aTransactions[0]->quantity;
		$oUser = Auth::user();
		$oData = [
			'show_welcome_screen' => $oUser->show_welcome_screen,
			'user_name' => $oUser->name,
			'enrolled' => $iQty,
			'total' => $iTotal,
			'client' => $oClient, 
			'employees_enrolled' => $aEmployees,
			'transactions' => $aTransactions,
		];
		if ($oData['enrolled'] > $oData['total']){
			$oData['enrolled'] .= ' OVERLOADED';
			$iInActive = 0;
			foreach ($aEmployees as $oE){
				if (!$oE['is_active']){
					$iInActive++;
				}
			}
			$oData['inactive'] = $iInActive;
		}
		return view('tenants/dashboard')->with('data', $oData);
	}
	return view('dashboard')->with('data', 'companyonly');
}


}

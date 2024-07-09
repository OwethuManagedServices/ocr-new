<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FreeTrialsController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HumanRiskReportsController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsecureController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebsiteController;

/*   LOGGED OUT   */

Route::get('/', [
	HomeController::class,
	'home'
	])->name('home');

Route::get('home-our-services', [
	HomeController::class, 
	'our_services'
	])->name('home-our-services');

Route::get('home-cyber-awareness-training', [
	HomeController::class, 
	'cyber_awareness_training'
	])->name('home-cyber-awareness-training');

Route::get('home-dark-web-recognisance', [
	HomeController::class, 
	'dark_web_recognisance'
	])->name('home-dark-web-recognisance');

Route::get('home-simulated-phishing-attacks',[
	HomeController::class, 
	'simulated_phishing_attacks'
	])->name('home-simulated-phishing-attacks');
			
Route::get('home-policy-management-suite', [
	HomeController::class, 
	'policy_management_suite'
	])->name('home-policy-management-suite');

Route::get('home-human-risk-reporting', [
	HomeController::class, 
	'human_risk_reporting'
	])->name('home-human-risk-reporting');
	
Route::get('home-our-products', [
	HomeController::class, 
	'our_products'
	])->name('home-our-products');

Route::get('home-our-products-order', [
	HomeController::class, 
	'our_products_order'
	])->name('home-our-products-order');

Route::post('home-our-products-payment', [
	PaymentController::class, 
	'start'
	])->name('home-our-products-payment');

Route::get('home-our-products-payment-gateway', [
	PaymentController::class,
	'gateway'
	])->name('home-our-products-payment-gateway');


Route::post('home-our-products-payment-register-store', [
	PaymentController::class,
	'payment_register_store'
	])->name('home-our-products-payment-register-store');

Route::get('home-about-us', [
	HomeController::class, 
	'about_us'
	])->name('home-about-us');
	
Route::post('home-about-us-newsletter-store', [
	HomeController::class, 
	'about_us_newsletter_store'
	])->name('home-about-us-newsletter-store');

Route::get('home-contact-us', [
	HomeController::class, 
	'contact_us'
	])->name('home-contact-us');

Route::post('home-contact-us-store', [
	HomeController::class, 
	'contact_us_store'
	])->name('home-contact-us-store');

Route::get('home-human-risk-management', [
	HomeController::class, 
	'human_risk_management'
	])->name('home-human-risk-management');

Route::get('home-cyber-security-training', [
	HomeController::class, 
	'cyber_security_training'
	])->name('home-cyber-security-training');

Route::get('home-free-trial', [
	HomeController::class, 
	'free_trial'
	])->name('home-free-trial');

Route::post('home-free-trial-store', [
	FreeTrialsController::class, 
	'store'
	])->name('home-free-trial-store');

Route::get('home-free-trial/{freetrial}/verify', [
	FreeTrialsController::class, 
	'verify'
	])->name('home-free-trial-verify');

Route::post('home-free-trial/verifylink', [
	FreeTrialsController::class, 
	'verifylink'
	])->name('home-free-trial-verifylink');

Route::get('home-free-human-risk-report', [
	HomeController::class, 
	'free_human_risk_report'
	])->name('home-free-human-risk-report');

Route::post('home-free-human-risk-report-store', [
	HumanRiskReportsController::class, 
	'store'
	])->name('home-free-human-risk-report-store');

Route::get('home-free-human-risk-report/{hrr_id}/verify', [
	HumanRiskReportsController::class, 
	'verify'
	])->name('home-free-human-risk-report-verify');

Route::get('home-free-human-risk-report-store/{hrr_id}/{link}/verify', [
	HumanRiskReportsController::class, 
	'verifylink'
	])->name('home-free-humanrisk-report-verifylink');

Route::get('documents-terms', [
	HomeController::class, 
	'terms_show'
	])->name('documents-terms');

Route::get('documents-policy', [
	HomeController::class, 
	'policy_show'
	])->name('documents-policy');

	

Route::post('/ajax', [
	GeneralController::class, 
	'ajax']);







/*   LOGGED IN   */

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function(){


Route::get('/resources/docs/logos/{filename}', function($filename){
	$path = resource_path() . '/docs/logos/' . $filename;
	if(!file_exists($path)) {
		return response()->json(['message' => 'Image not found.'], 404);
	}

	$file = File::get($path);
	$type = File::mimeType($path);

	$response = Response::make($file, 200);
	$response->header("Content-Type", $type);

	return $response;
});
	

Route::get('dashboard', [
	DashboardController::class, 
	'index'
	])->name('dashboard');



Route::group(['middleware' => 'role:admin-access-control'], function(){

	Route::resource('admin-role', 
		RoleController::class);

	Route::get('admin-role/{admin_role}/members', [
		RoleController::class, 
		'editmembers'
		])->name('admin-role.members');
	
	Route::put('admin-role/{admin_role}/members', [
		RoleController::class, 
		'updatemember'
		])->name('admin-role.membersupdate');
	
	Route::get('admin-roles/{admin_role}/members', [
		PermissionController::class, 
		'roles'
		])->name('admin-roles.members');
	
	Route::resource('admin-permission', 
		PermissionController::class);
	
	Route::get('admin-permission/routes/update', [
		PermissionController::class, 
		'routesupdate'
		])->name('admin-permission.routes.update');
	
	Route::get('admin-permission/{admin_permission}/roles', [
		PermissionController::class, 
		'roles'
		])->name('admin-permission.roles');
	
	Route::get('admin-permission/{admin_permission}/users', [
		PermissionController::class, 
		'users'
		])->name('admin-permission.users');
	
	Route::put('admin-permission/{admin_permission}/roles', [
		PermissionController::class, 
		'rolesupdate'
		])->name('admin-permission.rolesupdate');

});



Route::group(['middleware' => 'role:admin-users'], function(){

	Route::resource('admin-users', 
		UsersController::class);

	Route::get('admin-users/{admin_user}/roles', [
		UsersController::class, 
		'roles'
		])->name('admin-users.roles');

	Route::put('admin-users/{admin_user}/roles', [
		UsersController::class, 
		'rolesupdate'
		])->name('admin-users.rolesupdate');

	Route::get('admin-users/import', [
		UsersController::class, 
		'import'
		])->name('admin-users.import');
		


});



Route::group(['middleware' => 'role:clients-clients'], function(){

	Route::resource('clients-clients', 
		ClientsController::class);

});



Route::group(['middleware' => 'role:clients-leads'], function(){

	Route::resource('clients-leads', 
		LeadsController::class);

	Route::get('clients-leads/import', [
		LeadsController::class, 
		'import'
		])->name('clients-leads.import');
	
});



Route::group(['middleware' => 'role:clients-transactions'], function(){

	Route::resource('clients-transactions', 
		TransactionsController::class);
		
});



Route::group(['middleware' => 'role:frontend-contact-us'], function(){

	Route::get('frontend-contactus', [
		ContactUsController::class, 
		'index'
		])->name('frontend-contactus.index');

	Route::get('frontend-contactus/{frontend_contactus}/edit', [
		ContactUsController::class, 
		'edit'
		])->name('frontend-contactus.edit');

	Route::put('frontend-contactus/{frontend_contactus}', [
		ContactUsController::class, 
		'update'
		])->name('frontend-contactus.update');

});



Route::group(['middleware' => 'role:frontend-free-trials'], function(){

	Route::get('frontend-freetrials', [
		FreeTrialsController::class, 
		'index'
		])->name('frontend-freetrials.index');

	Route::get('frontend-freetrials/{frontend_freetrial}/edit', [
		FreeTrialsController::class, 
		'edit'
		])->name('frontend-freetrials.edit');

	Route::put('frontend-freetrials/{frontend_freetrial}', [
		FreeTrialsController::class, 
		'update'
		])->name('frontend-freetrials.update');

});



Route::group(['middleware' => 'role:frontend-human-risk-reports'], function(){

	Route::get('frontend-humanriskreports', [
		HumanRiskReportsController::class, 
		'index'
		])->name('frontend-humanriskreports.index');

	Route::get('frontend-humanriskreports/{frontend_humanriskreport}/edit', [
		HumanRiskReportsController::class, 
		'edit'
		])->name('frontend-humanriskreports.edit');

	Route::put('frontend-humanriskreports/{frontend_humanriskreport}', [
		HumanRiskReportsController::class, 
		'update'
		])->name('frontend-humanriskreports.update');
});



Route::group(['middleware' => 'role:frontend-website'], function(){

	Route::get('frontend-website', [
		WebsiteController::class, 
		'index'
		])->name('frontend-website.index');

	Route::get('frontend-website/{frontend_website}/edit', [
		WebsiteController::class, 
		'edit'
		])->name('frontend-website.edit');

	Route::put('frontend-website/{frontend_website}', [
		WebsiteController::class, 
		'update'
		])->name('frontend-website.update');
});



Route::group(['middleware' => 'role:usecure-admin'], function(){

	Route::get('usecure-admin', [
			UsecureController::class, 
			'adminindex'
		])->name('usecure-admin.index');

	Route::get('usecure-admin/apirefresh', [
			UsecureController::class, 
			'adminapirefresh'
		])->name('usecure-admin.apirefresh');

	Route::get('usecure-admin/{usecure_admin}/edit', [
			UsecureController::class, 
			'adminedit'
		])->name('usecure-admin.edit');

});



Route::group(['middleware' => 'role:usecure-courses'], function(){

	Route::get('usecure-courses', [
			UsecureController::class, 
			'coursesindex'
		])->name('usecure-courses.index');

	Route::get('usecure-courses/apirefresh', [
			UsecureController::class, 
			'coursesapirefresh'
		])->name('usecure-courses.apirefresh');

	Route::get('usecure-courses/{usecure_course}/edit', [
			UsecureController::class, 
			'coursesedit'
		])->name('usecure-courses.edit');

});



Route::group(['middleware' => 'role:usecure-learners'], function(){

	Route::get('usecure-learners', [
			UsecureController::class, 
			'learnersindex'
		])->name('usecure-learners.index');

	Route::get('usecure-learners/apirefresh', [
			UsecureController::class, 
			'learnersapirefresh'
		])->name('usecure-learners.apirefresh');

	Route::get('usecure-learners/{usecure_learner}/edit', [
			UsecureController::class, 
			'learnersedit'
		])->name('usecure-learners.edit');

});



Route::group(['middleware' => 'role:usecure-reports'], function(){

	Route::get('usecure-reports', [
			UsecureController::class, 
			'reportsindex'
		])->name('usecure-reports.index');

	Route::get('usecure-reports/apirefresh', [
			UsecureController::class, 
			'reportsapirefresh'
		])->name('usecure-reports.apirefresh');

	Route::get('usecure-reports/{usecure_report}/edit', [
			UsecureController::class, 
			'reportsedit'
		])->name('usecure-reports.edit');

});



Route::group(['middleware' => 'role:usecure-tenants'], function(){

	Route::get('usecure-tenants', [
			UsecureController::class, 
			'tenantsindex'
		])->name('usecure-tenants.index');

	Route::get('usecure-tenants/apirefresh', [
			UsecureController::class, 
			'tenantsapirefresh'
		])->name('usecure-tenants.apirefresh');

	Route::get('usecure-tenants/{usecure_tenant}/edit', [
			UsecureController::class, 
			'tenantsedit'
		])->name('usecure-tenants.edit');

	Route::put('usecure-tenants/{usecure_tenant}', [
		UsecureController::class, 
		'tenantsupdate'
		])->name('usecure-tenants.update');
	});



Route::get('help-index', [
	HelpController::class, 
	'index'
	])->name('help-index');

Route::get('help-about', [
	HelpController::class, 
	'about'
	])->name('help-about');

Route::get('help-acls', [
	HelpController::class, 
	'acls'
	])->name('help-acls');

Route::get('help-welcome-screen-on', [
	HelpController::class, 
	'welcome_screen_on'
	])->name('help-welcome-screen-on');

Route::get('tenant-admin-profile', [
	TenantsController::class, 
	'profile'
	])->name('tenant-admin-profile.index');
	
Route::put('tenant-admin-profile.update', [
	TenantsController::class, 
	'profile_update'
	])->name('tenant-admin-profile.update');

Route::get('tenant-admin-employees', [
	TenantsController::class, 
	'employees'
	])->name('tenant-admin-employees.index');
	
Route::get('tenant-admin-employees/{id}/edit', [
	TenantsController::class, 
	'employees_edit'
	])->name('tenant-admin-employees.edit');

Route::post('tenant-admin-employees.store', [
	TenantsController::class, 
	'employees_add'
	])->name('tenant-admin-employees.store');

Route::put('tenant-admin-employees/{id}', [
	TenantsController::class, 
	'employees_update'
	])->name('tenant-admin-employees.update');

Route::delete('tenant-admin-employees/{id}', [
	TenantsController::class, 
	'employees_delete'
	])->name('tenant-admin-employees.destroy');

Route::get('tenant-admin-employees/import', [
	TenantsController::class, 
	'employees_import'
	])->name('tenant-admin-employees.import');
	
Route::get('tenant-admin-employees/create', [
	TenantsController::class, 
	'employees_create'
	])->name('tenant-admin-employees.create');

Route::get('tenant-admin-transactions', [
	TenantsController::class, 
	'transactions'
	])->name('tenant-admin-transactions');

Route::get('tenant-admin-users', [
	TenantsController::class, 
	'users'
	])->name('tenant-admin-users');



});
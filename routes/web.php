<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpController;

/*   LOGGED OUT   */

Route::get('/', [
	GeneralController::class,
	'home'
	])->name('home');


Route::post('/ajax', [
	GeneralController::class, 
	'ajax']);

Route::get('documents-terms', [
	GeneralController::class, 
	'terms_show'
	])->name('documents-terms');

Route::get('documents-policy', [
	GeneralController::class, 
	'policy_show'
	])->name('documents-policy');

	





/*   LOGGED IN   */

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function(){


Route::get('/resources/docs/statement/{filename}', function($filename){
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


});




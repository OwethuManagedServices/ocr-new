<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	use HasFactory;
	protected $table = 'acl_permissions';
	
	public function roles()
	{
		return $this->belongsToMany(Role::class,'roles_permissions');
	}


	protected $fillable = [
		'name',
		'slug',
	];

	public static $sortable = [
		'id',
		'name',
		'slug',
	];

	public static $formfields = [
		[
			'name' => 'Name',
			'dbtable' => 'acl_permissions',
			'dbfield' => 'name',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Slug',
			'dbtable' => 'acl_permsissions',
			'dbfield' => 'slug',
			'type' => 'text',
		],
	];
    
	public static $grid = [
		['field' => 'id', 'display' => 'ID', 'width' => 10],
		['field' => 'name', 'display' => 'Name', 'width' => 45],
		['field' => 'slug', 'display' => 'Slug', 'width' => 45],
	];

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use HasFactory;
	protected $table = 'acl_roles';

	public function permissions()
	{
		return $this->belongsToMany(Permission::class,'roles_permissions');
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
			'dbtable' => 'acl_roles',
			'dbfield' => 'name',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Slug',
			'dbtable' => 'acl_roles',
			'dbfield' => 'slug',
			'type' => 'text',
		],
	];
    
	public static $grid = [
		['field' => 'id', 'display' => 'Edit', 'width' => 10],
		['field' => 'id', 'display' => 'Members', 'width' => 10, 'href' => 'members'],
		['field' => 'name', 'display' => 'Name', 'width' => 45],
		['field' => 'slug', 'display' => 'Slug', 'width' => 45],
	];


}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

	protected $fillable = [
		'name',
		'email',
		'password',
		'email_verified_at',
];

	public static $sortable = [
		'id',
		'name',
		'email',
	];

	public static $formfields = [
		[
			'name' => 'Email',
			'dbtable' => 'users',
			'dbfield' => 'email',
			'type' => 'email',
		],
		[
			'name' => 'Name',
			'dbtable' => 'users',
			'dbfield' => 'name',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Password',
			'dbtable' => 'users',
			'dbfield' => 'password',
			'type' => 'text',
			'add_only' => 1,
		],
		[
			'name' => 'Verify',
			'dbtable' => 'users',
			'dbfield' => 'email_verified_at',
			'type' => 'checkbox',
			'add_only' => 1,
		],
	];
   
	public static $grid = [
		['field' => 'id', 'display' => 'ID', 'width' => 10],
		['field' => 'name', 'display' => 'Name', 'width' => 45],
		['field' => 'email', 'display' => 'Email', 'width' => 45],
	];

}

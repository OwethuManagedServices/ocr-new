<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = "website";
    use HasFactory;

	protected $fillable = [
		'language',
		'category',
		'key',
		'value',
];

	public static $sortable = [
		'id',
		'account_number',
		'company_name',
	];

	public static $formfields = [
		[
			'name' => 'Language',
			'dbtable' => 'website',
			'dbfield' => 'language',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Category',
			'dbtable' => 'website',
			'dbfield' => 'category',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Key',
			'dbtable' => 'website',
			'dbfield' => 'key',
			'type' => 'text',
			'required' => 1,
		],
		[
			'name' => 'Value',
			'dbtable' => 'website',
			'dbfield' => 'value',
			'type' => 'textarea',
			'required' => 1,
		],
		[
			'name' => 'Keys',
			'dbfield' => 0,
			'type' => 'select',
			'required' => 1,
		],
		[
			'name' => 'RealValue',
			'dbtable' => 'website',
			'dbfield' => 'realvalue',
			'type' => 'textarea',
            'css' => 'w-full',
			'required' => 1,
		],
	];
    
	public static $grid = [
		['field' => 'id', 'display' => 'ID', 'width' => 10],
		['field' => 'language', 'display' => 'Language', 'width' => 10],
		['field' => 'category', 'display' => 'Category', 'width' => 40],
		['field' => 'key', 'display' => 'Key', 'width' => 40],
	];

}

<?php
use \Illuminate\Database\Eloquent\Model;
class Texts extends Model
{
	protected $table = 'parse_table';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = [
		'url',
		'name',
		'uid',
		'id',
		'visitors',
		'views',
		'popularity'
	];
}
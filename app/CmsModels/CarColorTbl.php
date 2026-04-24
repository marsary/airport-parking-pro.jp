<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarColorTbl
 * 
 * @property int $car_col_id
 * @property string $name
 * @property int $sort
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class CarColorTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'car_color_tbl';
	protected $primaryKey = 'car_col_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'car_col_id' => 'int',
		'sort' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'name',
		'sort',
		'del_flg',
		'inserted',
		'modified'
	];
}

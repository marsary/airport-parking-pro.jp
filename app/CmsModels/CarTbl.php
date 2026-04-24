<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarTbl
 * 
 * @property int $car_maker_id
 * @property int $car_id
 * @property string $name
 * @property string $name_k
 * @property int $type
 * @property int $lsize_flg
 * @property int $sort
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class CarTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'car_tbl';
	protected $primaryKey = 'car_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'car_maker_id' => 'int',
		'car_id' => 'int',
		'type' => 'int',
		'lsize_flg' => 'int',
		'sort' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'car_maker_id',
		'name',
		'name_k',
		'type',
		'lsize_flg',
		'sort',
		'del_flg',
		'inserted',
		'modified'
	];
}

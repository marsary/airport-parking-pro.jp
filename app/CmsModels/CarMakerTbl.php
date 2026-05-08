<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarMakerTbl
 * 
 * @property int $car_maker_id
 * @property string $name
 * @property string $name_k
 * @property int $sort
 * @property boolean $del_flg
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class CarMakerTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'car_maker_tbl';
	protected $primaryKey = 'car_maker_id';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'car_maker_id' => 'int',
		'sort' => 'int',
		'del_flg' => 'boolean',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'name',
		'name_k',
		'sort',
		'del_flg',
		'inserted',
		'modified'
	];
}

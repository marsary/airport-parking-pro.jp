<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RsvIdPoolTbl
 * 
 * @property int $rsv_id
 * @property timestamp $inserted
 *
 * @package App\CmsModels
 */
class RsvIdPoolTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'rsv_id_pool_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'rsv_id' => 'int',
		'inserted' => 'timestamp'
	];

	protected $fillable = [
		'rsv_id',
		'inserted'
	];
}

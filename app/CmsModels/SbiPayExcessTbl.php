<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SbiPayExcessTbl
 * 
 * @property int $sbpe_id
 * @property int $rsv_id
 * @property int $excess
 * @property int $pay_type
 * @property int $state
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class SbiPayExcessTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'sbi_pay_excess_tbl';
	protected $primaryKey = 'sbpe_id';
	public $timestamps = false;

	protected $casts = [
		'rsv_id' => 'int',
		'excess' => 'int',
		'pay_type' => 'int',
		'state' => 'int',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'rsv_id',
		'excess',
		'pay_type',
		'state',
		'inserted',
		'modified'
	];
}

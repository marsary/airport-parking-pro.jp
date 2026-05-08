<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SbiPayTbl
 * 
 * @property int $sbp_id
 * @property int $rsv_id
 * @property int $pay
 * @property int $pay_type
 * @property int $cancel_state
 * @property timestamp $inserted
 * @property timestamp $modified
 *
 * @package App\CmsModels
 */
class SbiPayTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'sbi_pay_tbl';
	protected $primaryKey = 'sbp_id';
	public $timestamps = false;

	protected $casts = [
		'rsv_id' => 'int',
		'pay' => 'int',
		'pay_type' => 'int',
		'cancel_state' => 'int',
		'inserted' => 'timestamp',
		'modified' => 'timestamp'
	];

	protected $fillable = [
		'rsv_id',
		'pay',
		'pay_type',
		'cancel_state',
		'inserted',
		'modified'
	];
}

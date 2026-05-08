<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FeeLockedTbl
 * 
 * @property Carbon $fee_date
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class FeeLockedTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'fee_locked_tbl';
	protected $primaryKey = 'fee_date';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fee_date' => 'datetime',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'ins_date'
	];
}

<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FeeCarryOverTbl
 * 
 * @property int $fag_id
 * @property Carbon $fee_date
 * @property int $carry_over
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class FeeCarryOverTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'fee_carry_over_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fag_id' => 'int',
		'fee_date' => 'datetime',
		'carry_over' => 'int',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'carry_over',
		'ins_date'
	];
}

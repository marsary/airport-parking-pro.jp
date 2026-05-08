<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FeeTbl
 * 
 * @property int $fag_id
 * @property int $office_id
 * @property Carbon $fee_date
 * @property int $fee_num
 * @property int $fee_earning
 * @property int $fee_val
 * @property int $fee_imposition
 * @property int $fee_num_adjust
 * @property int $fee_val_adjust
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class FeeTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'fee_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fag_id' => 'int',
		'office_id' => 'int',
		'fee_date' => 'datetime',
		'fee_num' => 'int',
		'fee_earning' => 'int',
		'fee_val' => 'int',
		'fee_imposition' => 'int',
		'fee_num_adjust' => 'int',
		'fee_val_adjust' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'fee_num',
		'fee_earning',
		'fee_val',
		'fee_imposition',
		'fee_num_adjust',
		'fee_val_adjust',
		'ins_date',
		'up_date'
	];
}

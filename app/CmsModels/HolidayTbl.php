<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HolidayTbl
 * 
 * @property Carbon $holiday
 * @property int $holiday_type
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class HolidayTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'holiday_tbl';
	protected $primaryKey = 'holiday';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'holiday' => 'datetime',
		'holiday_type' => 'int',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'holiday_type',
		'ins_date'
	];
}

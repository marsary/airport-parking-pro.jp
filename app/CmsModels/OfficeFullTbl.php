<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OfficeFullTbl
 * 
 * @property int $office_id
 * @property Carbon $full_date
 * @property timestamp $ins_date
 *
 * @package App\CmsModels
 */
class OfficeFullTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'office_full_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'office_id' => 'int',
		'full_date' => 'datetime',
		'ins_date' => 'timestamp'
	];

	protected $fillable = [
		'ins_date'
	];
}

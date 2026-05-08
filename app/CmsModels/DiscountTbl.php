<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscountTbl
 * 
 * @property int $dsc_g_id
 * @property int $dsc_id
 * @property int $office_id
 * @property int $dsc_type
 * @property int $dsc_priority
 * @property int $dsc_rate
 * @property Carbon $dsc_date_start
 * @property Carbon $dsc_date_end
 * @property Carbon $dsc_date_start_excp
 * @property Carbon $dsc_date_end_excp
 * @property int $dsc_avl_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property int $agency_sun_id
 * @property int $ag_id1
 * @property int $ag_id2
 *
 * @package App\CmsModels
 */
class DiscountTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'discount_tbl';
	protected $primaryKey = 'dsc_id';
	public $timestamps = false;

	protected $casts = [
		'dsc_g_id' => 'int',
		'office_id' => 'int',
		'dsc_type' => 'int',
		'dsc_priority' => 'int',
		'dsc_rate' => 'int',
		'dsc_date_start' => 'datetime',
		'dsc_date_end' => 'datetime',
		'dsc_date_start_excp' => 'datetime',
		'dsc_date_end_excp' => 'datetime',
		'dsc_avl_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'agency_sun_id' => 'int',
		'ag_id1' => 'int',
		'ag_id2' => 'int'
	];

	protected $fillable = [
		'dsc_g_id',
		'office_id',
		'dsc_type',
		'dsc_priority',
		'dsc_rate',
		'dsc_date_start',
		'dsc_date_end',
		'dsc_date_start_excp',
		'dsc_date_end_excp',
		'dsc_avl_flg',
		'ins_date',
		'up_date',
		'agency_sun_id',
		'ag_id1',
		'ag_id2'
	];
}

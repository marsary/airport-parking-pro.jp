<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BackFltTbl
 * 
 * @property int $flt_id
 * @property int $air_port_id
 * @property int $term_id
 * @property Carbon $back_date
 * @property string $flt_info1
 * @property string $flt_info2
 * @property int $flt_info3
 * @property string $dpt_place_id
 * @property string $dpt_place
 * @property int $back_week_id
 * @property string $back_week
 * @property timestamp $ins_date
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class BackFltTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'back_flt_tbl';
	protected $primaryKey = 'flt_id';
	public $timestamps = false;

	protected $casts = [
		'air_port_id' => 'int',
		'term_id' => 'int',
		'back_date' => 'datetime',
		'flt_info3' => 'int',
		'back_week_id' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'air_port_id',
		'term_id',
		'back_date',
		'flt_info1',
		'flt_info2',
		'flt_info3',
		'dpt_place_id',
		'dpt_place',
		'back_week_id',
		'back_week',
		'ins_date',
		'up_date'
	];
}

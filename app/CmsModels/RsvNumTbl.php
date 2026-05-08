<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RsvNumTbl
 * 
 * @property int $office_id
 * @property Carbon $rn_date
 * @property int $rn_rsv_num
 * @property int $rn_grg_num
 * @property timestamp $up_date
 *
 * @package App\CmsModels
 */
class RsvNumTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'rsv_num_tbl';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'office_id' => 'int',
		'rn_date' => 'datetime',
		'rn_rsv_num' => 'int',
		'rn_grg_num' => 'int',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'rn_rsv_num',
		'rn_grg_num',
		'up_date'
	];
}
